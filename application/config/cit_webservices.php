<?php

defined('BASEPATH') OR exit('No direct script access allowed');

#####GENERATED_CONFIG_SETTINGS_START#####

$config["admin_update_user_status_in_listing"] = array(
    "title" => "Admin Update User status In Listing",
    "folder" => "admin",
    "method" => "GET_POST",
    "params" => array(
        "user_id"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["change_mobile_number"] = array(
    "title" => "Change Mobile Number",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "new_mobile_number",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["change_password"] = array(
    "title" => "Change Password",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "old_password",
        "new_password",
        "user_id",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["check_unique_user"] = array(
    "title" => "Check Unique User",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "type",
        "email",
        "mobile_number",
        "user_name"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["country_list"] = array(
    "title" => "Country List",
    "folder" => "tools",
    "method" => "GET_POST",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["country_with_states"] = array(
    "title" => "Country With States",
    "folder" => "tools",
    "method" => "GET_POST",
    "params" => array(
        "country_id"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["delete_account"] = array(
    "title" => "Delete Account",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_access_token",
        "user_id"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["edit_profile"] = array(
    "title" => "Edit Profile",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "first_name",
        "last_name",
        "user_profile",
        "dob",
        "address",
        "city",
        "latitude",
        "longitude",
        "state_id",
        "zipcode",
        "user_name",
        "mobile_number",
        "drink",
        "smoke",
        "420friendly",
        "kids",
        "height",
        "bodytype",
        "sign",
        "gender",
        "religion",
        "sexual_prefrence",
        "education",
        "profession",
        "income",
        "image1",
        "image2",
        "image3",
        "image4",
        "intrest",
        "marriage_status",
        "tatoos",
        "travaled_places",
        "triggers",
        "about_you",
        "about_late_person",
        "upload_doc"

    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["update_personal_info"] = array(
    "title" => "Update personal information",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "drink",
        "smoke",
        "420friendly",
        "kids",
        "height",
        "body_type",
        "sign",
        "gender",
        "religion",
        "sexual_preference",
        "education",
        "profession",
        "income",
        "image1",
        "image2",
        "image3",
        "image4",
        "image5",
        "interest",
        "marriage_status",
        "tattoos",
        "traveled_places",
        "places_want_to_travel",
        "triggers",
        "about_you",
        "about_late_person_passes",
        "upload_doc"

    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["forgot_password"] = array(
    "title" => "Forgot Password",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "email"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["forgot_password_phone"] = array(
    "title" => "Forgot Password Phone",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "mobile_number"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["get_config_paramaters"] = array(
    "title" => "Get Config Paramaters",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["get_template_message"] = array(
    "title" => "Get Template Message",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "template_code",
        "user_name",
        "otp"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["go_ad_free"] = array(
    "title" => "Go Ad Free",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "one_time_transaction_data"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["logout"] = array(
    "title" => "Logout",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["post_a_feedback"] = array(
    "title" => "Post a Feedback",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "feedback",
        "device_type",
        "device_model",
        "device_os",
        "image_1",
        "image_2",
        "image_3",
        "images_count",
        "app_version",
        "user_id",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["reset_password"] = array(
    "title" => "Reset Password",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "new_password",
        "reset_key"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["reset_password_confirmation"] = array(
    "title" => "Reset Password Confirmation",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "reset_key"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["reset_password_phone"] = array(
    "title" => "Reset Password Phone",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "new_password",
        "mobile_number",
        "reset_key"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["send_sms"] = array(
    "title" => "Send Sms",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "mobile_number",
        "message"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["send_verification_link"] = array(
    "title" => "Send Verification Link",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "email"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["social_login"] = array(
    "title" => "Social Login",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "social_login_type",
        "social_login_id",
        "device_type",
        "device_model",
        "device_os",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["social_sign_up"] = array(
    "title" => "Social Sign Up",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "longitude",
        "state_id",
        "state_name",
        "zipcode",
        "device_type",
        "device_model",
        "device_os",
        "device_token",
        "social_login_type",
        "social_login_id",
        "first_name",
        "last_name",
        "user_name",
        "email",
        "mobile_number",
        "user_profile",
        "dob",
        "address",
        "city",
        "latitude"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["states_list"] = array(
    "title" => "States List",
    "folder" => "basic_appineers_master",
    "method" => "GET",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["static_pages"] = array(
    "title" => "Static Pages",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "page_code"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["update_device_token"] = array(
    "title" => "Update Device Token",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["update_page_version"] = array(
    "title" => "Update Page Version",
    "folder" => "tools",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "page_type"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["update_push_notification_settings"] = array(
    "title" => "Update Push Notification Settings",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "notification"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["user_email_confirmation"] = array(
    "title" => "User Email Confirmation",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "confirmation_code"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["user_login_email"] = array(
    "title" => "User Login Email",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "email",
        "password",
        "device_type",
        "device_model",
        "device_os",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["user_login_phone"] = array(
    "title" => "User Login Phone",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "mobile_number",
        "password",
        "device_type",
        "device_model",
        "device_os",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["user_sign_up_email"] = array(
    "title" => "User Sign Up Email",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "device_type",
        "device_model",
        "device_os",
        "device_token",
        "first_name",
        "last_name",
        "user_name",
        "email",
        "mobile_number",
        "user_profile",
        "dob",
        "password",
        "address",
        "city",
        "latitude",
        "longitude",
        "state_id",
        "state_name",
        "zipcode",
        "app_section",
        "profession"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["user_sign_up_phone"] = array(
    "title" => "User Sign Up Phone",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "first_name",
        "last_name",
        "user_name",
        "email",
        "mobile_number",
        "user_profile",
        "dob",
        "password",
        "address",
        "city",
        "latitude",
        "longitude",
        "state_id",
        "state_name",
        "zipcode",
        "device_type",
        "device_model",
        "device_os",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["delete_api_log"] = array(
    "title" => "delete_api_log",
    "folder" => "misc",
    "method" => "GET_POST",
    "params" => array(
    )
);

$config["post_section"] = array(
    "title" => "Post Section",
    "folder" => "basic_appineers_master",
    "method" => "POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "section_id",
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["user_profiles"] = array(
    "title" => "User Profiles",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "latitude",
        "longitude",
        "age",
        "gender",
        "radius",
        "page_no",
        //"app_section"

    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["get_user_details"] = array(
    "title" => "Get User Details",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
       // "user_access_token",
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["subscription_purchase"] = array(
    "title" => "Subscription purchase",
    "folder" => "subscription",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(
        //receipt_type (ios, android)
        //receipt_data (json file)
        //user_access_token
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["connections"] = array(
    "title" => "User Connection Webservices",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(
        //post
            //connection_user_id
            //user_id
            //user_access_token
            //connection_type
            //app_section

        //Get
            //user_id
            //user_access_token
            //connection_type
            //app_section
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["notification"] = array(
    "title" => "User Notification Webservices",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["report_abusive_user"] = array(
    "title" => "Report Abusive User",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "message",
        "report_on"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["block_user"] = array(
    "title" => "Block User",
    "folder" => "basic_appineers_master",
    "method" =>$_SERVER['REQUEST_METHOD'],
    "params" => array(),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["send_message"] = array(
    "title" => "Send Message",
    "folder" => "friends",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "receiver_id",
        "message",
        "user_access_token",
        "firebase_id",
        "app_section"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["get_message_list"] = array(
    "title" => "Get Message List",
    "folder" => "friends",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["interest_list"] = array(
    "title" => "interest Type List",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["religion_list"] = array(
    "title" => "religion Type List",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["body_type_list"] = array(
    "title" => "body Type List",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["delete_image"] = array(
    "title" => "Delete user images",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(
        "image_id",
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["education_list"] = array(
    "title" => "education List",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["income_type_list"] = array(
    "title" => "Income Type List",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
#####GENERATED_CONFIG_SETTINGS_END#####

/* End of file cit_webservices.php */
/* Location: ./application/config/cit_webservices.php */
    
