<?php
/**
 * Created by PhpStorm.
 * User: liuxiaochun
 * Date: 2018/5/24
 * Time: 下午6:31
 */

namespace KDGCA\Storage;


use Qcloud\Cos\Client;

class Qcloud_Storage extends Inf
{
    private $objClient = null;
    private $strBucket = "";
    public function __construct($arrConfig)
    {
        $this->objClient = new Client(array('region' => $arrConfig['region'],
            'credentials' => $arrConfig['credentials']));
        $this->strBucket = $arrConfig['bucket'];
    }

    public function downLoad($strRemotePath, $strLocalPath = '.')
    {
        try {
            $strRemotePath = ltrim($strRemotePath,'/');
            if (strrchr($strLocalPath, '/') == '/') {
                $strLocalPath .= basename($strRemotePath);
            }
            $this->objClient->getObject(array(
                'Bucket' => $this->strBucket,
                'Key' => $strRemotePath,
                'SaveAs' => $strLocalPath));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function upLoad($strLocalPath, $strRemotePath = '/')
    {
        try {
            $strLName = basename($strLocalPath);
            if (strrchr($strRemotePath, '/') == '/') {
                $strRemotePath = trim($strRemotePath,'/') . '/' . $strLName;
            }
            $result = $this->objClient->putObject(array(
                'Bucket' => $this->strBucket,
                'Key' => $strRemotePath,
                'Body' => fopen($strLocalPath, 'rb')));
            print_r($result);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}