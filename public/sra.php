<?php

use fab2s\YaEtl\Extractors\File\LineExtractor;
use fab2s\YaEtl\Qualifiers\CallableQualifier;
use fab2s\YaEtl\YaEtl;
use Gremy\Yaetl\Etl\Campain\EtlCampain;
use Gremy\Yaetl\Etl\Interrupter\BasicInterrupter;
use Gremy\Yaetl\Etl\Loader\CustomCsvLoader;
use Gremy\Yaetl\Etl\Transformer\SraTransformer;
use Gremy\Yaetl\Etl\Transformer\StrToLowerTransformer;

require './../vendor/autoload.php';

$pathToInput = './data/input/sra-extract.txt';
//$pathToInput = './data/input/SRAVEH.txt';
$pathToBadInput = './data/input/sra-extract-bad.txt';

$pathToSraOutput1 = './data/output/fva1.csv';
$pathToSraOutput2 = './data/output/fva2.csv';
$pathToSraOutput3 = './data/output/fva3.csv';
$pathToSraOutput4 = './data/output/fva4.csv';

$yaEtl1 = new YaEtl;
$yaEtl2 = new YaEtl;
$yaEtl3 = new YaEtl;
$yaEtl4 = new YaEtl;

$lineExtractor  = new LineExtractor($pathToInput);
$lineExtractor2 = new LineExtractor($pathToBadInput);
$lineExtractor3 = new LineExtractor($pathToInput);
$lineExtractor4 = new LineExtractor($pathToInput);

$sraTransformer  = new SraTransformer;
$sraTransformer2 = new SraTransformer;
$sraTransformer3 = new SraTransformer;
$sraTransformer4 = new SraTransformer;

$strToLowerTransformer = new StrToLowerTransformer;

$sraLoader1 = new CustomCsvLoader($pathToSraOutput1);
$sraLoader2 = new CustomCsvLoader($pathToSraOutput2);
$sraLoader3 = new CustomCsvLoader($pathToSraOutput3);
$sraLoader4 = new CustomCsvLoader($pathToSraOutput4);

$basicInterrupter = new BasicInterrupter();

$filterProItems = Closure::fromCallable([$sraTransformer, 'filterProItems']);

$yaEtl1
    ->from($lineExtractor)
    ->qualify(new CallableQualifier($filterProItems))
    ->transform($sraTransformer)
    ->to($sraLoader1)
;

$yaEtl2
    ->from($lineExtractor2)
    ->transform($sraTransformer2)
    ->transform($strToLowerTransformer)
    ->to($sraLoader2)
;

$yaEtl3
    ->from($lineExtractor3)
    ->transform($sraTransformer3)
    ->to($sraLoader3)
;

$yaEtl4
    ->from($lineExtractor4)
    ->transform($sraTransformer4)
    ->to($sraLoader4)
;

$etlCampain = new EtlCampain([
    $yaEtl1,
    $yaEtl2,
    $yaEtl3,
    $yaEtl4
]);

$etlCampain->exec();