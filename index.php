<?php

include 'src/scheme.php';
$format = 'jpg';

$scheme = new Schceme();

header("Content-Type: image/".$format);
$scheme->addNode("start", array("shape"=>"Mdiamond"));
$scheme->addNode("finish", array("shape"=>"Mdiamond"));
$scheme->addLink('start','finish');
$scheme->addLink('start','pause');
$scheme->addLink('pause','finish');

$scheme->generateGraph($format);
