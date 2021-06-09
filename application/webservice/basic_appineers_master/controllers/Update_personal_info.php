<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Edit Profile Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Edit Profile
 *
 * @class update_personal_info.php
 *
 * @path application\webservice\basic_appineers_master\controllers\update_personal_info.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 23.12.2019
 */

class Update_personal_info extends Cit_Controller
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
            "update_profile",
            "get_updated_details",
        );
        $this->multiple_keys = array(
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        //$this->load->model('update_personal_info_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_update_personal_info method is used to validate api input params.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_update_personal_info($request_arr = array())
    {
        $valid_arr = array(
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            )
          
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "edit_profile");

        return $valid_res;
    }

    /**
     * start_update_personal_info method is used to initiate api execution flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_update_personal_info($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_update_personal_info($request_arr);
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


                $input_params = $this->update_profile($input_params);

                $condition_res = $this->is_details_updated($input_params);
                if ($condition_res["success"])
                {

                    $input_params = $this->get_updated_details($input_params);

                    $condition_res = $this->check_user_exists($input_params);
                    if ($condition_res["success"])
                    {

                        $output_response = $this->users_finish_success_2($input_params);
                        return $output_response;
                    }

                    else
                    {

                        $output_response = $this->users_finish_success_1($input_params);
                        return $output_response;
                    }
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
     * update_profile method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_profile($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["user_id"]))
            {
                $where_arr["user_id"] = $input_params["user_id"];
            }
           
             //*******upload doc *******************
             if (isset($_FILES["upload_doc"]["name"]) && isset($_FILES["upload_doc"]["tmp_name"]))
            {
                $sent_file2 = $_FILES["upload_doc"]["name"];
            }
            else
            {
                $sent_file2 = "";
            }

            //echo "file checking---". $sent_file2;

            if (!empty($sent_file2))
            {
                
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file2);
                $images_arr["upload_doc"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["upload_doc"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["upload_doc"]["ext"], $_FILES["upload_doc"]["name"]))
                {

                    if ($this->general->validateFileSize($images_arr["upload_doc"]["size"], $_FILES["upload_doc"]["size"]))
                    {
                        $images_arr["upload_doc"]["name"] = $file_name;
                    }
                   
                }
            }

            
            //*******upload doc *******************

            $path="widsconnect/personal_images";
                $size="102400";
                

                if (!empty($_FILES["image1"]["name"]) && !empty($_FILES["image1"]["tmp_name"]))
                {

                    list($file_name, $ext) = $this->general->get_file_attributes($_FILES["image1"]["name"]);
                    $images_arr["image1"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                    $images_arr["image1"]["size"] = $size;
                    if ($this->general->validateFileFormat($images_arr["image1"]["ext"], $_FILES["image1"]["name"]))
                    {
                        if ($this->general->validateFileSize($images_arr["image1"]["size"], $_FILES["image1"]["size"]))
                        {

                            $folder_name =$path;  
                            $images_arr["image1"]["name"] = $file_name;           
                             $temp_file = $_FILES["image1"]["tmp_name"];
                            $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image1"]["name"]);
                            if (!$res)
                                {
                                    

                                }
                        }
                    }
                }


                if (!empty($_FILES["image2"]["name"]) && !empty($_FILES["image2"]["tmp_name"]))
                {

                    list($file_name, $ext) = $this->general->get_file_attributes($_FILES["image2"]["name"]);
                    $images_arr["image2"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                    $images_arr["image2"]["size"] = $size;
                    if ($this->general->validateFileFormat($images_arr["image2"]["ext"], $_FILES["image2"]["name"]))
                    {
                        if ($this->general->validateFileSize($images_arr["image2"]["size"], $_FILES["image2"]["size"]))
                        {

                            $folder_name =$path;  
                            $images_arr["image2"]["name"] = $file_name;           
                             $temp_file = $_FILES["image2"]["tmp_name"];
                            $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image2"]["name"]);
                            if (!$res)
                                {
                                    

                                }
                        }
                    }
                }

                if (!empty($_FILES["image3"]["name"]) && !empty($_FILES["image3"]["tmp_name"]))
                {

                    list($file_name, $ext) = $this->general->get_file_attributes($_FILES["image3"]["name"]);
                    $images_arr["image3"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                    $images_arr["image3"]["size"] =$size;
                    if ($this->general->validateFileFormat($images_arr["image3"]["ext"], $_FILES["image3"]["name"]))
                    {
                        if ($this->general->validateFileSize($images_arr["image3"]["size"], $_FILES["image3"]["size"]))
                        {

                            $folder_name =$path;  
                            $images_arr["image3"]["name"] = $file_name;           
                             $temp_file = $_FILES["image3"]["tmp_name"];
                            $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image3"]["name"]);
                            if (!$res)
                                {
                                    

                                }
                        }
                    }
                }

                  if (!empty($_FILES["image4"]["name"]) && !empty($_FILES["image4"]["tmp_name"]))
                {

                    list($file_name, $ext) = $this->general->get_file_attributes($_FILES["image4"]["name"]);
                    $images_arr["image4"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                    $images_arr["image4"]["size"] = $size;
                    if ($this->general->validateFileFormat($images_arr["image4"]["ext"], $_FILES["image4"]["name"]))
                    {
                        if ($this->general->validateFileSize($images_arr["image4"]["size"], $_FILES["image4"]["size"]))
                        {

                            $folder_name =$path;  
                            $images_arr["image4"]["name"] = $file_name;           
                             $temp_file = $_FILES["image4"]["tmp_name"];
                            $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image4"]["name"]);
                            if (!$res)
                                {
                                    

                                }
                        }
                    }
                }

                 if (!empty($_FILES["image5"]["name"]) && !empty($_FILES["image5"]["tmp_name"]))
                {

                    list($file_name, $ext) = $this->general->get_file_attributes($_FILES["image5"]["name"]);
                    $images_arr["image5"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                    $images_arr["image5"]["size"] = $size;
                    if ($this->general->validateFileFormat($images_arr["image5"]["ext"], $_FILES["image5"]["name"]))
                    {
                        if ($this->general->validateFileSize($images_arr["image5"]["size"], $_FILES["image5"]["size"]))
                        {

                            $folder_name =$path;  
                            $images_arr["image5"]["name"] = $file_name;           
                             $temp_file = $_FILES["image5"]["tmp_name"];
                            $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image5"]["name"]);
                            if (!$res)
                                {
                                    

                                }
                        }
                    }
                }

            

             if (isset($images_arr["upload_doc"]["name"]))
            {
                $params_arr["upload_doc"] = $images_arr["upload_doc"]["name"];
            }

             if (isset($images_arr["image1"]["name"]))
            {
                $params_arr["image1"] = $images_arr["image1"]["name"];
            }
            if (isset($images_arr["image2"]["name"]))
            {
                $params_arr["image2"] = $images_arr["image2"]["name"];
            }
            if (isset($images_arr["image3"]["name"]))
            {
                $params_arr["image3"] = $images_arr["image3"]["name"];
            }

             if (isset($images_arr["image4"]["name"]))
            {
                $params_arr["image4"] = $images_arr["image4"]["name"];
            }

            if (isset($images_arr["image5"]["name"]))
            {
                $params_arr["image5"] = $images_arr["image5"]["name"];
            }

            
            $params_arr["_dtupdatedat"] = "NOW()";
           

            if (isset($input_params["drink"]))
            {
                $params_arr["drink"] = $input_params["drink"];
            }
            if (isset($input_params["smoke"]))
            {
                $params_arr["smoke"] = $input_params["smoke"];
            } 
            if (isset($input_params["420friendly"]))
            {
                $params_arr["420friendly"] = $input_params["420friendly"];
            }
            if (isset($input_params["kids"]))
            {
                $params_arr["kids"] = $input_params["kids"];
            }
            if (isset($input_params["height"]))
            {
                $params_arr["height"] = $input_params["height"];
            }
            if (isset($input_params["body_type"]))
            {
                $params_arr["bodytype"] = $input_params["body_type"];
            }
            if (isset($input_params["sign"]))
            {
                $params_arr["sign"] = $input_params["sign"];
            }
             if (isset($input_params["gender"]))
            {
                $params_arr["gender"] = $input_params["gender"];
            }
            if (isset($input_params["religion"]))
            {
                $params_arr["religion"] = $input_params["religion"];
            }
             if (isset($input_params["sexual_preference"]))
            {
                $params_arr["sexual_prefrence"] = $input_params["sexual_preference"];
            }
            if (isset($input_params["education"]))
            {
                $params_arr["education"] = $input_params["education"];
            }
            if (isset($input_params["profession"]))
            {
                $params_arr["profession"] = $input_params["profession"];
            }
            if (isset($input_params["income"]))
            {
                $params_arr["income"] = $input_params["income"];
            }
            if (isset($input_params["interest"])) //!empty($input_params["interest"]) &&
            {
                $params_arr["intrest"] = $input_params["interest"];
            }

            if (isset($input_params["marriage_status"]))
            {
                $params_arr["marriage_status"] = $input_params["marriage_status"];
            }

            if (isset($input_params["tattoos"]))
            {
                $params_arr["tatoos"] = $input_params["tattoos"];
            }

            if (isset($input_params["traveled_places"]))
            {
                $params_arr["travaled_places"] = $input_params["traveled_places"];
            }

             if (isset($input_params["places_want_to_travel"]))
            {
                $params_arr["places_want_to_travel"] = $input_params["places_want_to_travel"];
            }

            if (isset($input_params["triggers"]))
            {
                $params_arr["triggers"] = $input_params["triggers"];
            }
            if (isset($input_params["about_you"]))
            {
                $params_arr["about_you"] = $input_params["about_you"];
            }
            if (isset($input_params["about_late_person"]))
            {
                $params_arr["about_late_person_passes"] = $input_params["about_late_person"];
            }
            

            $this->block_result = $this->users_model->update_profile($params_arr, $where_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
            }
            $data_arr = $this->block_result["array"];
            $upload_path = $this->config->item("upload_path");
           
            
            //***** doc upload *********
             if (!empty($images_arr["upload_doc"]["name"]))
            {

                $folder_name = "widsconnect/upload_doc";             
                
                $temp_file = $_FILES["upload_doc"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["upload_doc"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed
                   // echo "file uploaderror msg--";
                }
            }
            //***** doc upload *********
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["update_profile"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_details_updated method is used to process conditions.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_details_updated($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["affected_rows"];
            $cc_ro_0 = 0;

            $cc_fr_0 = ($cc_lo_0 > $cc_ro_0) ? TRUE : FALSE;
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
     * get_updated_details method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_updated_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            $this->block_result = $this->users_model->get_updated_details($user_id);
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

                     $data = $data_arr["u_UploadDoc"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "widsconnect/upload_doc";
                    //$image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);
                    //print_r($data); exit;
                    $result_arr[$data_key]["u_UploadDoc"] = (false == empty($data)) ? $data : "";
                    

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

                   /* $data1 = $data_arr["u_Image1"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data1;
                     $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                        $image_arr["color"] = "FFFFFF";
                        $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="widsconnect/personal_images";
                    $data1 = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["u_Image1"] = (false == empty($data1))?$data1:"";



                     $data = $data_arr["u_Image2"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "widsconnect/personal_images";
                    $data = $this->general->get_image_aws($image_arr);
                    $result_arr[$data_key]["u_Image2"] = $data;


                     $data = $data_arr["u_Image3"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "widsconnect/personal_images";
                    $data = $this->general->get_image_aws($image_arr);
                    $result_arr[$data_key]["u_Image3"] = $data;

                     $data = $data_arr["u_Image4"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "widsconnect/personal_images";
                    $data = $this->general->get_image_aws($image_arr);
                    $result_arr[$data_key]["u_Image4"] = $data; 

                    $data = $data_arr["u_Image5"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "widsconnect/personal_images";
                    $data = $this->general->get_image_aws($image_arr);
                    $result_arr[$data_key]["u_Image5"] = $data;
                */
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
        $input_params["get_updated_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * check_user_exists method is used to process conditions.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_user_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_updated_details"]) ? 0 : 1);
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
     * users_finish_success_2 method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_2($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "users_finish_success_2",
        );
        $output_fields = array(
            'u_user_id',
            'u_email',
            'u_updated_at',
            "u_Smoke",
            "u_UploadDoc",
            "u_Drink",
            "u_420Friendly",
            "u_Height",
            "u_Kids",
            "u_BodyType",
            "u_Gender",
            "u_Sign",
            "u_Religion",
            "u_SexualPrefrence",
            "u_Education",
            "u_Profession",
            "u_Income",
            "u_images",
            "u_Intrest",
            "u_MarriageStatus",
            "u_Tatoos",
            "u_TravaledPlaces",
            "u_tTravalToPlaces",
            "u_Triggers",
            "u_AboutYou",
            "u_AboutLatePerson",
        );
        $output_keys = array(
            'get_updated_details',
        );
        $ouput_aliases = array(
            "get_updated_details" => "get_user_details",
            "u_user_id" => "user_id",
            "u_email" => "email",
            "u_updated_at" => "updated_at",
            "u_Smoke" => "smoke",
            "u_UploadDoc" => "upload_doc",
            "u_Drink" =>"drink",
            "u_420Friendly"=> "420friendly",
            "u_Height"=>"height",
            "u_Kids"=> "kids",
            "u_BodyType"=>"body_type",
            "u_Gender"=>"gender",
            "u_Sign"=> "sign",
            "u_Religion"=>"religion",
            "u_SexualPrefrence"=>"sexual_preference",
            "u_Education"=>"education",
            "u_Profession"=>"profession",
            "u_Income"=>"income",
            "u_images" => "user_images",
            "u_Intrest"=>"interest",
            "u_MarriageStatus"=>"marriage_status",
            "u_Tatoos"=>"tattoos",
            "u_TravaledPlaces"=>"traveled_places",
            "u_tTravalToPlaces"=>"places_want_to_travel",
            "u_Triggers"=>"triggers",
            "u_AboutYou"=>"about_you",
            "u_AboutLatePerson"=>"about_late_person",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "edit_profile";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
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

        $func_array["function"]["name"] = "edit_profile";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
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

        $func_array["function"]["name"] = "edit_profile";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
    
    /**
     * users_finish_success_3 method is used to process finish flow.
     * @created priyanka chillakuru | 25.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_3($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success_3",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "edit_profile";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
