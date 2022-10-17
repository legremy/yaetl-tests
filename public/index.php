<?php

require './../vendor/autoload.php';

use fab2s\YaEtl\Loaders\File\CsvLoader;
use fab2s\YaEtl\Transformers\CallableTransformer;
use fab2s\YaEtl\YaEtl;
use fab2s\YaEtl\Extractors\File\CsvExtractor;
use Gremy\Yaetl\Etl\Transformer\StrToUpperTransformer;

$yaEtl = new YaEtl;
$pathToCsvInput = './data/input/addresses.csv';
$pathToCsvOutput = './data/output/str-to-upper-addresses.csv';
try {
    $extractor = new CsvExtractor($pathToCsvInput);
    //$strToUpperTransformer = new StrToUpperTransformer;
    $strToUpperTransformer = new CallableTransformer('strtoupper');
    $loader = new CsvLoader($pathToCsvOutput);
} catch(Exception $e){
    echo $e->getMessage();
    die();
}

$yaEtl
    ->from($extractor)
    ->transform($strToUpperTransformer)
    ->to($loader)
    ->exec();

// // forgot something ?
// // continuing with the same object
// $yaEtl->transform(new AnotherTransformer)
// ->to(new CsvLoader)
// ->transform(new SuperCoolTransformer)
// ->to(new S3Loader)
// // never too cautious
// ->to(new FlatLogLoader)
// // Flows are repeatable
// ->exec();

// // oh but what if ...
// $yaEtl->branch(
// (new YaEtl)->transform(new SwaggyTransformer)
// // Enrich $extractor's records
// ->join($extractor, new HypeJoiner($pdo, $query, new OnClose('upstreamFieldName', 'joinerFieldName', function($upstreamRecord, $joinerRecord) {
// return array_replace($joinerRecord, $upstreamRecord);
// })))
// ->transform(new PutItAllTogetherTransformer)
// ->to(new SuperSpecializedLoader)
// )->exec();

// // or another branch for a subset of the extraction
// $yaEtl->branch(
// (new YaEtl)->qualify(new CallableQualifier(function($record) {
// return !empty($record['is_great']);
// })
// ->transform(new GreatTransformer)
// ->to(new GreatLoader)
// )->exec();

// // need a Progress Bar ?
// $progressSubscriber = new ProgressBarSubscriber($yaEtl);
// // with count ?
// $progressSubscriber->setNumRecords($count);