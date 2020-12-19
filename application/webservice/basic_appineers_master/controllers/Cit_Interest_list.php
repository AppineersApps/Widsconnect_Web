<?php

   
/**
 * Description of Change Password Extended Controller
 * 
 * @module Extended Change Password
 * 
 * @class Cit_Change_password.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Change_password.php
 * 
 * @author CIT Dev Team
 * 
 * @date 08.10.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Interest_list extends Interest_list {
        public function __construct()
{
    parent::__construct();
}
public function checkExistsInterest($input_params=array()){
    $arrData = array();

    $this->db->select('iUserInterestId');
    $this->db->from('user_interest');
    $this->db->where('iUserId', $input_params['user_id']);
    $arrData=$this->db->get()->result_array();
    if(false == empty($arrData)){
        try
        {
        $result_arr = array();
            if (false == empty($arrData))
            {
                
                if(is_array($arrData) && count($arrData)>1)
                {
                    $arrUserinterestId=array_column($arrData, 'iUserInterestId');
                    $conditions = "'" . implode("', '", $arrUserinterestId) . "'";
                    $strWhere ="iUserInterestId IN ($conditions) ";

                   $this->db->where($strWhere);
                }
                else{
                    $this->db->where("iUserInterestId =", $arrData[0]['iUserInterestId']);
                }
                
            }
            
            $res = $this->db->delete("user_interest");
            //echo $this->db->last_query();exit;
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        
    }else{
        $return_arr["success"] = 0;
        $return_arr["message"] = "No record found";
        $return_arr["data"] = $result_arr;
    }
    return $return_arr;
}
}
