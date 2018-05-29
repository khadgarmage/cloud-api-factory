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

    public function downLoad($strRemotePath, $strLocalPath = '.', $arrOpt = array())
    {
        try {
            $strLocal = fopen($strLocalPath, 'w');
            $this->objClient->read($strRemotePath, $strLocal);
            return array("code" => 0);
        } catch (\Exception $e) {
            return array("code" => $e->getCode(), "msg" => $e->getMessage());
        }
    }

    public function upLoad($strLocalPath, $strRemotePath = '/', $arrOpt = array())
    {
        try {
            $this->objClient->write($strRemotePath, fopen($strLocalPath, 'r'), $arrOpt);
            return array("code" => 0);
        } catch (\Exception $e) {
            return array("code" => $e->getCode(), "msg" => $e->getMessage());
        }
    }
}