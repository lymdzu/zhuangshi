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

    public function designer_list()
    {
        $this->load->model("CompanyModel", "company", true);
        $designer_list = $this->company->get_designer_list();
        $this->vars['designer_list'] = $designer_list;
        $this->vars['row'] = "designer";
        $this->page("company/designer_list.html");
    }

    public function new_page()
    {
        $this->vars['position'] = $this->config->item("position");
        $this->page('designer/designer.html');
    }
    public function edit_page()
    {
        $this->load->model("CompanyModel", "company", true);
        $id = $this->input->get("id");
        $designer = $this->company->get_designer_by_id($id);
        $this->vars['position'] = $this->config->item("position");
        $this->vars['designer'] = $designer;
        $this->page("designer/edit_designer.html");
    }
    public function edit_designer()
    {
        $id = trim($this->input->post("id"));
        $designer = trim($this->input->post("designer"));
        $position = trim($this->input->post("position"));
        $employ_time = trim($this->input->post("employ_time"));
        $idea = trim($this->input->post("idea"));
        $expert = trim($this->input->post("expert"));
        if (empty($designer)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写设计师姓名");
        }
        if ($position === "") {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请选择设计师职称");
        }
        if (empty($employ_time)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写设计师从业时间");
        }
        $this->load->model("CompanyModel", "company", true);
        $add_status = $this->company->edit_designer($id, $designer, $position, $employ_time, $idea, $expert);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "修改成功");
        } else {
            $this->json_result(API_ERROR, "", "修改失败");
        }
    }

    public function add_designer()
    {
        $designer = trim($this->input->post("designer"));
        $position = trim($this->input->post("position"));
        $employ_time = trim($this->input->post("employ_time"));
        $idea = trim($this->input->post("idea"));
        $expert = trim($this->input->post("expert"));
        if (empty($designer)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写设计师姓名");
        }
        if ($position === "") {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请选择设计师职称");
        }
        if (empty($employ_time)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写设计师从业时间");
        }
        $this->load->model("CompanyModel", "company", true);
        $add_status = $this->company->add_new_designer($designer, $position, $employ_time, $idea, $expert);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "添加成功");
        } else {
            $this->json_result(API_ERROR, "", "设计风格添加失败");
        }
    }

    /**
     * 设计师管理
     * @author
     */
    public function designer()
    {
        $this->vars['row'] = "designer";
        $this->page("company/designer.html");
    }

    /**
     * 设计风格管理
     * @author
     */
    public function style()
    {
        $this->vars['row'] = "style";
        $this->load->model("CompanyModel", "company", true);
        $style_list = $this->company->get_style_list();
        $designer_list = $this->company->get_designer_list();
        $style_items = $this->config->item("style_list");
        foreach ($style_list as $key => $style) {
            $style_list[$key]['style'] = $style_items[$style['style']];
        }
        $this->vars['designer_list'] = $designer_list;
        $this->vars['style_list'] = $style_list;
        $this->page("company/style_list.html");
    }

    /**
     * 上传案例图片
     * @author
     */
    public function style_pic()
    {
        $case_id = trim($this->input->get("case_id"));
        $this->vars['case_id'] = $case_id;
        $this->load->model("CompanyModel", "company", true);
        $pics = $this->company->get_case_pics($case_id);
        $this->vars['pics'] = $pics;
        $this->page("company/style_pic.html");
    }

    /**
     * 上传案例图片
     * @auther liuyongming@shopex.cn
     */
    public function case_pic()
    {
        $targetDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file_material_tmp';
        $uploadDir = dirname(APPPATH) . '/public/upload' . DIRECTORY_SEPARATOR . 'file';
        $case_id = trim($this->input->post("case_id"));
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }
        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $extension = pathinfo($fileName);
        $newName = time() . mt_rand(10000, 99999) . "." . $extension["extension"];
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        // Remove old temp files
        if ($cleanupTargetDir) {
            $dir = opendir($targetDir);
            if (!is_dir($targetDir) || !$dir) {
                $this->json_result(API_ERROR, "", "不能写入文件");
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "Failed to open input streams");
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $done = true;
        for ($index = 0; $index < $chunks; $index++) {
            if (!file_exists("{$filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }
        if ($done) {
            $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;

            if (!$out = @fopen($uploadPath, "wb")) {
                $this->json_result(WRITE_FILE_ERROR, "", "write file failed");
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $chunks; $index++) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $this->load->model("CompanyModel", "company", true);
            $save_status = $this->company->save_case_pic($case_id, $newName);
            $pathInfo = pathinfo($fileName);
            $filetype = strtoupper($pathInfo['extension']);
            $size = sprintf("%.2f", $_REQUEST['size'] / 1024 / 1024);
            $this->json_result(REQUEST_SUCCESS, $fileName);
        }
    }

    public function set_default_pic()
    {
        $pic_id = $this->input->post("pic_id");
        $case_id = $this->input->post("case_id");
        $this->load->model("CompanyModel", "company", true);
        $set_status = $this->company->set_default_pic($case_id, $pic_id);
        if ($set_status) {
            $this->json_result(REQUEST_SUCCESS, "设置成功");
        } else {
            $this->json_result(API_ERROR, "", "设置失败");
        }
    }

    /**
     * 添加设计风格
     * @author
     */
    public function add_style()
    {
        $style_name = trim($this->input->post("name"));
        $style = trim($this->input->post("style"));
        $designer = trim($this->input->post("designer"));
        $style_desc = trim($this->input->post("desc"));
        if ($style === "") {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请选择设计风格");
        }
        if (empty($style_name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写设计案例名称");
        }
        $this->load->model("CompanyModel", "company", true);
        $add_status = $this->company->add_new_style($style_name, $style_desc, $style, $designer);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "添加成功");
        } else {
            $this->json_result(API_ERROR, "", "设计风格添加失败");
        }
    }

    /**
     * 修改设计风格
     * @author
     */
    public function edit_style()
    {
        $cate_id = trim($this->input->post("id"));
        $style_name = trim($this->input->post("name"));
        $style = trim($this->input->post("style"));
        $designer = trim($this->input->post("designer"));
        $style_desc = trim($this->input->post("desc"));
        if ($style === "") {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请选择设计风格");
        }
        if (empty($style_name)) {
            $this->json_result(LACK_REQUIRED_PARAMETER, "", "请填写设计案例名称");
        }
        $this->load->model("CompanyModel", "company", true);
        $add_status = $this->company->edit_new_style($cate_id, $style_name, $style_desc, $style, $designer);
        if ($add_status) {
            $this->json_result(REQUEST_SUCCESS, "修改成功");
        } else {
            $this->json_result(API_ERROR, "", "设计风格修改失败");
        }
    }
}