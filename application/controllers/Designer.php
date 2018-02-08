<?php
/**
 * 文件名称:Designer.php
 * 摘    要:
 * 修改日期: 13/11/17
 */
class Designer extends PublicController
{
    public function show()
    {
        $id = trim($this->input->get("id"));
        $this->load->model("CompanyModel", "company", true);
        $designer = $this->company->get_designer_by_id($id);
        $this->vars['designer'] = $designer;
        $this->page("designer/show.html");
    }
}