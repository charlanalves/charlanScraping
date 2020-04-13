<?php
/**
 *
 * EiPriceScraping
 * 
 * Classe responsável por agrupar metodos customizados (definidos no arquivo de config Output.config) 
 * para sumarização ou manipulação dos dados ja extraidos da web sendo que os parâmetros para 
 * essa extração são definidos no arquivo de config pages.config
 *
 * @author Charlan Santos
 */   
require_once 'CharlanScraping.php';
require_once 'CsvHelper.php';

class EiPriceScraping extends CharlanScraping
{
	/**
     * Constantes de classe utilizada no metodo status
     */
	const MAIS_CARO   = 'MAIS CARO'; 
	const MAIS_BARATO = 'MAIS BARATO';
	const IGUAL       = 'IGUAL';
	 
	/*
     * Obtém os dados do vendedor que tem o produto de menor preço
     *
     * @autor Charlan Santos
     *
     * @param $currentHeaderColumn - Contém o nome da coluna atual do CSV definido no arquivo OutputConfig.php
	 * Esse parâmetro é preenchido automaticamente pela mini Egine (CharlanScraping.php) 
     *
     * @return array - retorna um array associativo para preparar a estrutura da geração do CSV
	 * Ex: array('PRECO REF' => '1458,22')
     *
     */
	public function customFnGetVendedor($currentHeaderColumn)
	{
		$pt = $this->extractedData['PRICE_TABLE'];
		$sellers = $this->extractedData['SELLER'];
		return array($currentHeaderColumn => $sellers[array_keys($pt, min($pt))[0]]);
	}

	/*
     * Obtém os dados o percentual de diferença entre o preço referência e o 
	 * menor preço encontrado no canal
     *
     * @autor Charlan Santos
     *
     * @param $currentHeaderColumn - Contém o nome da coluna atual do CSV definido no arquivo OutputConfig.php
	 * Esse parâmetro é preenchido automaticamente pela mini Egine (CharlanScraping.php) 
     *
     * @return array - retorna um array associativo para preparar a estrutura da geração do CSV
	 * Ex: array('GAP' => '14.58')
     *
     */
	public function customFnGetGap($currentHeaderColumn)
	{
		$minPrice = $this->outputData[0]['CSV_RESUMO']['MIN'];
		
		$gap = ($this->paramsOutput['PRECO REF'] - $minPrice) * 100 / $this->paramsOutput['PRECO REF'];
		$gap = number_format((float)$gap, 2, '.', '');
	
		return array($currentHeaderColumn => $gap);
	}

	/*
     * Obtém o status do preço, isto é, se o preço referencia esta mais barato,
	 * mais caro ou igual ao menor preco do canal	 
     *
     * @autor Charlan Santos
     *
     * @param $currentHeaderColumn - Contém o nome da coluna atual do CSV definido no arquivo OutputConfig.php
	 * Esse parâmetro é preenchido automaticamente pela mini Egine (CharlanScraping.php) 
     *
     * @return array - retorna um array associativo para preparar a estrutura da geração do CSV
	 * Ex: array('STATUS' => 'MAIS CARO')
     *
     */
	public function customFnGetStatus($currentHeaderColumn)
	{
		$minPrice = $this->outputData[0]['CSV_RESUMO']['MIN'];
		switch($this->paramsOutput['PRECO REF'])
		{
			case ($this->paramsOutput['PRECO REF'] > $minPrice):
			  return array($currentHeaderColumn => self::MAIS_CARO);
			case ($this->paramsOutput['PRECO REF'] < $minPrice):
			  return array($currentHeaderColumn => self::MAIS_BARATO);
			default:
			  return array($currentHeaderColumn => self::IGUAL);
		}
	}
}


$data = [];
$minPrice = '';

$products = CsvHelper::getCsv('eip.csv');

// Obtem os parametros para realizar a extração de dados e a geração da saida csv (presentes nos arquivos outputConfig.php e PagesConfig.php)
$params   = EiPriceScraping::getStaticParams();

// Percorre todos produtos do csv para realizar a extração de dados e preparar a estrutura do csv 
foreach($products as $k => $rowCsv)
{	
	// Define a pagina inicial variando o codigo EAN. Achei mais prático já ir direto para essa página economizando tempo
	$startUrl    =  'https://www.google.com.br/search?q='.$rowCsv['EAN'].'&tbm=shop';
	$eiClient    =  new EiPriceScraping($startUrl,$params);
	
	$data[]      =  $eiClient->extractAllData($rowCsv);

	$csvData[]   = $eiClient->createCsvStruct($rowCsv, $data[$k]);
	
}

// Cria o arquivo csv com o path ,vindo do arquivo de configuração OutputConfig.php, e os todos demais dados ja extraidos e manipulados
CsvHelper::saveFileSystem($params['OUTPUT']['CSV'][0]['FILE_NAME'], $csvData,'CSV_RESUMO');
CsvHelper::saveFileSystem($params['OUTPUT']['CSV'][1]['FILE_NAME'], $csvData,'CSV_DETALHE');

die('Finalizado com sucesso');

