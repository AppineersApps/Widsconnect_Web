<?php

   
/**
 * Description of User profiles Controller
 * 
 * @module Extended User Login Email
 * 
 * @class Cit_User_profiles.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_User_profiles.php
 * 
 * @author CIT Dev Team
 * 
 * @date 13.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_User_profiles extends User_profiles {
    public function __construct()
    {
        parent::__construct();
    }
public function helperPrepareWhere(&$input_params=array())
    {
        $return = array();
    	$return[0]['status']=1;
    	$return[0]['message']="";
    	$return[0]['where_clause']='0=1';
        $where = array();

    	if($input_params['user_id']!='')
        {
            $input_params['radius'] = isset($input_params["radius"]) ? $input_params["radius"]:"100";

            if(isset($input_params['radius']) && $input_params['radius'] != ''){
               $input_params = $this->prepare_distance($input_params);
            }

            $input_params['distance'] = isset($input_params["distance"]) ? $input_params["distance"] : "";

        	    $this->db->select('iUserId,vEmail,app_section');
        	    $this->db->from('users');
        	    $this->db->where('iUserId', $input_params['user_id']);
                $this->db->where('eStatus', "Active");
        	    $data=$this->db->get()->result_array();

                if(count($data) > 0){

                   // print_r($data);

                    if (isset($input_params['radius']) && $input_params['radius'] != "")
                    {
                        $where[]= "FLOOR(".$input_params['distance'].") <='".$input_params['radius']."' ";
                    }


                    if ((isset($input_params['min_age']) && $input_params['min_age'] != "") && (isset($input_params['max_age']) && $input_params['max_age'] != ""))
                    {

                          $min_age = $input_params['min_age'];
                          $minlastYear = date("Y", strtotime("-$min_age years"));

                          $max_age = $input_params['max_age'];
                          $maxlastYear = date("Y", strtotime("-$max_age years"));
                          // echo "last yr--".$lastYear;

                         // $where[]="YEAR(dDob) <='".$lastYear."'";

                    $this->db->where('YEAR(dDob) BETWEEN "'. $maxlastYear. '" and "'. $minlastYear.'"');
                    }


                    if(isset($input_params['gender']) && $input_params['gender'] != ""){

                        $where[]="eGender='".$input_params['gender']."'";
                    }

                   // if(isset($input_params['app_section']) && $input_params['app_section'] != ""){

                        $where[]="app_section='".$data[0]['app_section']."'";
                    //}

                }
        

    	}else{
    		$return[0]['status']=0;
    		$return[0]['message']="Please provide valid detail.";
    	}

    	$return[0]['where_clause']=implode(" AND ",$where);
        return $return;
        
    }

      public function prepare_distance($input_params = array())
    {
        if (!method_exists($this, "prepareDistanceQuery"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->prepareDistanceQuery($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["prepare_distance"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    public function prepareDistanceQuery($input_params=array()){
 
      $user_latitude    =   $input_params['latitude'];
      $user_longitude   =   $input_params['longitude'];
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
            
          }else{
               //distance filter
            $distance= 'IF(1=1,"","")'; 
          }
          
          $return_arr['distance']=$distance;
        
          return $return_arr;
    }

}
