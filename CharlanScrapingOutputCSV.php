<?php
require_once 'CsvHelper.php';

class CharlanScrapingOutputCSV
{
    protected $config = '';
    protected $csvRow = '';
    protected $extractedData = '';
    protected $clientClass = '';
    protected $csvData;

    public function __construct($config,$clientClass)
    {
        $this->config = $config;                
        $this->clientClass = $clientClass;
    }

    public function run()
    {   
        return $this->createStruct();
    }
    

    private function createStruct()
    {  
        $this->csvData[$this->config['ID']] = [];  
        foreach($this->config['DATA'] as $dataLabel => $c)
        { 
            if (!isset($c['PARAMS'])){$c['PARAMS'] = null;}

            if (method_exists($this->clientClass, $c['SOURCE']))
            {
                $ret = call_user_func(array($this->clientClass, $c['SOURCE']),$dataLabel, $c['PARAMS']); 
                $this->csvData[$this->config['ID']][array_keys($ret)[0]] = array_values($ret)[0];
            }
        }   
        return $this->csvData;
    }
  
    

}