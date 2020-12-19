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

class Religion_type_list_model extends CI_Model
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
    public function religion_type_list()
    {
       // print_r("Text");exit;
            try {
            $result_arr = array();
                                
            $this->db->from("religion");
            $this->db->select('iReligionId AS religion_id');
            $this->db->select('vReligionName AS religion_name');
           // $this->db->select("vReligionImage AS religion_image");
            $this->db->where("eStatus", 'Active');
            $this->db->order_by("iReligionId", "asc");

            
            
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


     public function get_religion_details($arrResult)
    {

        try
        {
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';            
            
            $this->db->from("religion AS i");
            /*$this->db->select("i.iItemId  AS item_id");
            $this->db->select("i.vItemName AS item_name");*/   

            $this->db->select("i.iReligionId  AS religion_id");
            $this->db->select("i.vReligionName AS religion_name");  
            //$this->db->select("i.dtAddedAt AS date_added");      

           

            if(false == empty($arrResult['religion_type']))
            {
              $this->db->where("eReligiontatus = '".$arrResult['religion_type']."' AND iReligionId ='".$arrResult['religion_id']."'"); 
            }
            if(false == empty($arrResult['religion_id']))
            {
              $this->db->where("iReligionId = '".$arrResult['religion_id']."'"); 
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
