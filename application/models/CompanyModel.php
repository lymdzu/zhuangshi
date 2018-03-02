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

    /**
     *
     * @param $style
     * @return mixed
     * @author liuyongming@shopex.cn
     */
    public function get_style_exam($style)
    {
        $query = $this->db->query("select c.name, p.pic, c.id, c.style from t_case as c LEFT JOIN t_case_pic as p on c.id = p.case_id where p.is_default = 1 and c.style=?", $style);
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
        $style = array("name" => $name, "style" => $style, "designer" => $designer, "desc" => $desc, "create_time" => time(), "update_time" => time(), "status" => 1);
        $insert_status = $this->db->insert("t_case", $style);
        $row = $this->db->affected_rows();
        if ($insert_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 添加设计师
     * @param $designer
     * @param $position
     * @param $employ_time
     * @param $idea
     * @param $expert
     * @param $designer_pic
     * @param $intro
     * @param $school
     * @param $works
     * @param $honor
     * @return bool
     * @auther lymdzu@hotmail.com
     */
    public function add_new_designer($designer, $position, $employ_time, $idea, $expert, $designer_pic, $intro, $school, $works, $honor)
    {
        $designer = array("designer" => $designer, "position" => $position, "employ_time" => $employ_time, "idea" => $idea, "expert" => $expert, "designer_pic" => $designer_pic, "create_time" => time(), "update_time" => time(), "intro" => $intro, "school" => $school, "works" => $works, "honor" => $honor);
        $insert_status = $this->db->insert("t_designer", $designer);
        $row = $this->db->affected_rows();
        if ($insert_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 修改设计师信息
     * @param $id
     * @param $designer
     * @param $position
     * @param $employ_time
     * @param $idea
     * @param $expert
     * @param $designer_pic
     * @param $intro
     * @param $school
     * @param $works
     * @param $honor
     * @return bool
     * @auther lymdzu@hotmail.com
     */
    public function edit_designer($id, $designer, $position, $employ_time, $idea, $expert, $designer_pic, $intro, $school, $works, $honor)
    {
        $this->db->where("id", $id);
        $designer = array("designer" => $designer, "position" => $position, "employ_time" => $employ_time, "idea" => $idea, "expert" => $expert, "create_time" => time(), "update_time" => time(), "intro" => $intro, "school" => $school, "works" => $works, "honor" => $honor);
        if (!empty($designer_pic)) {
            $designer['designer_pic'] = $designer_pic;
        }
        $insert_status = $this->db->update("t_designer", $designer);
        $row = $this->db->affected_rows();
        if ($insert_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除设计师
     * @param $id
     * @return bool
     * @auther lymdzu@hotmail.com
     */
    public function delete_designer($id)
    {
        $this->db->where("id", $id);
        $delete_status = $this->db->delete("t_designer");
        $row = $this->db->affected_rows();
        if ($delete_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return mixed
     * @auther lymdzu@hotmail.com
     */
    public function get_designer_list()
    {
        $query = $this->db->query("select * from t_designer");
        return $query->result_array();
    }

    public function get_designer_by_id($id)
    {
        $query = $this->db->query("select * from t_designer where id=?", $id);
        return $query->row_array();
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
        $style = array("name" => $name, "style" => $style, "designer" => $designer, "desc" => $desc, "create_time" => time(), "update_time" => time(), "status" => 1);
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
     * 删除案例图片
     * @param $pic_id
     * @return bool
     * @auther lymdzu@hotmail.com
     */
    public function delete_case_pic($pic_id)
    {
        $this->db->where("id", $pic_id);
        $delete_status = $this->db->delete("t_case_pic");
        $row = $this->db->affected_rows();
        if ($delete_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 保存生活照片
     * @param $filename
     * @return bool
     * @auther lymdzu@hotmail.com
     */
    public function save_activity_pic($filename)
    {
        $pic = array("pic" => $filename, "create_time" => time());
        $insert_status = $this->db->insert("t_activity", $pic);
        $row = $this->db->affected_rows();
        if ($insert_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除活动照片
     * @param $id
     * @return bool
     * @auther lymdzu@hotmail.com
     */
    public function delete_activity_pic($id)
    {
        $this->db->where("id", $id);
        $delete_status = $this->db->delete("t_activity");
        $row = $this->db->affected_rows();
        if ($delete_status && $row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取活动照片
     * @return mixed
     * @auther lymdzu@hotmail.com
     */
    public function get_activity_pic()
    {
        $query = $this->db->get("t_activity");
        return $query->result_array();
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

    public function get_designer_exams($desiger_id)
    {
        $query = $this->db->query("select * from t_case as c LEFT JOIN t_case_pic as p ON p.case_id = c.id WHERE c.designer={$desiger_id} and p.is_default=1");
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