<?php
namespace Nonda\Util;

use Nonda\Laravel\Proxy\Nonda;
use Nonda\Parsedb\Driver\CursorWrapper;
use Nonda\Parsedb\Model\Document;

class CsvUtil
{
    public static $tempPath;

    public static function getTempPath()
    {
        if (self::$tempPath) {
            return self::$tempPath;
        }

        return sys_get_temp_dir();
    }

    public static function makeCsv($fileName, $body, $saveToS3 = true)
    {
        $tempDir = self::getTempPath() . '/newfile.csv';
        $file = fopen($tempDir,'w+');
        fwrite($file, $body);

        if ($saveToS3) {
            Nonda::s3Handler()->uploadCsv($fileName,  file_get_contents($tempDir), 'text/csv');
        }

        fclose($file);
        return $tempDir;
    }

    public static function makeCsvFromArray($fileName, $array, $saveToS3 = true)
    {
        $csvBody = '';

        foreach ($array as $num => $row) {
            $csvBody .=($num+1) . ',"' . implode('","', $row) . '"' . PHP_EOL;
        }

        $csvHeader =' ,"'. implode('","', array_keys($row)). '"' . PHP_EOL;

        return self::makeCsv($fileName, $csvHeader.$csvBody, $saveToS3);
    }


    /**
     * @param CursorWrapper $list
     */
    public static function makeCsvFromDocuments($fileName, $list, $needFields = [], $saveToS3)
    {
        $csvBody = '';
        $structure = [];

        foreach ($list as $num => $document) {

            if (!$document) {
                continue;
            }

            if (!$structure) {
                $structure = $document->getStructure();
            }

            $temp = [];

            foreach ($document->getArrayCopy() as $field => $value) {
                if ($needFields && !in_array($field, $needFields)) {
                    continue;
                }

                if (!array_key_exists($field, $structure)) {
                    continue;
                }

                $temp[$field] = self::fieldFormat($structure, $field, $value);
            }

            $csvBody .= ($num+1).',"'.implode('","', $temp). '"'.PHP_EOL;
        }

        $csvHeader = ' ,'.implode(',', array_keys($temp)).PHP_EOL;

        return self::makeCsv($fileName, $csvHeader.$csvBody, $saveToS3);
    }

    private static function fieldFormat($structure, $field, $value)
    {
        if (!array_key_exists($field, $structure)) {
            return $value;
        }

        switch ($structure[$field])
        {
            case Document::TYPE_STRING:
            case Document::TYPE_INT:
            case Document::TYPE_FLOAT:
            case Document::TYPE_BOOL:
            case Document::TYPE_FILE:
            case Document::TYPE_MIXED:
            case Document::TYPE_FOREIGN:
                return $value;
                break;
            case Document::TYPE_ARRAY:
                return json_encode($value);
                break;
            case Document::TYPE_DATETIME:
                return $value->format('Y-m-d H:i:s');
                break;
        }
    }
}
