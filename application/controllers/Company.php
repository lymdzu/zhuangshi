<?php

/**
 * 文件名称:Company.php
 * 摘    要:
 * 修改日期: 29/10/17
 */
class Company extends AdController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 设计师管理
     * @author
     */
    public function designer()
    {
        $this->page("company/designer.html");
    }

    /**
     * 设计风格管理
     * @author
     */
    public function style()
    {
        $this->load->model("CompanyModel", "company", true);
        $style_list = $this->company->get_style_list();
        $this->vars['style_list'] = $style_list;
        $this->page("company/style_list.html");
    }

    /**
     * 添加设计风格
     * @author liuyongming@shopex.cn
     */
    public function add_style()
    {
        $style_name = $this->input->post("name");
        $style_desc = $this->input->post("desc");
        if (empty($style_name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写设计风格名称");
        }
        if (empty($style_desc)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写设计风格描述");
        }
        $this->load->model("CompanyModel", "company", true);
        $add_status = $this->company->add_new_style($style_name, $style_desc);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "添加成功");
        } else {
            $this->json_result(API_ERROR, "", "设计风格添加失败");
        }
    }
}