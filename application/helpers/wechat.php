<?php

/**
 * 文件名称:wechat.php
 * 摘    要:
 * 修改日期: 16/11/2
 */
class Wechat
{
    const API_URL_PREFIX  = 'https://api.weixin.qq.com/cgi-bin';
    const MENU_CREATE_URL = '/menu/create?';
    const AUTH_URL        = '/token?grant_type=client_credential&';
    const MSGTYPE_TEXT    = 'text';


    public $errCode;
    public $errMsg;

    private $token;
    private $encodingAesKey;
    private $encrypt_type;
    private $appid;
    private $appsecret;
    private $access_token;
    private $postxml;
    private $_receive;
    private $_funcflag    = false;
    private $_text_filter = true;
    private $_msg;

    /**
     * Wechat constructor.
     * @param $options
     */
    public function __construct($options)
    {
        $this->appid = isset($options['appid']) ? $options['appid'] : '';
        $this->appsecret = isset($options['appsecret']) ? $options['appsecret'] : '';
    }

    /**
     * 创建微信公众号菜单
     * @param $data
     * @return bool
     */
    public function createMenu($data)
    {
        log_message("info", "create weixin menu post data," . self::json_encode($data));
        if (!$this->access_token && !$this->getAccEssToken()) {
            return false;
        }
        $result = $this->http_post(self::API_URL_PREFIX . self::MENU_CREATE_URL . 'access_token=' . $this->access_token, self::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            log_message("info", "create weixin menu response," . $result);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 刷新微信
     * @return mixed
     */
    public function refreshAccessToken()
    {
        $appid = $this->appid;
        $secret = $this->appsecret;
        log_message('info', "[weixin_refreshAccessToken]:" . "appid:{$appid},secret:{$secret}");

        $re = $this->http_get(self::API_URL_PREFIX . self::AUTH_URL . "appid=" . $appid . "&secret=" . $secret);
        $re1 = json_decode($re, 1);
        $this->access_token = $re1["access_token"];
        $CI = get_instance();
        $CI->load->model('UserModel', 'user', true);
        $wxdb = $CI->user;
        if ($appid == QIUKONG_APPID) {
            $wxdb->query("INSERT INTO access_token (account, token, create_time) VALUES ('qiukong', $this->access_token, {time()}) ON DUPLICATE KEY UPDATE token = $this->access_token, create_time={time()}");
            $rows = $wxdb->db->affected_rows();
            log_message('info', "[refreshAccessToken]:" . "update_access_token, affected_rows:{$rows}, appid:{$this->appid },accessToken:{$this->access_token}");
        }
        return $this->access_token;
    }

    /**
     * For weixin server validation
     */
    private function checkSignature($str = '')
    {
        $signature = isset($_GET["signature"]) ? $_GET["signature"] : '';
        $signature = isset($_GET["msg_signature"]) ? $_GET["msg_signature"] : $signature; //如果存在加密验证则用加密验证段
        $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
        $nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : '';

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce, $str);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * For weixin server validation
     * @param bool|false $return
     * @return bool
     */
    public function valid($return = false)
    {
        $encryptStr = "";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $postStr = file_get_contents("php://input");
            $array = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->encrypt_type = isset($_GET["encrypt_type"]) ? $_GET["encrypt_type"] : '';
            if ($this->encrypt_type == 'aes') { //aes加密
                $encryptStr = $array['Encrypt'];
                $pc = new Prpcrypt($this->encodingAesKey);
                $array = $pc->decrypt($encryptStr, $this->appid);
                if (!isset($array[0]) || ($array[0] != 0)) {
                    if (!$return) {
                        die('decrypt error!');
                    } else {
                        return false;
                    }
                }
                $this->postxml = $array[1];
                if (!$this->appid) {
                    $this->appid = $array[2];
                }//为了没有appid的订阅号。
            } else {
                $this->postxml = $postStr;
            }
        } elseif (isset($_GET["echostr"])) {
            $echoStr = $_GET["echostr"];
            if ($return) {
                if ($this->checkSignature()) {
                    return $echoStr;
                } else {
                    return false;
                }
            } else {
                if ($this->checkSignature()) {
                    die($echoStr);
                } else {
                    die('no access');
                }
            }
        }

        if (!$this->checkSignature($encryptStr)) {
            if ($return) {
                return false;
            } else {
                die('no access');
            }
        }
        return true;
    }

    /**
     * json 编码
     * @param $arr
     * @return string
     */
    static function json_encode($arr)
    {
        if (count($arr) == 0) {
            return "[]";
        }
        $parts = array();
        $is_list = false;
        //Find out if the given array is a numerical array
        $keys = array_keys($arr);
        $max_length = count($arr) - 1;
        if (($keys [0] === 0) && ($keys [$max_length] === $max_length)) { //See if the first key is 0 and last key is length - 1
            $is_list = true;
            for ($i = 0; $i < count($keys); $i++) { //See if each key correspondes to its position
                if ($i != $keys [$i]) { //A key fails at position check.
                    $is_list = false; //It is an associative array.
                    break;
                }
            }
        }
        foreach ($arr as $key => $value) {
            if (is_array($value)) { //Custom handling for arrays
                if ($is_list) {
                    $parts [] = self::json_encode($value);
                } /* :RECURSION: */
                else {
                    $parts [] = '"' . $key . '":' . self::json_encode($value);
                } /* :RECURSION: */
            } else {
                $str = '';
                if (!$is_list) {
                    $str = '"' . $key . '":';
                }
                //Custom handling for multiple data types
                if (!is_string($value) && is_numeric($value) && $value < 2000000000) {
                    $str .= $value;
                } //Numbers
                elseif ($value === false) {
                    $str .= 'false';
                } //The booleans
                elseif ($value === true) {
                    $str .= 'true';
                } else {
                    $str .= '"' . addslashes($value) . '"';
                } //All other things
                // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
                $parts [] = $str;
            }
        }
        $json = implode(',', $parts);
        if ($is_list) {
            return '[' . $json . ']';
        } //Return numerical JSON
        return '{' . $json . '}'; //Return associative JSON
    }

    /**
     * 获取微信access_token
     * @return mixed
     */
    public function getAccEssToken()
    {
        if (!$this->access_token) {
            $CI = get_instance();
            $CI->load->model('Userinfo', 'user', true);
            $wxdb = $CI->user;
            if ($this->appid == TEEGON_APPID) {
                $wxdb->db->where("id", 1);
                $query = $wxdb->db->get("wx_access_token");
                $data = $query->row();
            } elseif ($this->appid == MAFU_APPID) {
                $wxdb->db->where("id", 3);
                $query = $wxdb->db->get("wx_access_token");
                $data = $query->row();
            } elseif ($this->appid == FULI_APPID) {
                $wxdb->db->where("id", 2);
                $query = $wxdb->db->get("wx_access_token");
                $data = $query->row();
            }
            if ((time() - $data->created > 600) || !$data->token) {
                $this->refreshAccessToken();
            } else {
                $this->access_token = $data->token;
            }
        }

        log_message('info', "[weixin_getAccEssToken]:" . "appid:{$this->appid },accessToken:{$this->access_token}");
        return $this->access_token;
    }

    /**
     * 接受微信消息
     * @return $this
     */
    public function getRev()
    {
        if ($this->_receive) {
            return $this;
        }
        $postStr = !empty($this->postxml) ? $this->postxml : file_get_contents("php://input");
        log_message("info", $postStr);
        if (!empty($postStr)) {
            $this->_receive = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return $this;
    }

    /**
     * 获取消息发送者
     */
    public function getRevFrom()
    {
        if (isset($this->_receive['FromUserName'])) {
            return $this->_receive['FromUserName'];
        } else {
            return false;
        }
    }

    /**
     * 获取消息接受者
     */
    public function getRevTo()
    {
        if (isset($this->_receive['ToUserName'])) {
            return $this->_receive['ToUserName'];
        } else {
            return false;
        }
    }

    /**
     * 获取接收消息的类型
     */
    public function getRevType()
    {
        if (isset($this->_receive['MsgType'])) {
            return $this->_receive['MsgType'];
        } else {
            return false;
        }
    }

    /**
     * 获取消息ID
     */
    public function getRevID()
    {
        if (isset($this->_receive['MsgId'])) {
            return $this->_receive['MsgId'];
        } else {
            return false;
        }
    }

    /**
     * 获取消息发送时间
     */
    public function getRevCtime()
    {
        if (isset($this->_receive['CreateTime'])) {
            return $this->_receive['CreateTime'];
        } else {
            return false;
        }
    }

    /**
     * 获取接收消息内容正文
     */
    public function getRevContent()
    {
        if (isset($this->_receive['Content'])) {
            return $this->_receive['Content'];
        } else {
            if (isset($this->_receive['Recognition'])) //获取语音识别文字内容，需申请开通
            {
                return $this->_receive['Recognition'];
            } else {
                return false;
            }
        }
    }

    /**
     * 获取接收消息图片
     */
    public function getRevPic()
    {
        if (isset($this->_receive['PicUrl'])) {
            return array(
                'mediaid' => $this->_receive['MediaId'],
                'picurl'  => (string)$this->_receive['PicUrl'],    //防止picurl为空导致解析出错
            );
        } else {
            return false;
        }
    }

    /**
     * 获取接收消息链接
     */
    public function getRevLink()
    {
        if (isset($this->_receive['Url'])) {
            return array(
                'url'         => $this->_receive['Url'],
                'title'       => $this->_receive['Title'],
                'description' => $this->_receive['Description']
            );
        } else {
            return false;
        }
    }

    /**
     * 获取接收地理位置
     */
    public function getRevGeo()
    {
        if (isset($this->_receive['Location_X'])) {
            return array(
                'x'     => $this->_receive['Location_X'],
                'y'     => $this->_receive['Location_Y'],
                'scale' => $this->_receive['Scale'],
                'label' => $this->_receive['Label']
            );
        } else {
            return false;
        }
    }

    /**
     * 获取上报地理位置事件
     */
    public function getRevEventGeo()
    {
        if (isset($this->_receive['Latitude'])) {
            return array(
                'x'         => $this->_receive['Latitude'],
                'y'         => $this->_receive['Longitude'],
                'precision' => $this->_receive['Precision'],
            );
        } else {
            return false;
        }
    }

    /**
     * 获取接收事件推送
     */
    public function getRevEvent()
    {
        if (isset($this->_receive['Event'])) {
            $array['event'] = $this->_receive['Event'];
        }
        if (isset($this->_receive['EventKey'])) {
            $array['key'] = $this->_receive['EventKey'];
        }
        if (isset($array) && count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 设置发送消息
     * @param string $msg
     * @param bool|false $append
     * @return array|string
     */
    public function Message($msg = '', $append = false)
    {
        if (is_null($msg)) {
            $this->_msg = array();
        } elseif (is_array($msg)) {
            if ($append) {
                $this->_msg = array_merge($this->_msg, $msg);
            } else {
                $this->_msg = $msg;
            }
            return $this->_msg;
        } else {
            return $this->_msg;
        }
    }

    /**
     * 过滤文字回复\r\n换行符
     * @param string $text
     * @return string|mixed
     */
    private function _auto_text_filter($text)
    {
        if (!$this->_text_filter) {
            return $text;
        }
        return str_replace("\r\n", "\n", $text);
    }

    /**
     * 设置回复消息
     * @param string $text
     * @return $this
     */
    public function text($text = '')
    {
        $FuncFlag = $this->_funcflag ? 1 : 0;
        $msg = array(
            'ToUserName'   => $this->getRevFrom(),
            'FromUserName' => $this->getRevTo(),
            'MsgType'      => self::MSGTYPE_TEXT,
            'Content'      => $this->_auto_text_filter($text),
            'CreateTime'   => time(),
            'FuncFlag'     => $FuncFlag
        );
        $this->Message($msg);
        return $this;
    }

    /**
     * 组CDATA格式
     * @param $str
     * @return string
     */
    public static function xmlSafeStr($str)
    {
        return '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $str) . ']]>';
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @return string
     */
    public static function data_to_xml($data)
    {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml .= "<$key>";
            $xml .= (is_array($val) || is_object($val)) ? self::data_to_xml($val) : self::xmlSafeStr($val);
            list($key,) = explode(' ', $key);
            $xml .= "</$key>";
        }
        return $xml;
    }

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id 数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    public function xml_encode($data, $root = 'xml', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
    {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<{$root}{$attr}>";
        $xml .= self::data_to_xml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 回复微信服务器
     * @param array $msg
     * @param bool|false $return
     * @return bool|string
     */
    public function reply($msg = array(), $return = false)
    {
        if (empty($msg)) {
            if (empty($this->_msg))   //防止不先设置回复内容，直接调用reply方法导致异常
            {
                return false;
            }
            $msg = $this->_msg;
        }
        $xmldata = $this->xml_encode($msg);
        if ($this->encrypt_type == 'aes') { //如果来源消息为加密方式
            $pc = new Prpcrypt($this->encodingAesKey);
            $array = $pc->encrypt($xmldata, $this->appid);
            $ret = $array[0];
            if ($ret != 0) {
                return false;
            }
            $timestamp = time();
            $nonce = rand(77, 999) * rand(605, 888) * rand(11, 99);
            $encrypt = $array[1];
            $tmpArr = array($this->token, $timestamp, $nonce, $encrypt);//比普通公众平台多了一个加密的密文
            sort($tmpArr, SORT_STRING);
            $signature = implode($tmpArr);
            $signature = sha1($signature);
            $xmldata = $this->generate($encrypt, $signature, $timestamp, $nonce);
        }
        if ($return) {
            return $xmldata;
        } else {
            echo $xmldata;
        }
    }

    /**
     * xml格式加密，仅请求为加密方式时再用
     * @param $encrypt
     * @param $signature
     * @param $timestamp
     * @param $nonce
     * @return string
     */
    private function generate($encrypt, $signature, $timestamp, $nonce)
    {
        //格式化加密信息
        $format = "<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>";
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }

    /**
     * http get请求
     * @param $url
     * @return bool|mixed
     */
    private function http_get($url)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /**
     * POST 请求
     * @param $url
     * @param $param
     * @param bool|false $post_file 是否文件上传
     * @return bool|mixed
     */
    private function http_post($url, $param, $post_file = false)
    {
        $curl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param)) {
            $strPOST = $param;
        } elseif ($post_file) {
            foreach ($param as $key => $val) {
                if (substr($val, 0, 1) == '@') {
                    $param[$key] = new \CURLFile(realpath(substr($val, 1)));
                }
            }
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($curl);
        $aStatus = curl_getinfo($curl);
        curl_close($curl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }
}

/**
 * PKCS7Encoder class
 *
 * 提供基于PKCS7算法的加解密接口.
 */
class PKCS7Encoder
{
    public static $block_size = 32;

    /**
     * 对需要加密的明文进行填充补位
     * @param $text 需要进行填充补位操作的明文
     * @return 补齐明文字符串
     */
    function encode($text)
    {
        $block_size = PKCS7Encoder::$block_size;
        $text_length = strlen($text);
        //计算需要填充的位数
        $amount_to_pad = PKCS7Encoder::$block_size - ($text_length % PKCS7Encoder::$block_size);
        if ($amount_to_pad == 0) {
            $amount_to_pad = PKCS7Encoder::block_size;
        }
        //获得补位所用的字符
        $pad_chr = chr($amount_to_pad);
        $tmp = "";
        for ($index = 0; $index < $amount_to_pad; $index++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }

    /**
     * 对解密后的明文进行补位删除
     * @param $text 解密后的明文
     * @return 删除填充补位后的明文
     */
    function decode($text)
    {

        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > PKCS7Encoder::$block_size) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

}

/**
 * Prpcrypt class
 *
 * 提供接收和推送给公众平台消息的加解密接口.
 */
class Prpcrypt
{
    public $key;

    function __construct($k)
    {
        $this->key = base64_decode($k . "=");
    }

    /**
     * 兼容老版本php构造函数，不能在 __construct() 方法前边，否则报错
     */
    function Prpcrypt($k)
    {
        $this->key = base64_decode($k . "=");
    }

    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @param string $appid appid
     * @return string 加密后的密文
     */
    public function encrypt($text, $appid)
    {

        try {
            //获得16位随机字符串，填充到明文之前
            $random = $this->getRandomStr();//"aaaabbbbccccdddd";
            $text = $random . pack("N", strlen($text)) . $text . $appid;
            // 网络字节序
            $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv = substr($this->key, 0, 16);
            //使用自定义的填充方式对明文进行补位填充
            $pkc_encoder = new PKCS7Encoder;
            $text = $pkc_encoder->encode($text);
            mcrypt_generic_init($module, $this->key, $iv);
            //加密
            $encrypted = mcrypt_generic($module, $text);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);

            //			print(base64_encode($encrypted));
            //使用BASE64对加密后的字符串进行编码
            return array(ErrorCode::$OK, base64_encode($encrypted));
        } catch (Exception $e) {
            //print $e;
            return array(ErrorCode::$EncryptAESError, null);
        }
    }

    /**
     * 对密文进行解密
     * @param string $encrypted 需要解密的密文
     * @return string 解密得到的明文
     */
    public function decrypt($encrypted, $appid)
    {

        try {
            //使用BASE64对需要解密的字符串进行解码
            $ciphertext_dec = base64_decode($encrypted);
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv = substr($this->key, 0, 16);
            mcrypt_generic_init($module, $this->key, $iv);
            //解密
            $decrypted = mdecrypt_generic($module, $ciphertext_dec);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);
        } catch (Exception $e) {
            return array(ErrorCode::$DecryptAESError, null);
        }


        try {
            //去除补位字符
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
            //去除16位随机字符串,网络字节序和AppId
            if (strlen($result) < 16) {
                return "";
            }
            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_appid = substr($content, $xml_len + 4);
            if (!$appid) {
                $appid = $from_appid;
            }
            //如果传入的appid是空的，则认为是订阅号，使用数据中提取出来的appid
        } catch (Exception $e) {
            //print $e;
            return array(ErrorCode::$IllegalBuffer, null);
        }
        if ($from_appid != $appid) {
            return array(ErrorCode::$ValidateAppidError, null);
        }
        //不注释上边两行，避免传入appid是错误的情况
        return array(0, $xml_content, $from_appid); //增加appid，为了解决后面加密回复消息的时候没有appid的订阅号会无法回复

    }


    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    function getRandomStr()
    {

        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

}

/**
 * error code
 * 仅用作类内部使用，不用于官方API接口的errCode码
 */
class ErrorCode
{
    public static $OK                     = 0;
    public static $ValidateSignatureError = 40001;
    public static $ParseXmlError          = 40002;
    public static $ComputeSignatureError  = 40003;
    public static $IllegalAesKey          = 40004;
    public static $ValidateAppidError     = 40005;
    public static $EncryptAESError        = 40006;
    public static $DecryptAESError        = 40007;
    public static $IllegalBuffer          = 40008;
    public static $EncodeBase64Error      = 40009;
    public static $DecodeBase64Error      = 40010;
    public static $GenReturnXmlError      = 40011;
    public static $errCode                = array(
        '0'     => '处理成功',
        '40001' => '校验签名失败',
        '40002' => '解析xml失败',
        '40003' => '计算签名失败',
        '40004' => '不合法的AESKey',
        '40005' => '校验AppID失败',
        '40006' => 'AES加密失败',
        '40007' => 'AES解密失败',
        '40008' => '公众平台发送的xml不合法',
        '40009' => 'Base64编码失败',
        '40010' => 'Base64解码失败',
        '40011' => '公众帐号生成回包xml失败'
    );

    public static function getErrText($err)
    {
        if (isset(self::$errCode[$err])) {
            return self::$errCode[$err];
        } else {
            return false;
        }
    }
}