<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Users Model
 *
 * @category notification
 *
 * @package users
 *
 * @subpackage models
 *
 * @module Users
 *
 * @class Users_model.php
 *
 * @path application\notification\users\models\Users_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 27.04.2020
 */

class Users_model extends CI_Model
{
    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
    }

      /**
     * update_the_likes_per_day_of_user method is used to execute database queries for Manage Likes Count notification.
     * @created saikrishna bellamkonda | 25.07.2019
     * @modified saikrishna bellamkonda | 25.07.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_the_likes_per_day_of_user($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($params_arr["ms_value"]))
            {
                $this->db->set("iLikesPerDay", $params_arr["ms_value"]);
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
     * fetch_the_subscribed_users method is used to execute database queries for Check Subscription Status notification.
     * @created CIT Dev Team
     * @modified saikrishna bellamkonda | 23.12.2019
     * @return array $return_arr returns response of query block.
     */
    public function fetch_the_subscribed_users()
    {
        try
        {
            $result_arr = array();

            $this->db->from("user_subscription AS us");
            $this->db->join("users AS u", "u.iUserId= us.iUserId ", "left");
            $this->db->select("us.iUserId AS u_user_id");
           //  $this->db->select("us.iSubscriptionId AS u_user_subscription_id");
            $this->db->select("us.eDeviceType AS u_receipt_type");
            $this->db->select("us.vProductId AS u_subscription_id");
            $this->db->select("us.vOrginalTransactionId AS u_transaction_id");
            $this->db->select("us.dLatestExpiryDate AS u_expiry_date");
            $this->db->select("us.lReceiptData AS u_receipt_data");
            $this->db->where("us.eAutoRenewal", 1);
            $this->db->where("u.eStatus", 'Active');

            $result_obj = $this->db->get();

            //echo "--".$this->db->last_query();

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

    public function get_archived_users()
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            $this->db->join("users_profile_images AS up","up.iUserId = u.iUserId","LEFT");
            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vEmail AS email");
            $this->db->select("u.vFirstName AS email_user_name");
            $this->db->select("u.vProfileImage AS profile_image");
            $this->db->select("GROUP_CONCAT( up.vImage ) as 'personal_images'");
            $this->db->group_by("u.iUserId");
            $this->db->where_in("u.eStatus", array('Pending_delete'));

            $result_obj = $this->db->get();

          //  echo "--".$this->db->last_query(); exit;

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


    public function delete_records_frm_connection_tables($request_arr = array())
    {
         try
        {
          $users_data = array();

            if(count($request_arr) > 0)
            {
                foreach ($request_arr as $key => $value) 
                {

                //  echo $value['u_user_id']."----";

                    $this->db->query("DELETE FROM abusive_reports WHERE iReportedBy='".$value['u_user_id']."' OR iReportedOn='".$value['u_user_id']."' ");

                   // echo $this->db->last_query();

                    $this->db->query("DELETE FROM notification WHERE iReceiverId='".$value['u_user_id']."' OR iSenderId='".$value['u_user_id']."'");

                    //echo $this->db->last_query();

                      $this->db->query("DELETE FROM users_connections WHERE iUserId='".$value['u_user_id']."' OR iConnectionUserId='".$value['u_user_id']."' ");

                    //echo $this->db->last_query();


                    $this->db->query("DELETE FROM users_profile_images WHERE iUserId='".$value['u_user_id']."'");

                    //echo $this->db->last_query();

                    $this->db->query("DELETE FROM user_block WHERE iUserId='".$value['u_user_id']."' OR iBlockUserId='".$value['u_user_id']."' ");

                    //echo $this->db->last_query();


                    $this->db->query("DELETE FROM user_interest WHERE iUserId='".$value['u_user_id']."'");

                   // echo $this->db->last_query();

                      $this->db->query("DELETE FROM user_query WHERE iUserId='".$value['u_user_id']."'");

                    // echo $this->db->last_query();

                    $this->db->query("DELETE FROM user_subscription WHERE iUserId='".$value['u_user_id']."'");

                    //echo $this->db->last_query();

                     $this->db->query("UPDATE message SET iDelete_user_id='".$value['u_user_id']."' WHERE iSenderId = '".$value['u_user_id']."' OR iReceiverId= '".$value['u_user_id']."' ");

                    //echo $this->db->last_query();

                    $this->db->query("DELETE FROM users WHERE iUserId='".$value['u_user_id']."' AND eStatus='Pending_delete' ");

                   // echo $this->db->last_query();

                     $users_data[$key]['email'] = $value['email'];
                     $users_data[$key]['email_user_name'] = $value['email_user_name'];

                }


                if($this->db->affected_rows() > 0 )
                {
                     $success = 1;

                }
                else
                {
                     $success = 0;

                }
            }
            
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $users_data;

        return $return_arr;
    }
}
