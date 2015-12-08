<?php

include 'src/scheme.php';
$format = 'jpg';

$dotFile = 'files'.DIRECTORY_SEPARATOR.'dot';
$outputFile = 'images'.DIRECTORY_SEPARATOR.'output.png';


$scheme = new Scheme();
//if you use windows
//$scheme = new Schceme('C:\Program Files (x86)\Graphviz2.38\bin\dot.exe');

$scheme->addNode("start", array("shape"=>"Mdiamond", "color"=>"salmon2", "style"=>"filled"));
$scheme->addNode("finish", array("sides"=>"3", "distortion"=>"0", "shape"=>"polygon", "style"=>"filled", "color"=>"greenyellow"));
$scheme->addLink('start','finish');
$scheme->addLink('start','pause');
$scheme->addLink('pause','finish');
$scheme->writeDotFile($dotFile);

// you can save graph if you want
//$scheme->saveGraph($dotFile, $outputFile);
//echo '<img src="'.$outputFile.'">';
// or just show it
header("Content-Type: image/".$format);
echo $scheme->generateGraph($dotFile, $format);
