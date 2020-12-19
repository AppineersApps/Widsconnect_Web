<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of States List Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module States List
 *
 * @class States_list.php
 *
 * @path application\webservice\basic_appineers_master\controllers\States_list.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Interest_list extends Cit_Controller
{
    public $settings_params;
    public $output_params;
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
            "get_interest_list",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('interest_type_list_model');
        //Model wali file
    }

    /**
     * rules_states_list method is used to validate api input params.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_interest_list($request_arr = array())
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
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "interest_list");

        return $valid_res;
    }
    public function rules_update_interest($request_arr = array())
    {
        
         $valid_arr = array(            
            "interest_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "interest_id_required",
                )
            ),
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                 )
                
            )
        );
        
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "update_interest");

        return $valid_res;
    }

    /**
     * start_states_list method is used to initiate api execution flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_interest_list($request_arr = array(), $inner_api = FALSE)
    {
       //print_r($request_arr);
        $method = $_SERVER['REQUEST_METHOD']; ///cit file
        $output_response = array();

        switch ($method) {
        case 'GET':
           $output_response =  $this->get_interest($request_arr);
           return  $output_response;
           break;
        case 'PUT':
           $output_response =  $this->update_interest($request_arr);
           return  $output_response;
           break;
        }
    }
     public function get_interest($request_arr)
    {
        //print_r($request_arr);//"interest_id_required
         try
        {
            $validation_res = $this->rules_interest_list($request_arr);
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

            // print_r($input_params);exit;
            
            $input_params = $this->get_interest_list_v1($input_params);

            $condition_res = $this->condition($input_params);
            if ($condition_res["success"])
            {
                $output_response = $this->mod_state_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->mod_state_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;

    }

    public function update_interest($request_arr)
    {
        //print_r($request_arr);
         try
        {
            $validation_res = $this->rules_update_interest($request_arr);
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

            // print_r($input_params);exit;
            $input_params = $this->check_exists_interest($input_params);
            $input_params = $this->set_interest($input_params);

            $condition_res = $this->is_set($input_params);
            if ($condition_res["success"])
            {
                $output_response = $this->set_interest_finish_success($input_params);
                return $output_response;
            }

            else
            {
                $output_response = $this->set_interest_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;

    }
     //Set Interest

    public function set_interest($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $params_arr = array(); 
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }           
            
            if (isset($input_params["interest_id"]))
            {
                $params_arr["interest_id"] = $input_params["interest_id"];
            }
             $params_arr["_addedat"] = "NOW()";
            $this->block_result = $this->interest_type_list_model->set_interest($params_arr);

            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            $data_arr = $this->block_result["data"];
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["update_interest"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
        return $input_params;
    }

    /**
     * get_business_type_list_v1 method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_interest_list_v1($input_params = array())
    {
       // print_r($input_param); // no states found

        $this->block_result = array();
        try
        {
            $this->block_result = $this->interest_type_list_model->interest_type_list();
             if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
           
           // print_r($result_arr);
           
            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {

                    /*$data = $data_arr["interest_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $dest_path = "interests_image";//mad_collab wala folder path
                    $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["interest_image"] = $data;*/

                    $data = $data_arr["interest_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "mad_collab/interests_image";
                    $p_key = ($data_arr["interest_id"] != "") ? $data_arr["interest_id"] : $input_params["interest_id"];
                    $image_arr["pk"] = $p_key;
                   // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);

                    $result_arr[$data_key]["interest_image"] = $data;

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
        $input_params["get_interest_type_list_v1"] = $this->block_result["data"];

        return $input_params;
    }
    /**
     * checkuniqueusername method is used to process custom function.
     * @created priyanka chillakuru | 25.09.2019
     * @modified saikumar anantham | 08.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function check_exists_interest($input_params = array())
    {

        if (!method_exists($this, "checkExistsInterest"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkExistsInterest($input_params);
        }
        $format_arr = $result_arr;
        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkexistsinterest"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * condition method is used to process conditions.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function condition($input_params = array())
    {
        //print_r($input_params); // no state

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_interest_type_list_v1"]) ? 0 : 1);
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
     * condition method is used to process conditions.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_set($input_params = array())
    {
        //print_r($input_params); // no state

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["update_interest"]) ? 0 : 1);
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
     * mod_state_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function mod_state_finish_success($input_params = array())
    {
       /* print_r($input_params); exit;*/
        $setting_fields = array(
            "success" => "1",
            "message" => "Get interest type list fetched successfully.",
        );
        $output_fields = array(
            'interest_id',
            'interest_name',
            'interest_image',
        );

        $output_keys = array(
            'get_interest_type_list_v1',
        );
        $ouput_aliases = array(
            "get_interest_type_list_v1" => "get_interest_type_list",
            "iInterestsId" => "interest_id",
            "vInterestsName" => "interest_name",
            "vInterestsImage" => "interest_image",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_interest_type_list_v1";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * mod_state_finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function mod_state_finish_success_1($input_params = array())
    {
        //print_r($input_params);exit;
        $setting_fields = array(
            "success" => "0",
            "message" => "mod_state_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_interest_type_list_v1";//Function name
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
    /**
     * set_interest_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function set_interest_finish_success($input_params = array())
    {
        //print_r($input_params);exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "Set user interests",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "set_interest";//Function name
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
     /**
     * set_interest_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function set_interest_finish_success_1($input_params = array())
    {
        //print_r($input_params);exit;
        $setting_fields = array(
            "success" => "0",
            "message" => "Not set interests",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "set_interest";//Function name
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

   
}
