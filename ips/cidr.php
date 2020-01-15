<?php

$app = new \Slim\Slim();

$app->container->singleton('logger', function () {
    $logger = new \Monolog\Logger('formaweb');
    $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));

    return $logger;
});

$app->container->singleton('db', function () {
    return new \FormaWeb\Librerias\BaseDatos("Credentials");
});

parseIps();

function parseIps()
{
    Consultas::createTable(\Slim\Slim::getInstance()->db);
    $fileips = fopen("Country-IPv4DB.csv", "r");

    while (($data = fgetcsv($fileips)) !== FALSE) {

        $range = cidrToRange($data[0]);
        $geonameid = $data[1];

        $iso = getIso($geonameid);

        Consultas::addToTable(\Slim\Slim::getInstance()->db, $range[0], $range[1], ip2long($range[0]), ip2long($range[1]), $iso[1], $iso[2]);
    }
}

function getIso ($geonameid){

    $fileiso = fopen("LocationsDB.csv", "r");

    while (($iso = fgetcsv($fileiso)) !== FALSE) {

        if(in_array($geonameid, $iso)){
            $iso === FALSE;
            return $iso;
        }
    }
}

function cidrToRange($cidr) {

    $range = array();

    $cidr = explode('/', $cidr);

    $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
    $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);

    return $range;
}

