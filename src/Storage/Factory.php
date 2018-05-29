<?php
/**
 * Created by PhpStorm.
 * User: liuxiaochun
 * Date: 2018/5/24
 * Time: 下午5:12.
 */

namespace KDGCA\Storage;

class Factory extends Inf
{
    private static $_instances = array();
    private $objCurr = null;
    private function __construct($arrConfig) {
        $strClassName = "KDGCA\\Storage\\" . ucfirst($arrConfig['type']) . "_Storage";
        $obj = new $strClassName($arrConfig);
        $this->objCurr = $obj;
    }

    /**
     * @param array $arrConfig type为类型，其他为该类型存储需要的配置
     * @return Factory
     * @throws \Exception
     */
    public static function getInstance($arrConfig) {
        $key = md5(json_encode($arrConfig));
        if (empty(self::$_instances[$key])) {
            if (empty($arrConfig['type'])) {
                throw new \Exception("type not found.");
            }
            self::$_instances[$key] = new Factory($arrConfig);
        }
        return self::$_instances[$key];
    }

    /**
     * @param string $strRemotePath the file path of remote server
     * @param string $strLocalPath the file path of local machine
     * @param array $arrOpt options
     * @return array
     */
    public function downLoad($strRemotePath, $strLocalPath = '.', $arrOpt = array())
    {
        $strRemotePath = ltrim($strRemotePath,'/');
        if (strrchr($strLocalPath, '/') == '/') {
            $strLocalPath .= basename($strRemotePath);
        }
        $ret = $this->objCurr->downLoad($strRemotePath, $strLocalPath, $arrOpt);
        return $ret;
    }

    /**
     * @param string $strLocalPath the file path of local machine
     * @param string $strRemotePath the file path of remote server
     * @param array $arrOpt options
     * @return array
     */
    public function upLoad($strLocalPath, $strRemotePath = '/', $arrOpt = array())
    {
        $strLName = basename($strLocalPath);
        if (strrchr($strRemotePath, '/') == '/') {
            $strRemotePath = trim($strRemotePath,'/') . '/' . $strLName;
        }
        $ret = $this->objCurr->upLoad($strLocalPath, $strRemotePath, $arrOpt);
        return $ret;
    }
}
