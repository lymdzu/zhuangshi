<?php
/**
 * 文件名称:common_helper.php
 * 摘    要:
 * 修改日期: 28/8/17
 * 作    者:liuyongming@shopex.cn
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Common Function Helpers
 *
 * @package        CodeIgniter
 * @subpackage     Helpers
 * @category       Helpers
 * @author         liuyongming@shopex.cn
 */
// ------------------------------------------------------------------------

if (!function_exists('is_mobile_num')) {

    /**
     * 判断是否是手机号码
     * @param $mobile
     * @return bool
     * @author liuyongming@shopex.cn
     */
    function is_mobile_num($mobile)
    {
        $mobile = intval($mobile);
        if (preg_match("/^1[34578]{1}\d{9}$/", $mobile)) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('is_phone_no')) {
    /**
     * 判断是否是电话号码
     * @param $phone
     * @return bool
     * @author liuyongming@shopex.cn
     */
    function is_phone_no($phone)
    {
        $phone = intval($phone);
        if (!preg_match("/(^0\d{2,3}-?\d{7,8}$)|(^0\d{2,3}-?\d{7,8}-?\d{1,3}$)|(^(400|800)-?\d{3,4}-?\d{3,4}$)|(^1[34578]\d{1}( |-)?\d{4}( |-)?\d{4}$)/", $phone)) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('curl_post')) {
    /**
     * 获取http POST请求
     * @param $url
     * @param $data
     * @return mixed
     */
    function curl_post($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
