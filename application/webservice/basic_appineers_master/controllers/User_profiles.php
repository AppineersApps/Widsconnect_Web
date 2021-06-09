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
 * @class user_profiles.php
 *
 * @path application\webservice\basic_appineers_master\controllers\user_profiles.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class User_profiles extends Cit_Controller
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
            "get_user_details",
            "update_user_lat_lon",
        );
        $this->multiple_keys = array(
            "prepare_where",
            "get_filtered_profiles",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        //$this->load->model('user_profiles_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_user_profiles method is used to validate api input params.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_user_profiles($request_arr = array())
    {
        $valid_arr = array(
           "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            ),
           "latitude" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "latitude_required", 
                )
            ),
           "longitude" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "longitude_required",
                )
            ),
           "app_section" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "app_section_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "user_profiles");

        return $valid_res;
    }

    /**
     * start_user_profiles method is used to initiate api execution flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_user_profiles($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_user_profiles($request_arr);


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
     

                $condition_res = $this->check_user_exists($input_params);
                if ($condition_res["success"])
                {
                 
                    $input_params = $this->update_user_lat_lon($input_params);

                        $input_params = $this->prepare_where($input_params);

                        $input_params = $this->get_filtered_profiles($input_params);

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

    public function get_filtered_profiles($input_params = array())
    {
         $this->block_result = array();
        try
        {
             //print_r($input_params['where_clause']);

            $this->block_result = $this->users_model->get_filtered_profiles($input_params);
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
                    
                    $age = "";
                    $currYear = date("Y");
                    $dobYear = $data_arr["dob_year"];

                    if( $dobYear > 0)
                    {
                        $age = $currYear - $dobYear;
                    }
                    

                    $result_arr[$data_key]["age"] = $age;                    

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

        $input_params["get_filtered_profiles"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    
    }
    public function update_user_lat_lon($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["user_id"]))
            {
                $where_arr["user_id"] = $input_params["user_id"];
            }
            
            if (isset($input_params["latitude"]))
            {
                $params_arr["latitude"] = $input_params["latitude"];
            }
            if (isset($input_params["longitude"]))
            {
                $params_arr["longitude"] = $input_params["longitude"];
            }
            

            $this->block_result = $this->users_model->update_profile($params_arr, $where_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
            }
            $data_arr = $this->block_result["array"];
           

        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["update_user_lat_lon"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * prepare_where method is used to process custom function.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
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
     * get_user_details method is used to get user details.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
     public function get_user_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";

            $this->block_result = $this->users_model->get_user_details($user_id);
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
                    $image_arr["path"] = "widsconnect/user_profile";
                    //$image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);
                    //print_r($data); exit;
                    $result_arr[$data_key]["u_profile_image"] = (false == empty($data)) ? $data : "";

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
        $input_params["get_user_details"] = $this->block_result["data"];
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

            $cc_lo_0 = (empty($input_params["get_user_details"]) ? 0 : 1);
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
            'u_status',
            'u_log_status_updated',
            'u_gender',
            'u_sexual_prefrence',
            'age',
            'u_app_section',
            'u_images',
            'connection_type_by_receiver_user',
            'miles',
           /* "u_Image1",
            "u_Image2",
            "u_Image3",
            "u_Image4",
            "u_Image5",*/
           
        );
        $output_keys = array(
            'get_filtered_profiles',
        );
        $ouput_aliases = array(
            "get_filtered_profiles" => "get_filtered_profiles",
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
            "u_status" => "status",
           
            "u_log_status_updated" => "log_status_updated",
            "u_gender" => "gender",
            "u_sexual_prefrence" => "sexual_preference",
            "age" => "age",
            "u_app_section" => "app_section",
            "u_images"=>"user_images",
            "connection_type_by_receiver_user"=>"connection_type_by_receiver_user",
            "miles"=>"miles",
           /* "u_Image1"=>"image1",
            "u_Image2"=>"image2",
            "u_Image3"=>"image3",
            "u_Image4"=>"image4",
            "u_Image5"=>"image5",*/
          
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

    /**
     * finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
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

        $func_array["function"]["name"] = "user_profiles";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
