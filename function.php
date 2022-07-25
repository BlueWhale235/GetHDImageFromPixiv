<?php
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
