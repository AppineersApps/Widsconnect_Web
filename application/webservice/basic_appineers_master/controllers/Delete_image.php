<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of delete_image List Controller
 *
 * @category webservice
 *
 * @package delete_images
 *
 * @subpackage controllers
 *
 * @module delete_image List
 *
 * @class delete_image_list.php
 *
 * @path application\webservice\delete_images\controllers\delete_image_list.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 27.06.2019
 */

class Delete_image extends Cit_Controller
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

        $this->multiple_keys = array(
            "get_delete_image_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_delete_image_list method is used to validate api input params.
     * @created priyanka chillakuru | 04.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_delete_image_list($request_arr = array())
    {
        $valid_arr = array(
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            ),

             "image_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "image_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "delete_image");

        return $valid_res;
    }
    /**
     * start_set_store_item method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_delete_image($request_arr = array(), $inner_api = FALSE)
    {

    // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();
        switch ($method) {

         case 'POST':
            //print_r($request_arr);exit;
            $output_response =  $this->delete_user_image($request_arr);

            return  $output_response;
            break;
        }
    }


/**
     * delete_delete_image method is used to initiate api execution flow.
     * @created aditi billore | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function delete_user_image($request_arr = array())
    {
      try
        {

          $validation_res = $this->rules_delete_image_list($request_arr);
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

	     if(isset($input_params["user_id"])){

 	         
         
		          $input_params = $this->get_user_media_image($input_params);

                  $input_params = $this->delete_user_media_image($input_params);

                 // print_r($input_params);

                   if ($input_params["affected_rows"])
                    {
                        $output_response = $this->delete_image_finish_success($input_params);
                        return $output_response;
                    }else{


                        $output_response = $this->delete_image_finish_success_1($input_params);
                        return $output_response;
                    }
	
            }
      }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

    public function get_user_media_image($input_params = array())
    {

        $this->block_result = array();
        try
        {

            
            $where_arr['user_id']  = isset($input_params["user_id"]) ? $input_params["user_id"] : ""; 

            if(isset($input_params["image_id"]) && $input_params["image_id"] > 0)
            {
                $where_arr['image_id']  = $input_params["image_id"];
            

                $this->block_result = $this->users_model->get_user_personal_images($where_arr);
                if (!$this->block_result["success"])
                {
                    throw new Exception("No records found.");
                }
                $result_arr = $this->block_result["data"];

                    $data1 = $result_arr["image_url"];
                    $this->block_result["data"] = $data1;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["image_name"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

     /**
     * delete review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
   

    public function delete_user_media_image($input_params = array())
    {
      $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $where_arr['user_id']  = isset($input_params["user_id"]) ? $input_params["user_id"] : ""; 

            if(isset($input_params["image_id"]))
            {
                $arrResult['image_id']  = $input_params["image_id"];
            }

            $this->block_result = $this->users_model->delete_user_media_image($where_arr,$arrResult);

            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }

            if(isset($input_params["image_name"]))
            {
                $image_name  = $input_params["image_name"];

                 $data11 = $this->general->deleteAWSFileData($folder_name = "widsconnect/personal_images", $image_name);
            }

            $result_arr = $this->block_result["data"];
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["delete_images"] = $result_arr;
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       
       return $input_params;

    }


    /**
     * delete_delete_image_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function delete_image_finish_success_1($input_params = array())
    {
     $setting_fields = array(
            "success" => "0",
            "message" => "delete_image_finish_success_1",

        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_image";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }


    /**
     * delete_delete_image_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function delete_image_finish_success($input_params = array())
    {
     $setting_fields = array(
            "success" => "1",
            "message" => "delete_image_finish_success",

        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_image";
        $func_array["function"]["single_keys"] = $this->single_keys;


        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

}
