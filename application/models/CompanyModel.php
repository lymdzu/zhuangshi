<?php

class CompanyModel extends MY_Model
{
    /**
     * 获取案例列表
     * @return mixed
     * @author
     */
    public function get_style_list()
    {
        $query = $this->db->query("select c.id, c.name, c.style, c.desc, d.designer from t_case as c LEFT JOIN t_designer as d  ON d.id=c.designer WHERE c.status = 1 ORDER BY c.create_time desc");
        return $query->result_array();
    }
    public function get_style_exam($style)
    {
        $query = $this->db->query("select c.name, p.pic, c.id from t_case as c LEFT JOIN t_case_pic as p on c.id = p.case_id where p.is_default = 1 and c.style=?", $style);
        return $query->row_array();
    }

    /**
     * 添加案例
     * @param $name
     * @param $desc
     * @param $style
     * @param $designer
     * @return bool
     * @auther lymdzu@hotmail.com
     */
    public function add_new_style($name, $desc, $style, $designer)
    {
        $style = array("name" => $name, "style" => $style, "designer" =>$designer, "desc" => $desc, "create_time" => time(), "update_time" => time(), "status" => 1);
        $insert_status = $this->db->insert("t_case", $style);
        $row = $this->db->affected_rows();
        if ($insert_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 修改案例
     * @param $id
     * @param $name
     * @param $desc
     * @param $style
     * @param $designer
     * @return bool
     * @auther lymdzu@hotmail.com
     */
    public function edit_new_style($id, $name, $desc, $style, $designer)
    {
        $this->db->where("id", $id);
        $style = array("name" => $name, "style" => $style, "designer" =>$designer, "desc" => $desc, "create_time" => time(), "update_time" => time(), "status" => 1);
        $update_status = $this->db->update("t_case", $style);
        $row = $this->db->affected_rows();
        if ($update_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 添加案例图片
     * @param $case_id
     * @param $filename
     * @return bool
     * @auther liuyongming@shopex.cn
     */
    public function save_case_pic($case_id, $filename)
    {
        $pic = array("case_id" => $case_id, "pic" => $filename, "create_time" => time(), "status" => 1);
        $insert_status = $this->db->insert("t_case_pic", $pic);
        $row = $this->db->affected_rows();
        if ($insert_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取案例图片
     * @param $case_id
     * @return mixed
     * @auther liuyongming@shopex.cn
     */
    public function get_case_pics($case_id)
    {
        $this->db->where("case_id", $case_id);
        $query = $this->db->get("t_case_pic");
        return $query->result_array();
    }

    /**
     * 设置默认图片
     * @param $case_id
     * @param $pic_id
     * @return bool
     * @auther liuyongming@shopex.cn
     */
    public function set_default_pic($case_id, $pic_id)
    {
        $this->db->trans_start();
        $this->db->where("case_id", $case_id);
        $this->db->update("t_case_pic", array("is_default" => 0));
        $this->db->where(array("case_id" => $case_id, "id" => $pic_id));
        $this->db->update("t_case_pic", array("is_default" => 1));
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function get_case_example()
    {
        $query = $this->db->query("select * from t_case as c LEFT JOIN t_case_pic as p ON p.case_id = c.id WHERE p.is_default=1");
        return $query->result_array();
    }

    /**
     *
     * @param $style_id
     * @return mixed
     * @auther lymdzu@hotmail.com
     */
    public function get_case_exams($style_id)
    {
        $query = $this->db->query("select * from t_case as c LEFT JOIN t_case_pic as p ON p.case_id = c.id WHERE c.style=? and p.is_default=1", $style_id);
        return $query->result_array();
    }
}