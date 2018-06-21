<?php

use Symfony\Component\Yaml\Yaml;

require 'vendor/autoload.php';

function getNameClass($class) {
    $path = explode('\\', $class);

    return array_pop($path);
}

$jms = new \Giansalex\Serializer\JmsGenerator();

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
    rmdir($pathDir);
}

mkdir($pathDir);

foreach ($result as $class => $props) {
    $yaml = Yaml::dump([$class => $props], 4);
    $name = getNameClass($class);
    file_put_contents($pathDir.'/model.'.$name.'.yaml', $yaml);
}