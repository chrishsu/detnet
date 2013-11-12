var RecaptchaOptions = {
    theme : 'clean'
};
var total_pages = 3;
var challenge = '';

$( function() {
    $(".create-box:nth-of-type("+$("#page").val()+")").show();
    switch(parseInt($("#page").val())) {
        case 1:
            break;
        case total_pages:
            $("#submit").show().removeClass('hidden');
            $("#next").hide().addClass('hidden');
        default:
            $("#prev").show().removeClass('hidden');
            break;
    }
    
    // matches yyyy-mm-dd
    $.validator.addMethod("dateYMD", function(value, element) {
        var check = false;
        var re = /^\d{4}-\d{1,2}-\d{1,2}$/;
        if( re.test(value)) {
            var adata = value.split('-');
            var yyyy = parseInt(adata[0],10);
            var mm = parseInt(adata[1],10);
            var dd = parseInt(adata[2],10);
            var xdata = new Date(yyyy,mm-1,dd);
            if ( ( xdata.getFullYear() === yyyy ) && ( xdata.getMonth() === mm - 1 ) && ( xdata.getDate() === dd ) ){
                check = true;
            } else {
                check = false;
            }
        } else {
            check = false;
        }
        return this.optional(element) || check;
    }, "Please enter a correct date. Format: YYYY-MM-DD");
    
    // value not equals
    $.validator.addMethod("notEquals", function(value, element, arg){
        return arg != value;
    }, "Value must not equal arg.");
    
    // matches xxx-xxx-xxxx
    $.validator.addMethod("phoneUS", function(phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^([2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
    }, "Please specify a valid phone number. Format: xxx-xxx-xxxx");
    
    // password with 1 capital letter and 1 number
    $.validator.addMethod("specPassword", function(password, element) {
        return password.match(/^.*(?=.*[A-Z])(?=.*\d).*$/);
    }, "Use at least one number and one capital letter.");
    
    var V = $("#ca-form").validate({
        groups: {
            'grad': "grad-semester grad-year"
        },
        rules: {
            'firstname': { minlength: 2 },
            'lastname': { minlength: 2},
            'password': {
                minlength: 7,
                specPassword: true
            },
            'password2': { equalTo: '#password' },
            'phone': { phoneUS: 'default' },
            'birthday': { dateYMD: true },
            'recaptcha_response_field': {
                required: true,
                /*remote: {
                    url: "register.php",
                    type: "post",
                    data: {
                        'ajax': 'recaptcha',
                        'recaptcha_challenge_field': $("#recaptcha_challenge_field").val()
                    }
                }*/
            }
        },
        onkeyup: function(element, event) {
            if ($(element).attr('id') == 'recaptcha_response_field') {
                return false;
            }
            return true;
        },
        messages: {
            'password2': { equalTo: "The password does not match!" },
            'grad-semester': { required: 'Please select a value.' },
            'grad-year': { required: 'Please select a value.' },
            'school': { required: 'Please select a value.' },
            'texting': { required: "Please select a response." },
            /*'recaptcha_response_field': {
                remote: "Incorrect input. Please try again." 
            }*/
        },
        errorPlacement: function(error, element) {
            if (element.attr('type') === 'radio') {
                error.insertAfter(element.parent('.radio-group'));
            }
            else if (element.prop('tagName').toLowerCase() == 'textarea') {
                error.insertAfter(element).css('margin-top', "-"+element.css('height'));
            }
            else if (element.prop('tagName').toLowerCase() == 'select') {
                error.insertAfter(element.parent('.select-group'));
            }
            else if (element.attr('id') == 'recaptcha_response_field') {
                error.insertAfter(element.parents('#recaptcha_widget_div'));
            }
            else {
               error.insertAfter(element);
            }
        }
    });
    
    $("#next").click( function() {
        var cur_page = parseInt($("#page").val());
        if (!V.form()) return; //validate form
        if (1 <= cur_page && cur_page < total_pages) {
            $("#page").val(cur_page+1);
            $(".create-box:nth-of-type("+cur_page+")").hide();
            $(".create-box:nth-of-type("+(cur_page+1)+")").show();
        }
        if (cur_page == total_pages - 1) {
            $(this).hide().addClass('hidden');
            $("#submit").show().removeClass('hidden');
        }
        if (cur_page == 1) {
            $("#prev").show().removeClass('hidden');
            //generate username for page 2
            generateUsername($("#lastname").val(), $("#firstname").val());
        }
    });
    $("#prev").click( function() {
        var cur_page = parseInt($("#page").val());
        if (1 < cur_page && cur_page <= total_pages) {
            $("#page").val(cur_page-1);
            $(".create-box:nth-of-type("+cur_page+")").hide();
            $(".create-box:nth-of-type("+(cur_page-1)+")").show();
        }
        if (cur_page == total_pages) {
            $("#submit").hide().addClass('hidden');
            $("#next").show().removeClass('hidden');
        }
        if (cur_page <= 2) {
            $(this).hide().addClass('hidden');
        }
    });
    challenge = $("#recaptcha_challenge_field").val();
    /*$("#recaptcha_reload_btn").click( function() {
        setTimeout(updateRule(challenge), 10000);
    });*/
    //$("#recaptcha_image img").bind('load', updateRule(challenge));
    $("#submit").click( function() {
    });
});

/*function generateUsername(lname, fname) {
    if ($("#username_h").val().length != 0) return;
    var username = lname.toLowerCase();
    if (username.length < 3) {
        username += fname.charAt(0).toLowerCase();
    }
    $("#username").val(username);
    $("#username_h").val(username);
}*/

function updateRule(current) {
    var newC = RecaptchaState.challenge;
    console.log('updating.. '+newC);
    /*if (current == newC) {
        setTimeout(updateRule(current), 10000);
        return;
    }*/
    $("#recaptcha_response_field").rules("remove", "remote");
    $("#recaptcha_response_field").rules("add", {
        remote: {
            url: "register.php",
            type: "post",
            data: {
                'ajax': 'recaptcha',
                'recaptcha_challenge_field': newC
            }
        },
        messsages: {
            remote: "Incorrect input. Please try again." 
        }
    });
    challenge = newC;
}