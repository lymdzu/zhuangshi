<?php

/**
 * 文件名称:Weixin.php
 * 摘    要:
 * 修改日期: 16/11/2
 */
class Weixin extends AdController
{
    public function __construct()
    {
        require_once APPPATH . "helpers/wechat.php";
        parent::__construct();
    }

    public function menu()
    {
        $this->vars['appid'] = QIUKONG_APPID;
        $this->vars['secret'] = QIUKONG_SECRET;
        $this->page("weixin/menu.html");
    }

    public function createmenu()
    {
        $wechat = new Wechat(array("appid" => QIUKONG_APPID, "appsecret" => QIUKONG_SECRET));
        $menu = array(
            "button" => array(
                array(
                    "name"       => "秋空设计",
                    "sub_button" => array(
                        array(
                            "type" => "view",
                            "name" => "首页",
                            "url"  => "https://iqiukong.com/home/index"
                        ),
                        array(
                            "type" => "view",
                            "name" => "装修案例",
                            "url"  => "https://iqiukong.com/home/classic_case"
                        ),
                        array(
                            "type" => "view",
                            "name" => "设计团队",
                            "url"  => "https://iqiukong.com/home/team"
                        ),
                        array(
                            "type" => "view",
                            "name" => "团队生活",
                            "url"  => "https://iqiukong.com/home/life"
                        )
                    )
                ),
                array(
                    "type" => "view",
                    "name" => "客户点评",
                    "url"  => "http://m.dianping.com/shop/92816848"
                )
            )
        );
        $status = $wechat->createMenu($menu);
        if ($status) {
            $this->json_result(REQUEST_SUCCESS, "创建微信菜单成功");
        } else {
            $this->json_result(API_ERROR, "", "创建微信菜单失败");
        }
    }
}