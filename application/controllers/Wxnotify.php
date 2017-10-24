<?php

/**
 * 文件名称:Wechatnotify.php
 * 摘    要:
 * 修改日期: 16/11/3
 */
class Wxnotify extends PublicController
{
    public function __construct()
    {
        require_once APPPATH . "helpers/Wechat.php";
        parent::__construct();
    }

    public function notify()
    {
        log_message("info", "weixin response event, get:" . var_export($_GET, true) . ", post:" . var_export($_POST, true));
        $this->load->model("UserModel", "user", true);
        $this->load->model("SceneModel", "scene", true);
        $options = array(
            "appid"          => QIUKONG_APPID,
            "appsecret"      => QIUKONG_SECRET,
            "token"          => QIUKONG_TOKEN,
            "encodingaeskey" => ENCODING_AES_KEY,
        );
        $weObj = new Wechat($options);
        $weObj->valid(true);
        $event = $weObj->getRev()->getRevEvent();
        $openid = $weObj->getRev()->getRevFrom();
        $our = $weObj->getRev()->getRevTo();
        $text = $weObj->getRev()->getRevContent();
        $msgtype = $weObj->getRev()->getRevType();
        log_message("info", "weixin response event," . var_export($event, true));
        if ($event['event'] == "subscribe") {

            $weObj->text("你好,欢迎关注秋空DESIGN,回复“帮助”,查看使用帮助。")->reply();
        }
        log_message("info", "weixin text and msgtype" . $text . $msgtype);
        if ($msgtype == "text") {
            if ($text == "帮助") {
                $msg = array(
                    "ToUserName"   => $openid,
                    "FromUserName" => $our,
                    "CreateTime"   => time(),
                    "MsgType"      => "news",
                    "ArticleCount" => 5,
                    "Articles"     => array(
                        array(
                            "Title"       => "如何设置一店多人收通知?",
                            "Description" => "",
                            "PicUrl"      => "https://mmbiz.qlogo.cn/mmbiz_jpg/e9HZsSmNVsqxqyENMMNcIyvIC90B63xIp5ghpla3q7AG4bALBGXMjGFJ7Nib54ccELoCLicibhcXmOURFv8PLLtKQ/0?wx_fmt=jpeg",
                            "Url"         => "http://mp.weixin.qq.com/s/bAnGD8_a8rp5Xh8oqLZn5A"
                        ),
                        array(
                            "Title"       => "如何绑定银行卡? 提现?",
                            "Description" => "",
                            "PicUrl"      => "https://mmbiz.qlogo.cn/mmbiz_png/e9HZsSmNVsqxqyENMMNcIyvIC90B63xIzDOrJibNZRuxSloqMzUwZZgDqyTUeRuiaBI2H1bvQ0Z9ibcWNe7U15AkA/0?wx_fmt=png",
                            "Url"         => "http://mp.weixin.qq.com/s/cdWl-1XHKdv08LLl4NVnrQ"
                        ),
                        array(
                            "Title"       => "我要印刷收款码怎么弄?",
                            "Description" => "",
                            "PicUrl"      => "https://mmbiz.qlogo.cn/mmbiz_png/e9HZsSmNVsqxqyENMMNcIyvIC90B63xInDek45pIKm59KWUFibTosqKVYiaC9yhkJgiayibUN1ia57ZSsTUHN9qGjow/0?wx_fmt=png",
                            "Url"         => "http://mp.weixin.qq.com/s/1x7E4ACF5UChKYbMzv1baw"
                        ),
                        array(
                            "Title"       => "如何查询及导出对账单",
                            "Description" => "",
                            "PicUrl"      => "https://mmbiz.qlogo.cn/mmbiz_png/e9HZsSmNVsqxqyENMMNcIyvIC90B63xIiaUOebqicQaTg1eFoao8FYGBAWoraPrsKdsaGQmGOUcCjq4iac7qotyIg/0?wx_fmt=png",
                            "Url"         => "http://mp.weixin.qq.com/s/q4SEOttmObh3bUct7pfQHg"
                        ),
                        array(
                            "Title"       => "客服联系方式",
                            "Description" => "",
                            "PicUrl"      => "https://mmbiz.qlogo.cn/mmbiz_png/e9HZsSmNVsqxqyENMMNcIyvIC90B63xI6g0nubIaEzRsSMGN9liaM0RRzbGAQF4jhLQgw4icoLQbicznhboPmpegg/0?wx_fmt=png",
                            "Url"         => "http://mp.weixin.qq.com/s/n1y9yIyIpk3GegbnvYOmFw"
                        )
                    ),
                    "FuncFlag"     => 0

                );
                log_message("info", "post to user data" . json_encode($msg));
                $weObj->reply($msg);
            } else {
                echo "";
                exit;
            }

        }
    }
}