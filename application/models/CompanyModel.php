<?php

/**
 * 文件名称:CompanyModel.php
 * 摘    要:
 * 修改日期: 2018/2/1
 * 作    者:
 */
class CompanyModel extends MY_Model
{
    /**
     * 获取设计案例列表
     * @return mixed
     * @author
     */
    public function get_style_list()
    {
        $this->db->where("status", 1);
        $query = $this->db->get("t_case");
        return $query->result_array();
    }

    /**
     * 添加保存设计案例
     * @param $name
     * @param $desc
     * @return bool
     * @author
     */
    public function add_new_style($name, $desc)
    {
        $style = array("name" => $name, "desc" => $desc, "create_time" => time(), "update_time" => time(), "status" => 1);
        $insert_status = $this->db->insert("t_case", $style);
        $row = $this->db->affected_rows();
        if ($insert_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }
}