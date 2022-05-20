<?php
/*
Copyright (C) 2022 BuleWhale

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
require_once 'function.php';
require_once 'defined.php';
require_once 'vendor/autoload.php';
/*  Init whoops and  */
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


$headerHttp = [
    'cookie'            => _COOKIE,
    'referer'           => _REFERER,
    'user-agent'        => _UA,
    'accept-language'   => _LANG
];

/*  MAIN
Variant:
--  $arrFile    : array     --枚举目录文件
--  $numArr     : int       --获取$arrFile成员数
--  $pID        : int       --图片illust id
--  $pageCount  : int       --图片有1p, 2p等, 这里用来区分1或2
--  $pathParts  : string    --暂存pathinfo的变量
--  $reqUrl     : string    --图片url
--  $httpReq    : var       --GuzzleHttp\Client句柄
--  $HttpRepose : string    --变量httpReq的响应
--  $fileHand   : res       --保存图片文件句柄
*/

$arrFile = dirToArray('./testdir/');
$numArr = count($arrFile);
$httpReq = new GuzzleHttp\Client([
    'headers'           => $headerHttp,
    'connect_timeout'   => 3
]);
for ($i = 0; $i < $numArr; $i++) {
    $pID = getPid($arrFile[$i]);
    $pageCount = pageCount($pID, $arrFile[$i]);
    if ($pageCount !== -1) {
        $req = getAjax($pID);
        if ($req['code'] !== 200 || 204 || 206) {
            throw new Exception('Notice: PID ' . $pID . ' has an error because the stats code is not 2xx. Stats Code is ' . $req['code'] . '. Please check this.');
        } else {
            if ($req[$pageCount] !== 'none') {
                $httpRepose = $httpReq->request('GET', $req[$pageCount])->getBody()->getContents();
                $pathParts = pathinfo($req[$pageCount]);
                $fileHandle = fopen('dl/' . $pathParts['basename'], "w") or throw new Exception("Warning: Unable to create/write " . $pathParts['basename']);
                fwrite($fileHandle, $httpRepose);
                fclose($fileHandle);
            } else {
                throw new Exception("Notice: Unable to find PID" . $pID . " illust.");
            }
        }
        throw new Exception("Notice: Unable to find PID" . $pID . " illust.");
    }
}
echo ('Done!');
