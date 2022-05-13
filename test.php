<?php
require_once 'vendor/autoload.php';
$httpreq = new GuzzleHttp\Client();
$response = $httpreq->get('https://www.pixiv.net/ajax/illust/98301246?lang=zh');
var_dump($response->getBody()->getContents());