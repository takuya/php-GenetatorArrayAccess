<?php

use Takuya\Php\GeneratorArrayAccess;
require_once 'vendor/autoload.php';
$g = ( function ( $arr ) {foreach ($arr as $e) {yield $e;}})(range(0, 9));
$iter = new GeneratorArrayAccess($g);
var_dump($iter[7]);
