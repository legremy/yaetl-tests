<?php

require './../vendor/autoload.php';

use fab2s\NodalFlow\Events\FlowEvent;
use fab2s\NodalFlow\Events\FlowEventInterface;
use fab2s\NodalFlow\Flows\FlowRegistry;
use fab2s\YaEtl\Extractors\CallableExtractor;
use fab2s\YaEtl\Loaders\File\CsvLoader;
use fab2s\NodalFlow\Nodes\CallableNode;
use fab2s\YaEtl\YaEtl;
use fab2s\YaEtl\Extractors\File\CsvExtractor;
use Gremy\Yaetl\Etl\Loader\CustomCsvLoader;
use Gremy\Yaetl\Etl\Transformer\StrToLowerTransformer;
use Gremy\Yaetl\Etl\Transformer\StrToUpperTransformer;

$yaEtl = new YaEtl;

$pathToCsvInput = './data/input/addresses.csv';
$inputCsvFile = fopen($pathToCsvInput, 'r');

$pathToCsvInput2 = './data/input/addresses2.csv';

$pathToCsvStrtoupperOutput = './data/output/str-to-upper-addresses.csv';
$pathToCsvStrtolowerOutput = './data/output/str-to-lower-addresses.csv';

$strToUpperTransformer = new StrToUpperTransformer;
$strToLowerTransformer = new StrToLowerTransformer;

try {
    $csvExtractor = new CsvExtractor($inputCsvFile); // fonctionne avec url ou ressource
    $csvExtractor2 = new CsvExtractor($pathToCsvInput2);
    $testCallableExtractor = new CallableExtractor(function() {
        return range(1, 100);
    });

    $strtoupperCsvLoader = new CsvLoader($pathToCsvStrtoupperOutput);
    $strtoupperCsvLoader->setHeader(['Firstname', 'Name', 'Address', 'City', 'State', 'Zipcode']);
    $strtolowerCsvLoader = new CustomCsvLoader($pathToCsvStrtolowerOutput);

} catch(Exception $e){
    echo $e->getMessage();
    die();
}

$yaEtl->setProgressMod(1);

$yaEtl->getDispatcher()->addListener(FlowEvent::FLOW_START, function(FlowEventInterface $event) {
    dump('Début du flow');
});

$yaEtl->getDispatcher()->addListener(FlowEvent::FLOW_PROGRESS, function(FlowEventInterface $event) {
    dump('étape - id node : ' . $event->getNode()->getId());
});

$yaEtl->getDispatcher()->addListener(FlowEvent::FLOW_SUCCESS, function(FlowEventInterface $event) {
    dump('Succès du flow');
});


// premier etl - output dans un fichier csv
$yaEtl
    ->from($csvExtractor)
//    ->qualify(new CallableQualifier(function($record){
//        return $record[5]==" 08075";
//    }))
    //->join($csvExtractor, $csvExtractor2, new OnClose())
    ->transform($strToUpperTransformer)
    ->to($strtoupperCsvLoader)
    //->exec()
;

// Ajout d'une phase à l'etl
$yaEtl
    ->transform($strToLowerTransformer)
    ->to($strtolowerCsvLoader)
    ->exec()
;


$callableExecNode = new CallableNode(function($param) {
    return $param + 1;
}, true);


dump($result = $callableExecNode->exec(1));

$callableTraversableNode = new CallableNode(function($param) {
    for($i = 1; $i < 5; $i++) {
        yield $param + $i;
    }
}, true, true);


foreach ($callableTraversableNode->getTraversable(10) as $result) {
    //dump($result);
}

$registry = new FlowRegistry;

dump($registry->getNode($callableTraversableNode->getId()));
dump($callableTraversableNode->getId());



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