<?php
require_once 'function.php';
require_once 'defined.php';
$arrFile = [];
$pID = null;
$pName = null;
$pageCount = null;
$curlUrl = null;
/*  MAIN  */
$arrFile = dirToArray('./testdir/');
$numArr = count($arrFile);

$curl = curl_init();

for ($i = 0; $i < $numArr; $i++) {
    $pID = getPid($arrFile[$i]);
    $pageCount = pageCount($pID, $arrFile[$i]);
    if ($pageCount != -1) {
        $curlUrl = getAjax($pID)[$pageCount];
        $pathParts = pathinfo($curlUrl);
        curl_init_func($curl, $curlUrl, _REFER, _COOKIES, _UA);
        $picRes = curl_exec($curl);
        $fileHand = fopen('dl/' . $pathParts['basename'], "w") or die("Unable to create/write " . $pathParts['basename']);
        fwrite($fileHand, $picRes);
        fclose($fileHand);
    }
}
echo ('Done! ');
exit();
