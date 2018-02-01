<?php

/**
 * �ļ�����:CompanyModel.php
 * ժ    Ҫ:
 * �޸�����: 2018/2/1
 * ��    ��:
 */
class CompanyModel extends MY_Model
{
    /**
     * ��ȡ��ư����б�
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
     * ��ӱ�����ư���
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