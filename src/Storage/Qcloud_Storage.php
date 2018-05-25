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
            $this->objClient->putObject(array(
                'Bucket' => $this->strBucket,
                'Key' => $strRemotePath,
                'Body' => fopen($strLocalPath, 'rb')));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}