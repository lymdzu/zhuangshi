<?php
/**
 * Created by IntelliJ IDEA.
 * User: imo
 * Date: 15/12/30
 * Time: 下午4:24
 */

class TgPage{
    private $total;
    private $pagesize;
    private $page;
    private $pagenum;
    private $url;
    private $bothnum;


    public function __construct($params){
        $_total = $params['total'];
        $_pagesize = $params['pagesize'];
        $this->total = $_total ? $_total : 1;
        $this->pagesize = $_pagesize;
        $this->pagenum = ceil($this->total / $this->pagesize);
        $this->page = $this->setPage();
        $this->url = $this->setUrl();
        $this->bothnum = 5;
    }

    private function setPage(){
        if(isset($_GET['page']) && !empty($_GET['page']))
        {
            $_GET['page'] = intval($_GET['page']);
            if(!empty($_GET['page'])){
                if($_GET['page'] > 0){
                    if($_GET['page'] > $this->pagenum){
                        return $this->pagenum;
                    }
                    else{
                        return $_GET['page'];
                    }
                }
            }
        }
        return 1;
    }

    private function setUrl(){
        $_url = $_SERVER["REQUEST_URI"];
        $_par = parse_url($_url);
        if (isset($_par['query'])) {
            parse_str($_par['query'],$_query);
            unset($_query['page']);
            $_url = $_par['path'].'?'.http_build_query($_query);
        }
        else{
            $_url = $_par['path'].'?';
        }
        return $_url;
    }

    private function first(){
        return ' <li><a href="'.$this->url.'&page=1" aria-label="Previous"><span aria-hidden="true">首页</span></a></li>';
    }

    private function last(){
        return ' <li><a href="'.$this->url.'&page='.$this->pagenum.'" aria-label="Previous"><span aria-hidden="true">末页</span></a></li>';
    }

    private function prev(){
        if ($this->page == 1) {
            return '<li class="disabled"><a href="'.$this->url.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
        }
        return '<li><a href="'.$this->url.'&page='.($this->page-1).'" aria-label="Next"><span aria-hidden="true">&laquo;</span></a></li>';
    }

    private function next(){
        if ($this->page == $this->pagenum) {
            return '<li class="disabled"><a href="'.$this->url.'" aria-label="Previous"><span aria-hidden="true">&raquo;</span></a></li>';
        }

        return '<li><a href="'.$this->url.'&page='.($this->page+1).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
    }

    private function pageList(){
        $_pagelist = "";
        for ($i=$this->bothnum;$i>=1;$i--) {
            $_page = $this->page-$i;
            if ($_page < 1) continue;
            $_pagelist .= '<li><a href="'.$this->url.'&page='.$_page.'">'.$_page.'</a></li>';
        }
        $_pagelist .= '<li class="active"><a href="'.$this->url.'&page='.$this->page.'">'.$this->page.'</a></li>';
        for ($i=1;$i<=$this->bothnum;$i++) {
            $_page = $this->page+$i;
            if ($_page > $this->pagenum) break;
            $_pagelist .= '<li><a href="'.$this->url.'&page='.$_page.'">'.$_page.'</a></li>';
        }
        return $_pagelist;
    }

    public function showpage(){
        $content = '<div class="dataTables_paginate paging_simple_numbers text-center">';
        $content .='<ul class="pagination">';
        $content .= $this->first();
        $content .= $this->prev();
        $content .= $this->pageList();
        $content .= $this->next();
        $content .= $this->last();
        $content .='</ul>';
        $content .= '</div>';
        return $content;
    }

}