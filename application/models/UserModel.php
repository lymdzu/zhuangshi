<?php

/**
 * 文件名称:UserModel.php
 * 摘    要:
 * 修改日期: 19/9/17
 */
class UserModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 游客报名
     * @param $username
     * @param $phone
     * @return bool
     */
    public function signup_user($username, $phone)
    {
        $userinfo = array(
            "user_name"   => $username,
            "phone"       => $phone,
            "create_time" => time()
        );
        $insert_status = $this->db->insert("t_guest", $userinfo);
        $affected_row = $this->db->affected_rows();
        if ($insert_status && $affected_row == 1) {
            return true;
        } else {
            return false;
        }
    }
}