<?php  

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Abusive Reports User Model
 * 
 * @category webservice
 *            
 * @package misc
 *
 * @subpackage models
 *
 * @module Abusive Reports
 * 
 * @class Report_abusive_user_model.php
 * 
 * @path application\webservice\misc\models\Report_abusive_user_modell.php
 * 
 * @version 4.4
 *
 * @author CIT Dev Team
 * 
 * @since 03.05.2019
 */
 
class Report_abusive_user_model extends CI_Model
{
    public $default_lang = 'EN';
    
    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }
    
    /**
     * insert_report method is used to execute database queries for Report Abusive User API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 02.05.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function insert_report($params_arr = array())
    {
        try {
            $result_arr = array();
                        
            if(!is_array($params_arr) || count($params_arr) == 0){
                throw new Exception("Insert data not found.");
            }

            
            if(isset($params_arr["user_id"])){
                /*$this->db->set("iReportedBy", $params_arr["user_id"]);*/
                $this->db->set("iReportedBy", $params_arr["user_id"]);
            }

            if(isset($params_arr["message"])){
                /*$this->db->set("vMessage", $params_arr["message"]);*/
                $this->db->set("vMessage", $params_arr["message"]);
            }

            if(isset($params_arr["report_on"])){
                $this->db->set("iReportedOn", $params_arr["report_on"]);
            }

            //

           /* if(isset($params_arr["reporting_user_id"])){
                $this->db->set("vMessage", $params_arr["message"]);
                $this->db->set("iReportedUserId", $params_arr["reporting_user_id"]);
            }*/

            //

            /*if(isset($params_arr["report_on"])){
                $this->db->set("iReportedOn", $params_arr["report_on"]);
            }*/
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
           /* $this->db->insert("abusive_reports");*/
            //$this->db->set($this->db->protect("dtTimestamp"), $params_arr["_dttimestamp"], FALSE); 
            $this->db->insert("abusive_reports");
            $insert_id = $this->db->insert_id();
            if(!$insert_id){
                 throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
            
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
    
    
}