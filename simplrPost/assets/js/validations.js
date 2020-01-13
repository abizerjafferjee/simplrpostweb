// $url = 'http://'+window.location.hostname+'/';
// $url = 'http://'+window.location.hostname+'/pro1/simplrpost/';
$url = base_url;
$(document).ready(function() {
    $('.validate_error').css('display', 'none');

    $('input').focus(function() {
        $('.validate_error').css({ 'display': 'none' });
    })

    $(document).ajaxStart(function() {
        $('#loader').addClass('loader');
        $('#loader-div').css('z-index', '10');
    });

    $(document).ajaxComplete(function() {
        $('#loader').removeClass('loader');
        $('#loader-div').css('z-index', '-1');
    });

    // signin form validations
    $('#signIn').click(function(event) {
        event.preventDefault();

        $userEmail = $('#userEmail').val();
        $userPassword = $('#userPassword').val();
        // console.log($userPassword);
        if (validateValue($userEmail) == false) {
            $('#validateEmailError').css({ 'display': 'block', 'z-index': '10' }).html('Please fill this field');
            $('#userEmail').val('');
        } else if (!validateEmail($userEmail)) {
            $('#validateEmailError').css({ 'display': 'block', 'z-index': '10' }).html('Please enter avalid email address');
        } else if (validateValue($userPassword) == false) {
            $('#userPassword').val('');
            $('#validateEmailError').css('display', 'none').html('');
            $('#validatePasswordError').css({ 'display': 'block', 'z-index': '10' }).html('Please fill this field');
        } else if (!validatePassword($userPassword)) {
            $('#validateEmailError').css('display', 'none').html('');
            $('#validatePasswordError').css({ 'display': 'block', 'z-index': '10' }).html('Either email or password is invalid');
        } else {
            $.ajax({
                type: "post",
                url: $url + 'Login/verifyUser/',
                data: {
                    'emailId': $userEmail,
                    'password': $userPassword
                },
                success: function(data) {
                    console.log("data====>"+data);
                    if (data == 0) {
                        $('#validatePasswordError').css({ 'display': 'block', 'z-index': '10' }).html('wrong credentials, please try again');
                    } else {
                       window.location.replace($url + "admin-dashboard");
                    }
                }
            });
        }
    })

    // forgot password form validations
    $('#forgotPassword').click(function(event) {
        //alert($url);
        event.preventDefault();

        $userEmail = $('#forgotPasswordEmail').val();
        //alert($userEmail);
        if (!validateValue($userEmail)) {
            $('#forgotPasswordEmail').val('');
            $('.validate_error').css({ 'display': 'block', 'z-index': '10' }).html('Please fill this field');
        } else if (!validateEmail($userEmail)) {
            $('.validate_error').css({ 'display': 'block', 'z-index': '10' }).html('Please enter a valid email address');
        } else {
            $.ajax({
                type: "post",
                url: $url + 'Login/forgotPasswordLogic/',
                data: {
                    'emailId': $userEmail
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    if (obj.result == 1) {
                        localStorage.setItem('otpId', obj.otpId);
                        window.location.replace($url + "otp-validation");
                    } else {
                        $('.validate_error').css({ 'display': 'block', 'z-index': '10' }).html('This email id is not registered');
                    }
                }
            });
        }
    })

    // otp form validations
    $('#otpSubmitButton').click(function(event) {
        event.preventDefault();

        $otp = $('#otpValue').val();

        if ($otp.trim().length != 6) {
            $('#otp').val('');
            $('.validate_error').css({ 'display': 'block', 'z-index': '10' }).html('Please enter a valid otp');
        } else {
            $.ajax({
                type: "post",
                url: $url + 'Login/otpValidation/',
                data: {
                    'otpId': localStorage.getItem('otpId'),
                    'otp': $otp
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    if (obj.result == 0) {
                        $('.validate_error').css({ 'display': 'block', 'z-index': '10' }).html('OTP didn\'t matched');
                    } else {
                        localStorage.setItem('userId', obj.userId);
                        window.location.replace($url + "reset-password");
                    }
                }
            });
        }
    })

    // resend Otp
    $('#resendOtp').click(function(event) {
        event.preventDefault();

        $.ajax({
            type: "post",
            url: $url + 'Login/resendOtp/',
            data: {
                'otpId': localStorage.getItem('otpId')
            },
            success: function() {
                console.log('changed');
            }
        });

    })

    // reset password form validations
    $('#resetPasswordButton').click(function(event) {
        event.preventDefault();

        $resetPassword = $('#resetPassword').val();
        $confirmResetPassword = $('#confirmResetPassword').val();

        if (!validateValue($resetPassword)) {
            $('#resetPassword').val('');
            $('#validateErrorResetPassword').css({ 'display': 'block', 'z-index': '10' }).html('Please fill this field');
        } else if (!validatePassword($resetPassword)) {
            $('#validateErrorResetPassword').css({ 'display': 'block', 'z-index': '10' }).html('Please retry with a strong password');
        } else if (!validateValue($confirmResetPassword)) {
            $('#confirmResetPassword').val('');
            $('#validateErrorResetPassword').css('display', 'none').html('');
            $('#validateErrorConfirmResetPassword').css({ 'display': 'block', 'z-index': '10' }).html('Please fill this field');
        } else if ($resetPassword !== $confirmResetPassword) {
            $('#validateErrorResetPassword').css('display', 'none').html('');
            $('#validateErrorConfirmResetPassword').css({ 'display': 'block', 'z-index': '10' }).html('Password and Confirm Password should be the same');
        } else {
            $.ajax({
                type: 'post',
                url: $url + 'Login/resetPasswordLogic/',
                data: {
                    userId: localStorage.getItem('userId'),
                    password: $resetPassword
                },
                success: function(data) {
                    if (data == 0) {
                        $('#validateErrorResetPassword').css({ 'display': 'block', 'z-index': '10' }).html('new password shouldn\'t be same as old password');
                    } else {
                        window.location.replace($url + "login");
                    }
                }
            })
        }
    })

    // change password
    // reset password form validations
    $('#changePasswordButton').click(function(event) {
        event.preventDefault();

        $currentPassword = $('#currentPassword').val();
        $changePassword = $('#changePassword').val();
        $confirmChangePassword = $('#confirmChangePassword').val();

        if (!validateValue($currentPassword)) {
            $('#currentPassword').val('');
            $('#validateErrorCurrentPassword').css({ 'display': 'block', 'z-index': '10' }).html('Please fill this field');
        } else if (!validatePassword($currentPassword)) {
            $('#validateErrorCurrentPassword').css({ 'display': 'block', 'z-index': '10' }).html('Please enter a valid password');
        } else if (!validateValue($changePassword)) {
            $('#changePassword').val('');
            $('#validateErrorCurrentPassword').css('display', 'none').html('');
            $('#validateErrorChangePassword').css({ 'display': 'block', 'z-index': '10' }).html('Please fill this field');
        } else if (!validatePassword($changePassword)) {
            $('#validateErrorCurrentPassword').css('display', 'none').html('');
            $('#validateErrorChangePassword').css({ 'display': 'block', 'z-index': '10' }).html('Please retry with a strong password');
        } else if (!validateValue($confirmChangePassword)) {
            $('#confirmChangePassword').val('');
            $('#validateErrorCurrentPassword').css('display', 'none').html('');
            $('#validateErrorChangePassword').css('display', 'none').html('');
            $('#validateErrorConfirmChangePassword').css({ 'display': 'block', 'z-index': '10' }).html('Please fill this field');
        } else if ($confirmChangePassword != $changePassword) {
            $('#validateErrorChangePassword').css('display', 'none').html('');
            $('#validateErrorCurrentPassword').css('display', 'none').html('');
            $('#validateErrorConfirmChangePassword').css({ 'display': 'block', 'z-index': '10' }).html('Password and Confirm Password should be the same');
        } else {
            $.ajax({
                type: 'post',
                url: $url + 'Login/changePasswordLogic/',
                data: {
                    currentPassword: $currentPassword,
                    newPassword: $changePassword
                },
                success: function(data) {
                    if (data == 0) {
                        $('#validateErrorCurrentPassword').css({ 'display': 'block', 'z-index': '10' }).html('current password didn\'t matched');
                    } else {
                        window.location.replace($url + "Login/index");
                    }
                }
            })
        }
    })

    // validate values to check if they are null
    function validateValue(value) {
        if (value.trim() == '') {
            return false;
        } else {
            return true;
        }
    }

    // function to validate email
    function validateEmail(email) {
        $re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        // $re = /^(([^<>()[]\.,;:s@"]+(.[^<>()[]\.,;:s@"]+)*)|(".+"))@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}])|(([a-zA-Z-0-9]+.)+[a-zA-Z]{2,}))$/;
        return $re.test(email);
    }

    // function to validate password
    function validatePassword(password) {
        if (password.length > 7)
            return true
    }
});