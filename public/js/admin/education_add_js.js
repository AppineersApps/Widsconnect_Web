/** users_management module script */
Project.modules.education_management = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
            "i_education_name": {
                "required": true
            },
            "i_education_status": {
                "required": true
            },
            "i_order_number": {
                "required": true
            }
        },
            messages : {
            
            "i_education_name": {
                "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.EDUCATION_MANAGEMENT_NAME)
            },
            "i_education_status": {
                "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.EDUCATION_MANAGEMENT_STATUS)
            },
            "i_order_number": {
                "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.EDUCATION_MAMAGEMENT_SERIAL_NUMBER)
            }
        },
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                       
                        case 'i_education_name':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;

                        case 'i_education_status':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;

                        case 'i_order_number':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                    default:
                        printErrorMessage(element, valid_more_elements, error);
                        break;
                }
                
            },
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {                    
                    validator.errorList[0].element.focus();
                }
            },
            submitHandler: function (form) {
                getAdminFormValidate();
                return false;
            }
        });

        $("#i_order_number").change(function(){
           //alert("The text has been changed yoyoy.");

                var last_order_number = $("#last_order_number").val();
                var i_order_number = $("#i_order_number").val();

                if(i_order_number > last_order_number){

                    if(i_order_number > last_order_number){
                    popup_msg = "Please enter sequence number less than "+ last_order_number + "";
                    }
                
                    var label_elem = '<div />';
                    var label_text = ci_js_validation_message(popup_msg, '#STATUS#', "");

                    var option_params = {
                        title: "Reorder records confirmation",
                        dialogClass: "dialog-confirm-box grid-status-btn-cnf",
                        buttons: [
                            {
                                text: js_lang_label.GENERIC_OK,
                                bt_type: 'ok',
                                click: function () {
                                   $(this).remove();
                                }
                            },
                           
                        ]
                    }

                    jqueryUIdialogBox(label_elem, label_text, option_params);
                }
                else if(i_order_number > 0){
                    
                popup_msg = "Are you sure you want to reorder the sequence number of all records";
                
                var label_elem = '<div />';
                var label_text = ci_js_validation_message(popup_msg, '#STATUS#', "");
                var postdata = {
                    "oper": "status",
                    "status": status,
                    "id": "",
                    "AllRowSelected": "",
                    "filters": ""
                };
               
                var option_params = {
                    title: "Reorder records confirmation",
                    dialogClass: "dialog-confirm-box grid-status-btn-cnf",
                    buttons: [
                        {
                            text: js_lang_label.GENERIC_OK,
                            bt_type: 'ok',
                            click: function () {
                               // $(this).remove();
                               $("form").submit();
                               $(this).remove();
                            }
                        },
                        {
                            text: js_lang_label.GENERIC_CANCEL,
                            bt_type: 'cancel',
                            click: function () {
                                window.location.href = "education/education_management/index";
                                $(this).remove();
                            }
                        }
                    ]
                }
                jqueryUIdialogBox(label_elem, label_text, option_params);
            
            }

        });
        
    },
    callEvents: function() {
        this.validate();
        this.initEvents();
        this.toggleEvents();
        callGoogleMapEvents();
        
    },
    callChilds: function(){
        
        callGoogleMapEvents();
    },
    initEvents: function(elem){
    
    },
    childEvents: function(elem, eleObj){
        
    },
    toggleEvents: function(){
        
    },
    dropdownLayouts:function(elem){
        
    }
}
Project.modules.education_management.init();
