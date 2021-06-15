/** users_management module script */
Project.modules.income_type_management = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
            "i_income_type_name": {
                "required": true
            },
            "i_income_type_status": {
                "required": true
            }
        },
            messages : {
            "i_income_type_name": {
                "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.INCOME_TYPE_MANAGEMENT_NAME)
            },
            "i_income_type_status": {
                "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.INCOME_TYPE_MANAGEMENT_STATUS)
            }
        },
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                        case 'i_income_type_name':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;

                        case 'i_income_type_status':
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
Project.modules.income_type_management.init();
