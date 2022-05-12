<?php
require_once 'defined.php';
/* Main */
function getAjax($picId)
{
    $ajaxCont = curl_init(); //$ajax_c --> ajax_content
    $Url = 'https://www.pixiv.net/ajax/illust/' . $picId . "?lang=zh";
    curl_init_func($ajaxCont, $Url, _REFER, _COOKIES, _UA);
    $t = [];
    $t = json_decode(curl_exec($ajaxCont), true);
    //get page number
    $picNum = $t['body']['pageCount'];
    $picUrl = $t['body']['urls']['original'];
    $pathParts = pathinfo($picUrl);
    $arrUrl = [];
    for ($i = 0; $i < $picNum; $i++) {
        $arrUrl[$i] = $pathParts['dirname'] . '/' . $picId . '_p' . $i . '.' . $pathParts['extension'];
    }
    return $arrUrl;
}

function dirToArray($dir)
{
    $result = [];
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $result[] = $value;
            }
        }
    }
    return $result;
}

function get_between($input, $start, $end)
{
    $substr = substr($input, strlen($start) + strpos($input, $start), (strlen($input) - strpos($input, $end)) * (-1));
    return $substr;
}

function getPId($name)
{
    return (int)get_between($name, "", "_p");
}

function pageCount($pid, $name)
{
    if (substr_count($name, "_master") != 0) {
        return (int)get_between($name, $pid . "_p", "_master");
    } else return -1;
}

function curl_init_func($Curl_Handle, $Url, $Refer, $COOKIES, $UA)
{
    curl_setopt($Curl_Handle, CURLOPT_REFERER, $Refer);
    curl_setopt($Curl_Handle, CURLOPT_COOKIE, $COOKIES);
    curl_setopt($Curl_Handle, CURLOPT_USERAGENT, $UA);
    curl_setopt($Curl_Handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($Curl_Handle, CURLOPT_URL, $Url);
}
