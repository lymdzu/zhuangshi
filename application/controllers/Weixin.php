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
        $this->load->model("MenuModel", "menu", true);
        $first_level = $this->menu->get_category_by_level(1);
        $category = array();
        if (!empty($first_level)) {
            foreach ($first_level as $first_key => $list) {
                if ($list['has_child'] == 1) {
                    $category[$first_key]["name"] = $list['name'];
                    $second_level = $this->menu->get_category_by_level(2, $list['id']);
                    $second = array();
                    if (!empty($second_level)) {
                        foreach ($second_level as $second_key => $second_list) {
                            $second[$second_key]["type"] = "view";
                            $second[$second_key]["name"] = $second_list["name"];
                            $second[$second_key]['url'] = $second_list["href"];
                        }
                        $category[$first_key]["sub_button"] = $second;
                    }
                    else
                    {
                        $category[$first_key]["type"] = "view";
                        $category[$first_key]["url"] = $list['href'];
                    }
                } else {
                    $category[$first_key] = array("type" => "view", "name" => $list['name'], "url" => $list['href']);
                }

            }
        } else {
            $this->json_result(RECORD_NOT_FOUND, "", "没有所要生成的菜单");
        }

        $menu = array(
            "button" => $category
        );
        $status = $wechat->createMenu($menu);
        if ($status) {
            $this->json_result(REQUEST_SUCCESS, "创建微信菜单成功");
        } else {
            $this->json_result(API_ERROR, "", "创建微信菜单失败");
        }
    }
}