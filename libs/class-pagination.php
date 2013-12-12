<?php 
/**
 * @fileName: class-pagination.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

/*
 * Pagination can be annoying. This class was made to be lightweight and reusable, making pagination simple.
 * Code license GNU GPL v2 author spaceshipcollaborative url https://code.google.com/p/spaceshipcollaborative/
 *
 */
class Pagination{
    var $output = '';    
    var $options = array(
        'urlscheme' => '',
        'perpage' => '',
        'page' => '',
        'total' => '',
        'numlinks' => '',
        'nexttext' => 'Next',
        'prevtext' => 'Previous',
        'focusedclass' => '',
        'delimiter' => ', '
    );
   
   function set($who,$what){
       $this->output = '';
       $this->options[$who] = $what;
   }
   
   function checkValues(){
       $errors = array();
       if($this->options['perpage']=='') $errors[] = 'Invalid perpage value';
       if($this->options['page']=='') $errors[] = 'Invalid page value';
       if($this->options['total']=='') $errors[] = 'Invalid total value';
       if($this->options['numlinks']=='') $errors[] = 'Invalid numlinks value';
   }
   function display($return = false){
       $this->checkValues();	   
       if($this->output=='') $this->generateOutput();	   
       if(!$return) echo $this->output;
       else return $this->output;
   }
   function generateOutput(){
       $elements = array();
       $num_pages = ceil($this->options['total']/$this->options['perpage']);
       $front_links = ceil($this->options['numlinks']/2);
       $end_links = floor($this->options['numlinks']/2);
       if($this->options['page'] > $num_pages){ $this->set('page',1); }
       
       $start_page = max(1,($this->options['page']-$front_links+1));
       $end_page = min($this->options['numlinks'] + $start_page-1,$num_pages);

       if($this->options['page'] > 1){
           $elements[] = $this->generate_link($this->options['page']-1,$this->options['prevtext']);
       }
       
       for($i=$start_page; $i<=$end_page; $i++){
           $elements[] = $this->generate_link($i);
       }
       
       if($this->options['page'] < $num_pages){
           $elements[] = $this->generate_link($this->options['page']+1,$this->options['nexttext']);
       }
       
       $this->output = implode($this->options['delimiter'],$elements);
   }
   
   function generate_link($page,$label=''){
       $url = str_replace('%page%',$page,$this->options['urlscheme']);
       if($label=='') $label=$page;
       $html = "<a ".(($this->options['focusedclass']!='' && $page == $this->options['page'])?"class=\"{$this->options['focusedclass']}\" ":"")."href=\"{$url}\">{$label}</a>";
       return $html;
   }
}

/**
 * Sample Usage
$paging = new Pagination();
$paging->set('urlscheme','site.php?page=%page%');
$paging->set('perpage',10);
$paging->set('page',max(1,intval($_GET['page'])));
$paging->set('total',3000);
$paging->set('nexttext','Next Page');
$paging->set('prevtext','Previous Page');
$paging->set('focusedclass','selected');
$paging->set('delimiter','');
$paging->set('numlinks',9);
**/
?>
