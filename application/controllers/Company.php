<?php
/**
 * 文件名称:Company.php
 * 摘    要:
 * 修改日期: 29/10/17
 */
class Company extends AdController
{
    public function __construct() {
        parent::__construct();
    }
    public function designer()
    {
        $this->page("company/designer.html");
    }
}