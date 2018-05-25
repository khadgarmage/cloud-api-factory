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

    public function downLoad($strRemotePath, $strLocalPath = '.')
    {
        $strRemotePath = ltrim($strRemotePath,'/');
        if (strrchr($strLocalPath, '/') == '/') {
            $strLocalPath .= basename($strRemotePath);
        }
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
            $strRemotePath = trim($strRemotePath,'/') . '/' . $strLName;
        }
        $this->objClient->putObject(array(
            'Bucket' => $this->strBucket,
            'Key'    => $strRemotePath,
            'ACL'    => 'public-read',
            'SourceFile' => $strLocalPath,
        ));
    }
}