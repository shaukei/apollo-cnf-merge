<?php

include dirname(__DIR__) . '/vendor/autoload.php';

use Freya\ApolloCnf\ApolloCnf;

$opts = [
    'appid'         =>  'SampleTest',
    'namespaces'    =>  ['SampleAPI'],
    'enable'        =>  true,
    'apolloHost'    =>  'http://127.0.0.1:8080',
    'cluster'       =>  'default',
    'mergeMode'     =>  \Freya\ApolloCnf\CnfMode\PhpFile::class,
    'path'          =>  '/tmp',
    'file'          =>  'default.php',
    'NSFile'        =>  true,
];

$freya = new ApolloCnf($opts);
$freya->run();
