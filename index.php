<?php

use Symfony\Component\Yaml\Yaml;

require 'vendor/autoload.php';

$eof = php_sapi_name() === 'cli' ? PHP_EOL : '<br>';
function getNameClass($class) {
    $path = explode('\\', $class);

    return array_pop($path);
}

function delDir($path){
    if(is_dir($path) == TRUE){
        $rootFolder = scandir($path);
        if(sizeof($rootFolder) > 2){
            foreach($rootFolder as $folder){
                if($folder != "." && $folder != ".."){
                    delDir($path."/".$folder);
                }
            }
            rmdir($path);
        }
    }else{
        if(file_exists($path) == TRUE){
            unlink($path);
        }
    }
}

$jms = new \Giansalex\Serializer\JmsGenerator();

echo 'Convert classes'.$eof;
$result = $jms->fromClasses([
    \Greenter\Model\Sale\Invoice::class,
    \Greenter\Model\Sale\Note::class,
    \Greenter\Model\Summary\Summary::class,
    \Greenter\Model\Voided\Voided::class,
    \Greenter\Model\Voided\Reversion::class,
    \Greenter\Model\Perception\Perception::class,
    \Greenter\Model\Retention\Retention::class,
    \Greenter\Model\Despatch\Despatch::class,
]);

$pathDir = __DIR__.'/serializer';
if (is_dir($pathDir)) {
    delDir($pathDir);
}

mkdir($pathDir);

echo 'Writing yaml files'.$eof;
foreach ($result as $class => $props) {
    $yaml = Yaml::dump([$class => $props], 4);
    $name = getNameClass($class);
    file_put_contents($pathDir.'/model.'.$name.'.yaml', $yaml);
}

echo 'Completed!'.$eof;