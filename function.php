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

function getAjax($picId)
{
    $Header = [
        'cookie'            => _COOKIE,
        'referer'           => _REFERER,
        'user-agent'        => _UA,
        'accept-language'   => _LANG
    ];
    $Url = 'https://www.pixiv.net/ajax/illust/' . $picId . "?lang=zh";
    $request = new GuzzleHttp\Client([
        'headers'           => $Header,
        'connect_timeout'   => 3,
        'http_errors'       => false
    ]);
    $response = $request->request('GET', $Url);
    $response_cont = json_decode($response->getBody()->getContents(),true);
    //get page count
    $picCount = $response_cont['body']['pageCount'] ?? -1;
    $picUrl = $response_cont['body']['urls']['original'] ?? 'none';
    $pathParts = pathinfo($picUrl);
    $arr = [];
    for ($i = 0; $i < $picCount; $i++) {
        $arr[$i] = $pathParts['dirname'] . '/' . $picId . '_p' . $i . '.' . $pathParts['extension'];
    }
    $arr['code'] = $response->getStatusCode();
    return $arr;
}