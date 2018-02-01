<?php

/**
 * 文件名称:Admin.php
 * 摘    要:
 * 修改日期: 25/10/17
 */
class Admin extends AdController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 展示后台页面
     * @author
     */
    public function index()
    {
        $this->load->model("UserModel", "user", true);
        $page = $this->input->get("page");
        $offset = empty($page) ? 0 : (intval($page) - 1) * PAGESIZE;
        $total = $this->user->count_admin_list();
        $admin_list = $this->user->get_admin_list($offset, PAGESIZE);
        $this->load->library("tgpage", array('total' => $total, 'pagesize' => PAGESIZE));
        $this->vars['pagelist'] = $this->tgpage->showpage();
        $this->vars['admin_list'] = $admin_list;
        $this->page("admin/index.html");
    }

    /**
     * 添加管理员方法
     * @author
     */
    public function add_user()
    {
        $username = $this->input->post("user-name", true);
        $password = $this->input->post("password", true);
        $repwd = $this->input->post("repwd", true);
        if (trim($password) != trim($repwd)) {
            $this->json_result(PARAMETER_WRONG, "", "twice password are not same");
        }
        $this->load->model("UserModel", "user", true);
        $salt = mt_rand(100000, 999999);
        $newpwd = md5(trim($password . $salt));
        $insert_status = $this->user->insert_user($username, $newpwd, $salt);
        if ($insert_status) {
            $this->json_result(REQUEST_SUCCESS, "管理员添加成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器错误");
        }
    }

    /**
     * 操作管理员
     * @author
     */
    public function operate_admin()
    {
        $id = $this->input->post("id", true);
        $status = $this->input->post("status", true);
        $reverse_status = $status == 1 ? 0 : 1;
        $this->load->model("UserModel", "user", true);
        $opt_res = $this->user->operate_user(trim($id), $reverse_status);
        if ($opt_res) {
            $this->json_result(REQUEST_SUCCESS, "操作成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器错误");
        }
    }
}