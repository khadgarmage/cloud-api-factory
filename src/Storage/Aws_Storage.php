<?php
/**
 * Created by PhpStorm.
 * User: liuxiaochun
 * Date: 2018/5/24
 * Time: 下午6:31
 */

namespace KDGCA\Storage;


use Aws\S3\S3Client;

class Aws_Storage extends Inf
{
    private $objClient = null;
    private $strBucket = "";
    public function __construct($arrConfig)
    {
        $arrParam = array('version' => 'latest', 'region' => $arrConfig['region']);
        if (isset($arrConfig['credentials'])) {
            $arrParam['credentials'] = $arrConfig['credentials'];
        }
        $this->objClient = new S3Client($arrParam);
        $this->strBucket = $arrConfig['bucket'];
    }

    public function downLoad($strRemotePath, $strLocalPath = '.', $arrOpt = array())
    {
        try {
            $this->objClient->getObject(array(
                'Bucket' => $this->strBucket,
                'Key' => $strRemotePath,
                'SaveAs' => $strLocalPath
            ));
            return array("code" => 0);
        } catch(\Exception $e) {
            return array("code" => $e->getCode(), "msg" => $e->getMessage());
        }
    }

    public function upLoad($strLocalPath, $strRemotePath = '/', $arrOpt = array())
    {
        try {
            $strRemotePath = ltrim($strRemotePath, '/');
            if (empty($strRemotePath)) {
                $strRemotePath = './';
            }
            $arrDefault = array(
                'Bucket' => $this->strBucket,
                'Key' => $strRemotePath,
                'ACL' => 'public-read',
                'SourceFile' => $strLocalPath,
            );
            $arrObj = array_merge($arrDefault, $arrOpt);
            $ret = $this->objClient->putObject($arrObj);
            if (empty($ret['ObjectURL'])) {
                return array("code" => 100001, "msg" => 'url is empty');
            }
            return array("code" => 0);
        } catch(\Exception $e) {
            return array("code" => $e->getCode(), "msg" => $e->getMessage());
        }
    }
}