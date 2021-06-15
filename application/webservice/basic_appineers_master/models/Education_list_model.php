<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of States List Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module States List
 *
 * @class States_list_model.php
 *
 * @path application\webservice\basic_appineers_master\models\States_list_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Education_list_model extends CI_Model
{
    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * get_states_list_v1 method is used to execute database queries for States List API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param string $STATES_LIST_COUNTRY_ID STATES_LIST_COUNTRY_ID is used to process query block.
     * @param string $STATES_LIST_COUNTRY_CODE STATES_LIST_COUNTRY_CODE is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function education_list()
    {
       // print_r("Text");exit;
            try {
            $result_arr = array();
                                
            $this->db->from("education");
            $this->db->select('iEducationId AS education_id');
            $this->db->select('vEducationName AS education_name');
            $this->db->where("eStatus", 'Active');
            $this->db->order_by("iOrderNumber", "asc");


            $result_obj = $this->db->get();

            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            
            if(!is_array($result_arr) || count($result_arr) == 0){
                throw new Exception('No records found.');
            }
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        
        $this->db->_reset_all();
       // echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }


     public function get_education_details($arrResult)
    {

        try
        {
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';            
            
            $this->db->from("education");
            $this->db->select('iEducationId AS education_id');
            $this->db->select('vEducationName AS education_name');           

            if(false == empty($arrResult['status']))
            {
              $this->db->where("eStatus = '".$arrResult['status']."' AND iEducationId ='".$arrResult['education_id']."'"); 
            }
            if(false == empty($arrResult['education_id']))
            {
              $this->db->where("iEducationId = '".$arrResult['education_id']."'"); 
            }

            $result_obj = $this->db->get();
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
}
