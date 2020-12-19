<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Login Phone Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module User Login Phone
 *
 * @class User_login_phone.php
 *
 * @path application\webservice\basic_appineers_master\controllers\User_login_phone.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class User_login_phone extends Cit_Controller
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
            "get_user_login_details_v1",
            "update_device_details_v1",
        );
        $this->multiple_keys = array(
            "prepare_where",
            "generate_auth_token",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('user_login_phone_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_user_login_phone method is used to validate api input params.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_user_login_phone($request_arr = array())
    {
        $valid_arr = array(
            "mobile_number" => array(
                array(
                    "rule" => "number",
                    "value" => TRUE,
                    "message" => "mobile_number_number",
                )
            ),
            "device_type" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_type_required",
                )
            ),
            "device_model" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_model_required",
                )
            ),
            "device_os" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_os_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "user_login_phone");

        return $valid_res;
    }

    /**
     * start_user_login_phone method is used to initiate api execution flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_user_login_phone($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_user_login_phone($request_arr);
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

            $input_params = $this->prepare_where($input_params);

            $condition_res = $this->check_status($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->generate_auth_token($input_params);

                $input_params = $this->get_user_login_details_v1($input_params);

                $condition_res = $this->check_user_exists($input_params);
                if ($condition_res["success"])
                {

                    $condition_res = $this->check_login_status($input_params);
                    if ($condition_res["success"])
                    {

                        $input_params = $this->update_device_details_v1($input_params);

                        $condition_res = $this->is_logged_in($input_params);
                        if ($condition_res["success"])
                        {

                            $output_response = $this->users_finish_success_3($input_params);
                            return $output_response;
                        }

                        else
                        {

                            $output_response = $this->users_finish_success_4($input_params);
                            return $output_response;
                        }
                    }

                    else
                    {

                        $condition_res = $this->is_archived($input_params);
                        if ($condition_res["success"])
                        {

                            $output_response = $this->users_finish_success_1($input_params);
                            return $output_response;
                        }

                        else
                        {

                            $output_response = $this->users_finish_success_2($input_params);
                            return $output_response;
                        }
                    }
                }

                else
                {

                    $output_response = $this->users_finish_success($input_params);
                    return $output_response;
                }
            }

            else
            {

                $output_response = $this->finish_success($input_params);
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
     * prepare_where method is used to process custom function.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 31.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function prepare_where($input_params = array())
    {
        if (!method_exists($this, "helperPrepareWhere"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->helperPrepareWhere($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["prepare_where"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * check_status method is used to process conditions.
     * @created CIT Dev Team
     * @modified ---
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
     * generate_auth_token method is used to process custom function.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function generate_auth_token($input_params = array())
    {
        if (!method_exists($this->general, "generateAuthToken"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->general->generateAuthToken($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["generate_auth_token"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * get_user_login_details_v1 method is used to process query block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_user_login_details_v1($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $auth_token = isset($input_params["auth_token"]) ? $input_params["auth_token"] : "";
            $where_clause = isset($input_params["where_clause"]) ? $input_params["where_clause"] : "";
            $this->block_result = $this->users_model->get_user_login_details_v1($auth_token, $where_clause);
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

                    $data = $data_arr["u_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $dest_path = "user_profile";
                   /* $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image($image_arr);*/
                    $image_arr["path"] ="widsconnect/user_profile";
                    $data = $this->general->get_image_aws($image_arr);

                    $result_arr[$data_key]["u_profile_image"] = $data;


                    //****************************
                    $data1 = $data_arr["u_Image1"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data1;
                    $dest_path = "personal_images";
         
                    $image_arr["path"] ="widsconnect/personal_images";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image1"] = (false == empty($data1))?$data1:"";

                    //****************************
                    $data1 = $data_arr["u_Image2"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data2;
                    $dest_path = "personal_images";
                    $image_arr["path"] ="widsconnect/personal_images";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image2"] = (false == empty($data2))?$data2:"";

                    //****************************
                    $data1 = $data_arr["u_Image3"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data3;
                    $dest_path = "personal_images";
                    $image_arr["path"] ="widsconnect/personal_images";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image3"] = (false == empty($data3))?$data3:"";

                    //****************************
                    $data1 = $data_arr["u_Image4"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data4;
                    $dest_path = "personal_images";

                    $image_arr["path"] ="widsconnect/personal_images";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image4"] = (false == empty($data4))?$data4:"";


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
        $input_params["get_user_login_details_v1"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * check_user_exists method is used to process conditions.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_user_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_user_login_details_v1"]) ? 0 : 1);
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
     * check_login_status method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_login_status($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["u_status"];
            $cc_ro_0 = "Active";

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
     * update_device_details_v1 method is used to process query block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_device_details_v1($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["u_user_id"]))
            {
                $where_arr["u_user_id"] = $input_params["u_user_id"];
            }
            if (isset($input_params["device_type"]))
            {
                $params_arr["device_type"] = $input_params["device_type"];
            }
            if (isset($input_params["device_model"]))
            {
                $params_arr["device_model"] = $input_params["device_model"];
            }
            if (isset($input_params["device_os"]))
            {
                $params_arr["device_os"] = $input_params["device_os"];
            }
            if (isset($input_params["device_token"]))
            {
                $params_arr["device_token"] = $input_params["device_token"];
            }
            $params_arr["_vaccesstoken"] = "'".$input_params["auth_token"]."'";
            $this->block_result = $this->users_model->update_device_details_v1($params_arr, $where_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["update_device_details_v1"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_logged_in method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_logged_in($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["update_device_details_v1"]) ? 0 : 1);
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
     * @created CIT Dev Team
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
            'u_first_name',
            'u_last_name',
            'u_user_name',
            'u_email',
            'u_mobile_no',
            'u_profile_image',
            'u_dob',
            'u_address',
            'u_city',
            'u_latitude',
            'u_longitude',
            'u_state_id',
            'u_state_name',
            'u_zip_code',
            'u_email_verified',
            'u_device_type',
            'u_device_model',
            'u_device_os',
            'u_device_token',
            'u_status',
            'u_added_at',
            'u_updated_at',
            'auth_token1',
            'u_social_login_type',
            'u_social_login_id',
            'u_push_notify',
            //'ms_state',
            'e_one_time_transaction',
            't_one_time_transaction',
            'u_terms_conditions_version',
            'u_privacy_policy_version',
            'u_log_status_updated',
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
            'u_MarriageStatus',
            'u_Tatoos',
            'u_TravaledPlaces',
            'u_Triggers',
            'u_AboutYou',
            'u_AboutLatePerson',
            'u_Image1',
            'u_Image2',
            'u_Image3',
            'u_Image4'

        );
        $output_keys = array(
            'get_user_login_details_v1',
        );
        $ouput_aliases = array(
            "get_user_login_details_v1" => "get_user_details",
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
            "u_email_verified" => "email_verified",
            "u_device_type" => "device_type",
            "u_device_model" => "device_model",
            "u_device_os" => "device_os",
            "u_device_token" => "device_token",
            "u_status" => "status",
            "u_added_at" => "added_at",
            "u_updated_at" => "updated_at",
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
            'u_UploadDoc' => 'uploadDoc',
            'u_Drink'=>'drink',
            'u_420Friendly'=>'420friendly',
            'u_Height'=>'height',
            'u_Kids'=>'kids',
            'u_BodyType'=>'bodytype',
            'u_Gender'=>'gender',
            'u_Sign'=>'sign',
            'u_Religion'=>'religion',
            'u_SexualPrefrence'=>'sexualprefrence',
            'u_Education'=>'education',
            'u_Profession'=>'profession',
            'u_Income'=>'income',
            'u_Intrest'=>'intrest',
            'u_MarriageStatus'=>'marriagestatus',
            'u_Tatoos'=>'tatoos',
            'u_TravaledPlaces'=>'travaledplaces',
            'u_Triggers'=>'triggers',
            'u_AboutYou'=>'aboutyou',
            'u_AboutLatePerson'=>'aboutlateperson',
            'u_Image1'=> 'image1',
            'u_Image2'=>'image2',
            'u_Image3'=> 'image3',
            'u_Image4'=>'image4',
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_login_phone";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success_4 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_4($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success_4",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_login_phone";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * is_archived method is used to process conditions.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_archived($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["u_status"];
            $cc_ro_0 = "Archived";

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
     * users_finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_login_phone";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success_2 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 06.02.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_2($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success_2",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_login_phone";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 06.02.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "2",
            "message" => "users_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_login_phone";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_login_phone";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}