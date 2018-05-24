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
                $strClassName = ucfirst($arrItem['type']) . "Storage";
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
     * @return bool true(download success)/ false(download fail)
     */
    public function downLoad($strRemotePath, $strLocalPath = '.')
    {
        foreach (self::$_list as $k => $obj) {
            $obj->downLoad($strRemotePath, $strLocalPath);
        }
    }

    /**
     * @param string $strLocalPath the file path of local machine
     * @param string $strRemotePath the file path of remote server
     * @return bool true(upLoad success)/ false(upLoad fail)
     */
    public function upLoad($strLocalPath, $strRemotePath = '/')
    {
        foreach (self::$_list as $k => $obj) {
            $obj->upLoad($strRemotePath, $strLocalPath);
        }
    }
}
