<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Users Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module Users
 *
 * @class Users_model.php
 *
 * @path application\webservice\basic_appineers_master\models\Users_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class Users_model extends CI_Model
{
    public $default_lang = 'EN';

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }

    /**
     * logout method is used to execute database queries for Logout API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function logout($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }
            $this->db->where_in("eStatus", array('Active'));

            $this->db->set($this->db->protect("vAccessToken"), $params_arr["_vaccesstoken"], FALSE);
            $this->db->set($this->db->protect("vDeviceToken"), $params_arr["_vdevicetoken"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * update_device_token method is used to execute database queries for Update Device Token API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_device_token($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }
            if (isset($params_arr["device_token"]))
            {
                $this->db->set("vDeviceToken", $params_arr["device_token"]);
            }
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

     /**
     * get_sender_image method is used to execute database queries for Send Message API.
     * @created Devangi Nirmal | 18.06.2019
     * @modified Devangi Nirmal | 18.06.2019
     * @param string $s_users_id s_users_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_sender_image($s_users_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS ui");

            $this->db->select("ui.vProfileImage AS ui_image");
            $this->db->select("ui.iUserId AS ui_users_id");
            if (isset($s_users_id) && $s_users_id != "")
            {
                $this->db->where("ui.iUserId =", $s_users_id);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

     /**
     * get_send_image method is used to execute database queries for Get Message List API.
     * @created Devangi Nirmal | 18.06.2019
     * @modified Devangi Nirmal | 30.07.2019
     * @param string $sender_id sender_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_send_image($sender_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vProfileImage AS u_image");
            $this->db->select("u.iUserId AS u_users_id");
            if (isset($sender_id) && $sender_id != "")
            {
                $this->db->where("u.iUserId =", $sender_id);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
    
     /**
     * get_receiver_images method is used to execute database queries for Get Message List API.
     * @created Devangi Nirmal | 18.06.2019
     * @modified Devangi Nirmal | 30.07.2019
     * @param string $receiver_id receiver_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_receiver_images($receiver_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS ui");

            $this->db->select("ui.vProfileImage AS ui_image");
            $this->db->select("ui.iUserId AS ui_users_id");
            if (isset($receiver_id) && $receiver_id != "")
            {
                $this->db->where("ui.iUserId =", $receiver_id);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_notification method is used to execute database queries for Update Push Notification Settings API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 17.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_notification($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();

            $this->db->where_in("eStatus", array('Active'));
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }
            if (isset($params_arr["notification"]))
            {
                $this->db->set("ePushNotify", $params_arr["notification"]);
            }
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * update_new_password method is used to execute database queries for Change Password API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_new_password($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }
            $this->db->where_in("eStatus", array('Active'));
            if (isset($params_arr["new_password"]))
            {
                $this->db->set("vPassword", $params_arr["new_password"]);
            }
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * check_unique_mobile_number method is used to execute database queries for Change Mobile Number API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param string $new_mobile_number new_mobile_number is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_unique_mobile_number($new_mobile_number = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUserId AS u_user_id");
            if (isset($new_mobile_number) && $new_mobile_number != "")
            {
                $this->db->where("u.vMobileNo =", $new_mobile_number);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_mobile_number method is used to execute database queries for Change Mobile Number API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 09.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_mobile_number($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }
            if (isset($params_arr["new_mobile_number"]))
            {
                $this->db->set("vMobileNo", $params_arr["new_mobile_number"]);
            }
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * get_user method is used to execute database queries for User Email Confirmation API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vFirstName AS u_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.iUserId AS u_user_id");
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_confirmation method is used to execute database queries for User Email Confirmation API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_confirmation($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["u_user_id"]) && $where_arr["u_user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["u_user_id"]);
            }
            if (isset($where_arr["confirmation_code"]) && $where_arr["confirmation_code"] != "")
            {
                $this->db->where("vEmailVerificationCode =", $where_arr["confirmation_code"]);
            }

            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set("eEmailVerified", $params_arr["_eemailverified"]);
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            $res = $this->db->update("users");

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
        return $return_arr;
    }

    /**
     * create_user method is used to execute database queries for User Sign Up Email API.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function create_user($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["user_name"]))
            {
                $this->db->set("vUserName", $params_arr["user_name"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["mobile_number"]))
            {
                $this->db->set("vMobileNo", $params_arr["mobile_number"]);
            }
            if (isset($params_arr["user_profile"]) && !empty($params_arr["user_profile"]))
            {
                $this->db->set("vProfileImage", $params_arr["user_profile"]);
            }
            
            if (isset($params_arr["upload_doc"]) && !empty($params_arr["upload_doc"]))
            {
                $this->db->set("vUploadDoc", $params_arr["upload_doc"]);
            }

            if (isset($params_arr["dob"]))
            {
                $this->db->set("dDob", $params_arr["dob"]);
            }
            if (isset($params_arr["password"]))
            {
                $this->db->set("vPassword", $params_arr["password"]);
            }
            if (isset($params_arr["address"]))
            {
                $this->db->set("tAddress", $params_arr["address"]);
            }
            if (isset($params_arr["city"]))
            {
                $this->db->set("vCity", $params_arr["city"]);
            }
            if (isset($params_arr["latitude"]))
            {
                $this->db->set("dLatitude", $params_arr["latitude"]);
            }
            if (isset($params_arr["longitude"]))
            {
                $this->db->set("dLongitude", $params_arr["longitude"]);
            }
            if (isset($params_arr["state_id"]))
            {
                $this->db->set("iStateId", $params_arr["state_id"]);
            }
            if (isset($params_arr["state_name"]))
            {
                $this->db->set("vStateName", $params_arr["state_name"]);
            }
            if (isset($params_arr["zipcode"]))
            {
                $this->db->set("vZipCode", $params_arr["zipcode"]);
            }
            $this->db->set("eStatus", $params_arr["status"]);
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            if (isset($params_arr["device_type"]))
            {
                $this->db->set("eDeviceType", $params_arr["device_type"]);
            }
            if (isset($params_arr["device_model"]))
            {
                $this->db->set("vDeviceModel", $params_arr["device_model"]);
            }
            if (isset($params_arr["device_os"]))
            {
                $this->db->set("vDeviceOS", $params_arr["device_os"]);
            }
            if (isset($params_arr["device_token"]))
            {
                $this->db->set("vDeviceToken", $params_arr["device_token"]);
            }

            if (isset($params_arr["app_section"]))
            {
                $this->db->set("app_section", $params_arr["app_section"]);
            }
            if (isset($params_arr["profession"]))
            {
                $this->db->set("vProfession", $params_arr["profession"]);
            }
            
            if (isset($params_arr["upload_doc_url"]))
            {
                $this->db->set("vDocumentUrl", $params_arr["upload_doc_url"]);
            }

            $this->db->set("eEmailVerified", $params_arr["_eemailverified"]);
            if (isset($params_arr["email_confirmation_code"]))
            {
                $this->db->set("vEmailVerificationCode", $params_arr["email_confirmation_code"]);
            }
            $this->db->set("vTermsConditionsVersion", $params_arr["_vtermsconditionsversion"]);
            $this->db->set("vPrivacyPolicyVersion", $params_arr["_vprivacypolicyversion"]);
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
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

  /**
     * get_filtered_profiles method is used to execute database queries get filtered user profiles API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $auth_token auth_token is used to process query block.
     * @param string $where_clause where_clause is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_filtered_profiles($input_params = '')
    {

        try
        {
           //print_r($input_params); exit();
            $where_clause = isset($input_params["where_clause"]) ? $input_params["where_clause"] : "";

           // echo $where_clause;
            $page_no = 1;
            $start_offset = 0;
            $end_offset =  $this->config->item("PAGINATION_ROW_COUNT");

            if($input_params["page_no"] != "" )
            {
                $page_no = isset($input_params["page_no"]) ? $input_params["page_no"] : 1;

                $start_offset = ($page_no * $end_offset) - $end_offset;
            }

            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("YEAR(u.dDob) AS dob_year");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS email_user_name", FALSE);
            $this->db->select("u.eLogStatus AS u_log_status_updated");
            $this->db->select("u.app_section AS u_app_section");
            $this->db->select("u.eGender AS u_gender");
            $this->db->select("u.eSexualPrefrence AS u_sexual_prefrence");
           // $this->db->select("'' as u_images");
            $this->db->select("'' AS connection_type_by_receiver_user");
            $this->db->select("FLOOR(".$input_params['distance'].") AS miles");
            
         
            if ((isset($input_params['other_user_id']) && $input_params['other_user_id'] != ""))
            {
              
                $this->db->where("u.eStatus =", 'Active');            
                $this->db->where("u.iUserId =", $input_params['other_user_id']);
                $this->db->limit(1);
            }else
            {
              
                $this->db->where("".$where_clause."", FALSE, FALSE);

                
                /*$strWhere = "u.iUserId not in (SELECT DISTINCT(u.iUserId) AS user_id
                FROM users AS u
                LEFT JOIN users_connections AS uc ON uc.iConnectionUserId = u.iUserId
                LEFT JOIN users_connections AS ub ON ub.iUserId = u.iUserId
                WHERE u.eStatus = 'Active' AND (uc.iUserId = '".$input_params['user_id']."') AND IF(u.app_section!=3, uc.app_section='".$input_params['app_section']."', uc.app_section='".$input_params['app_section']."'))";*/

                 $strWhere = "u.iUserId not in (SELECT DISTINCT(uc.iConnectionUserId) AS user_id
                    FROM users_connections AS uc
                    LEFT JOIN users AS u ON uc.iConnectionUserId = u.iUserId
                    WHERE u.eStatus = 'Active' AND uc.iUserId = '".$input_params['user_id']."' AND uc.app_section='".$input_params['app_section']."')";

                //echo $strWhere."---";
               
                if (isset($strWhere) && $strWhere != "")
                {
                      $this->db->where($strWhere);
                }

                 $strWhere1 = "u.iUserId not in (SELECT DISTINCT(u.iUserId) AS user_id
                FROM users AS u
                LEFT JOIN user_block AS uc ON uc.iBlockUserId = u.iUserId
                OR uc.iUserId = u.iUserId
                WHERE u.eStatus = 'Active' AND u.iUserId <> '".$input_params['user_id']."' AND (uc.iUserId = '".$input_params['user_id']."' OR uc.iBlockUserId = '".$input_params['user_id']."')) ";

                if (isset($strWhere1) && $strWhere1 != "")
                {
                    $this->db->where($strWhere1);
                }

                $this->db->where("u.eStatus =", 'Active');
                $this->db->where("u.iUserId !=", $input_params['user_id']);

                $this->db->limit($end_offset,$start_offset);
                $this->db->group_by('iUserId');
                $this->db->order_by('miles');
            }

            
              
            $result_obj = $this->db->get();
         // echo $this->db->last_query(); 
                   
           $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();

            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            //print_r($result_arr);
                 $success = 1;

            foreach ($result_arr as $key => $val) {

            //    echo "-- val--". $val['u_user_id'] . "---- key $key". "----";

                $this->db->reset_query();
                $this->db->from("users_connections AS us1");
                $this->db->join("users AS u", "us1.iConnectionUserId = u.iUserId", "left");
                $this->db->join("users_connections AS us2", "us1.iConnectionUserId = us2.iUserId", "left");

                $this->db->select("u.iUserId AS user_id");
                /*  $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS user_name", FALSE);
                  $this->db->select("us1.eConnectionType AS connection_type");
                  $this->db->select("us1.app_section AS app_section"); */
        
                if (isset($input_params['user_id']) && $input_params['user_id'] != "") {
                    //$this->db->where("us1.iUserId =", $params_arr['user_id']);
                
                    $strwher="(us1.eConnectionType = 'Like' AND u.eStatus = 'Active' AND us1.iUserId = '".$input_params['user_id']."' AND us1.iConnectionUserId='".$val["u_user_id"]."') ";
                    $this->db->where($strwher);
                }
                if (isset($input_params['user_id']) && $input_params['user_id'] != "") {
                    $strwher="(us2.eConnectionType = 'Like' AND u.eStatus = 'Active' AND us2.iConnectionUserId = '".$input_params['user_id']."' AND us2.iUserId='".$val["u_user_id"]."')  AND us1.app_section = us2.app_section";
                    $this->db->where($strwher);
                }

                $this->db->limit(1);
             
                $result_obj23 = $this->db->get();

               //   echo $this->db->last_query();//exit;
                $this->db->reset_query();
                  
                $result_arr23 = is_object($result_obj23) ? $result_obj23->result_array() : array();

                if (count($result_arr23) > 0) {
                    if ($val["u_user_id"] == $result_arr23[0]['user_id']) {
                        //unset($result_arr[$key]);

                    ///    echo "-- removing key $key". "----";
                     //   array_splice($result_arr, $key, 1);
                        $result_arr[$key] = array();
                        
                    }
                }
            }
           // print_r($result_arr); 
            $opt_result_arr = array();
            $g = 0;

            foreach ($result_arr as $key => $val) {
 
                if($val["u_user_id"] > 0)
                {
                    $opt_result_arr[$g] = $val;
                    $this->db->from("users_profile_images");
                    $this->db->select("Distinct(iImageId) AS image_id");
                    $this->db->select("vImage as image_url");
                    $this->db->where("iUserId =", $val["u_user_id"]);
    
                    $result_objjj = $this->db->get();
                   //echo $this->db->last_query();
                    $imgArr = array();
    
                    foreach ($result_objjj->result_array() as $rowB)
                    {
                        $imgArr[] = $rowB;
                    }   
                    //print_r($imgArr);
                    $opt_result_arr[$g]["u_images"] = $imgArr;
    
    
                      $this->db->reset_query();
    
                    $user_id = $input_params['user_id'];
                    $connection_id =  $val["u_user_id"];
    
                    $strSql=
                        "SELECT '' AS connection_type,
                        (SELECT eConnectionType
                        FROM users_connections
                        WHERE iUserId=".$connection_id." AND iConnectionUserId = ".$user_id." order by iConnectionId DESC LIMIT 1) AS connection_type_by_receiver_user
                        FROM users_connections LIMIT 1";
    
    
    
                        $result_objQ = $this->db->query($strSql);
                        //echo $this->db->last_query();exit;
                        $result_arrQ = is_object($result_objQ) ? $result_objQ->result_array() : array();
    
    
                        if(isset($result_arrQ[0]['connection_type_by_receiver_user'])){
    
                        $opt_result_arr[$g]['connection_type_by_receiver_user'] = $result_arrQ[0]['connection_type_by_receiver_user'];
                        }

                    $g++;
                }
               
            }
            //$this->db->reset_query();

        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

       // print_r($opt_result_arr); exit;

        $this->db->_reset_all();
        
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $opt_result_arr;
        return $return_arr;
   
    }

    /**
     * get_user_personal_details method is used to execute database queries to get user details
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param string $insert_id insert_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_personal_details($logged_user_id = '',$insert_id = '', $user_latitude = '', $user_longitude = '', $app_section = '')
    {
        try
        {

            $result_arr = array();

            $this->db->from("users AS u");
            $this->db->join("user_block as ub","u.iUserId = ub.iBlockUserId AND ub.iUserId='".$logged_user_id."'","left");
            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("ub.eStatus AS block_status");
            $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS email_user_name", FALSE);

            $this->db->select("u.vAccessToken AS u_access_token");
            $this->db->select("u.vDeviceToken AS u_device_token");

            $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
            $this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            $this->db->select("u.ePushNotify AS u_push_notify");
            $this->db->select("u.vTermsConditionsVersion AS u_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS u_privacy_policy_version");
            $this->db->select("u.eLogStatus AS u_log_status_updated");
            $this->db->select("u.app_section AS u_app_section");
            $this->db->select("u.eSmoke AS u_Smoke");
            $this->db->select("u.vUploadDoc AS u_UploadDoc");
            $this->db->select("u.eDrink AS u_Drink");
            $this->db->select("u.e420Friendly AS u_420Friendly");
            $this->db->select("u.dHeight AS u_Height");
            $this->db->select("u.iKids AS u_Kids");
            $this->db->select("u.vBodyType AS u_BodyType");
            $this->db->select("u.eGender AS u_Gender");
            $this->db->select("u.vSign AS u_Sign");
            $this->db->select("u.vReligion AS u_Religion");
            $this->db->select("u.eSexualPrefrence AS u_SexualPrefrence");
            $this->db->select("u.vEducation AS u_Education");
            $this->db->select("u.vProfession AS u_Profession");
            $this->db->select("u.vIncome AS u_Income");
            $this->db->select("u.app_section AS u_app_section");
            $this->db->select("u.vImage1 AS u_images");
           /* $this->db->select("u.vImage2 AS u_Image2");
            $this->db->select("u.vImage3 AS u_Image3");
            $this->db->select("u.vImage4 AS u_Image4");
            $this->db->select("u.vDocumentUrl AS u_Image5");*/
            $this->db->select("u.tIntrest AS u_Intrest");
            $this->db->select("u.eMarriageStatus AS u_MarriageStatus");
            $this->db->select("u.vTatoos AS u_Tatoos");
            $this->db->select("u.tTravaledPlaces AS u_TravaledPlaces");
            $this->db->select("u.tTravalToPlaces AS u_tTravalToPlaces");
            $this->db->select("u.vTriggers AS u_Triggers");
            $this->db->select("u.tAboutYou AS u_AboutYou");
            $this->db->select("u.tAboutLatePerson AS u_AboutLatePerson");
            $this->db->select("'' AS connection_type_by_logged_user");
            $this->db->select("'' AS connection_type_by_receiver_user");
            $this->db->select("'' AS age");
            $this->db->select("'' AS u_Intrest_name");

            //echo $user_latitude."------";

            if(!empty($user_longitude) && !empty($user_latitude))
            {

                    $distance = "
                        3959 * acos (
                          cos ( radians($user_latitude) )
                          * cos( radians( u.dLatitude ) )
                          * cos( radians( u.dLongitude ) - radians($user_longitude) )
                          + sin ( radians($user_latitude) )
                          * sin( radians( u.dLatitude ) )
                        )";

                $this->db->select("FLOOR(".$distance.") AS miles");
            
                    
            }else{
               //distance filter
                $this->db->select("'' AS miles");
            
            }

            if (isset($insert_id) && $insert_id != "")
            {
                $this->db->where("u.iUserId =", $insert_id);
            }
            

            $this->db->limit(1);

            $result_obj = $this->db->get();

           // echo $this->db->last_query(); exit;
 
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }

            if (isset($insert_id) && $insert_id != "")
            {
                $this->db->from("user_interest AS ui");
                $this->db->select("Distinct(ui.iInterestsId) AS InterestsId,i.vInterestsName");
                $this->db->join("interests as i","ui.iInterestsId = i.iInterestsId","left");
                $this->db->where("ui.iUserId =", $insert_id);

                $result_objj = $this->db->get();

                $interestArr = [];
                $interestNameArr = [];

                foreach ($result_objj->result_array() as $rowA)
                {
                    $interestArr[] = $rowA['InterestsId'];
                    $interestNameArr[] = $rowA['vInterestsName'];
                }   
                
                $result_arr[0]["u_Intrest"] = implode(",", $interestArr);
                $result_arr[0]["u_Intrest_name"] = implode(",", $interestNameArr);

                $this->db->reset_query();
          
                $this->db->from("users_profile_images");
                $this->db->select("Distinct(iImageId) AS image_id");
                $this->db->select("vImage as image_url");
                $this->db->where("iUserId =", $insert_id);

                $result_objjj = $this->db->get();

                $imgArr = [];

                foreach ($result_objjj->result_array() as $rowB)
                {
                    $imgArr[] = $rowB;
                }   
                
                $result_arr[0]["u_images"] = $imgArr;
                
                $this->db->reset_query();

                $user_id = $logged_user_id;
                $connection_id = $insert_id;

                $strSql=
                    "SELECT '' AS connection_type,
                    (SELECT eConnectionType
                    FROM users_connections as u1
                    WHERE iUserId=".$user_id." AND iConnectionUserId = ".$connection_id." AND IF(".$app_section." > 0, app_section = ".$app_section.",'') order by iConnectionId DESC LIMIT 1) AS connection_type_by_logged_user,
                    (SELECT eConnectionType
                    FROM users_connections as u2
                    WHERE iUserId=".$connection_id." AND iConnectionUserId = ".$user_id." AND IF(".$app_section." > 0, app_section = ".$app_section.",'') order by iConnectionId DESC LIMIT 1) AS connection_type_by_receiver_user
                    FROM users_connections LIMIT 1";



                    $result_objQ = $this->db->query($strSql);
                    //echo $this->db->last_query();exit;
                    $result_arrQ = is_object($result_objQ) ? $result_objQ->result_array() : array();

                    if(isset($result_arrQ[0]['connection_type_by_logged_user'])){

                    $result_arr[0]['connection_type_by_logged_user'] = $result_arrQ[0]['connection_type_by_logged_user'];
                    }
                    
                    if(isset($result_arrQ[0]['connection_type_by_receiver_user'])){

                    $result_arr[0]['connection_type_by_receiver_user'] = $result_arrQ[0]['connection_type_by_receiver_user'];
                    }
                    //echo $result_arr[0]["u_dob"]."--";

                    if( $result_arr[0]["u_dob"] != "0000-00-00")
                    {
                        $currYr = date("Y");
                        $birthYr = date("Y",strtotime($result_arr[0]["u_dob"]));
                    
                        $result_arr[0]["age"] = $currYr - $birthYr;
                    }
                

            }
            $success = 1;

        }
        catch(Exception $e)
        {
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


    /**
     * get_user_details method is used to execute database queries for User Sign Up Email API.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param string $insert_id insert_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_details($insert_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS email_user_name", FALSE);
            $this->db->select("u.eLogStatus AS u_log_status_updated");
            $this->db->select("u.app_section AS u_app_section");
            $this->db->select("u.eGender AS u_gender");
            $this->db->select("u.vUploadDoc AS u_UploadDoc");

            if (isset($insert_id) && $insert_id != "")
            {
                $this->db->where("u.iUserId =", $insert_id);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * create_user_v1 method is used to execute database queries for User Sign Up Phone API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function create_user_v1($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["user_name"]))
            {
                $this->db->set("vUserName", $params_arr["user_name"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["mobile_number"]))
            {
                $this->db->set("vMobileNo", $params_arr["mobile_number"]);
            }
            if (isset($params_arr["user_profile"]) && !empty($params_arr["user_profile"]))
            {
                $this->db->set("vProfileImage", $params_arr["user_profile"]);
            }
               /*if (isset($params_arr["upload_doc"]) && !empty($params_arr["upload_doc"]))
            {
                $this->db->set("vUploadDoc", $params_arr["upload_doc"]);
            }*/
            
            if (isset($params_arr["dob"]))
            {
                $this->db->set("dDob", $params_arr["dob"]);
            }
            if (isset($params_arr["password"]))
            {
                $this->db->set("vPassword", $params_arr["password"]);
            }
            if (isset($params_arr["address"]))
            {
                $this->db->set("tAddress", $params_arr["address"]);
            }
            if (isset($params_arr["city"]))
            {
                $this->db->set("vCity", $params_arr["city"]);
            }
            if (isset($params_arr["latitude"]))
            {
                $this->db->set("dLatitude", $params_arr["latitude"]);
            }
            if (isset($params_arr["longitude"]))
            {
                $this->db->set("dLongitude", $params_arr["longitude"]);
            }
            if (isset($params_arr["state_id"]))
            {
                $this->db->set("iStateId", $params_arr["state_id"]);
            }
            if (isset($params_arr["state_name"]))
            {
                $this->db->set("vStateName", $params_arr["state_name"]);
            }
            if (isset($params_arr["zipcode"]))
            {
                $this->db->set("vZipCode", $params_arr["zipcode"]);
            }
            $this->db->set("eStatus", $params_arr["status"]);
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            if (isset($params_arr["device_type"]))
            {
                $this->db->set("eDeviceType", $params_arr["device_type"]);
            }
            if (isset($params_arr["device_model"]))
            {
                $this->db->set("vDeviceModel", $params_arr["device_model"]);
            }
            if (isset($params_arr["device_os"]))
            {
                $this->db->set("vDeviceOS", $params_arr["device_os"]);
            }
            if (isset($params_arr["device_token"]))
            {
                $this->db->set("vDeviceToken", $params_arr["device_token"]);
            }
            $this->db->set($this->db->protect("vEmailVerificationCode"), $params_arr["_vemailverificationcode"], FALSE);
            if (isset($params_arr["auth_token"]))
            {
                $this->db->set("vAccessToken", $params_arr["auth_token"]);
            }
            $this->db->set("eEmailVerified", $params_arr["_eemailverified"]);
            $this->db->set("vTermsConditionsVersion", $params_arr["_vtermsconditionsversion"]);
            $this->db->set("vPrivacyPolicyVersion", $params_arr["_vprivacypolicyversion"]);
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
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

    /**
     * get_user_details_v1 method is used to execute database queries for User Sign Up Phone API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $insert_id insert_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_details_v1($insert_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            //$this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS email_user_name", FALSE);
            $this->db->select("u.eEmailVerified AS u_email_verified");
            $this->db->select("u.vAccessToken AS u_access_token");
            $this->db->select("u.eDeviceType AS u_device_type");
            $this->db->select("u.vDeviceModel AS u_device_model");
            $this->db->select("u.vDeviceOS AS u_device_os");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.dtAddedAt AS u_added_at");
            //$this->db->select("ms.vState AS ms_state");
            $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
            $this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            $this->db->select("u.ePushNotify AS u_push_notify");
            $this->db->select("u.vTermsConditionsVersion AS u_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS u_privacy_policy_version");
            $this->db->select("u.eLogStatus AS u_log_status_updated");
            if (isset($insert_id) && $insert_id != "")
            {
                $this->db->where("u.iUserId =", $insert_id);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * create_user_social method is used to execute database queries for Social Sign Up API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function create_user_social($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["user_name"]))
            {
                $this->db->set("vUserName", $params_arr["user_name"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["mobile_number"]))
            {
                $this->db->set("vMobileNo", $params_arr["mobile_number"]);
            }
            if (isset($params_arr["user_profile"]) && !empty($params_arr["user_profile"]))
            {
                $this->db->set("vProfileImage", $params_arr["user_profile"]);
            }

            if (isset($params_arr["upload_doc"]) && !empty($params_arr["upload_doc"]))
            {
                $this->db->set("vUploadDoc", $params_arr["upload_doc"]);
            }

            if (isset($params_arr["dob"]))
            {
                $this->db->set("dDob", $params_arr["dob"]);
            }
            if (isset($params_arr["address"]))
            {
                $this->db->set("tAddress", $params_arr["address"]);
            }
            if (isset($params_arr["city"]))
            {
                $this->db->set("vCity", $params_arr["city"]);
            }
            if (isset($params_arr["latitude"]))
            {
                $this->db->set("dLatitude", $params_arr["latitude"]);
            }
            if (isset($params_arr["longitude"]))
            {
                $this->db->set("dLongitude", $params_arr["longitude"]);
            }
            if (isset($params_arr["state_id"]))
            {
                $this->db->set("iStateId", $params_arr["state_id"]);
            }
            if (isset($params_arr["state_name"]))
            {
                $this->db->set("vStateName", $params_arr["state_name"]);
            }
            if (isset($params_arr["zipcode"]))
            {
                $this->db->set("vZipCode", $params_arr["zipcode"]);
            }
            $this->db->set("eStatus", $params_arr["status"]);
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            if (isset($params_arr["device_type"]))
            {
                $this->db->set("eDeviceType", $params_arr["device_type"]);
            }
            if (isset($params_arr["device_model"]))
            {
                $this->db->set("vDeviceModel", $params_arr["device_model"]);
            }
            if (isset($params_arr["device_os"]))
            {
                $this->db->set("vDeviceOS", $params_arr["device_os"]);
            }
            if (isset($params_arr["device_token"]))
            {
                $this->db->set("vDeviceToken", $params_arr["device_token"]);
            }
            $this->db->set($this->db->protect("vEmailVerificationCode"), $params_arr["_vemailverificationcode"], FALSE);
            if (isset($params_arr["auth_token"]))
            {
                $this->db->set("vAccessToken", $params_arr["auth_token"]);
            }
            if (isset($params_arr["social_login_type"]))
            {
                $this->db->set("eSocialLoginType", $params_arr["social_login_type"]);
            }
            if (isset($params_arr["social_login_id"]))
            {
                $this->db->set("vSocialLoginId", $params_arr["social_login_id"]);
            }

            if (isset($params_arr["profession"]))
            {
                $this->db->set("vProfession", $params_arr["profession"]);
            }
            
            if (isset($params_arr["upload_doc_url"]))
            {
                $this->db->set("vDocumentUrl", $params_arr["upload_doc_url"]);
            }

            $this->db->set("eEmailVerified", $params_arr["_eemailverified"]);
            $this->db->set("vTermsConditionsVersion", $params_arr["_vtermsconditionsversion"]);
            $this->db->set("vPrivacyPolicyVersion", $params_arr["_vprivacypolicyversion"]);
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
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

    /**
     * get_user_details_v1_v1 method is used to execute database queries for Social Sign Up API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $insert_id insert_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_details_v1_v1($insert_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            //$this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS email_user_name", FALSE);
            $this->db->select("u.eEmailVerified AS u_email_verified");
            $this->db->select("u.vAccessToken AS u_access_token");
            $this->db->select("u.eDeviceType AS u_device_type");
            $this->db->select("u.vDeviceModel AS u_device_model");
            $this->db->select("u.vDeviceOS AS u_device_os");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.dtAddedAt AS u_added_at");
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            //$this->db->select("ms.vState AS ms_state");
            $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
            $this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.ePushNotify AS u_push_notify");
            $this->db->select("u.vTermsConditionsVersion AS terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS privacy_policy_version");
            $this->db->select("u.eLogStatus AS u_log_status_updated");
            $this->db->select("u.vUploadDoc AS u_UploadDoc");
            $this->db->select("u.vDocumentUrl AS u_DocumentUrl");

            if (isset($insert_id) && $insert_id != "")
            {
                $this->db->where("u.iUserId =", $insert_id);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * check_user_exists_or_not method is used to execute database queries for Send Verification Link API.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_user_exists_or_not($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.eEmailVerified AS u_email_verified");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("(concat(u.vFirstName,\" \",u.vLastName)) AS email_user_name", FALSE);
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }
            $this->db->where_in("u.eEmailVerified", array('No'));
            $this->db->where_in("u.eStatus", array('Inactive'));

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_email_verification_code method is used to execute database queries for Send Verification Link API.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_email_verification_code($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["email"]) && $where_arr["email"] != "")
            {
                $this->db->where("vEmail =", $where_arr["email"]);
            }
            if (isset($params_arr["email_confirmation_code"]))
            {
                $this->db->set("vEmailVerificationCode", $params_arr["email_confirmation_code"]);
            }
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * get_user_login_details method is used to execute database queries for User Login Email API.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $auth_token auth_token is used to process query block.
     * @param string $where_clause where_clause is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_login_details($auth_token = '', $where_clause = '')
    {
        try
        {

            $result_arr = array();

            $this->db->from("users AS u");
            //$this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eEmailVerified AS u_email_verified");
            $this->db->select("u.eDeviceType AS u_device_type");
            $this->db->select("u.vDeviceModel AS u_device_model");
            $this->db->select("u.vDeviceOS AS u_device_os");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.dtAddedAt AS u_added_at");
            $this->db->select("u.dtUpdatedAt AS u_updated_at");
            $this->db->select("(".$this->db->escape("".$auth_token."").") AS auth_token1", FALSE);
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            $this->db->select("u.ePushNotify AS u_push_notify");
            //$this->db->select("ms.vState AS ms_state");
            $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
            $this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.vTermsConditionsVersion AS u_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS u_privacy_policy_version");
            $this->db->select("u.eLogStatus AS u_log_status_updated");

            $this->db->select("u.eSmoke AS u_Smoke");
            $this->db->select("u.vUploadDoc AS u_UploadDoc");
            $this->db->select("u.eDrink AS u_Drink");
            $this->db->select("u.e420Friendly AS u_420Friendly");
            $this->db->select("u.dHeight AS u_Height");
            $this->db->select("u.iKids AS u_Kids");
            $this->db->select("u.vBodyType AS u_BodyType");
            $this->db->select("u.eGender AS u_Gender");
            $this->db->select("u.vSign AS u_Sign");
            $this->db->select("u.vReligion AS u_Religion");
            $this->db->select("u.eSexualPrefrence AS u_SexualPrefrence");
            $this->db->select("u.vEducation AS u_Education");
            $this->db->select("u.vProfession AS u_Profession");
            $this->db->select("u.vIncome AS u_Income");
            $this->db->select("u.vImage1 AS u_Image1");
            $this->db->select("u.vImage2 AS u_Image2");
            $this->db->select("u.vImage3 AS u_Image3");
            $this->db->select("u.vImage4 AS u_Image4");
            $this->db->select("u.vDocumentUrl AS u_Image5");
            $this->db->select("u.tIntrest AS u_Intrest");
            $this->db->select("u.eMarriageStatus AS u_MarriageStatus");
            $this->db->select("u.vTatoos AS u_Tatoos");
            $this->db->select("u.tTravaledPlaces AS u_TravaledPlaces");
            $this->db->select("u.vTriggers AS u_Triggers");
            $this->db->select("u.tAboutYou AS u_AboutYou");
            $this->db->select("u.tAboutLatePerson AS u_AboutLatePerson");
            $this->db->select("u.app_section AS app_section");
            $this->db->select("u.tTravalToPlaces AS u_tTravalToPlaces");
            //$this->db->select("u.eIsSubscribed AS u_IsSubscribed");
            $this->db->select("'' AS subscription");
           
            
            $this->db->where("".$where_clause."", FALSE, FALSE);

            $this->db->limit(1);

            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }

            if (isset($where_clause) && $where_clause != "")
            {
                    $current_timezone = date_default_timezone_get();
                    // convert the current timezone to UTC
                     date_default_timezone_set('UTC');
                    $current_date = date("Y-m-d H:i:s");
                    // Again coverting into local timezone
                    date_default_timezone_set($current_timezone);

                    $this->db->select('vProductId as product_id,dLatestExpiryDate,"" as subscription_status, lReceiptData as purchase_token '); //vOrginalTransactionId
                    $this->db->from('user_subscription as u');
                    $this->db->where("".$where_clause."", FALSE, FALSE);
                    $this->db->order_by('dLatestExpiryDate','DESC');
                    //$this->db->where('iUserId',$user_id);
                    $status_data=$this->db->get()->result_array();

                    $subscription = array();
                    $subscription_plans = array();

                    foreach ($status_data as $key => $value) 
                    {
                        if(in_array($value['product_id'], $subscription_plans))
                        {
                            continue;
                        }

                        $expire_date=$value['dLatestExpiryDate']; 

                        unset($value['dLatestExpiryDate']);
                        //latest expire date is greater than current date
                        if(strtotime($expire_date) > strtotime($current_date) || $expire_date == "0000-00-00 00:00:00")
                        {
                            $value['subscription_status'] = 1;

                        }else
                        {
                            $value['subscription_status'] = 0;
                        }

                       // print_r($value);

                        $subscription[] = $value; 
                        $subscription_plans[] = $value['product_id']; 

                    }

                   //  print_r($subscription);

                $result_arr[0]["subscription"] = $subscription;
            
            }

            $this->db->reset_query();
            $success = 1;
        }
        catch(Exception $e)
        {
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

    /**
     * update_device_details method is used to execute database queries for User Login Email API.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_device_details($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["u_user_id"]) && $where_arr["u_user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["u_user_id"]);
            }
            if (isset($params_arr["device_type"]))
            {
                $this->db->set("eDeviceType", $params_arr["device_type"]);
            }
            if (isset($params_arr["device_model"]))
            {
                $this->db->set("vDeviceModel", $params_arr["device_model"]);
            }
            if (isset($params_arr["device_os"]))
            {
                $this->db->set("vDeviceOS", $params_arr["device_os"]);
            }
            if (isset($params_arr["device_token"]))
            {
                $this->db->set("vDeviceToken", $params_arr["device_token"]);
            }
            $this->db->set($this->db->protect("vAccessToken"), $params_arr["_vaccesstoken"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }


      /**
     * get_user_login_details_v1 method is used to execute database queries for User Login Phone API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $auth_token auth_token is used to process query block.
     * @param string $where_clause where_clause is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_login_details_v1($auth_token = '', $where_clause = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            //$this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eEmailVerified AS u_email_verified");
            $this->db->select("u.eDeviceType AS u_device_type");
            $this->db->select("u.vDeviceModel AS u_device_model");
            $this->db->select("u.vDeviceOS AS u_device_os");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.dtAddedAt AS u_added_at");
            $this->db->select("u.dtUpdatedAt AS u_updated_at");
            $this->db->select("(".$this->db->escape("".$auth_token."").") AS auth_token1", FALSE);
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            $this->db->select("u.ePushNotify AS u_push_notify");
            //$this->db->select("ms.vState AS ms_state");
            $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
            $this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.vTermsConditionsVersion AS u_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS u_privacy_policy_version");
            $this->db->select("u.eLogStatus AS u_log_status_updated");


            $this->db->select("u.eSmoke AS u_Smoke");
            $this->db->select("u.vUploadDoc AS u_UploadDoc");
            $this->db->select("u.eDrink AS u_Drink");
            $this->db->select("u.e420Friendly AS u_420Friendly");
            $this->db->select("u.dHeight AS u_Height");
            $this->db->select("u.iKids AS u_Kids");
            $this->db->select("u.vBodyType AS u_BodyType");
            $this->db->select("u.eGender AS u_Gender");
            $this->db->select("u.vSign AS u_Sign");
            $this->db->select("u.vReligion AS u_Religion");
            $this->db->select("u.eSexualPrefrence AS u_SexualPrefrence");
            $this->db->select("u.vEducation AS u_Education");
            $this->db->select("u.vProfession AS u_Profession");
            $this->db->select("u.vIncome AS u_Income");
            $this->db->select("u.vImage1 AS u_Image1");
            $this->db->select("u.vImage2 AS u_Image2");
            $this->db->select("u.vImage3 AS u_Image3");
            $this->db->select("u.vImage4 AS u_Image4");
            $this->db->select("u.tIntrest AS u_Intrest");
            $this->db->select("u.eMarriageStatus AS u_MarriageStatus");
            $this->db->select("u.vTatoos AS u_Tatoos");
            $this->db->select("u.tTravaledPlaces AS u_TravaledPlaces");
            $this->db->select("u.vTriggers AS u_Triggers");
            $this->db->select("u.tAboutYou AS u_AboutYou");
            $this->db->select("u.tAboutLatePerson AS u_AboutLatePerson");
            $this->db->select("u.app_section AS app_section");
           
            $this->db->where("".$where_clause."", FALSE, FALSE);

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_device_details_v1 method is used to execute database queries for User Login Phone API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_device_details_v1($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["u_user_id"]) && $where_arr["u_user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["u_user_id"]);
            }
            if (isset($params_arr["device_type"]))
            {
                $this->db->set("eDeviceType", $params_arr["device_type"]);
            }
            if (isset($params_arr["device_model"]))
            {
                $this->db->set("vDeviceModel", $params_arr["device_model"]);
            }
            if (isset($params_arr["device_os"]))
            {
                $this->db->set("vDeviceOS", $params_arr["device_os"]);
            }
            if (isset($params_arr["device_token"]))
            {
                $this->db->set("vDeviceToken", $params_arr["device_token"]);
            }
            $this->db->set($this->db->protect("vAccessToken"), $params_arr["_vaccesstoken"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * get_user_login_details_v1_v1 method is used to execute database queries for Social Login API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $auth_token auth_token is used to process query block.
     * @param string $where_clause where_clause is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_login_details_v1_v1($auth_token = '', $where_clause = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            //$this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eEmailVerified AS u_email_verified");
            $this->db->select("u.eDeviceType AS u_device_type");
            $this->db->select("u.vDeviceModel AS u_device_model");
            $this->db->select("u.vDeviceOS AS u_device_os");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.dtAddedAt AS u_added_at");
            $this->db->select("u.dtUpdatedAt AS u_updated_at");
            $this->db->select("(".$this->db->escape("".$auth_token."").") AS auth_token1", FALSE);
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            $this->db->select("u.ePushNotify AS u_push_notify");
            //$this->db->select("ms.vState AS ms_state");
            $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
            $this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.vTermsConditionsVersion AS u_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS u_privacy_policy_version");
            $this->db->select("u.eLogStatus AS u_log_status_updated");

            
            $this->db->select("u.eSmoke AS u_Smoke");
            $this->db->select("u.vUploadDoc AS u_UploadDoc");
            $this->db->select("u.eDrink AS u_Drink");
            $this->db->select("u.e420Friendly AS u_420Friendly");
            $this->db->select("u.dHeight AS u_Height");
            $this->db->select("u.iKids AS u_Kids");
            $this->db->select("u.vBodyType AS u_BodyType");
            $this->db->select("u.eGender AS u_Gender");
            $this->db->select("u.vSign AS u_Sign");
            $this->db->select("u.vReligion AS u_Religion");
            $this->db->select("u.eSexualPrefrence AS u_SexualPrefrence");
            $this->db->select("u.vEducation AS u_Education");
            $this->db->select("u.vProfession AS u_Profession");
            $this->db->select("u.vIncome AS u_Income");
            $this->db->select("u.vImage1 AS u_Image1");
            $this->db->select("u.vImage2 AS u_Image2");
            $this->db->select("u.vImage3 AS u_Image3");
            $this->db->select("u.vImage4 AS u_Image4");
            $this->db->select("u.tIntrest AS u_Intrest");
            $this->db->select("u.eMarriageStatus AS u_MarriageStatus");
            $this->db->select("u.vTatoos AS u_Tatoos");
            $this->db->select("u.tTravaledPlaces AS u_TravaledPlaces");
            $this->db->select("u.vTriggers AS u_Triggers");
            $this->db->select("u.tAboutYou AS u_AboutYou");
            $this->db->select("u.tAboutLatePerson AS u_AboutLatePerson");
            $this->db->select("u.app_section AS app_section");
            $this->db->select("u.tTravalToPlaces AS u_tTravalToPlaces");
         //   $this->db->select("u.eIsSubscribed AS u_IsSubscribed");
            $this->db->select("'' AS subscription");

            $this->db->where("".$where_clause."", FALSE, FALSE);

            $this->db->limit(1);

            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }

             if (isset($where_clause) && $where_clause != "")
            {
                    $current_timezone = date_default_timezone_get();
                    // convert the current timezone to UTC
                     date_default_timezone_set('UTC');
                    $current_date = date("Y-m-d H:i:s");
                    // Again coverting into local timezone
                    date_default_timezone_set($current_timezone);

                    $this->db->select('vProductId as product_id,dLatestExpiryDate,"" as subscription_status, lReceiptData as purchase_token '); //vOrginalTransactionId
                    $this->db->from('user_subscription');
                    $this->db->where('iUserId',$result_arr[0]['u_user_id']);
                    $this->db->order_by('dLatestExpiryDate','DESC');
                    $status_data=$this->db->get()->result_array();

                    $subscription = array();
                    $subscription_plans = array();

                    foreach ($status_data as $key => $value) 
                    {

                        if(in_array($value['product_id'], $subscription_plans))
                        {
                            continue;
                        }

                        $expire_date=$value['dLatestExpiryDate']; 

                        unset($value['dLatestExpiryDate']);
                        //latest expire date is greater than current date
                        if(strtotime($expire_date) > strtotime($current_date) || $expire_date == "0000-00-00 00:00:00")
                        {
                            $value['subscription_status'] = 1;

                        }else
                        {
                            $value['subscription_status'] = 0;
                        }

                        $subscription[] = $value; 
                        $subscription_plans[] = $value['product_id']; 
                    }

                $result_arr[0]["subscription"] = $subscription;
            
            }

            $success = 1;
        }
        catch(Exception $e)
        {
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

    /**
     * update_device_details_v1_v1 method is used to execute database queries for Social Login API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_device_details_v1_v1($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["u_user_id"]) && $where_arr["u_user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["u_user_id"]);
            }
            if (isset($params_arr["device_type"]))
            {
                $this->db->set("eDeviceType", $params_arr["device_type"]);
            }
            if (isset($params_arr["device_model"]))
            {
                $this->db->set("vDeviceModel", $params_arr["device_model"]);
            }
            if (isset($params_arr["device_os"]))
            {
                $this->db->set("vDeviceOS", $params_arr["device_os"]);
            }
            if (isset($params_arr["device_token"]))
            {
                $this->db->set("vDeviceToken", $params_arr["device_token"]);
            }
            $this->db->set($this->db->protect("vAccessToken"), $params_arr["_vaccesstoken"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * check_email_exists method is used to execute database queries for Forgot Password API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_email_exists($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.eStatus AS u_status");
            $this->db->select("(concat(u.vFirstName,\" \",u.vLastName)) AS email_username", FALSE);
            $this->db->select("u.vEmail AS u_email");
            if (isset($email) && $email != "")
            {
                $this->db->where("u.vEmail =", $email);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_reset_key method is used to execute database queries for Forgot Password API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_reset_key($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["email"]) && $where_arr["email"] != "")
            {
                $this->db->where("vEmail =", $where_arr["email"]);
            }
            if (isset($params_arr["reset_key"]))
            {
                $this->db->set("vResetPasswordCode", $params_arr["reset_key"]);
            }
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * reset_password method is used to execute database queries for Reset Password API.
     * @created priyanka chillakuru | 17.09.2019
     * @modified priyanka chillakuru | 17.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function reset_password($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["reset_key"]) && $where_arr["reset_key"] != "")
            {
                $this->db->where("vResetPasswordCode =", $where_arr["reset_key"]);
            }
            if (isset($params_arr["new_password"]))
            {
                $this->db->set("vPassword", $params_arr["new_password"]);
            }
            $this->db->set($this->db->protect("vResetPasswordCode"), $params_arr["_vresetpasswordcode"], FALSE);
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * get_user_by_mobile_number method is used to execute database queries for Forgot Password Phone API.
     * @created priyanka chillakuru | 17.09.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param string $mobile_number mobile_number is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_by_mobile_number($mobile_number = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("(concat(u.vFirstName,\"\",u.vLastName)) AS msg_user_name", FALSE);
            if (isset($mobile_number) && $mobile_number != "")
            {
                $this->db->where("u.vMobileNo =", $mobile_number);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_reset_key_phone method is used to execute database queries for Forgot Password Phone API.
     * @created priyanka chillakuru | 17.09.2019
     * @modified priyanka chillakuru | 17.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_reset_key_phone($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["mobile_number"]) && $where_arr["mobile_number"] != "")
            {
                $this->db->where("vMobileNo =", $where_arr["mobile_number"]);
            }
            if (isset($params_arr["reset_key"]))
            {
                $this->db->set("vResetPasswordCode", $params_arr["reset_key"]);
            }
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * check_mobile_number method is used to execute database queries for Reset Password Phone API.
     * @created priyanka chillakuru | 17.09.2019
     * @modified priyanka chillakuru | 17.09.2019
     * @param string $mobile_number mobile_number is used to process query block.
     * @param string $reset_key reset_key is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_mobile_number($mobile_number = '', $reset_key = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.iUserId AS u_user_id");
            if (isset($mobile_number) && $mobile_number != "")
            {
                $this->db->where("u.vMobileNo =", $mobile_number);
            }
            if (isset($reset_key) && $reset_key != "")
            {
                $this->db->where("u.vResetPasswordCode =", $reset_key);
            }

            $this->db->limit(1);

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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_user_password method is used to execute database queries for Reset Password Phone API.
     * @created priyanka chillakuru | 17.09.2019
     * @modified priyanka chillakuru | 17.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_user_password($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["u_user_id"]) && $where_arr["u_user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["u_user_id"]);
            }
            if (isset($where_arr["mobile_number"]) && $where_arr["mobile_number"] != "")
            {
                $this->db->where("vMobileNo =", $where_arr["mobile_number"]);
            }
            if (isset($where_arr["reset_key"]) && $where_arr["reset_key"] != "")
            {
                $this->db->where("vResetPasswordCode =", $where_arr["reset_key"]);
            }
            if (isset($params_arr["new_password"]))
            {
                $this->db->set("vPassword", $params_arr["new_password"]);
            }
            $this->db->set($this->db->protect("vResetPasswordCode"), $params_arr["_vresetpasswordcode"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * update_profile method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_profile($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();

            $imgArr = [];

            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $imgArr['iUserId'] = $where_arr["user_id"];
               
            }

             if (isset($params_arr["_dtupdatedat"])  && $params_arr["_dtupdatedat"] != "")
            {
                $imgArr['dtAddedAt'] = date("Y-m-d h:i:sa");
            }
           
            if (isset($params_arr["image1"]) && !empty($params_arr["image1"]))
            {
               // $this->db->set("vImage1", $params_arr["image1"]);

                $imgArr['vImage'] = $params_arr["image1"];
  
                $this->db->insert("users_profile_images",$imgArr);
            }
            if (isset($params_arr["image2"]) && !empty($params_arr["image2"]))
            {
               // $this->db->set("vImage2", $params_arr["image2"]);

                $imgArr['vImage'] = $params_arr["image2"];

                $this->db->insert("users_profile_images",$imgArr);
            }
            if (isset($params_arr["image3"]) && !empty($params_arr["image3"]))
            {
              //  $this->db->set("vImage3", $params_arr["image3"]);

                $imgArr['vImage'] = $params_arr["image3"];
                 $this->db->insert("users_profile_images",$imgArr);
            }
            if (isset($params_arr["image4"]) && !empty($params_arr["image4"]))
            {
              //  $this->db->set("vImage4", $params_arr["image4"]);

                $imgArr['vImage'] = $params_arr["image4"];
                 $this->db->insert("users_profile_images",$imgArr);
            }

            if (isset($params_arr["image5"]) && !empty($params_arr["image5"]))
            {
                // $this->db->set("vImage5", $params_arr["image5"]);

                $imgArr['vImage'] = $params_arr["image5"];
                 $this->db->insert("users_profile_images",$imgArr);
            }

            $this->db->reset_query();

            $this->db->start_cache();

            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }
            $this->db->where_in("eStatus", array('Active'));
            $this->db->stop_cache();
            if (isset($params_arr["first_name"]) && $params_arr["first_name"] != "")
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]) && $params_arr["first_name"] != "")
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["user_profile"]) && !empty($params_arr["user_profile"]))
            {
                $this->db->set("vProfileImage", $params_arr["user_profile"]);
            }

            if (isset($params_arr["upload_doc"]) && !empty($params_arr["upload_doc"]))
            {
                $this->db->set("vUploadDoc", $params_arr["upload_doc"]);
            }


            if (isset($params_arr["dob"]) && $params_arr["dob"] != "")
            {
                $this->db->set("dDob", $params_arr["dob"]);
            }
            if (isset($params_arr["address"])  && $params_arr["address"] != "")
            {
                $this->db->set("tAddress", $params_arr["address"]);
            }
            if (isset($params_arr["city"])  && $params_arr["city"] != "")
            {
                $this->db->set("vCity", $params_arr["city"]);
            }
            if (isset($params_arr["latitude"])  && $params_arr["latitude"] != "")
            {
                $this->db->set("dLatitude", $params_arr["latitude"]);
            }
            if (isset($params_arr["longitude"])  && $params_arr["longitude"] != "")
            {
                $this->db->set("dLongitude", $params_arr["longitude"]);
            }
            if (isset($params_arr["state_id"])  && $params_arr["state_id"] != "")
            {
                $this->db->set("iStateId", $params_arr["state_id"]);
            }
            if (isset($params_arr["state_name"])  && $params_arr["state_name"] != "")
            {
                $this->db->set("vStateName", $params_arr["state_name"]);
            }
            if (isset($params_arr["zipcode"])  && $params_arr["zipcode"] != "")
            {
                $this->db->set("vZipCode", $params_arr["zipcode"]);
            }

            if (isset($params_arr["_dtupdatedat"])  && $params_arr["_dtupdatedat"] != "")
            {
                 $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            }

           
            if (isset($params_arr["user_name"])  && $params_arr["user_name"] != "")
            {
                $this->db->set("vUserName", $params_arr["user_name"]);
            }
            if (isset($params_arr["mobile_number"])  && $params_arr["mobile_number"] != "")
            {
                $this->db->set("vMobileNo", $params_arr["mobile_number"]);
            }

             if (isset($params_arr["drink"])  && $params_arr["drink"] != "")
            {
                $this->db->set("eDrink", $params_arr["drink"]);
            }
             if (isset($params_arr["smoke"])  && $params_arr["smoke"] != "")
            {
                $this->db->set("eSmoke", $params_arr["smoke"]);
            }
             if (isset($params_arr["420friendly"])  && $params_arr["420friendly"] != "")
            {
                $this->db->set("e420Friendly", $params_arr["420friendly"]);
            }
             if (isset($params_arr["kids"])  && $params_arr["kids"] != "")
            {
                $this->db->set("iKids", $params_arr["kids"]);
            }
             if (isset($params_arr["height"])  && $params_arr["height"] != "")
            {
                $this->db->set("dHeight", $params_arr["height"]);
            }
             if (isset($params_arr["bodytype"])  && $params_arr["bodytype"] != "")
            {
                $this->db->set("vBodyType", $params_arr["bodytype"]);
            }
             if (isset($params_arr["sign"])  && $params_arr["sign"] != "")
            {
                $this->db->set("vSign", $params_arr["sign"]);
            }
             if (isset($params_arr["gender"])  && $params_arr["gender"] != "")
            {
                $this->db->set("eGender", $params_arr["gender"]);
            }
             if (isset($params_arr["religion"])  && $params_arr["religion"] != "")
            {
                $this->db->set("vReligion", $params_arr["religion"]);
            }
             if (isset($params_arr["sexual_prefrence"])  && $params_arr["sexual_prefrence"] != "")
            {
                $this->db->set("eSexualPrefrence", $params_arr["sexual_prefrence"]);
            }
             if (isset($params_arr["education"])  && $params_arr["education"] != "")
            {
                $this->db->set("vEducation", $params_arr["education"]);
            }
             if (isset($params_arr["profession"])  && $params_arr["profession"] != "")
            {
                $this->db->set("vProfession", $params_arr["profession"]);
            }
             if (isset($params_arr["income"])  && $params_arr["income"] != "")
            {
                $this->db->set("vIncome", $params_arr["income"]);
            }
             
            if (isset($params_arr["marriage_status"])  && $params_arr["marriage_status"] != "")
            {
                $this->db->set("eMarriageStatus", $params_arr["marriage_status"]);
            }
            if (isset($params_arr["tatoos"]) )
            {
                $this->db->set("vTatoos", $params_arr["tatoos"]);
            }
            if (isset($params_arr["travaled_places"]))
            {
                $this->db->set("tTravaledPlaces", $params_arr["travaled_places"]);
            }

            if (isset($params_arr["places_want_to_travel"]))
            {
                $this->db->set("tTravalToPlaces", $params_arr["places_want_to_travel"]);
            }
            if (isset($params_arr["triggers"]) )
            {
                $this->db->set("vTriggers", $params_arr["triggers"]);
            }
            if (isset($params_arr["about_you"]) )
            {
                $this->db->set("tAboutYou", $params_arr["about_you"]);
            }
            if (isset($params_arr["about_late_person_passes"]) )
            {
                $this->db->set("tAboutLatePerson", $params_arr["about_late_person_passes"]);
            }

            if (isset($params_arr["section_id"])  && $params_arr["section_id"] != "")
            {
                $this->db->set("app_section", $params_arr["section_id"]);
            }

            $res = $this->db->update("users");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;


            if (isset($params_arr["intrest"]) && isset($where_arr["user_id"]))
            {
                //echo "in intrest ---".$params_arr["intrest"];

                $this->db->query("delete from user_interest where iUserId = '".$where_arr["user_id"]."'");
                
               // echo $this->db->last_query();

                if($params_arr["intrest"] != "")
                {
                    $count=count(explode(",",$params_arr["intrest"]));
                   
                    if($count==1)
                    {

                        if (isset($params_arr["intrest"]))
                        {
                            $this->db->set("iInterestsId", $params_arr["intrest"]);
                        }
                        if (isset($where_arr["user_id"]))
                        {
                            $this->db->set("iUserId", $where_arr["user_id"]);
                        }
                        $this->db->set($this->db->protect("dAddedAt"), $params_arr["_dtupdatedat"], FALSE);
                        $this->db->insert("user_interest");
                        $insert_id = $this->db->insert_id();
                        //echo $this->db->last_query();exit;
                        if (!$insert_id)
                        {
                            throw new Exception("Failure in insertion.");
                        }

                    }else if($count>1){

                            $arrInterestIds = explode(',',$params_arr["intrest"]);
                            foreach($arrInterestIds as $key=>$intInterestValue)
                            {
                                $insert_arr[$key]['iUserId']=$where_arr["user_id"];
                                $insert_arr[$key]['iInterestsId']=$intInterestValue;
                                $insert_arr[$key]['dAddedAt']=date('Y-m-d H:i:s');
                            }

                         if(is_array($insert_arr) && !empty($insert_arr))
                            {
                                $res = $this->db->insert_batch("user_interest",$insert_arr);
                            }
                        //$affected_rows = $this->db->affected_rows();

                    }

                }

                    
            }
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
        return $return_arr;
    }

    /**
     * get_updated_details method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_updated_details($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            //$this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.ePushNotify AS u_push_notify");
            $this->db->select("u.vAccessToken AS u_access_token");
            $this->db->select("u.eDeviceType AS u_device_type");
            $this->db->select("u.vDeviceModel AS u_device_model");
            $this->db->select("u.vDeviceOS AS u_device_os");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.dtAddedAt AS u_added_at");
            $this->db->select("u.dtUpdatedAt AS u_updated_at");
            $this->db->select("u.eEmailVerified AS u_email_verified");
            //$this->db->select("ms.vState AS ms_state");
            $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
            $this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.vTermsConditionsVersion AS u_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS u_privacy_policy_version");
            $this->db->select("u.eLogStatus AS u_log_status_updated");
            $this->db->select("u.app_section AS u_section_id");

            $this->db->select("u.eSmoke AS u_Smoke");
            $this->db->select("u.vUploadDoc AS u_UploadDoc");
            $this->db->select("u.eDrink AS u_Drink");
            $this->db->select("u.e420Friendly AS u_420Friendly");
            $this->db->select("u.dHeight AS u_Height");
            $this->db->select("u.iKids AS u_Kids");
            $this->db->select("u.vBodyType AS u_BodyType");
            $this->db->select("u.eGender AS u_Gender");
            $this->db->select("u.vSign AS u_Sign");
            $this->db->select("u.vReligion AS u_Religion");
            $this->db->select("u.eSexualPrefrence AS u_SexualPrefrence");
            $this->db->select("u.vEducation AS u_Education");
            $this->db->select("u.vProfession AS u_Profession");
            $this->db->select("u.vIncome AS u_Income");
           /* $this->db->select("u.vImage1 AS u_Image1");
            $this->db->select("u.vImage2 AS u_Image2");
            $this->db->select("u.vImage3 AS u_Image3");
            $this->db->select("u.vImage4 AS u_Image4");
            $this->db->select("u.vDocumentUrl AS u_Image5");*/
            $this->db->select("u.tIntrest AS u_Intrest");
            $this->db->select("u.eMarriageStatus AS u_MarriageStatus");
            $this->db->select("u.vTatoos AS u_Tatoos");
            $this->db->select("u.tTravaledPlaces AS u_TravaledPlaces");
            $this->db->select("u.tTravalToPlaces AS u_tTravalToPlaces");
            $this->db->select("u.vTriggers AS u_Triggers");
            $this->db->select("u.tAboutYou AS u_AboutYou");
            $this->db->select("u.tAboutLatePerson AS u_AboutLatePerson");
             $this->db->select("u.eIsSubscribed AS u_IsSubscribed");
             $this->db->select("'' AS subscription");
            $this->db->select("u.vImage1 AS u_images");

            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("u.iUserId =", $user_id);
            }

            $this->db->limit(1);

            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();

         
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }

            $this->db->reset_query();
            if (isset($user_id) && $user_id != "")
            {
                $this->db->from("user_interest AS ui");
                $this->db->select("Distinct(ui.iInterestsId) AS InterestsId");
                $this->db->where("ui.iUserId =", $user_id);

                $result_objj = $this->db->get();

                $interestArr = [];

                foreach ($result_objj->result_array() as $rowA)
                {
                    $interestArr[] = $rowA['InterestsId'];
                }   
                
                $result_arr[0][u_Intrest] = implode(",", $interestArr);
              
            }

            if (isset($user_id) && $user_id != "")
            {
                    $current_timezone = date_default_timezone_get();
                    // convert the current timezone to UTC
                     date_default_timezone_set('UTC');
                    $current_date = date("Y-m-d H:i:s");
                    // Again coverting into local timezone
                    date_default_timezone_set($current_timezone);

                    $this->db->select('vProductId as product_id,dLatestExpiryDate,"" as subscription_status, lReceiptData as purchase_token '); //vOrginalTransactionId
                    $this->db->from('user_subscription');
                    $this->db->where('iUserId',$user_id);
                    $this->db->order_by('dLatestExpiryDate','DESC');
                    $status_data=$this->db->get()->result_array();

                    $subscription = array();
                    $subscription_plans = array();


                    foreach ($status_data as $key => $value) 
                    {

                         if(in_array($value['product_id'], $subscription_plans))
                        {
                            continue;
                        }

                        $expire_date=$value['dLatestExpiryDate']; 

                        unset($value['dLatestExpiryDate']);
                        //latest expire date is greater than current date
                        if(strtotime($expire_date) > strtotime($current_date) || $expire_date == "0000-00-00 00:00:00")
                        {
                            $value['subscription_status'] = 1;

                        }else
                        {
                            $value['subscription_status'] = 0;
                        }

                        $subscription[] = $value; 
                        $subscription_plans[] = $value['product_id']; 
                    }

                $result_arr[0]["subscription"] = $subscription;
            
            }

            $this->db->reset_query();
            if (isset($user_id) && $user_id != "")
            {
                $this->db->from("users_profile_images");
                $this->db->select("Distinct(iImageId) AS image_id");
                $this->db->select("vImage as image_url");
                $this->db->where("iUserId =", $user_id);

                $result_objjj = $this->db->get();

                $imgArr = [];

                foreach ($result_objjj->result_array() as $rowB)
                {
                    $imgArr[] = $rowB;
                }   
                
                $result_arr[0]["u_images"] = $imgArr;
                
            }

            $success = 1;
        }
        catch(Exception $e)
        {
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

    /**
     * update_email_verfied_status method is used to execute database queries for Admin Update User status In Listing API.
     * @created priyanka chillakuru | 24.09.2019
     * @modified saikrishna bellamkonda | 25.10.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_email_verfied_status($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }

            $this->db->set("eEmailVerified", $params_arr["_eemailverified"]);
            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            $this->db->set($this->db->protect("dtDeletedAt"), $params_arr["_dtdeletedat"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * update_transaction_data method is used to execute database queries for Go Ad Free API.
     * @created priyanka chillakuru | 26.09.2019
     * @modified priyanka chillakuru | 26.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_transaction_data($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }
            $this->db->where_in("eStatus", array('Active'));

            $this->db->set("eOneTimeTransaction", $params_arr["_eonetimetransaction"]);
            if (isset($params_arr["one_time_transaction_data"]))
            {
                $this->db->set("tOneTimeTransaction", $params_arr["one_time_transaction_data"]);
            }
            $res = $this->db->update("users");
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
        return $return_arr;
    }

    /**
     * delete_user_account method is used to execute database queries for Delete Account API.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function delete_user_account($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }

            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set("vDeviceToken", "");
            $this->db->set($this->db->protect("dtDeletedAt"), $params_arr["_dtdeletedat"], FALSE);
            $res = $this->db->update("users");
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
        return $return_arr;
    }


    public function delete_user_media_image($where_arr = array(), $params_arr = array())
  {
    try
        {
            $result_arr = array();
            $affected_rows = 0;

            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);

                if (isset($params_arr["image_id"]))
                {
                    $this->db->where("iImageId =", $params_arr["image_id"]);

                    $res = $this->db->delete("users_profile_images");

               // echo $this->db->last_query();

                    $affected_rows = $this->db->affected_rows();
                    if (!$res || $affected_rows == -1)
                    {
                        throw new Exception("Failure in updation.");
                    }
                }

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
        //print_r($return_arr);exit;
        return $return_arr;
    }

    
    public function get_user_personal_images($where_arr = array())
    {
        try
        {

            if ($where_arr['user_id'] > 0 && $where_arr['image_id'] > 0)
            {

                $this->db->from("users_profile_images");
                $this->db->select("Distinct(iImageId) AS image_id");
                $this->db->select("vImage as image_url");
                $this->db->where("iUserId =", $where_arr['user_id']);
                $this->db->where("iImageId =", $where_arr['image_id']);

                $result_objjj = $this->db->get();

                $imgArr = [];

                foreach ($result_objjj->result_array() as $rowB)
                {
                    $imgArr = $rowB['image_url'];
                }   
                
                $result_arr["image_url"] = $imgArr;
                
            }

            $success = 1;
        }
        catch(Exception $e)
        {
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
