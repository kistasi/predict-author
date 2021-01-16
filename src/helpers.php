<?php

function csvToArray(string $filename): array
{
    $result = [];
    $file = fopen($filename, 'r');
    while (($line = fgetcsv($file, '', ';')) !== false) {
        $result[] = $line;
    }
    fclose($file);

    return $result;
}

function getRandomElementsFromArray(array &$array, int $num): array
{
    $result = [];
    $indexes = array_rand($array, $num);

    foreach ($indexes as $index) {
        $result[] = $array[$index];
        unset($array[$index]);
    }

    return $result;
}
