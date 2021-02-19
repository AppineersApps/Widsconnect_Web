<?php

   
/**
 * Description of Edit Profile Extended Controller
 * 
 * @module Extended Edit Profile
 * 
 * @class Cit_Edit_profile.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Edit_profile.php
 * 
 * @author CIT Dev Team
 * 
 * @date 25.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Update_personal_info extends Update_personal_info {
    
    public function __construct()
    {
        parent::__construct();
    }

}
