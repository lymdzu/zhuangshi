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
     * 游客报名
     */
    public function signup()
    {
        $username = trim($this->input->post("username"));
        $phone = $this->input->post("phone");
        if (!isset($username) || empty($username)) {
            $this->json_result(400, "", "请输入姓名");
        }
        if (!isset($phone) || empty($phone)) {
            $this->json_result(400, "", "请输入手机号");
        }
        if (!is_mobile_num($phone)) {
            $this->json_result(400, "", "抱歉,请输入正确的手机号码");
        }
        $this->load->model("UserModel", "user", true);
        $add_status = $this->user->signup_user($username, $phone);
        if ($add_status) {
            $this->json_result(0, "报名提交成功");
        } else {
            $this->json_result(400, "", "抱歉报名失败");
        }
    }
    public function style()
    {
        $this->load->model("CompanyModel", "company", true);
        $exams = $this->company->get_case_example();
        $this->vars["exams"] = $exams;
        $this->vars["gray_bg"] = true;
        $this->page("style.html");
    }

    /**
     * 经典案例
     */
    public function classic_case()
    {
        $this->load->model("CompanyModel", "company", true);
        $exams = $this->company->get_case_example();
        $this->vars["exams"] = $exams;
        $this->vars['page'] = "case";
        $this->page("case.html");
    }

    public function case_desc()
    {
        $case_id = $this->input->get("case_id");
        $this->load->model("CompanyModel", "company", true);
        $pics = $this->company->get_case_pics($case_id);
        $exams = $this->company->get_case_example();
        $this->vars["exams"] = $exams;
        $this->vars['pics'] = $pics;
        $this->vars['page'] = "desc";
        $this->page("case_desc.html");
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