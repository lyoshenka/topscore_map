#!/usr/bin/php
<?php

$zipfip = file_get_contents('zipfip');

$lookup = array();
foreach(explode("\n", $zipfip) as $line)
{
    list($zip,$fip) = explode(',',$line);
    $lookup[(int)$zip] = $fip;
}


$zips = file_get_contents('zip.tsv');
$count = 0;
$rates = array();
foreach(explode("\n", $zips) as $line)
{
    list($zip,$rate) = explode(',',preg_replace('/\s+/',',',$line));
    if ($zip == 'id') continue;

    $zip = (int)$zip;
    if (!isset($lookup[$zip]))
    {
        continue;
    }

    $fip = $lookup[(int)$zip];

    if (!isset($rates[$fip]))
    {
        $rates[$fip] = 0;
    }
    $rates[$fip] += $rate;
}


$maxRate = max($rates);
foreach($rates as $fip => $rate)
{
    echo $fip . ',' . ($rate/$maxRate) . "\n";
}