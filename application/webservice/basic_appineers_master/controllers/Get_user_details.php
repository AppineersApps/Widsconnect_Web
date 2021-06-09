<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Login Email Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module User Login Email
 *
 * @class User_login_email.php
 *
 * @path application\webservice\basic_appineers_master\controllers\User_login_email.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class Get_user_details extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
    public $multiple_keys;
    public $block_result;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings_params = array();
        $this->output_params = array();
        $this->single_keys = array(
            "get_user_login_details",
        );
        $this->multiple_keys = array();
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model("basic_appineers_master/users_model");
        $this->load->model('wids_user_model');
    }

    /**
     * rules_user_login_email method is used to validate api input params.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_user_details($request_arr = array())
    {
        $valid_arr = array(
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            ),
            "other_user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "other_user_id_required",
                )
            ),

           
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "user_profiles");

        return $valid_res;
    }

    /**
     * start_user_login_email method is used to initiate api execution flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_get_user_details($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_get_user_details($request_arr);
            if ($validation_res["success"] == "-5")
            {
                if ($inner_api === TRUE)
                {
                    return $validation_res;
                }
                else
                {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
            $input_params = $validation_res['input_params'];
            $output_array = $func_array = array();
         
            $input_params = $this->get_user_details($input_params);

          //  echo $input_params['user_latitude']."-----------";
           // print_r($input_params); exit;

               $input_params = $this->get_user_login_details($input_params);

             //  print_r($input_params); exit;
 
                $condition_res = $this->check_user_exists($input_params);
                if ($condition_res["success"])
                {

                    $output_response = $this->users_finish_success_3($input_params);
                    return $output_response;
                       
                }

                else
                {

                    $output_response = $this->users_finish_success($input_params);
                    return $output_response;
                }
  
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

  
    /**
     * check_status method is used to process conditions.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_status($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["status"];
            $cc_ro_0 = 1;

            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }


    /**
     * get_user_login_details method is used to process query block.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_user_login_details($input_params = array())
    {

       $user_id= isset($input_params['user_id']) ? $input_params['user_id'] : "";
       $other_user_id= isset($input_params['other_user_id']) ? $input_params['other_user_id'] : "";
       $lat= isset($input_params['user_latitude']) ? $input_params['user_latitude'] : "";
       $lon= isset($input_params['user_longitude']) ? $input_params['user_longitude'] : "";
       $app_section = isset($input_params['app_section']) ? $input_params['app_section'] :"0";

        $this->block_result = array();
        try
        {

            $this->block_result = $this->users_model->get_user_personal_details($user_id,$other_user_id,$lat,$lon,$app_section);

            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {
                    //****************************
                    $data1 = $data_arr["u_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data1;
                     $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                        $image_arr["color"] = "FFFFFF";
                        $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="widsconnect/user_profile";
                    $data1 = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_profile_image"] = (false == empty($data1))?$data1:"";

                    //****************************
                /*  $data1 = $data_arr["u_Image1"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data1;
                     $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                        $image_arr["color"] = "FFFFFF";
                        $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="widsconnect/personal_images";
                    $data1 = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image1"] = (false == empty($data1))?$data1:"";

                    $data1 = $data_arr["u_Image2"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data1;
                     $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                        $image_arr["color"] = "FFFFFF";
                        $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="widsconnect/personal_images";
                    $data1 = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image2"] = (false == empty($data1))?$data1:"";

                    $data1 = $data_arr["u_Image3"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data1;
                     $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                        $image_arr["color"] = "FFFFFF";
                        $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="widsconnect/personal_images";
                    $data1 = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image3"] = (false == empty($data1))?$data1:"";


                    $data1 = $data_arr["u_Image4"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data1;
                     $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                        $image_arr["color"] = "FFFFFF";
                        $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="widsconnect/personal_images";
                    $data1 = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image4"] = (false == empty($data1))?$data1:"";
                */


                    foreach ($data_arr["u_images"] as $key => $value) {

                       $data1 = $value["image_url"];
                        $image_arr = array();
                        $image_arr["image_name"] = $data1;
                         $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                            $image_arr["color"] = "FFFFFF";
                            $image_arr["no_img"] = FALSE;
                        $image_arr["path"] ="widsconnect/personal_images";
                        $data1 = $this->general->get_image_aws($image_arr);


                        $result_arr[$data_key]["u_images"][$key]["image_url"] = (false == empty($data1))?$data1:"";
                    }
                    //****************************

                    $data = $data_arr["u_UploadDoc"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "widsconnect/upload_doc";
                   
                    $data = $this->general->get_image_aws($image_arr);
                    //print_r($data); exit;
                    $result_arr[$data_key]["u_UploadDoc"] = (false == empty($data)) ? $data : "";


                    $i++;
                }
                $this->block_result["data"] = $result_arr;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_user_login_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    public function get_user_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $this->block_result = $this->users_model->get_user_details($input_params['user_id']);

            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];

            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {
                    //****************************
                    $input_params["user_latitude"] = $data_arr["u_latitude"];
                    $input_params["user_longitude"] = $data_arr["u_longitude"];
                }
  
          //  $this->block_result["data"] = $data1;
            }
            
        }
        catch(Exception $e)
        {
            $success = 0;
            //$this->block_result["data"] = array();
        }

       // print_r($input_params); exit();

        //$input_params["get_user_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }


    /**
     * check_user_exists method is used to process conditions.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_user_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_user_login_details"]) ? 0 : 1);
            $cc_ro_0 = 1;

            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }

    
    /**
     * users_finish_success_3 method is used to process finish flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_3($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "users_finish_success_3",
        );
        $output_fields = array(
            'u_user_id',
            "u_first_name",
            "u_last_name",
            "u_user_name",
            "u_email" ,
            "u_mobile_no",
            "u_profile_image",
            "u_dob" ,
            "u_address",
            "u_city" ,
            "u_latitude" ,
            "u_longitude",
            "u_state_id",
            "u_state_name",
            "u_zip_code" ,

            "u_device_token",
            "u_status" ,

            "auth_token1" ,
            "u_social_login_type",
            "u_social_login_id",
            "u_push_notify",
            //"ms_state" => "state",
            "e_one_time_transaction" ,
            "t_one_time_transaction" ,
            "u_terms_conditions_version",
            "u_privacy_policy_version",
            "u_log_status_updated",

            'u_Smoke',
            'u_UploadDoc',
            'u_Drink',
            'u_420Friendly',
            'u_Height',
            'u_Kids',
            'u_BodyType',
            'u_Gender',
            'u_Sign',
            'u_Religion',
            'u_SexualPrefrence',
            'u_Education',
            'u_Profession',
            'u_Income',
            'u_Intrest',
            'u_Intrest_name',
            'u_MarriageStatus',
            'u_Tatoos',
            'u_TravaledPlaces',
            'u_tTravalToPlaces',
            'u_Triggers',
            'u_AboutYou',
            'u_AboutLatePerson',
            'u_app_section',
            'u_images',
            'connection_type_by_logged_user',
            'connection_type_by_receiver_user',
            'age',
            'miles',
            'block_status'
           /* 'u_Image2',
            'u_Image3',
            'u_Image4',
            'u_Image5'*/
        );
        $output_keys = array(
            'get_user_login_details',
        );
        $ouput_aliases = array(
            "get_user_login_details" => "get_user_personal_details",
            "u_user_id" => "user_id",
            "u_first_name" => "first_name",
            "u_last_name" => "last_name",
            "u_user_name" => "user_name",
            "u_email" => "email",
            "u_mobile_no" => "mobile_no",
            "u_profile_image" => "profile_image",
            "u_dob" => "dob",
            "u_address" => "address",
            "u_city" => "city",
            "u_latitude" => "latitude",
            "u_longitude" => "longitude",
            "u_state_id" => "state_id",
            "u_state_name" => "state_name",
            "u_zip_code" => "zip_code",

            "u_device_token" => "device_token",
            "u_status" => "status",

            "auth_token1" => "access_token",
            "u_social_login_type" => "social_login_type",
            "u_social_login_id" => "social_login_id",
            "u_push_notify" => "push_notify",
            //"ms_state" => "state",
            "e_one_time_transaction" => "purchase_status",
            "t_one_time_transaction" => "purchase_receipt_data",
            "u_terms_conditions_version" => "terms_conditions_version",
            "u_privacy_policy_version" => "privacy_policy_version",
            "u_log_status_updated" => "log_status_updated",

            'u_Smoke' => 'smoke',
            'u_UploadDoc' => 'upload_doc',
            'u_Drink'=>'drink',
            'u_420Friendly'=>'420friendly',
            'u_Height'=>'height',
            'u_Kids'=>'kids',
            'u_BodyType'=>'body_type',
            'u_Gender'=>'gender',
            'u_Sign'=>'sign',
            'u_Religion'=>'religion',
            'u_SexualPrefrence'=>'sexual_preference',
            'u_Education'=>'education',
            'u_Profession'=>'profession',
            'u_Income'=>'income',
            'u_Intrest'=>'interest',
            'u_Intrest_name'=>'interest_names',
            'u_MarriageStatus'=>'marriage_status',
            'u_Tatoos'=>'tattoos',
            'u_TravaledPlaces'=>'traveled_places',
            'u_tTravalToPlaces'=>'places_want_to_travel',
            'u_Triggers'=>'triggers',
            'u_app_section'=>'app_section',
            'u_AboutYou'=>'about_you',
            'u_AboutLatePerson'=>'about_late_person',
            'u_images'=> 'user_images',
             'connection_type_by_logged_user' => 'connection_type_by_logged_user',
            'connection_type_by_receiver_user' => 'connection_type_by_receiver_user',
            'age' => 'age',
            'miles' => 'miles',
            'block_status' => 'blocked_status'
            /*'u_Image2'=>'image2',
            'u_Image3'=> 'image3',
            'u_Image4'=>'image4',
            'u_Image5'=>'image5',*/
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_profiles";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    
    /**
     * users_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_profiles";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

}
