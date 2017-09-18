<?php

/**
 * 文件名称:MY_Controller.php
 * 摘    要:
 * 修改日期: 2017/5/23
 */
class PublicController extends CI_Controller
{
    public $layout = "layout.html";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('smarty/Smarty');
        $this->load->library('session');
        $this->smarty->setCompileDir(APPPATH . '/cache/tpl');
        $this->smarty->setTemplateDir(VIEWPATH);
        $this->smarty->left_delimiter = '{{';
        $this->smarty->right_delimiter = '}}';
        $this->smarty->registerPlugin('function', 'site_url', array($this, 'smarty_modifier_site_url'));
        $this->smarty->registerPlugin('function', 'base_url', array($this, 'smarty_modifier_base_url'));
        $this->smarty->registerPlugin('modifier', 'site_url', array($this, 'smarty_modifier_site_url'));
        $this->smarty->registerPlugin('modifier', 'base_url', array($this, 'smarty_modifier_base_url'));
    }

    /**
     * 显示页面
     * @param $view
     */
    public function display($view)
    {
        echo $this->fetch($view);
        exit();
    }

    /**
     * 加载页面
     * @param $view
     */
    public function page($view)
    {
        $this->vars['__PAGE__'] = $view;
        if ($_SESSION['message'] && !$this->vars['message']) {

            $this->vars['error_message'] = $_SESSION['message'] . '';
            $this->vars['message_code'] = intval($_SESSION['message_code']);
            unset($_SESSION['message_code']);
            unset($_SESSION['message']);
        }
        $this->display($this->layout);
        exit();
    }

    public function result($succ, $message, $redirect = "", $external = false)
    {
        $result = array(
            'status'   => (bool)$succ,
            'message'  => $message,
            'redirect' => $this->config->base_url($redirect),
            'addon'    => ob_get_clean(),
            'external' => $external,
        );
        header('Content-Type: text/json;charset=utf8');
        echo json_encode($result);
        exit();
    }

    public function fetch($view)
    {
        $this->vars['BASE_URL'] = $this->config->base_url();
        $this->vars['SITE_URL'] = $this->config->site_url();
        foreach ($this->vars as $k => $v) {
            $this->smarty->assign($k, $v);
        }
        return $this->smarty->fetch($view);
    }

    public function smarty_modifier_base_url($s)
    {
        return $this->config->base_url($s);
    }


    public function smarty_modifier_site_url($s)
    {
        return $this->config->site_url($s);
    }

    public function smarty_modifier_filter_keyword($s)
    {
        $keywords = $this->config->config['filter_keyword'];
        return str_replace($keywords, '**', $s);
    }

    public function smarty_modifier_pager($page_num)
    {
        foreach ($_GET as $k => $v) {
            if ($k != 'p') {
                $new[$k] = $v;
            }
        }
        $new['p'] = $page_num;
        return http_build_query($new);
    }
}