<?php
/**
 * 文件名称:Memu.php
 * 摘    要:
 * 修改日期: 25/10/17
 */
class Menu extends AdController
{
    public function __construct() {
        parent::__construct();
    }
    /**
     * 微信菜单列表
     */
    public function level_list()
    {
        $level = $this->input->get("level", true);
        $parent = $this->input->get("parent", true);
        $this->vars['parent'] = empty($parent) ? 0 : intval($parent);
        $level = empty($level) ? 1 : (intval($level) > 3 ? 3 : intval($level));
        $this->load->model("MenuModel", "menu", true);
        $type_list = $this->menu->get_menu_type_list($level, $parent);
        $this->vars['level_list'] = $type_list;
        $this->vars['level'] = $level;
        $this->vars['next_level'] = $level + 1;
        $this->vars['page'] = "level_list";
        $this->page("menu/level_list.html");
    }

    /**
     * 添加微信菜单
     */
    public function add_level()
    {
        $name = $this->input->post("name", true);
        $level = $this->input->post("level", true);
        $parent = $this->input->post("parent", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入微信菜单名称");
        }
        if (empty($level)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请选择微信菜单级别");
        }
        if ($level != 1 && empty($parent)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入菜单上级");
        }
        $this->load->model("MenuModel", "menu", true);
        $operate = $this->session->userdata("admin_user");
        $add_status = $this->menu->add_menu_type($name, $level, $parent, $operate);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "微信菜单添加成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器出错");
        }
    }

    /**
     * 修改微信菜单
     */
    public function edit_level()
    {
        $name = $this->input->post("name", true);
        $id = $this->input->post("id", true);
        if (empty($name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请输入微信菜单名称");
        }
        if (empty($id)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少需要修改的菜单id");
        }
        $this->load->model("MenuModel", "menu", true);
        $edit_status = $this->menu->edit_menu_type($id, $name);
        if ($edit_status) {
            $this->json_result(REQUEST_SUCCESS, "微信菜单修改成功");
        } else {
            $this->json_result(API_ERROR, "", "修改失败请检查输入");
        }
    }

    /**
     * 删除当前及其下属菜单
     */
    public function delete_level()
    {
        $id = $this->input->post("id", true);
        if (empty($id)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "缺少需要修改的菜单id");
        }
        $this->load->model("MenuModel", "menu", true);
        $edit_status = $this->menu->delete_menu_type($id);
        if ($edit_status) {
            $this->json_result(REQUEST_SUCCESS, "微信菜单删除成功");
        } else {
            $this->json_result(API_ERROR, "", "服务器出错");
        }
    }
    /**
     * 重新生成商品类目
     */
    public function make_category()
    {
        $this->load->model("MenuModel", "menu", true);
        $first_level = $this->menu->get_category_by_level(1);
        $category = array();
        if (!empty($first_level)) {
            foreach ($first_level as $first_key => $list) {
                $category[$first_key]["text"] = $list['name'];
                $category[$first_key]['state'] = array("opened" => true);
                $second_level = $this->menu->get_category_by_level(2, $list['id']);
                $second = array();
                if (!empty($second_level)) {
                    foreach ($second_level as $second_key => $second_list) {
                        $second[$second_key]["text"] = $second_list['name'];
                        $second[$second_key]["state"] = array("opened" => true);
                        $third_level = $this->menu->get_category_by_level(3, $second_list['id']);
                        $third = array();
                        if (!empty($third_level)) {
                            foreach ($third_level as $third_key => $third_list) {
                                $third[$third_key]["text"] = $third_list['name'];
                                $third[$third_key]["icon"] = 'none';
                            }
                        }
                        $second[$second_key]['children'] = $third;
                    }
                }
                $category[$first_key]['children'] = $second;
            }
        }
        $cat_json = "var json =" . json_encode($category);
        file_put_contents(dirname(APPPATH) . "/public/file/category.js", $cat_json);
        $this->json_result(REQUEST_SUCCESS, "生成成功");
    }
}