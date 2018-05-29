<?php
/**
 * Created by PhpStorm.
 * User: liuxiaochun
 * Date: 2018/5/24
 * Time: 下午5:13.
 */

namespace KDGCA\Storage;

abstract class Inf
{
    /**
     * @param string $strRemotePath the file path of remote server
     * @param string $strLocalPath the file path of local machine
     * @param array $arrOpt options
     * @return array
     */
    public abstract function downLoad($strRemotePath, $strLocalPath = ".", $arrOpt = array());

    /**
     * @param string $strLocalPath the file path of local machine
     * @param string $strRemotePath the file path of remote server
     * @param array $arrOpt options
     * @return array
     */
    public abstract function upLoad($strLocalPath, $strRemotePath = "/", $arrOpt = array());
}
