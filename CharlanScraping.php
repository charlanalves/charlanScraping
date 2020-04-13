<?php
/*
 * 
 *
 * 
 *
 * 
 * 
 */
      
declare(strict_types=1);



use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

require_once 'vendor/autoload.php';

/**
 * @author Charlan Santos <charlan.job@gmail.com.com>
 */
Abstract class CharlanScraping
{
    private $browser = '';
	private $page = '';
	private $params = '';
	public $extractedData = [];
	public $outputData;
	protected $paramsOutput;

	public function __construct($startUrl, $params = null)
    {
		$puppeteer = new Puppeteer();
		$this->browser   = $puppeteer->launch(
			[
				'args' =>
					[
						// Parametros abaixo obrigatórios para Puppeteer no docker
						'--no-sandbox',
						'--disable-setuid-sandbox',
						'--disable-dev-shm-usage',
					],
			]
		);
		 
		$this->page = $this->browser->newPage();
		$this->page->goto($startUrl);

		if (is_null($params)) 
		{
		   $this->params = $this->getStaticParams();
		}else
		{
			$this->params = $params;
		}
	}

		
	public function createCsvStruct($rowCsv)
	{
		return $this->generateOutput($rowCsv);
	}
	
	public static function getStaticParams()
	{	
		$config = [];
		$config['PAGES'] =   require_once('PagesConfig.php');
		$config['OUTPUT'] =  require_once('OutputConfig.php');
		
		return $config;
	}


	public function extractAllData()
    {	
		foreach($this->params['PAGES'] as $p)
		{
			$this->goTo($p['CLICK_SELECTOR']);

			foreach($p['EXTRACT_DATA'] as $d)
			{
				if(isset($d['REGEX']))
				{
					$pt = $this->extractDataAndAplyRegex($d);
				}
				else
				{
					$pn = $this->extractData($d);
				}
			}	
			$this->eventOnFinishIteration($p);
		}

	//	$this->generateOutput();
		
		return $this->extractedData;
	}

	protected function generateOutput($paramsOutput)
	{	
		$this->paramsOutput = $paramsOutput;
		if(count($this->params['OUTPUT']))
		{
			foreach($this->params['OUTPUT'] as $outputType => $outConfigArray)
			{
				foreach($outConfigArray as $outConfig)
			   {
					$outputClass = 'CharlanScrapingOutput'.$outputType;
					require_once($outputClass.'.php');
					$o = new $outputClass($outConfig, $this);
					$this->outputData[] = $o->run();
				
			   }
			}
		}
		return  $this->outputData;

	}
	
	protected function eventOnFinishIteration($p)
	{
		if(isset($p['EXEC_ON_EVENTS']['onFinishIteration']))
		{
			foreach($p['EXEC_ON_EVENTS']['onFinishIteration'] as $fn)
			{
				$a = $fn['CALL_METHOD'];
				
				$this->{$a}($fn);
			}
		}
	}
	
	
	private function goTo($pageEl)
	{
		$this->page->click($pageEl);
		$this->page->waitFor('section');
		// $this->page->screenshot(
		// 	[
		// 		'path'     => 'produto'.uniqid().'.png',
		// 		'fullPage' => true,
		// 	]
		// );
		
	}

	protected function extractData($el)
	{
		$this->extractedData[$el['DATA_LABEL']] = 
		$this->page
			 ->querySelector($el['SELECTOR'])
			 ->getProperty($el['ACTION'])
			 ->jsonValue();

		return $this->extractedData[$el['DATA_LABEL']];
	}

	private function remove_nbsp($string)
	{
		$string_to_remove = "&nbsp;";
		return str_replace(',','.',str_replace($string_to_remove, "", $string));
	}

	protected function extractDataAndAplyRegex($el)
	{
		$html = $this->extractData($el);

		preg_match_all($el['REGEX'], $html, $matches);			  

		$this->extractedData[$el['DATA_LABEL']] = array_map(array($this, 'remove_nbsp'), $matches[0]);

		return $this->extractedData[$el['DATA_LABEL']];
	}

	public function extractedData($dataLabel, $params)
    {   
        return array_intersect_key($this->extractedData, array_flip(array($dataLabel)));
    }

    public function initialCsv($dataLabel, $params)
    {  
        return array($dataLabel =>$this->paramsOutput[$dataLabel] );
    }

    public function internalFnGetMin($dataLabel, $params)
    {
        return array($dataLabel => min($this->extractedData[$params]));
    }

    public function internalFnGetMax($dataLabel, $params)
    {
        return array($dataLabel => max($this->extractedData[$params]));
    }

    public function internalFnGetAVG($dataLabel, $params)
    {   
        $avg = array_sum($this->extractedData[$params]) / count($this->extractedData[$params]);
        $avg = number_format((float)$avg, 2, ',', '');

        return array($dataLabel => $avg);
    }

    public function internalFnGetDate($dataLabel, $params)
    {
        return array($dataLabel => date($params));
    }

    public function internalFnGetCount($dataLabel, $params)
    {
        return array($dataLabel => count($this->extractedData[$params]));
    }


   

	
}