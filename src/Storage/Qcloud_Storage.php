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
            return array("code" => 0);
        } catch (\Exception $e) {
            return array("code" => $e->getCode(), "msg" => $e->getMessage());
        }
    }

    public function upLoad($strLocalPath, $strRemotePath = '/')
    {
        try {
            $ret = $this->objClient->putObject(array(
            'Bucket' => $this->strBucket,
            'Key' => $strRemotePath,
            'Body' => fopen($strLocalPath, 'rb')));
            if (empty($ret['ObjectURL'])) {
                return array("code" => 100001, "msg" => 'url is empty');
            }
            return array("code" => 0);
        } catch (\Exception $e) {
            return array("code" => $e->getCode(), "msg" => $e->getMessage());
        }
    }
}