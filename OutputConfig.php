<?php
/**
 *
 * OutputConfig
 * 
 * Arquivo de configuração da saida de dados CSV, DATABASE ETC
 * Sendo que só foi implementado CSV
 * 
 * Documentação de exemplo:
 * CSV => tipo da saida, aqui poderiamos ter outras se quisessemos como database e etc. * 
 *  [
 *     'FILE_NAME' => 'layout_entrega_resumo_item.csv' - path com o nome do arquivo
 *     'ID'   => CSV_RESUMO - nome de identificaço para ser utilizado internamente
 *     'DATA'  => Colunas e origem dos dados
 *            [
 *              'PRODUTO'     =>  ['SOURCE' => 'extractedData'], // dados obtidos atraves da extracao 
 *            'EAN'         =>  ['SOURCE' => 'initialCsv'], // dados obtidos direto do csv inicial
 *            'PRECO REF'   =>  ['SOURCE' => 'initialCsv'],  // dados obtidos direto do csv inicial 
 *             
 *                  Dados obtidos a partir de funcoes internas. Fiz dessa forma objetivando 
 *                  reutilização uma  vez que é so indicar o nome da funcao na chave SOURCE
 *                  e em PARAMS indicar o DATA_LABEL do dado extraido  
 * 
 *            'MIN'         =>  ['SOURCE' => 'internalFnGetMin',  'PARAMS' => 'PRICE_TABLE'],
 *            'MAX'         =>  ['SOURCE' => 'internalFnGetMax',  'PARAMS' => 'PRICE_TABLE'],
 *            'MEDIA'       =>  ['SOURCE' => 'internalFnGetAVG',  'PARAMS' => 'PRICE_TABLE'],
 *            'QTDE SELLER' =>  ['SOURCE' => 'internalFnGetCount','PARAMS' => 'SELLER'],
 *            'DATA'        =>  ['SOURCE' => 'internalFnGetDate', 'PARAMS' => 'd/m/Y'],
 *                 
 *                   se precisar criar alguma funcao customizada so utlizar 
 *                   o SOURCE com o nome da funcao presente no arquivo EiPriceScraping.php
 * 
 *                   como o exemplo abaixo:
 *            'GAP'         =>  ['SOURCE' => 'customFnGetGap', 'PARAMS' => 'PRICE_TABLE'],
 * 
 *          ]
 *  ],
 * 
 * Apos executar cada extração ele montará um array conforme o exemplo abaixo que 
 * podera ser utilizado posteriormente nas classes da Engine e do EiPriceScraping
 * EX: 
 * 'PRODUTO' => 'dados_extraidos',
 * 'PRICE_TABLE' => 'dados_extraidos',
 * 'SELLER' =>  'dados_extraidos'
 *
 * @author Charlan Santos
 */ 
return 
[   
        'CSV' =>
        [
            [
                'FILE_NAME' => 'layout_entrega_resumo_item.csv',
                'ID'        => 'CSV_RESUMO',
                'DATA'      => 
                [
                    'PRODUTO'     =>  ['SOURCE' => 'extractedData'],
                    'EAN'         =>  ['SOURCE' => 'initialCsv'],
                    'PRECO REF'   =>  ['SOURCE' => 'initialCsv'],                    
                    'MIN'         =>  ['SOURCE' => 'internalFnGetMin',  'PARAMS' => 'PRICE_TABLE'],
                    'MAX'         =>  ['SOURCE' => 'internalFnGetMax',  'PARAMS' => 'PRICE_TABLE'],
                    'MEDIA'       =>  ['SOURCE' => 'internalFnGetAVG',  'PARAMS' => 'PRICE_TABLE'],
                    'QTDE SELLER' =>  ['SOURCE' => 'internalFnGetCount','PARAMS' => 'SELLER'],
                    'DATA'        =>  ['SOURCE' => 'internalFnGetDate', 'PARAMS' => 'd/m/Y'],
                ]
        
            ],
        
            [
                'FILE_NAME' => 'layout_entrega_detalhe_item.csv',
                'ID'        => 'CSV_DETALHE',
                'DATA'      => 
                [
                    'PRODUTO'     =>  ['SOURCE' => 'extractedData'],
                    'EAN'         =>  ['SOURCE' => 'initialCsv'],
                    'VENDEDOR'    =>  ['SOURCE' => 'customFnGetVendedor'],
                    'PRECO REF'   =>  ['SOURCE' => 'initialCsv'],                    
                    'PREÇO CANAL' =>  ['SOURCE' => 'internalFnGetMin', 'PARAMS' => 'PRICE_TABLE'],
                    'GAP'         =>  ['SOURCE' => 'customFnGetGap', 'PARAMS' => 'PRICE_TABLE'],
                    'STATUS'      =>  ['SOURCE' => 'customFnGetStatus',  'PARAMS' => 'PRICE_TABLE'],
                    'DATA'        =>  ['SOURCE' => 'internalFnGetDate', 'PARAMS' =>  'd/m/Y'],
                    'HORA'        =>  ['SOURCE' => 'internalFnGetDate',  'PARAMS' => 'H:i:s'],
                ]
        
            ],
        ]
       
        // TODO: Implementar outros ouputs como database e etc.
       // 'DATABASE' => []
    
];

