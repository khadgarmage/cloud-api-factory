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
    private $strKey = "";
    public function __construct($arrConfig)
    {
        $this->objClient = new S3Client(array('version' => 'latest', 'region' => $arrConfig['region']));
        $this->strBucket = $arrConfig['bucket'];
        $this->strKey = $arrConfig['key'];
    }

    public function downLoad($strRemotePath, $strLocalPath = '.')
    {
        $this->objClient->getObject(array(
            'Bucket' => $this->strBucket,
            'Key' => $strRemotePath,
            'SaveAs' => $strLocalPath
        ));
    }

    public function upLoad($strLocalPath, $strRemotePath = '/')
    {
        $strLName = basename($strLocalPath);
        $strRName = basename($strRemotePath);
        if ($strLName != $strRName) {
            $strRemotePath = rtrim($strRemotePath,'/') . '/' . $strLName;
        }
        $this->objClient->putObject(array(
            'Bucket' => $this->strBucket,
            'Key'    => $strRemotePath,
            'ACL'    => 'public-read',
            'SourceFile' => $strLocalPath,
        ));
    }
}