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
        $this->vars['appid'] = MAFU_APPID;
        $this->vars['secret'] = MAFU_SECRET;
        $this->page("weixin/menu.html");
    }
    public function createmenu()
    {
        $appid = $this->input->post("appid", true);
        $secret = $this->input->post("secret", true);
        $wechat = new Wechat(array("appid" => $appid, "appsecret" => $secret));
        $menu = array("button" => array(array("type" => "view", "name" => "收银台", "url" => "https://mafuh5.teegon.com/dashboard"), array("type" => "view", "name" => "提现对账", "url" => "https://mafuh5.teegon.com/account"), array("type" => "view", "name" => "账户设置", "url" => "https://mafuh5.teegon.com/tools")));
        $status = $wechat->createMenu($menu);
        if($status)
        {
            $this->result(true, "创建微信菜单成功");
        }
        else
        {
            $this->result(false, "创建微信菜单失败");
        }
    }
}