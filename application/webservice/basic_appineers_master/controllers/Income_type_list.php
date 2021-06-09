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
 * @path application\webservice\basic_appineers_master\controllers\income_type_list.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Income_type_list extends Cit_Controller
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
            "get_income_type_list",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('income_type_list_model');
        //Model wali file
    }

    /**
     * rules_states_list method is used to validate api input params.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_income_type_list($request_arr = array())
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
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "religion_list");

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
    public function start_income_type_list($request_arr = array(), $inner_api = FALSE)
    {
       //print_r($request_arr);
        $method = $_SERVER['REQUEST_METHOD']; ///cit file
        $output_response = array();

        switch ($method) {
        case 'GET':
           $output_response =  $this->get_income_type($request_arr);
           return  $output_response;
           break;
        /*case 'PUT':
           $output_response =  $this->update_religion($request_arr);
           return  $output_response;
           break;*/
        }
    }
     public function get_income_type($request_arr)
    {
        //print_r($request_arr);//"income_type_id_required
         try
        {
            $validation_res = $this->rules_income_type_list($request_arr);
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

            $input_params = $this->get_income_type_list_v1($input_params);

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

    /**
     * get_business_type_list_v1 method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_income_type_list_v1($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $this->block_result = $this->income_type_list_model->income_type_list();
             if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
           
           // print_r($result_arr);
           
            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
            
                $this->block_result["data"] = $result_arr;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_income_type_type_list_v1"] = $this->block_result["data"];

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

            $cc_lo_0 = (empty($input_params["get_income_type_type_list_v1"]) ? 0 : 1);
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
            "message" => "Income types fetched successfully.",
        );
        $output_fields = array(
            'income_type_id',
            'income_type_name',
        );

        $output_keys = array(
            'get_income_type_type_list_v1',
        );


        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_income_type_type_list_v1";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    public function mod_state_finish_success_1($input_params = array())
    {
       /* print_r($input_params); exit;*/
        $setting_fields = array(
            "success" => "0",
            "message" => "Income types are not available.",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        //$func_array["function"]["name"] = "get_income_type_type_list_v1";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}