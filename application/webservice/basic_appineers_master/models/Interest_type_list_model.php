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

class Interest_type_list_model extends CI_Model
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
    public function interest_type_list()
    {
       // print_r("Text");exit;
            try {
            $result_arr = array();
                                
            $this->db->from("interests");
            $this->db->select('iInterestsId AS interest_id');
            $this->db->select('vInterestsName AS interest_name');
            $this->db->select("vInterestsImage AS interest_image");
            $this->db->where("eStatus", 'Active');
            $this->db->order_by("iInterestsId", "asc");

            
            
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


    public function set_interest($params_arr = array())
    {
        try
        {
            $result_arr = array();
            $insert_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }

            if (isset($params_arr["interest_id"]))
            {
                $count=count(explode(",",$params_arr["interest_id"]));
               
                 if($count==1){
                    if (isset($params_arr["interest_id"]))
                    {
                        $this->db->set("iInterestsId", $params_arr["interest_id"]);
                    }
                    if (isset($params_arr["user_id"]))
                    {
                        $this->db->set("iUserId", $params_arr["user_id"]);
                    }
                    $this->db->set($this->db->protect("dAddedAt"), $params_arr["_addedat"], FALSE);
                    $this->db->insert("user_interest");
                    $insert_id = $this->db->insert_id();
                    //echo $this->db->last_query();exit;
                    if (!$insert_id)
                    {
                        throw new Exception("Failure in insertion.");
                    }
                     $result_param = "user_interest";
                     $result_arr[0][$result_param] = $insert_id;
                 }else if($count>1){
                    $arrInterestIds = explode(',',$params_arr["interest_id"]);
                    foreach($arrInterestIds as $key=>$intInterestValue){
                        $insert_arr[$key]['iUserId']=$params_arr["user_id"];
                        $insert_arr[$key]['iInterestsId']=$intInterestValue;
                        $insert_arr[$key]['dAddedAt']=date('Y-m-d H:i:s');
                    }

                     if(is_array($insert_arr) && !empty($insert_arr))
                        {
                            $res = $this->db->insert_batch("user_interest",$insert_arr);
                        }
                    $affected_rows = $this->db->affected_rows();
                    if (!$res || $affected_rows == -1)
                    {
                        throw new Exception("Failure in updation.");
                    }
                    $result_param = "affected_rows";
                    $result_arr[0][$result_param] = $affected_rows;

                }
            
            $success = 1;

        }
    }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        #echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

     public function get_interest_details($arrResult)
    {

        try
        {
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';            
            
            $this->db->from("interest AS i");
            /*$this->db->select("i.iItemId  AS item_id");
            $this->db->select("i.vItemName AS item_name");*/   

            $this->db->select("i.iInterestsId  AS interest_id");
            $this->db->select("i.vInterestsName AS interest_name");  
            //$this->db->select("i.dtAddedAt AS date_added");      

           

            if(false == empty($arrResult['interest_type']))
            {
              $this->db->where("eInterestStatus = '".$arrResult['interest_type']."' AND iInterestsId ='".$arrResult['interest_id']."'"); 
            }
            if(false == empty($arrResult['interest_id']))
            {
              $this->db->where("iInterestsId = '".$arrResult['interest_id']."'"); 
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
