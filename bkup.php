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
class CrawlerEiPrice
{
private $browser = '';
private $page = '';
	private $params = '';
    

    public function __construct($startUrl, $params)
    {
		$puppeteer = new Puppeteer();
		$this->browser   = $puppeteer->launch(
			[
				'args' =>
					[
						// this is required for dockerized puppeteer
						'--no-sandbox',
						'--disable-setuid-sandbox',
						'--disable-dev-shm-usage',
					],
			]
		);
		 
		$this->page = $this->browser->newPage();
		$this->page->goto($startUrl);
		$this->params = $params;
	}
	
	private function goTo($pageEl)
	{
		$this->page->click($pageEl);
		$this->page->waitFor('section');
		$this->page->screenshot(
			[
				'path'     => 'opportunities.png',
				'fullPage' => true,
			]
		);
		
	}


	private function extractData($el)
	{
		return $this->page->querySelector($el)
				->getProperty('innerText')->jsonValue();
	}

	private function remove_nbsp($string)
	{
		$string_to_remove = "&nbsp;";
		return str_replace(',','.',str_replace($string_to_remove, "", $string));
	}

	private function extractDataByRegex($el)
	{
		$html = $this->page
				->querySelector($el['SELECTOR'])
				->getProperty('innerHTML')
				->jsonValue();
		
		preg_match_all($el['REGEX'], $html, $matches);		
	
		return  array_map(array($this, 'remove_nbsp'), $matches[0]);
	}

	private function getMinMaxMedia($pArray)
	{
		
	   $pdata['min'] = min($pArray);
	   $pdata['max'] = max($pArray);
	   $pdata['avg'] = array_sum($pArray) / count($pArray);

	   return $pdata;
	}

    public function extractAllData()
    {
		
		$this->goTo($this->params['PAGES']['COMPARE_PRICES']);
		
		$pn    = $this->extractData($this->params['DATA']['PRODUCT_NAME']);
		$pt    = $this->extractDataByRegex($this->params['DATA']['PRICE_TABLE']);
		$pdata = $this->getMinMaxMedia($pt);

		return [
			'PRODUCT_DETAILS'  => 
			[
			]$pn,
			'PRICE_TABLE'   => $pt,
			'PRICE_DETAILS' => $pdata 
		];
	}
}



$params = [
	'PAGES'=> 
	[
		'COMPARE_PRICES' => '.C1iIFb.IHk3ob a'
	],
	'DATA' =>
	[
		'PRODUCT_NAME' => '#sg-product__pdp-container .sh-t__title',
		'PRICE_TABLE' =>
		[
			'REGEX' => '/(?<=R\$).*?(?=<)/m',
			'SELECTOR' => 'table#sh-osd__online-sellers-grid'
		]
	]
];

$startUrl = 'https://www.google.com.br/search?q=28877362779&tbm=shop';

$c = new CrawlerEiPrice($startUrl, $params);

$data = $c->extractAllData();

print_r(
	$data
);


die(' ---> fim');
