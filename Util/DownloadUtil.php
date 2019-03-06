<?php
namespace Nonda\Util;

class DownloadUtil
{
    public static function downloadFromUrl($url, $name, $dir = '')
    {
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', $url);

        if ($res->getStatusCode() != 200) {
            return '';
        }

        if (!$dir) {
            $dir = sys_get_temp_dir();
        }

        $path = $dir.'/'.$name;
        $body = $res->getBody();

        $file = fopen($path,'w+');
        fwrite($file,$body);
        fclose($file);

        return $path;
    }
}
