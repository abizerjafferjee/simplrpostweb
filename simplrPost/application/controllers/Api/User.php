<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/User_model');
        $this->load->library('PHPMailer');
        $this->load->library('africastalking');
    }
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function checkUserStatus($userId)
    {
        $arrResult = $this->User_model->checkUserStatus($userId);

        // print_r($arrResult); exit();
        if ($arrResult['status'] == -5) {
            $arrResponse[code] = -5;
            $arrResponse[data] = 'Account has been blocked';
        } else if ($arrResult['status'] == -1) {
            $arrResponse[code] = -1;
            $arrResponse[data] = 'Account has been deleted';
        } else if ($arrResult['status'] == 0) {
            $arrResponse[code] = 0;
            $arrResponse[data] = 'Account has been deactivated';
        } else {
            $arrResponse[code] = 1;
            $arrResponse[data] = 'Success';
        }
        return $arrResponse;
    }

    /*******API #1******* Function for Get base_url *************/
    public function getBaseURL()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method.',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrResult = $this->User_model->baseUrl();
                if (!empty($arrResult)) {
                    $arrReturn = array(
                        code => 1,
                        data => $arrResult,
                    );
                    echo json_encode($arrReturn);
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not send',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /*******API #2******* Function for signUp *************/
    public function signUp()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['profilePicURL']) && isset($arrRequestData['name']) && isset($arrRequestData['userName']) && isset($arrRequestData['emailId']) && isset($arrRequestData['password']) && isset($arrRequestData['contactNumber'])) {
                    if ($this->User_model->validateIfEmailExisted($arrRequestData['emailId'])) {
                        $arrReturn = array(
                            code => -3,
                            data => 'Email Id already registered',
                        );
                        echo json_encode($arrReturn);
                        die;
                    } else if ($this->User_model->validateIfUserNameExisted(strtolower($arrRequestData['userName']))) {
                        $arrReturn = array(
                            code => -4,
                            data => 'Username already registered',
                        );
                        echo json_encode($arrReturn);
                        die;
                    } else if ($this->User_model->validateIfContactNumberExisted($arrRequestData['contactNumber'])) {
                        $arrReturn = array(
                            code => -6,
                            data => 'Contact Number already registered',
                        );
                        echo json_encode($arrReturn);
                        die;
                    } else {
                        /**********************************************/
                        // $temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
                        // file_put_contents($temp_file_path, base64_decode($arrRequestData['profilePicURL']));
                        // $image_info = getimagesize($temp_file_path);
                        // $_FILES['img'] = array(
                        //     'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
                        //     'tmp_name' => $temp_file_path,
                        //     'size'  => filesize($temp_file_path),
                        //     'error' => UPLOAD_ERR_OK,
                        //     'type'  => $image_info['mime'],
                        // );
                        USER_UPLOAD_DIR;
                        $img = $arrRequestData['profilePicURL'];
                        $img = str_replace('data:image/png;base64,', '', $img);
                        $img = str_replace(' ', '+', $img);
                        $img = str_replace('data:image/jpg;base64,', '', $img);
                        $img = str_replace(' ', '+', $img);
                        $img = str_replace('data:image/jpeg;base64,', '', $img);
                        $img = str_replace(' ', '+', $img);
                        $imgdata = base64_decode($img);

                        $getMaxId = $this->User_model->getUserMaxId();
                        // print_r($getMaxId);exit;
                        $maxId = $getMaxId + 1;
                        $image = $maxId . '.png';
                        $imgUrl = USER_UPLOAD_DIR . $image;
                        $success = file_put_contents($imgUrl, $imgdata);
                        //print_r($files); exit;
                        $arrRequestData['profilePicURL'] = 'user/' . $image;
                        $arrRequestData['password'] = md5($arrRequestData['password']);
                        // $arrRequestData['referenceCode'] = 'ref code';
                        $arrRequestData['referenceCode'] = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 10);
                        $arrRequestData['emailVerificationToken'] = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 20);
                        $arrRequestData['createDate'] = date("Y-m-d H:i:s");
                        $arrRequestData['status'] = 1;
                        $result['userId'] = $this->User_model->signUp($arrRequestData);
                        /********** Save address ***********/
                        $savedAddress['listName'] = 'Default List';
                        $savedAddress['userId'] = $result['userId'];
                        $savedAddress['createDate'] = date('Y-m-d H:i:s a');
                        $savedAddress['isDefault'] = 1;
                        $savedAddress['status'] = 1;

                        $insertAddressData = $this->User_model->saveUserAddress($savedAddress);
                        /*************** Email Data *******************/
                        $email_data['email_title'] = 'Registration';
                        $email_data['name'] = ucfirst($arrRequestData['name']);
                        $email_data['email_id'] = $arrRequestData['emailId'];
                        $this->sendEmail($email_data);

                        $email_data1['email_title'] = 'Confirmation';
                        $email_data1['email_id'] = $arrRequestData['emailId'];
                        $email_data1['heading'] = "Hey, ". ucfirst($arrRequestData['name']);
                        $email_data1['message'] = "<div style='padding:10px 30px;'><p style='text-align: center;'>You're almost ready to start enjoying all the capabilities of Simplr Post. Simply click the button below to verify your email address</p></div><div><a href='".SITE_URL."confirm-email/".$arrRequestData['emailVerificationToken']."' style='background-color:#1bac71;color:white;padding:7px 20px;text-decoration:none;border-radius:5px'>Confirm</a></div>";
                        $email_data1['footer'] = '<p style="text-align: center;">If you have any questions or concerns please direct them to <a href="mailto:abizerjafferjee@simplrpost.com?Subject=Email%20Confirmation" target="_blank" style="text-decoration:none;color:#1bac71">abizerjafferjee@simplrpost.com</a></p>';
                        $this->sendOTPEmail($email_data1);
                        /**********************************************/

                        $intRandom = mt_rand(100000, 999999);
                        $arrOtpData['otp'] = $intRandom;
                        $arrOtpData['userId'] = $result['userId'];
                        $arrOtpData['createDate'] = date("Y-m-d H:i:s");
                        $arrOtpData['otpType'] = 2;
                        $otpId = $this->User_model->saveOtp($arrOtpData);

                        // $message = $arrRequestData['name'] . " your OTP is $intRandom";
                        $welcomeMessage = "Hey,";
                        $welcomeMessage .= "Thank you for signing up with Simplr Post. Let’s get your home or business and set up with an address that you can easily share with others.";
                        $message = "Hi, you recently sent a request for a one time verification code for Simplr Post. Here it is: $intRandom";

                        // $msg = str_replace(' ', '%20', $msg);
                        // $baseURL = "https://api.budgetsms.net/sendsms/?username=" . SMS_USERNAME . "&handle=" . SMS_HANDLE . "&userid=" . SMS_USERID . "&%20msg=" . $msg . "&from=simplrPost&to=" . str_replace('+', '', $arrRequestData['contactNumber']);
                        // file_get_contents($baseURL);
                        $this->africastalking->sendMessage($arrRequestData['contactNumber'], $welcomeMessage);
                        $this->africastalking->sendMessage($arrRequestData['contactNumber'], $message);
                        $result['otpId'] = $otpId['otpId'];
                        if ($result) {
                            $arrReturn = array(
                                code => 1,
                                data => $result,
                            );
                            echo json_encode($arrReturn);
                            die;
                        } else {
                            $arrReturn = array(
                                code => -2,
                                data => 'something went wrong',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not send',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    public function resendVerificationEmail()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['emailId'])) {
                    $arrResult = $this->User_model->getValuesWithEmailId($arrRequestData['emailId']);
                    if (!empty($arrResult)) {
                        $emailVerificationToken = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 20);
                        $result = $this->User_model->updateVerifictionToken($arrResult['userId'], $emailVerificationToken);
                        if($result > 0){
                            $email_data['email_title'] = 'Confirm Email';
                            // $email_data['name'] = ucfirst($arrResult['name']);
                            $email_data['email_id'] = $arrRequestData['emailId'];
                            $email_data['heading'] = "Hey, ". ucfirst($arrRequestData['name']);
                            $email_data['message'] = "<div style='padding:10px 30px;'><p style='text-align: center;'>You're almost ready to start enjoying all the capabilities of Simplr Post. Simply click the button below to verify your email address</p></div><div><a href='".SITE_URL."confirm-email/".$emailVerificationToken."' style='background-color:#1bac71;color:white;padding:7px 20px;text-decoration:none;border-radius:5px'>Confirm</a></div>";
                            $email_data['footer'] = '<p style="text-align: center;">If you have any questions or concerns please direct them to <a href="mailto:abizerjafferjee@simplrpost.com?Subject=Email%20Confirmation" target="_blank" style="text-decoration:none;color:#1bac71">abizerjafferjee@simplrpost.com</a></p>';
                            $email_data['view_url'] = 'email/emailTemplate';
                            $this->sendOTPemail($email_data);
                            $arrReturn = array(
                                code => 1,
                                data => 'success',
                            );
                            echo json_encode($arrReturn);
                            die;
                            
                        }
                    } else {
                        $arrReturn = array(
                            code => -4,
                            data => 'This account does not exist',
                        );
                        echo json_encode($arrReturn);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not send',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    public function resendVerificationOTP()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['contactNumber'])) {
                    $arrResult = $this->User_model->getValuesWithContactNumber($arrRequestData['contactNumber']);
                    if (!empty($arrResult)) {
                        $intRandom = mt_rand(100000, 999999);
                        $arrOtpData['otp'] = $intRandom;
                        $arrOtpData['userId'] = $arrResult['userId'];
                        $arrOtpData['createDate'] = date("Y-m-d H:i:s");
                        $arrOtpData['otpType'] = 2;
                        $otpId = $this->User_model->saveOtp($arrOtpData);
                        if(!empty($otpId)){
                            $message = "Hi, you recently sent a request for a one time verification code for Simplr Post. Here it is: $intRandom";
                            // $msg = str_replace(' ', '%20', $msg);
                            // $baseURL = "https://api.budgetsms.net/sendsms/?username=" . SMS_USERNAME . "&handle=" . SMS_HANDLE . "&userid=" . SMS_USERID . "&%20msg=" . $msg . "&from=simplrPost&to=" . str_replace('+', '', $arrRequestData['contactNumber']);
                            // file_get_contents($baseURL);
                            $this->africastalking->sendMessage($arrRequestData['contactNumber'], $message);

                            $arrReturn = array(
                                code => 1,
                                data => $otpId,
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        $arrReturn = array(
                            code => -4,
                            data => 'This account does not exist',
                        );
                        echo json_encode($arrReturn);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not send',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    public function verifyEmail($refCode = null)
    {
        // $emailVerified = $this->User_model->verifyEmail($refCode);
        $verificationStatus = $this->User_model->checkVerificationStatus($refCode);
        if ($verificationStatus == '') {
            redirect('/index.php/verification-error');
        } else if ($verificationStatus == 1) {
            redirect('/index.php/already-verified');
        } else {
            $this->User_model->verifyEmail($refCode);
            redirect('/index.php/verification-successfull');
        }
    }
    public function verifyContactNumber($otpId)
    {
        $userId = $this->User_model->getUserIdWithOtpId($otpId);
        $contactNumberVerified = $this->User_model->verifyContactNumber($userId['userId']);
        if ($contactNumberVerified > 0) {
            $arrReturn = array(
                code => 1,
                data => 'Success',
            );
            echo json_encode($arrReturn);
            die;
        }
    }
    /*******API #3******* Function for signIn *************/
    public function validateEmailUserName($email)
    {
        return (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email));
    }
    public function signIn()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);
                $email = $arrRequestData['emailUserName'];

                if (isset($arrRequestData['emailUserName']) && isset($arrRequestData['password'])) {
                    if ($this->validateEmailUserName($arrRequestData['emailUserName'])) {
                        if ($this->User_model->validateIfEmailExisted($arrRequestData['emailUserName'])) {
                            // print_r(md5($arrRequestData['password']));exit;
                            $arrResult = $this->User_model->signInWithEmail($arrRequestData['emailUserName'], md5($arrRequestData['password']));
                            $this->logInAfterValidateEmailUserName($arrResult);
                        } else {
                            $arrReturn = array(
                                code => -4,
                                data => 'This account does not exist',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        if ($this->User_model->validateIfUserNameExisted($arrRequestData['emailUserName'])) {
                            // print_r(md5($arrRequestData['password']));exit;
                            $arrResult = $this->User_model->signInWithUserName($arrRequestData['emailUserName'], md5($arrRequestData['password']));
                            $this->logInAfterValidateEmailUserName($arrResult);
                        } else {
                            $arrReturn = array(
                                code => -4,
                                data => 'This account does not exist',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not send',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    public function logInAfterValidateEmailUserName($arrUserDeatil)
    {
        try {
            if (!empty($arrUserDeatil)) {
                $arrResult = $this->checkUserStatus($arrUserDeatil['userId']);

                // print_r($arrResult); exit();
                if ($arrResult[code] == 1) {
                    $arrReturn = array(
                        code => 1,
                        data => $arrUserDeatil,
                    );
                    echo json_encode($arrReturn);
                    die;
                } else if ($arrResult[code] == 0) {
                    $this->User_model->reactivateAccount($arrUserDeatil['userId']);
                    $arrReturn = array(
                        code => 1,
                        data => $arrUserDeatil,
                    );
                    echo json_encode($arrReturn);
                    die;
                } else {
                    echo json_encode($arrResult);
                    die;
                }
            } else {
                $arrReturn = array(
                    code => -3,
                    data => 'wrong credentials',
                );
                echo json_encode($arrReturn);
                die;
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /******API #4********* function for forgot password ***************/
    public function forgotPassword()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);
                $arrRequestData['function'] = 'insert';

                $intRandom = mt_rand(100000, 999999);
                // $intRandom = 111111;

                if (($arrRequestData['isEmailUsed'] == 1)) {
                    $arrResult = $this->User_model->getValuesWithEmailId($arrRequestData['emailId']);
                    $this->processAfterCheckIsUsedEmailUsed($arrResult, $arrRequestData, $intRandom);
                } else if (($arrRequestData['isEmailUsed'] == 0)) {
                    $arrResult = $this->User_model->getValuesWithContactNumber($arrRequestData['contactNumber']);
                    $this->sendOTPToNumber($arrResult, $arrRequestData, $intRandom);

                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not send',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    public function sendOTPToNumber($arrResult, $arrRequestData, $intRandom)
    {
        if ($arrResult) {
            $userStatus = $this->checkUserStatus($arrResult['userId']);
            if ($userStatus['resultCode'] == 1 || $userStatus['resultCode'] == 0) {
                $arrOtpData['otp'] = $intRandom;
                $arrOtpData['userId'] = $arrResult['userId'];
                $arrOtpData['otpType'] = 1;
                $arrOtpData['createDate'] = date("Y-m-d H:i:s");
                $message = "Hi, you recently sent a request for a one time verification code for Simplr Post. Here it is: $intRandom";
                // $msg = str_replace(' ', '%20', $msg);
                // $baseURL = "https://api.budgetsms.net/sendsms/?username=".SMS_USERNAME."&handle=".SMS_HANDLE."&userid=".SMS_USERID."&%20msg=".$msg."&from=simplrPost&to=".str_replace('+', '',$arrRequestData['contactNumber']);
                // file_get_contents($baseURL);
                $this->africastalking->sendMessage($arrRequestData['contactNumber'], $message);
                if ($arrRequestData['function'] == 'insert') {
                    $save = $this->User_model->saveOtp($arrOtpData);
                } else if ($arrRequestData['function'] == 'update') {
                    $arrOtpData['otpId'] = $arrRequestData['otpId'];
                    $save = $this->User_model->updateOtp($arrOtpData);
                }
                if ($save) {

                    $arrReturn = array(
                        code => 1,
                        data => $save,
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            } else {
                echo json_encode($userStatus);
                die;
            }
        } else {
            $arrReturn = array(
                code => -4,
                data => 'This account does not exist',
            );
            echo json_encode($arrReturn);
            die;
        }
    }
    /*******API #4.5****API #7.5****** this functon will be called after isUsedEmailUsed Check*********************/
    public function processAfterCheckIsUsedEmailUsed($arrResult, $arrRequestData, $intRandom)
    {
        try {
            if ($arrResult) {
                $userStatus = $this->checkUserStatus($arrResult['userId']);
                if ($userStatus['resultCode'] == 1 || $userStatus['resultCode'] == 0) {
                    $intRandom = 111111;
                    $to = $arrRequestData['emailId'];

                    $arrOtpData['otp'] = $intRandom;
                    $arrOtpData['userId'] = $arrResult['userId'];
                    $arrOtpData['createDate'] = date("Y-m-d H:i:s");

                    // Sending email
                    $emailData['heading'] = "Hey, ". ucfirst($arrResult['name']);
                    $emailData['message'] = "<div style='padding:10px 30px;'><p style='text-align: center;'>Seems like you forgot your password for Simplr Post. If this is true, your OTP is - $intRandom</p><p>If you didn't forget your password safely ignore this</p></div>";
                    $emailData['footer'] = '<p style="text-align: center;">If you have any questions or concerns please direct them to <a href="mailto:abizerjafferjee@simplrpost.com?Subject=Forgot%20Password" target="_blank" style="text-decoration:none;color:#1bac71">abizerjafferjee@simplrpost.com</a></p>';
                    ob_start();
                    $mail = new PHPMailer;
                    $mail->SMTPDebug = '';
                    $mail->IsSMTP();

                    $mail->Host = 'relay-hosting.secureserver.net';
                    $mail->Port = 25;
                    $mail->SMTPAuth = false;
                    $mail->From = 'davinder.codeapex@gmail.com';
                    $mail->FromName = 'Simplr Post';
                    $mail->AddAddress($arrRequestData['emailId'], $arrResult['name']);

                    $mail->Subject = 'One Time Password(OTP) for account verification, Simplr Post ';
                    $mail->Body = $this->load->view('email/otpEmailTemplate', $emailData, true);
                    $mail->AltBody = 'Simplr Post';
                    $mail->Send();
                    /***************************************/

                    if ($arrRequestData['function'] == 'insert') {
                        $save = $this->User_model->saveOtp($arrOtpData);
                    } else if ($arrRequestData['function'] == 'update') {
                        $arrOtpData['otpId'] = $arrRequestData['otpId'];
                        $save = $this->User_model->updateOtp($arrOtpData);
                    }

                    if ($save) {
                        $arrReturn = array(
                            code => 1,
                            data => $save,
                        );
                        echo json_encode($arrReturn);
                        die;
                    }
                } else {
                    echo json_encode($userStatus);
                    die;
                }
            } else {
                $arrReturn = array(
                    code => -4,
                    data => 'This account does not exist',
                );
                echo json_encode($arrReturn);
                die;
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /***********API #5************* function for otp validation **********************/
    public function validateOTP()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['otpId']) && isset($arrRequestData['otp'])) {
                    $arrResult = $this->User_model->validateOtp($arrRequestData);
                    if ($arrResult) {
                        if ($arrResult['isUsed'] == 0) {
                            $this->User_model->expireOtp($arrRequestData['otpId']);
                            if ($arrResult['otpType'] == 1) {
                                $arrReturn = array(
                                    code => 1,
                                    data => $arrResult,
                                );
                                echo json_encode($arrReturn);
                                die;
                            } else {
                                $this->verifyContactNumber($arrRequestData['otpId']);
                            }
                        } else {
                            $arrReturn = array(
                                code => -4,
                                data => 'This session has expired',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        $arrReturn = array(
                            code => -3,
                            data => 'OTP did not matched',
                        );
                        echo json_encode($arrReturn);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not send',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #6********** function for reset password **************************/
    public function resetPassword()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);
                if (isset($arrRequestData['otpId']) && isset($arrRequestData['password'])) {
                    $arrResult = $this->User_model->getUserIdWithOtpId($arrRequestData['otpId']);
                    if ($arrResult) {
                        $this->User_model->resetPassword($arrResult['userId'], md5($arrRequestData['password']));
                        $arrReturn = array(
                            code => 1,
                            data => 'success password changed',
                        );
                        echo json_encode($arrReturn);
                        die;
                    } else {
                        $arrReturn = array(
                            code => -3,
                            data => 'OTP id not existed',
                        );
                        echo json_encode($arrReturn);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /*********API #7********* function for resend otp *************************8*/
    public function resendOTP()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);
                $arrRequestData['function'] = 'update';

                // $intRandom = mt_rand(100000, 999999);
                $intRandom = 111111;
                if (($arrRequestData['isEmailUsed'] == 1)) {
                    $arrResult = $this->User_model->getValuesWithEmailId($arrRequestData['emailId']);
                    $this->processAfterCheckIsUsedEmailUsed($arrResult, $arrRequestData, $intRandom);
                } else if (($arrRequestData['isEmailUsed'] == 0)) {
                    $arrResult = $this->User_model->getValuesWithContactNumber($arrRequestData['contactNumber']);
                    $this->sendOTPToNumber($arrResult, $arrRequestData, $intRandom);
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #8********* this functon is for registerDevice *********************/
    public function registerDevice()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');

                $arrRequestData = json_decode($arrEntityBody, true);
                $arrRequestData['createDate'] = date("Y-m-d H:i:s");
                $arrRequestData['status'] = 1;

                if (isset($arrRequestData['userId']) && isset($arrRequestData['deviceId']) && isset($arrRequestData['deviceType']) && isset($arrRequestData['pushToken'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);
                    if ($userStatus[code] == 1) {
                        $data['userId'] = $arrRequestData['userId'];
                        $data['deviceId'] = $arrRequestData['deviceId'];
                        $data['deviceType'] = $arrRequestData['deviceType'];
                        $data['pushToken'] = $arrRequestData['pushToken'];
                        $data['createDate'] = date("Y-m-d H:i:s");
                        $data['status'] = 1;
                        if ($this->User_model->insertIntoRegisterDeviceTable($data)) {
                            $arrReturn = array(
                                code => 1,
                                data => 'success',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    /********API #9********* this functon is for getProfile *********************/
    public function getProfile()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['userId'])) {
                    $arrResult = $this->checkUserStatus($arrRequestData['userId']);
                    if ($arrResult[code] == 1) {
                        $arrResult = $this->User_model->getUserDetailWIthUserId($arrRequestData['userId']);
                        if ($arrResult) {
                            $arrReturn = array(
                                code => 1,
                                data => $arrResult,
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($arrResult);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #10********* this functon is for editProfile *********************/
    public function editProfile()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);
                // print_r($arrRequestData);exit;

                if (isset($arrRequestData['userId']) && isset($arrRequestData['profilePicURL']) && isset($arrRequestData['name']) && isset($arrRequestData['userName']) && isset($arrRequestData['emailId']) && isset($arrRequestData['contactNumber'])) {
                    // print_r($arrRequestData);exit;
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);

                    // print_r($userStatus);exit;

                    if ($userStatus[code] == 1) {
                        $data['emailVerificationToken'] = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 20);
                        $arrResult = $this->User_model->getUserDetailWIthUserIdEditProfile($arrRequestData['userId'], $data);
                        $result = $this->validateEmailUserNameContactNumber($arrResult, $arrRequestData, $data);
                        // print_r($result);
                        //print_r($arrResult);exit;
                        if ($result[code] == -3 || $result[code] == -4 || $result[code] == -6) {
                            echo json_encode($result);
                            die;
                        } else {
                            $arrRequestData['isEmailIdVerified'] = $result['isEmailIdVerified'];
                            $arrRequestData['isContactNumberVerified'] = $result['isContactNumberVerified'];

                            /**********************************************/
                            $tempFilePath = tempnam(sys_get_temp_dir(), 'androidtempimage');
                            file_put_contents($tempFilePath, base64_decode($arrRequestData['profilePicURL']));
                            $imageInfo = getimagesize($tempFilePath);
                            $_FILES['img'] = array(
                                'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $imageInfo['mime']),
                                'tmpName' => $tempFilePath,
                                'size' => filesize($tempFilePath),
                                'error' => UPLOAD_ERR_OK,
                                'type' => $imageInfo['mime'],
                            );
                            USER_UPLOAD_DIR;
                            $img = $arrRequestData['profilePicURL'];
                            $img = str_replace('data:image/png;base64,', '', $img);
                            $img = str_replace(' ', '+', $img);
                            $img = str_replace('data:image/jpg;base64,', '', $img);
                            $img = str_replace(' ', '+', $img);
                            $img = str_replace('data:image/jpeg;base64,', '', $img);
                            $img = str_replace(' ', '+', $img);
                            $mgData = base64_decode($img);
                            $image = $arrRequestData['userId'] . '.png';
                            $imgUrl = USER_UPLOAD_DIR . $image;
                            $success = file_put_contents($imgUrl, $mgData);

                            $arrRequestData['profilePicURL'] = 'user/' . $image;
                            $otpId['otpId'] = $result['otpId'];
                            $otpId['isEmailVerified'] = $result['isEmailIdVerified'];
                            //print_r($arrRequestData); exit;
                            if ($this->User_model->updateUserDetails($arrRequestData)) {
                                $arrReturn = array(
                                    code => 1,
                                    data => $otpId,
                                );
                                echo json_encode($arrReturn);
                                die;
                            }
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #10.5********* this functon is for editProfile valiidations*********************/

    public function validateEmailUserNameContactNumber($arrResult, $arrRequestData, $data)
    {
        try {
            $otpId = 0;
            $arrReturn['isEmailIdVerified'] = $arrResult['isEmailIdVerified'];
            $arrReturn['isContactNumberVerified'] = $arrResult['isContactNumberVerified'];
            if (strtolower($arrRequestData['userName']) != strtolower($arrResult['userName'])) {
                if ($this->User_model->validateIfUserNameExisted($arrRequestData['userName'])) {
                    $arrReturn = array(
                        code => -4,
                        data => 'User Name already registered',
                    );
                    return $arrReturn;
                }
            }
            if (strtolower($arrRequestData['emailId']) != strtolower($arrResult['emailId'])) {
                if ($this->User_model->validateIfEmailExisted($arrRequestData['emailId'])) {
                    $arrReturn = array(
                        code => -3,
                        data => 'Email Id already registered',
                    );
                    return $arrReturn;
                } else {
                    $arrReturn['isEmailIdVerified'] = 0;
                    /*************** Email Data *******************/
                    $email_data['email_title'] = 'Confirm Email Id';
                    $email_data['name'] = ucfirst($arrRequestData['name']);
                    $email_data['email_id'] = $arrRequestData['emailId'];
                    $email_data['message'] = "You have to confirm your email id for better user experience.<br>";
                    $email_data['message'] .= "Click on the link below to confirm your email id.<br>";
                    $email_data['message'] .= SITE_URL . "confirm-email/" . $data['emailVerificationToken'];
                    $email_data['view_url'] = 'email/emailTemplate';
                    $this->sendOTPemail($email_data);
                    /**********************************************/
                }
            }
            if ($arrRequestData['contactNumber'] != $arrResult['contactNumber']) {
                if ($this->User_model->validateIfContactNumberExisted($arrRequestData['contactNumber'])) {
                    $arrReturn = array(
                        code => -6,
                        data => 'Contact Number already registered',
                    );
                    return $arrReturn;
                } else {
                    $arrReturn['isContactNumberVerified'] = 0;
                    /************* Contact OTP data *************/
                    // $intRandom = mt_rand(100000, 999999);
                    $intRandom = 111111;
                    $arrOtpData['otp'] = $intRandom;
                    $arrOtpData['userId'] = $arrResult['userId'];
                    $arrOtpData['createDate'] = date("Y-m-d H:i:s");
                    $arrOtpData['otpType'] = 2;
                    $arrOtpId = $this->User_model->saveOtp($arrOtpData);
                    $otpId = $arrOtpId['otpId'];
                    $msg = $arrRequestData['name'] . " your OTP is $intRandom";
                    $msg = str_replace(' ', '%20', $msg);
                    $baseURL = "https://api.budgetsms.net/sendsms/?username=" . SMS_USERNAME . "&handle=" . SMS_HANDLE . "&userid=" . SMS_USERID . "&%20msg=" . $msg . "&from=simplrPost&to=" . str_replace('+', '', $arrRequestData['contactNumber']);
                    file_get_contents($baseURL);
                }
            }

            $arrReturn[code] = 1;
            $arrReturn['otpId'] = $otpId;
            return $arrReturn;
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #11********* this functon is for changePassword *********************/
    public function changePassword()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['userId']) && isset($arrRequestData['currentPassword']) && isset($arrRequestData['newPassword'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);
                    $arrRequestData['currentPassword'] = md5($arrRequestData['currentPassword']);
                    $arrRequestData['newPassword'] = md5($arrRequestData['newPassword']);
                    // print_r($arrRequestData); exit();
                    if ($userStatus[code] == 1) {
                        $arrResult = $this->User_model->updatePassword($arrRequestData);
                        // print_r($arrResult); exit();
                        if ($arrResult) {
                            $arrReturn = array(
                                code => 1,
                                data => 'success',
                            );
                            echo json_encode($arrReturn);
                            die;
                        } else {
                            $arrReturn = array(
                                code => -3,
                                data => 'current password didn\'t matched',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #12********* this functon is for signOut *********************/
    public function signOut()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['userId']) && isset($arrRequestData['deviceId']) && isset($arrRequestData['deviceType'])) {
                    if ($this->User_model->updateRegisterDeviceTable($arrRequestData)) {
                        $arrReturn = array(
                            code => 1,
                            data => 'success',
                        );
                        echo json_encode($arrReturn);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /*********API #13******** this functon is to delete account *********************/
    public function deleteAccount()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);
                $arrRequestData['status'] = -1;

                $this->deleteOrDeactivateStatusChangeFunction($arrRequestData);
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #14********* this functon is to deactivate account *********************/
    public function deactivateAccount()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);
                $arrRequestData['status'] = 0;

                $this->deleteOrDeactivateStatusChangeFunction($arrRequestData);
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /*******API #13.5****API #14.5**** deactivate and delete's common function*************/
    public function deleteOrDeactivateStatusChangeFunction($arrRequestData)
    {
        try {
            if (isset($arrRequestData['userId'])) {
                if ($this->User_model->updateUserStatusToDeleteOrDeactivateAccount($arrRequestData) > 0) {
                    $arrReturn = array(
                        code => 1,
                        data => 'success',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            } else {
                $arrReturn = array(
                    code => -6,
                    data => 'All fields not sent',
                );
                echo json_encode($arrReturn);
                die;
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #26********* this functon is to deactivate account *********************/
    public function aboutUs()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrResult = $this->User_model->getAboutUsContent();
                $arrReturn = array(
                    code => 1,
                    data => $arrResult,
                );
                echo json_encode($arrReturn);
                die;
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #27********* this functon is to deactivate account *********************/
    public function privacyPolicy()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrResult = $this->User_model->getPrivacyPolicyContent();
                $arrReturn = array(
                    code => 1,
                    data => $arrResult,
                );
                echo json_encode($arrReturn);
                die;
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #28********* this functon is to deactivate account *********************/
    public function termsConditions()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrResult = $this->User_model->getTermsConditionsContent();
                $arrReturn = array(
                    code => 1,
                    data => $arrResult,
                );
                echo json_encode($arrReturn);
                die;
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /********API #28********* this functon is to saveUserFeedback account *********************/
    public function userFeedback()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['userId']) && isset($arrRequestData['rating']) && isset($arrRequestData['content'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);

                    if ($userStatus[code] == 1) {
                        $arrRequestData['createDate'] = date("Y-m-d H:i:s");
                        if ($this->User_model->insertUserFeedback($arrRequestData)) {
                            $arrReturn = array(
                                code => 1,
                                data => 'success',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    /********API #29********* this functon is for validateEmailId *********************/
    public function validateEmailId()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    'resultCode' => -7,
                    'resultData' => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrReturn;
                $entityBody = file_get_contents('php://input');
                $data = json_decode($entityBody, true);
                if (isset($data['emailId'])) {
                    $getUserData = $this->User_model->getUserArray($data['emailId']);
                    /******* userStatusCheck ********/
                    $userStatus = $this->checkUserStatus($getUserData['userId']);
                    /*******************************/
                    if ($userStatus[code] == 1) {
                        if (!empty($getUserData)) {
                            $arrReturn = array(
                                'resultCode' => 1,
                                'resultData' => $getUserData,
                            );
                        } else {
                            $arrReturn = array(
                                'resultCode' => -3,
                                'resultData' => 'No data found',
                            );
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        'resultCode' => -6,
                        'resultData' => 'All fields not send',
                    );
                }
                echo json_encode($arrReturn);
                die;
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    /************* Send Mail Function *********************/
    public function sendEmail($email_data)
    {
        //print_r($email_data); exit;
        // ob_start();
        // $mail = new PHPMailer;

        // $mail->SMTPDebug = '';
        // $mail->IsSMTP();

        // $mail->Host = HOST_NAME;
        // $mail->Port = PORT_NAME;
        // $mail->SMTPAuth = false;

        // $mail->From = FROM_EMAIL;
        // $mail->FromName = FROM_NAME;
        // $mail->AddAddress($email_data['email_id'], $email_data['name']);

        // $mail->Subject = $email_data['email_title'];
        // $mail->Body = $this->load->view('email/emailTemplate', $email_data, true);
        // $mail->AltBody = BODY_TITLE;
        // $mail->Send();
        ob_start(); 
		$mail = new PHPMailer();

		$mail->SMTPDebug = '';
        $mail->IsSMTP();
        
		// $mail->isSMTP();
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->Username   = USERNAME_SMTP;
        $mail->Password   = PASSWORD_SMTP;
        $mail->Host       = HOST_NAME;
        $mail->Port       = PORT_NAME;
        $mail->SMTPAuth   = true;
		$mail->SMTPSecure = 'ssl';
		$mail->AddAddress($email_data['email_id'], $email_data['name']);

		$mail->Subject = $email_data['email_title'];
		$mail->Body = $this->load->view('email/emailTemplate',$email_data,TRUE);
    	$mail->AltBody = BODY_TITLE;
        $mail->Send();
    }
    public function sendOTPemail($email_data)
    {
        // ob_start(); 
		// $mail = new PHPMailer;

		// $mail->SMTPDebug = '';
		// $mail->IsSMTP();
		
		// $mail->Host = HOST_NAME;
		// $mail->Port = PORT_NAME;
		// $mail->SMTPAuth = false;

		// $mail->From = FROM_EMAIL;
        // $mail->FromName = FROM_NAME;
		// $mail->AddAddress($email_data['email_id'], $email_data['name']);

		// $mail->Subject = $email_data['email_title'];
		// $mail->Body = $this->load->view('email/otpEmailTemplate',$email_data,TRUE);
    	// $mail->AltBody = BODY_TITLE;
        // $mail->Send();
        ob_start(); 
		$mail = new PHPMailer();

		$mail->SMTPDebug = '';
        $mail->IsSMTP();
        
		// $mail->isSMTP();
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->Username   = USERNAME_SMTP;
        $mail->Password   = PASSWORD_SMTP;
        $mail->Host       = HOST_NAME;
        $mail->Port       = PORT_NAME;
        $mail->SMTPAuth   = true;
		$mail->SMTPSecure = 'ssl';
		$mail->AddAddress($email_data['email_id'], $email_data['name']);

		$mail->Subject = $email_data['email_title'];
		$mail->Body = $this->load->view('email/otpEmailTemplate',$email_data,TRUE);
    	$mail->AltBody = BODY_TITLE;
        $mail->Send();
    }
    /********API #28********* this functon is to get all FAQ *********************/
    public function getFAQ()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['userId'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);
                    // print_r($arrRequestData); exit();
                    if ($userStatus[code] == 1) {
                        $arrResult = $this->User_model->getFAQ();
                        // print_r($arrResult); exit();
                        if ($arrResult) {
                            $arrReturn = array(
                                code => 1,
                                data => $arrResult,
                            );
                            echo json_encode($arrReturn);
                            die;
                        } else {
                            $arrReturn = array(
                                code => -3,
                                data => 'No data found',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    /********API #28********* this functon is to submit report *********************/
    public function submitReport()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['reporterUserId']) && isset($arrRequestData['reporterName']) && isset($arrRequestData['reporterEmailId']) && isset($arrRequestData['reporterContactNumber']) && isset($arrRequestData['businessId']) && isset($arrRequestData['issueId']) && isset($arrRequestData['description'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['reporterUserId']);
                    // print_r($arrRequestData); exit();
                    if ($userStatus[code] == 1) {
                        $arrRequestData['createDate'] = date('Y-m-d H:i:s a');
                        // $result = $this->User_model->submitReport($arrRequestData);
                        // print_r($arrResult); exit();
                        if ($this->User_model->submitReport($arrRequestData)) {
                            $arrReturn = array(
                                code => 1,
                                data => 'success',
                            );
                            echo json_encode($arrReturn);
                            die;
                        } else {
                            $arrReturn = array(
                                code => -3,
                                data => 'No data found',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    /********API #28********* this functon is to getIssues *********************/
    public function getIssues()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrRequestData['createDate'] = date('Y-m-d H:i:s a');
                $arrResult = $this->User_model->getIssues();
                if (!empty($arrResult)) {
                    $arrReturn = array(
                        code => 1,
                        data => $arrResult,
                    );
                    echo json_encode($arrReturn);
                    die;
                } else {
                    $arrReturn = array(
                        code => -3,
                        data => 'No data found',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    /********API #28********* this functon is to getReceipientList *********************/
    public function getReceipientList()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['userId']) && isset($arrRequestData['addressId']) && isset($arrRequestData['isPublic'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);
                    // print_r($arrRequestData); exit();
                    if ($userStatus[code] == 1) {
                        $arrResult = $this->User_model->getReceipientList($arrRequestData['userId'], $arrRequestData['addressId'], $arrRequestData['isPublic']);
                        // print_r($arrResult); exit();
                        if ($arrResult) {
                            $arrReturn = array(
                                code => 1,
                                data => $arrResult,
                            );
                            echo json_encode($arrReturn);
                            die;
                        } else {
                            $arrReturn = array(
                                code => -3,
                                data => 'No data found',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    /********API #28********* this functon is to getNotificationList *********************/
    public function getNotificationList()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['userId']) && isset($arrRequestData['start']) && isset($arrRequestData['count'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);
                    // print_r($arrRequestData); exit();
                    if ($userStatus[code] == 1) {
                        $arrResult = $this->User_model->getNotificationList($arrRequestData);
                        // print_r($arrResult); exit();
                        if ($arrResult) {
                            $arrReturn = array(
                                code => 1,
                                data => $arrResult,
                            );
                            echo json_encode($arrReturn);
                            die;
                        } else {
                            $arrReturn = array(
                                code => -3,
                                data => 'No data found',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
    /********API #28********* this functon is to getAddressIdWithReferenceCode *********************/
    public function getAddressIdWithReferenceCode()
    {
        try {
            if ($this->getRequestMethod() != "POST") {
                $arrReturn = array(
                    code => -7,
                    data => 'Please check the request method',
                );
                echo json_encode($arrReturn);
                die;
            } else {
                $arrEntityBody = file_get_contents('php://input');
                $arrRequestData = json_decode($arrEntityBody, true);

                if (isset($arrRequestData['userId']) && isset($arrRequestData['addressReferenceId'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);
                    $arrResult = '';
                    if ($userStatus[code] == 1) {
                        $arrRefCode = explode('-', $arrRequestData['addressReferenceId']);
                        // print_r($arrRefCode[0]);exit;
                        if ($arrRefCode[0] == 'PUB') {
                            $arrResult = $this->User_model->getPublicAddressIdWithReferenceCode($arrRequestData['userId'], end($arrRefCode));
                        } else {
                            $arrResult = $this->User_model->getPrivateAddressIdWithReferenceCode($arrRequestData['userId'], end($arrRefCode));
                        }

                        if ($arrResult) {
                            $arrReturn = array(
                                code => 1,
                                data => $arrResult,
                            );
                            echo json_encode($arrReturn);
                            die;
                        } else {
                            $arrReturn = array(
                                code => -3,
                                data => 'No data found',
                            );
                            echo json_encode($arrReturn);
                            die;
                        }
                    } else {
                        echo json_encode($userStatus);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => -6,
                        data => 'All fields not sent',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }
}
