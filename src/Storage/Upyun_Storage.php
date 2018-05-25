<?php
/**
 * Created by PhpStorm.
 * User: liuxiaochun
 * Date: 2018/5/24
 * Time: 下午6:31
 */

namespace KDGCA\Storage;


use Upyun\Config;
use Upyun\Upyun;

class Upyun_Storage extends Inf
{
    private $objClient = null;
    public function __construct($arrConfig)
    {
        $arrConfig = new Config($arrConfig['bucket'], $arrConfig['user'], $arrConfig['pwd']);
        $this->objClient = new Upyun($arrConfig);
    }

    public function downLoad($strRemotePath, $strLocalPath = '.')
    {
        $strRemotePath = ltrim($strRemotePath,'/');
        if (strrchr($strLocalPath, '/') == '/') {
            $strLocalPath .= basename($strRemotePath);
        }
        $strLocal = fopen($strLocalPath, 'w');
        $this->objClient->read($strRemotePath, $strLocal);
    }

    public function upLoad($strLocalPath, $strRemotePath = '/')
    {
        $strLName = basename($strLocalPath);
        if (strrchr($strRemotePath, '/') == '/') {
            $strRemotePath = trim($strRemotePath,'/') . '/' . $strLName;
        }
        $this->objClient->write($strRemotePath, fopen($strLocalPath, 'r'));
    }
}