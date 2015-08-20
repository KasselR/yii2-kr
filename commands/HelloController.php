<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
    
    public function actionImport(){
    	require 'vendor/autoload.php';

		$db = new MongoClient("mongodb://localhost:27017");
		$grid = $db->selectDB('xpps')->getGridFS();
		$parser = new \Smalot\PdfParser\Parser();
		
		$dir = new RecursiveDirectoryIterator('C:\tmp\klett-cotta\daten\www.traumaundgewalt.de');
		foreach(new RecursiveIteratorIterator($dir) as $file) {
			if(!is_dir($file) AND ($file->getExtension() == "pdf")){
				
				$pdf = $parser->parseFile($file);
				$metas = $pdf->getDetails();
				echo basename($file). " path: ".  realpath($file) . "<br>";
				#echo var_dump((string)$file)."<br>";
				#$grid->storeFile((string)$file, array('metadata'=>$metas));
			}
		
		}	
    }
}


