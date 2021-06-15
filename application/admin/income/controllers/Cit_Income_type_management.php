<?php


/**

 * @class Cit_Education_management.php
 * 
 * @path application\admin\income\controllers\Cit_Income_type_management.php
 * 
 * @author CIT Dev Team
 * 
 * @date 01.10.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Income_type_management extends Income_type_management {
        public function __construct()
  {
      parent::__construct();
        $this->load->model('cit_api_model');
  }
  public function checkUniqueInterest($value = ''){

      $return_arr='1';
      if(false == empty($value)){
        $this->db->select('iUserId');
        $this->db->from('users');
        $this->db->where('vIncome', $value);
        $arrInterestData=$this->db->get()->result_array();
       if(true == empty($arrInterestData)){
           $return_arr = "0";
           return  $return_arr;
        }     
      } 
     return  $return_arr; 
      
  }
  public function showStatusButton($id='',$arr=array())
  {     
          $url = $this->general->getAdminEncodeURL('income/income_type_management/add').'|mode|'.$this->general->getAdminEncodeURL('Update').'|id|'.$this->general->getAdminEncodeURL($arr);
         return '<button type="button" data-id='.$arr.' class="btn btn-success operBut" data-url='.$url.' >Edit</button>';
        
  }
}