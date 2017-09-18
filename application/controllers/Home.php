<?php

/**
 * 文件名称:Home.php
 * 摘    要:
 * 修改日期: 30/8/17
 */
class Home extends PublicController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index()
    {
        $this->vars['page'] = "home";
        $this->page("home.html");
    }

    /**
     * 经典案例
     */
    public function classic_case()
    {
        $this->vars['page'] = "case";
        $this->page("case.html");
    }

    /**
     * 团队介绍
     */
    public function team()
    {
        $this->vars['page'] = "team";
        $this->page("team.html");
    }

    /**
     * 生活照
     */
    public function life()
    {
        $this->vars['page'] = "life";
        $this->page("life.html");
    }
}