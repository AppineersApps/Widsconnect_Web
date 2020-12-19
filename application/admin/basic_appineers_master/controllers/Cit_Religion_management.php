<?php


/**
 * Description of Users Management Extended Controller
 * 
 * @module Extended Users Management
 * 
 * @class Cit_Users_management.php
 * 
 * @path application\admin\basic_appineers_master\controllers\Cit_Users_management.php
 * 
 * @author CIT Dev Team
 * 
 * @date 01.10.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Religion_management extends Religion_management {
        public function __construct()
{
    parent::__construct();
      $this->load->model('cit_api_model');
}
  
  public function showStatusButton($id='',$arr=array())
  {     
          $url = $this->general->getAdminEncodeURL('basic_appineers_master/religion_management/add').'|mode|'.$this->general->getAdminEncodeURL('Update').'|id|'.$this->general->getAdminEncodeURL($arr);
         return '<button type="button" data-id='.$arr.' class="btn btn-success operBut" data-url='.$url.' >Edit</button>';
        
  }


}
