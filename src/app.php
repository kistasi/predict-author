<?php

error_reporting(E_ERROR | E_PARSE);

require 'vendor/autoload.php';
require 'helpers.php';

use Phpml\Classification\KNearestNeighbors;

const ARTICLES_TO_PREDICT_COUNT = 500;
const INDEX_TITLE = 0;
const INDEX_AUTHOR = 1;
const INDEX_TOPIC = 2;

$csvFilename = __DIR__ . './../data/444-articles.csv';
$articlesToTrain = csvToArray($csvFilename);
shuffle($articlesToTrain);
$articlesToPredict = getRandomElementsFromArray($articlesToTrain, ARTICLES_TO_PREDICT_COUNT);

$trainer = [];
foreach ($articlesToPredict as $article) {
    $trainer['samples'][] = [ $article[INDEX_TITLE], $article[INDEX_TOPIC] ];
    $trainer['labels'][] = $article[INDEX_AUTHOR];
}

$classifier = new KNearestNeighbors();
$classifier->train($trainer['samples'], $trainer['labels']);

$successCount = 0;
$failCount = 0;
foreach ($articlesToPredict as $articleToPredict) {
    $predictedValue = $classifier->predict([$articleToPredict[INDEX_TITLE], $articleToPredict[INDEX_TOPIC]]);

    if ($predictedValue === $articleToPredict[INDEX_AUTHOR]) {
        $successCount++;
    } else {
        $failCount++;
    }
}

$successRate = 100 * $successCount / ($successCount + $failCount);

echo 'All: ' . ARTICLES_TO_PREDICT_COUNT . PHP_EOL;
echo 'Success count: ' . $successCount . PHP_EOL;
echo 'Fail count: ' . $failCount . PHP_EOL;
echo 'Success rate: ' . $successRate . '%' . PHP_EOL;
