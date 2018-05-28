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
    private static $_list = array();
    private static $_instance = null;
    private function __construct() {}

    /**
     * @param array $arrConfig type为类型，其他为该类型存储需要的配置
     * @return Factory
     * @throws \Exception
     */
    public static function getInstance($arrConfig) {
        if (is_null(self::$_instance)) {
            foreach ($arrConfig as $arrItem) {
                if (empty($arrItem['type'])) {
                    throw new \Exception("type not found.");
                }
                $strClassName = "KDGCA\\Storage\\" . ucfirst($arrItem['type']) . "_Storage";
                $obj = new $strClassName($arrItem);
                self::$_list[$arrItem['type']] = $obj;
            }
            self::$_instance = new Factory();
        }
        return self::$_instance;
    }

    /**
     * @param string $strRemotePath the file path of remote server
     * @param string $strLocalPath the file path of local machine
     * @return array
     */
    public function downLoad($strRemotePath, $strLocalPath = '.')
    {
        $strRemotePath = ltrim($strRemotePath,'/');
        if (strrchr($strLocalPath, '/') == '/') {
            $strLocalPath .= basename($strRemotePath);
        }
        foreach (self::$_list as $k => $obj) {
            $ret = $obj->downLoad($strRemotePath, $strLocalPath);
            if (!empty($ret['code'])) {
                $ret['msg'] = $k . ' ' . $ret['msg'];
                return $ret;
            }
        }
        return array('code' => 0);
    }

    /**
     * @param string $strLocalPath the file path of local machine
     * @param string $strRemotePath the file path of remote server
     * @return array
     */
    public function upLoad($strLocalPath, $strRemotePath = '/')
    {
        $strLName = basename($strLocalPath);
        if (strrchr($strRemotePath, '/') == '/') {
            $strRemotePath = trim($strRemotePath,'/') . '/' . $strLName;
        }
        foreach (self::$_list as $k => $obj) {
            $ret = $obj->upLoad($strLocalPath, $strRemotePath);
            if (!empty($ret['code'])) {
                $ret['msg'] = $k . ' ' . $ret['msg'];
                return $ret;
            }
        }
        return array('code' => 0);
    }
}
