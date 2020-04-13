<?php
/*
 * 
 *
 * 
 *
 * 
 * 
 */

class CsvHelper
{

  public static function getCsv($csvPath)
  {
        $csv = array_map('str_getcsv', file($csvPath));
        array_walk($csv, function(&$a) use ($csv) { 
            $a = array_combine($csv[0], $a); 
        }); 
        array_shift($csv);

        return $csv;
  }


  public static function saveFileSystem($csvPath, $arrays, $key)
  {
        $header=null;
        $createFile = fopen($csvPath,"w+");

        foreach ($arrays as $array) 
        {
            foreach ($array as $row) 
            {
                if(isset($row[$key]))
                {
                    if(!$header) 
                    {
                        fputcsv($createFile, array_keys($row[$key]));               
                        fputcsv($createFile, $row[$key]);
                        $header = true;
                    }
                    else 
                    {
                        fputcsv($createFile, $row[$key]);
                    }   
                }
            }
        }
        fclose($createFile);
      
  }

}