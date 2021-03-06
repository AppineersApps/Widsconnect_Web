/** api_access_log module script */
Project.modules.api_access_log = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
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
        this.CCEvents();
        callGoogleMapEvents();
        
    },
    callChilds: function(){
        
        callGoogleMapEvents();
    },
    initEvents: function(elem){
        
            
                        $('#maa_access_date').datepicker({
                            dateFormat : 'yy-mm-dd', 
showOn : 'focus', 
changeMonth : true, 
changeYear : true, 
yearRange : 'c-100:c+100',
                            beforeShow: function(input, inst) {
                                var cal = inst.dpDiv;
                                var left = ($(this).offset().left + $(this).outerWidth()) - cal.outerWidth();
                                setTimeout(function() {
                                    cal.css({
                                        'left': left
                                    });
                                }, 10);
                            }
                        });
                        if(el_general_settings.mobile_platform){
                            $('#maa_access_date').attr('readonly', true);
                        }
                        
            $('#maa_input_params').elastic();
    },
    childEvents: function(elem, eleObj){
        
    },
    CCEvents: function(){
        
    }
}
Project.modules.api_access_log.init();
