<?php
defined('BASEPATH') or exit('No direct script access allowed');
  ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
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
    
    // public function signUp()
    // {
    //     try {
    //         if ($this->getRequestMethod() != "POST") {
    //             $arrReturn = array(
    //                 code => -7,
    //                 data => 'Please check the request method',
    //             );
    //             echo json_encode($arrReturn);
    //             die;
    //         } else {
    //             $arrEntityBody = file_get_contents('php://input');
    //             $arrRequestData = json_decode($arrEntityBody, true);

    //             if (isset($arrRequestData['profilePicURL']) && isset($arrRequestData['name']) && isset($arrRequestData['userName']) && isset($arrRequestData['emailId']) && isset($arrRequestData['password']) && isset($arrRequestData['contactNumber'])) {
    //                 if ($this->User_model->validateIfEmailExisted($arrRequestData['emailId'])) {
    //                     $arrReturn = array(
    //                         code => -3,
    //                         data => 'Email Id already registered',
    //                     );
    //                     echo json_encode($arrReturn);
    //                     die;
    //                 } else if ($this->User_model->validateIfUserNameExisted(strtolower($arrRequestData['userName']))) {
    //                     $arrReturn = array(
    //                         code => -4,
    //                         data => 'Username already registered',
    //                     );
    //                     echo json_encode($arrReturn);
    //                     die;
    //                 } else if ($this->User_model->validateIfContactNumberExisted($arrRequestData['contactNumber'])) {
    //                     $arrReturn = array(
    //                         code => -6,
    //                         data => 'Contact Number already registered',
    //                     );
    //                     echo json_encode($arrReturn);
    //                     die;
    //                 } else {
    //                     /**********************************************/
    //                     // $temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
    //                     // file_put_contents($temp_file_path, base64_decode($arrRequestData['profilePicURL']));
    //                     // $image_info = getimagesize($temp_file_path);
    //                     // $_FILES['img'] = array(
    //                     //     'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
    //                     //     'tmp_name' => $temp_file_path,
    //                     //     'size'  => filesize($temp_file_path),
    //                     //     'error' => UPLOAD_ERR_OK,
    //                     //     'type'  => $image_info['mime'],
    //                     // );
    //                     USER_UPLOAD_DIR;
    //                     $img = $arrRequestData['profilePicURL'];
    //                     $img = str_replace('data:image/png;base64,', '', $img);
    //                     $img = str_replace(' ', '+', $img);
    //                     $img = str_replace('data:image/jpg;base64,', '', $img);
    //                     $img = str_replace(' ', '+', $img);
    //                     $img = str_replace('data:image/jpeg;base64,', '', $img);
    //                     $img = str_replace(' ', '+', $img);
    //                     $imgdata = base64_decode($img);

    //                     $getMaxId = $this->User_model->getUserMaxId();
    //                     // print_r($getMaxId);exit;
    //                     $maxId = $getMaxId + 1;
    //                     $image = $maxId . '.png';
    //                     $imgUrl = USER_UPLOAD_DIR . $image;
    //                     $success = file_put_contents($imgUrl, $imgdata);
    //                     //print_r($files); exit;
    //                     $arrRequestData['profilePicURL'] = 'user/' . $image;
    //                     $arrRequestData['password'] = md5($arrRequestData['password']);
    //                     // $arrRequestData['referenceCode'] = 'ref code';
    //                     $arrRequestData['referenceCode'] = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 10);
    //                     $arrRequestData['emailVerificationToken'] = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 20);
    //                     $arrRequestData['createDate'] = date("Y-m-d H:i:s");
    //                     $arrRequestData['status'] = 1;

    //                     $result['userId'] = $this->User_model->signUp($arrRequestData);
    //                     /********** Save address ***********/
    //                     $savedAddress['listName'] = 'Default List';
    //                     $savedAddress['userId'] = $result['userId'];
    //                     $savedAddress['createDate'] = date('Y-m-d H:i:s a');
    //                     $savedAddress['isDefault'] = 1;
    //                     $savedAddress['status'] = 1;

    //                     $insertAddressData = $this->User_model->saveUserAddress($savedAddress);
    //                     /*************** Email Data *******************/
    //                     $email_data['email_title'] = 'Registration';
    //                     $email_data['name'] = ucfirst($arrRequestData['name']);
    //                     $email_data['email_id'] = $arrRequestData['emailId'];
    //                     $this->sendEmail($email_data);

    //                     $email_data1['email_title'] = 'Confirmation';
    //                     $email_data1['email_id'] = $arrRequestData['emailId'];
    //                     $email_data1['heading'] = "Hey ". ucfirst($arrRequestData['name']).',';
    //                     $email_data1['message'] = "<div style='padding:10px 30px;'><p style='text-align: center;'>You're almost ready to start enjoying all the capabilities of Simplr Post. Simply click the button below to verify your email address</p></div><div><a href='".BASE_URL."confirm-email/".$arrRequestData['emailVerificationToken']."' style='background-color:#1bac71;color:white;padding:7px 20px;text-decoration:none;border-radius:5px'>Confirm</a></div>";
    //                     $email_data1['footer'] = '<p style="text-align: center;">If you have any questions or concerns please direct them to <a href="mailto:abizerjafferjee@simplrpost.com?Subject=Email%20Confirmation" target="_blank" style="text-decoration:none;color:#1bac71">abizerjafferjee@simplrpost.com</a></p>';
    //                     $this->sendOTPEmail($email_data1);
    //                     /**********************************************/

    //                     $intRandom = mt_rand(100000, 999999);
    //                     $arrOtpData['otp'] = $intRandom;
    //                     $arrOtpData['userId'] = $result['userId'];
    //                     $arrOtpData['createDate'] = date("Y-m-d H:i:s");
    //                     $arrOtpData['otpType'] = 2;
    //                     $otpId = $this->User_model->saveOtp($arrOtpData);

    //                     // $message = $arrRequestData['name'] . " your OTP is $intRandom";
    //                     $welcomeMessage = "Hey,";
    //                     $welcomeMessage .= "Thank you for signing up with Simplr Post. Let’s get your home or business and set up with an address that you can easily share with others.";
    //                     $message = "Hi, you recently sent a request for a one time verification code for Simplr Post. Here it is: $intRandom";

    //                     // $msg = str_replace(' ', '%20', $msg);
    //                     // $baseURL = "https://api.budgetsms.net/sendsms/?username=" . SMS_USERNAME . "&handle=" . SMS_HANDLE . "&userid=" . SMS_USERID . "&%20msg=" . $msg . "&from=simplrPost&to=" . str_replace('+', '', $arrRequestData['contactNumber']);
    //                     // file_get_contents($baseURL);
    //                     $result['otpId'] = $otpId['otpId'];
    //                     try{
    //                         $this->africastalking->sendMessage($arrRequestData['contactNumber'], $welcomeMessage);
    //                         $this->africastalking->sendMessage($arrRequestData['contactNumber'], $message);
    //                     } catch(Exception $e){
    //                         // print("Africas talking Error ");
    //                     } finally{
    //                         if ($result) {
    //                             $arrReturn = array(
    //                                 code => 1,
    //                                 data => $result,
    //                             );
    //                             echo json_encode($arrReturn);
    //                             die;
    //                         } else {
    //                             $arrReturn = array(
    //                                 code => -2,
    //                                 data => 'something went wrong',
    //                             );
    //                             echo json_encode($arrReturn);
    //                             die;
    //                         }
    //                     }
    //                 }
    //             } else {
    //                 $arrReturn = array(
    //                     code => -6,
    //                     data => 'All fields not send',
    //                 );
    //                 echo json_encode($arrReturn);
    //                 die;
    //             }
    //         }
    //     } catch (Exception $e) {
    //         echo 'Received exception : ', $e->getMessage(), "\n";
    //     }
    // }
   
/* http://103.15.67.74/pro1/simplrpost/Api/User/signUp */
    
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
                $arrRequestData['contactNumber'] = $this->input->post('contactNumber');
               if ($this->User_model->validateIfContactNumberExisted($arrRequestData['contactNumber'])) {
                        $arrReturn = array(
                            code => '-6.0',
                            data => 'Contact Number already registered',
                        );
                        echo json_encode($arrReturn);
                        die;
                    } else { 

                        $arrRequestData['password'] = md5($this->input->post('password'));
                        $arrRequestData['createDate'] = date("Y-m-d H:i:s");
                        $arrRequestData['status'] = 0;


                        $result['userId'] = $this->User_model->signUp($arrRequestData);
                        $result['contactNumber'] = $arrRequestData['contactNumber'];
                        $contactNumber = $result['contactNumber'];
                        // print_r($result);die();
                        $sendOtpResponse = $this->send_otp($contactNumber,2);
                        if($sendOtpResponse){
                             $result['otpData'] = $sendOtpResponse;
                         }else{
                            $result['otpData']=[];
                         }
                       

                        if($sendOtpResponse['status'] == 0 && $sendOtpResponse['message'] == 'Invalid Mobile Number')
                        {
                            $this->User_model->update_data('user', array('status'=>'-1'), array('userId'=>$result['userId']));
                            $arrReturn = array(
                                code => '0',
                                data => 'Invalid Mobile Number',
                            );
                        }else{
                            $arrReturn = array(
                                code => '1.0',
                                data => 'Data saved ',
                                'response' => $result
                            );

                        }

                        echo json_encode($arrReturn);
                        die;
                    }
                }
        } catch (Exception $e) {
            echo 'Received exception : ', $e->getMessage(), "\n";
        }
    }

    public function resend_otp()
    {
        $contactNumber = $this->input->post('contactNumber');
        $type = $this->input->post('type');
        $where = array('contactNumber'=>$contactNumber);
        $a = $this->User_model->getNumRows('user',$where);
        if($a>0){
            $result['otpData'] = $this->send_otp($contactNumber,$type);

            $arrReturn = array(
                code => 1,
                data => 'Data saved ',
                'response' => $result,
            );
            echo json_encode($arrReturn);
            die;
        }else{
            $arrReturn = array(
                code => 0,
                data => 'Contact number not registerd ',
            );
            echo json_encode($arrReturn);
            die;
        }
        
    }



    public function send_otp($contactNumber = '',$type)
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

        $contactNumber = $this->input->post('contactNumber');
        if (!empty($contactNumber)){
            $result = $this->User_model->getSingleRow('user',['contactNumber'=>$contactNumber]);
            $otp = mt_rand(1000,9999);

            require './vendor/autoload.php';

            // Test

            // $sid = ""; // Your Account SID from www.twilio.com/console
            // $token = ""; // Your Auth Token from www.twilio.com/console

            $client = new Twilio\Rest\Client($sid, $token);

            try {
                //check for "example" in mail address
               $message = $client->messages->create(
                  $contactNumber, // Text this number
                  array(
                    'from' => '+13239401533', // From a valid Twilio number
                    'body' => $otp
                  )
                );

                $arrOtpData['userId'] = $result->userId;
                $arrOtpData['otp'] = $otp;
                $arrOtpData['createDate'] = date("Y-m-d H:i:s");
                $arrOtpData['otpType'] = $type;

                  $where =  array('userId'=>$result->userId,'otpType'=>$type);
                  $existOtp = $this->db->where($where)->get('otp')->row_array();
                if ($existOtp) {
                    $arrOtpData['isUsed'] =0;
                    $this->User_model->update_data('otp', $arrOtpData, array('userId'=>$result->userId,'otpType'=>$type));
                    $otpId =  $this->db->where(array('userId'=>$result->userId,'otpType'=>$type))->get('otp')->row_array();;
                }else{
                    $otpId = $this->User_model->saveOtp($arrOtpData);
                }

                // $arrOtpData['userId'] = $result->userId;
                // $arrOtpData['otp'] = $otp;
                // $arrOtpData['createDate'] = date("Y-m-d H:i:s");
                // $arrOtpData['otpType'] = $type;
                // $otpId = $this->User_model->saveOtp($arrOtpData);
                
                if ($otpId){
                    $otpId['type'] = $arrOtpData['otpType'];
                    // echo "working";
                    $res['status'] = 1;
                    $res['message'] = 'OTP send successfully';
                    $res['data'] = $otpId;
                    // return $res;
                }else{
                    // echo "not working";
                   $res['status'] = 0;
                    $res['message'] = 'OTP sending failed !'; 
                    // return $res;
                }
                // if(!is_array($message) && !is_object($message))
                // {
                //     $res['status'] = 0;
                //     $res['message'] = 'Invalid Mobile Number';
                // }
              }
              catch(Exception $e) {
               // print_r($e);
                 $res['status'] = 0;
                 $res['message'] = 'Invalid Mobile Number';
                //re-throw exception
                // throw new customException($email);
              }

            

          
       }else{
        // echo "contact no. empty";
        $res['status'] = 0;
        $res['message'] = 'Contact number can not be empty';
        // return $res;
       }
       // exit(json_encode($res));
       return $res;

    }


    // public function send_otp($contactNumber)
    // {
    //  $contactNumber = $this->input->post('contactNumber');   
    //     if (!empty($contactNumber)) {
    //           echo "working";
    //       }  else{
    //         echo "string";die();
    //       }
    // }


    //copy of Login->otpValidation
    public function verify_otp()
    {
        $arrRequestData['otpId'] = $this->input->post('otpId');
        $arrRequestData['otp'] = $this->input->post('otp');
        $arrRequestData['type'] = $this->input->post('type');
        if (isset($arrRequestData['otpId']) && isset($arrRequestData['otp'])) {
            $arrResult = $this->User_model->validateOtp($arrRequestData);
      
            $user = $this->db->where('otpId',$arrRequestData['otpId'])->get('otp')->row_array();
            $userId = $user['userId'];
            if ($arrResult) {
                $this->User_model->update_data('user', array('status'=>'1'), array('userId'=>$userId));
                if ($arrResult['isUsed'] == 0) {
                    $this->User_model->expireOtp($arrRequestData['otpId']);
                    if ($arrResult['otpType'] == 0) { 
                        $arrReturn = array(
                            code => 1,
                            data => $arrResult,
                        );
                        echo json_encode($arrReturn);
                        die;
                    } else {
                        // $this->verifyContactNumber($arrRequestData['otpId']);
                        $arrReturn = array(
                            code => 1,
                            data => 'Success',
                            'response' =>$arrResult,
                        );
                        echo json_encode($arrReturn);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => '-4.0',
                        data => 'This otp has expired',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            } else {
                $arrReturn = array(
                    code => '-3.0',
                    data => 'OTP did not matched',
                );
                echo json_encode($arrReturn);
                die;
            }
        }else {
            $arrReturn = array(
                code => '-6.0',
                data => 'All fields not send',
            );
            echo json_encode($arrReturn);
            die;
        }

    }


    public function verify_email_otp()
    {
        $arrRequestData['otpId'] = $this->input->post('otpId');
        $arrRequestData['otp'] = $this->input->post('otp');
        $arrRequestData['type'] = $this->input->post('type');
        if (isset($arrRequestData['otpId']) && isset($arrRequestData['otp'])) {
            $arrResult = $this->User_model->validateOtp($arrRequestData);
            
            $userId = $arrResult['userId'];

            if ($arrResult) {
                if ($arrResult['isUsed'] == 0) {
                    $this->User_model->expireOtp($arrRequestData['otpId']);
                    if ($arrResult['otpType'] == 0) { 
                        $arrReturn = array(
                            code => 1,
                            data => $arrResult,
                        );
                        echo json_encode($arrReturn);
                        die;
                    } else {
                        $data = array('isEmailIdVerified' => 1);
                        $where = array('userId' => $userId);
                        $this->User_model->update_data('user', $data, $where);

                        $whereIs = array('userId'=>$userId);
                        $userData = $this->User_model->getSingleRow('user',$whereIs);
                        $name = $userData->name;
                        $emailId = $userData->emailId;

                        $html = "<p style='font-size: 18px;color:#000;margin-bottom:10px;'>Hello ".$name.",</p>
                        <p style='color : #000;font-size: 14px;'>Thankyou, Your email is verified.</p> <br>";

                        $email_data['message'] = $html;
                        $email_data['heading'] = '';
                        $email_data['footer'] = '';

                        $html = $this->load->view('email/otpEmailTemplate',$email_data,TRUE);

                        sendEmailTemplate($emailId, 'Email confirmed', $html);


                        $arrReturn = array(
                            code => 1,
                            data => 'Success',
                            'response' =>$arrResult,
                        );
                        echo json_encode($arrReturn);
                        die;
                    }
                } else {
                    $arrReturn = array(
                        code => '-4.0',
                        data => 'This session has expired',
                    );
                    echo json_encode($arrReturn);
                    die;
                }
            } else {
                $arrReturn = array(
                    code => '-3.0',
                    data => 'OTP did not matched',
                );
                echo json_encode($arrReturn);
                die;
            }
        }else {
            $arrReturn = array(
                code => '-6.0',
                data => 'All fields not send',
            );
            echo json_encode($arrReturn);
            die;
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
                            $email_data['name'] = ucfirst($arrResult['name']);
                            $email_data['email_id'] = $arrRequestData['emailId'];
                            $email_data['heading'] = "Hey ". ucfirst($arrRequestData['name']).',';
                            $email_data['message'] = "<div style='padding:10px 30px;'><p style='text-align: center;'>You're almost ready to start enjoying all the capabilities of Simplr Post. Simply click the button below to verify your email address</p></div><div><a href='".BASE_URL."confirm-email/".$emailVerificationToken."' style='background-color:#1bac71;color:white;padding:7px 20px;text-decoration:none;border-radius:5px'>Confirm</a></div>";
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
                            try{
                                $this->africastalking->sendMessage($arrRequestData['contactNumber'], $message);
                            } catch(Exception $e){
                                // print("Africas talking Error ");
                            } finally{
                                $arrReturn = array(
                                    code => 1,
                                    data => $otpId,
                                );
                                echo json_encode($arrReturn);
                                die;
                            }
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
        // print_r($contactNumberVerified);

        if ($contactNumberVerified > 0) {
            $arrReturn = array(
                code => 1,
                data => 'Success',
            );
        // print_r($arrReturn);
            echo json_encode($arrReturn);
            die;
        }
    }
    /*******API #3******* Function for signIn *************/
    public function validateEmailUserName($email)
    {
        return (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email));
    }
    // public function signIn()
    // {
    //     try {
    //         if ($this->getRequestMethod() != "POST") {
    //             $arrReturn = array(
    //                 code => -7,
    //                 data => 'Please check the request method',
    //             );
    //             echo json_encode($arrReturn);
    //             die;
    //         } else {
    //             $arrEntityBody = file_get_contents('php://input');
    //             $arrRequestData = json_decode($arrEntityBody, true);
    //             $email = $arrRequestData['emailUserName'];

    //             if (isset($arrRequestData['emailUserName']) && isset($arrRequestData['password'])) {
    //                 if ($this->validateEmailUserName($arrRequestData['emailUserName'])) {
    //                     if ($this->User_model->validateIfEmailExisted($arrRequestData['emailUserName'])) {
    //                         // print_r(md5($arrRequestData['password']));exit;
    //                         $arrResult = $this->User_model->signInWithEmail($arrRequestData['emailUserName'], md5($arrRequestData['password']));
    //                         $this->logInAfterValidateEmailUserName($arrResult);
    //                     } else {
    //                         $arrReturn = array(
    //                             code => -4,
    //                             data => 'This account does not exist',
    //                         );
    //                         echo json_encode($arrReturn);
    //                         die;
    //                     }
    //                 } else {
    //                     if ($this->User_model->validateIfUserNameExisted($arrRequestData['emailUserName'])) {
    //                         // print_r(md5($arrRequestData['password']));exit;
    //                         $arrResult = $this->User_model->signInWithUserName($arrRequestData['emailUserName'], md5($arrRequestData['password']));
    //                         $this->logInAfterValidateEmailUserName($arrResult);
    //                     } else {
    //                         $arrReturn = array(
    //                             code => -4,
    //                             data => 'This account does not exist',
    //                         );
    //                         echo json_encode($arrReturn);
    //                         die;
    //                     }
    //                 }
    //             } else {
    //                 $arrReturn = array(
    //                     code => -6,
    //                     data => 'All fields not send',
    //                 );
    //                 echo json_encode($arrReturn);
    //                 die;
    //             }
    //         }
    //     } catch (Exception $e) {
    //         echo 'Received exception : ', $e->getMessage(), "\n";
    //     }
    // }

/* http://103.15.67.74/pro1/simplrpost/Api/User/signIn */

    public function signIn()
    {
       $contactNumber = $this->input->post('contactNumber');
       $password = md5($this->input->post('password'));
       
       $whereIs = array('contactNumber'=>$contactNumber);
       $checkVerifyStatus = $user = $this->db->where('contactNumber',$contactNumber)->get('user')->row_array();

        if($checkVerifyStatus && $checkVerifyStatus['status']==0){
            $res['status'] = 0;
            $res['message'] = 'Please verify account first.';
             exit(json_encode($res));
        }
        
       $validateUser = $this->User_model->getNumRows('user',$whereIs);
       if ($validateUser > 0) {
          if($this->input->post('login_type')=='otp'){
          $otpResponse = $this->send_otp($contactNumber,4);
             exit(json_encode($otpResponse));
             die;
        }else{
        $where = array('contactNumber'=>$contactNumber,'password'=>$password);
        $resp = $this->User_model->getNumRows('user',$where);
       
           if($resp > 0){
                $userData = $this->User_model->getSingleRow('user',['contactNumber'=>$contactNumber]);
                $res['status'] = 1;
                $res['message'] = 'Result found succesfully ';
                $res['data'] = $userData; 
           }else{
                $res['status'] = 0;
                $res['message'] = 'Invalid credentials !';
           }
       }
        }else
        {
            $res['status'] = 0;
            $res['message'] = 'Mobile number is not registered !';
        }
           exit(json_encode($res));
        }


    // public function signInWithOtp()
    // {
    //     $contactNumber = $this->input->post('contactNumber');
    //     $result['otpData'] = $this->send_otp($contactNumber);
    // }


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
    public function forgot_password_email()
    {



      

        $emailId  = $this->input->post('emailId');
        $where = array('emailId' => $emailId);

        $isEmailNum = $this->User_model->getSingleRow('user',$where);
        // print_r($isEmailNum->$isEmailNum);die();
        if (!empty($isEmailNum)) {

            $otp = mt_rand(1000,9999);
            $html = "<p style='font-size: 18px;color:#000;margin-bottom:10px;'>Hello ".$isEmailNum->name.",</p>
            <p style='color : #000;font-size: 14px;'>We have received request for reset password, Use this OTP to reset your password.</p> <br>

            <center style='color : #2dce89;font-size: 24px;'><h3><b>OTP</b> : ".$otp."</h3> </center>";

            $email_data['message'] = $html;
            $email_data['heading'] = '';
            $email_data['footer'] = '';

            $html = $this->load->view('email/otpEmailTemplate',$email_data,TRUE);

            sendEmailTemplate($emailId, 'Forget Password', $html);

            $insertData['otp'] = $otp;
            $insertData['otpType'] = 1;
            $insertData['userId'] = $isEmailNum->userId;
            $insertData['createDate'] = date('Y-m-d h:i:s');
            $insertData['isUsed'] = 0;
            
            $insert_id = $this->User_model->saveOtp($insertData);



            $arrReturn = array(
                code => 1,
                data => 'Success',
                'response' => ['mobile'=>$isEmailNum->contactNumber, 'userid'=>$isEmailNum->userId, 'email'=>$emailId, 'otpid'=>$insert_id['otpId'],'type'=>1]
            );
            echo json_encode($arrReturn);
            die;
        }else{
            // echo "email not found !";
            $arrReturn = array(
                code => 0,
                data => 'Failed',
            );
            echo json_encode($arrReturn);
            die;
        }

    }



    public function forgot_password_phone()
    {

        $contactNumber  = $this->input->post('contactNumber');
        $where = array('contactNumber' => $contactNumber);
        $isPhoneNum = $this->User_model->getNumRows('user',$where);
        if ($isPhoneNum > 0) {

            $result['data'] = $this->send_otp($contactNumber,1);
            $whereIs = array('contactNumber' => $contactNumber);
            $userData= $this->User_model->getSingleRow('user',$whereIs);
            $result['userId'] = $userData->userId; 
            
            $res['status'] = 1;
            $res['message'] = 'Result found succesfully ';
            $res['data'] = $result; 
        }else{
            // echo "email not found !";
           $res['status'] = 0;
           $res['message'] = 'Mobile number Not registered, please register first!';
        }
        exit(json_encode($res));

    }


    public function get_security_question()
    {
        $userId  = $this->input->post('userId');
        $where = array('userId' => $userId);
        $userData= $this->User_model->getSingleRow('user',$where);
        $result['security_question'] =  $userData->security_question;
        if ($userData) {
            $res['status'] = 1;
            $res['message'] = 'Result found succesfully ';
            $res['data'] = $result; 
        }else{
           $res['status'] = 0;
           $res['message'] = 'Failed !';
        }
        exit(json_encode($res));

    }


    public function verify_security_answer()
    {
        $userId  = $this->input->post('userId');
        $security_answer  = $this->input->post('security_answer');

        $where = array('userId' => $userId, 'security_answer'=>$security_answer);
        $isAnsNum = $this->User_model->getNumRows('user',$where);
        
        $whereIs = array('userId' => $userId);
        $userData= $this->User_model->getSingleRow('user',$whereIs);
        $ans = $userData->security_answer;

        if ($isAnsNum) {
                $res['status'] = 1;
                $res['message'] = 'Matched';        
        }elseif (empty($ans)) {
           $res['status'] = 0;
           $res['message'] = 'Unavailable!';
        }
        else{
           $res['status'] = 2;
           $res['message'] = 'Not matched !';
        }
       
        
        exit(json_encode($res));
    }

    public function change_password()
    {
        $userId = $this->input->post('userId');
        $password = md5($this->input->post('password'));

        $data['password'] = $password;
        $whereIs = array('userId'=>$userId);
        $resp = $this->User_model->update_data('user',$data, $whereIs);
        if ($resp) {
            $res['status'] = 1;
            $res['message'] = 'Password changed succesfully';
        }else{
            $res['status'] = 0;
            $res['message'] = 'Failed !';
        }
        
        exit(json_encode($res));
    }





    // public function forgotPassword()
    // {
    //     try {
    //         if ($this->getRequestMethod() != "POST") {
    //             $arrReturn = array(
    //                 code => -7,
    //                 data => 'Please check the request method',
    //             );
    //             echo json_encode($arrReturn);
    //             die;
    //         } else {
    //             $arrEntityBody = file_get_contents('php://input');
    //             $arrRequestData = json_decode($arrEntityBody, true);
    //             $arrRequestData['function'] = 'insert';

    //             $intRandom = mt_rand(100000, 999999);

    //             if (($arrRequestData['isEmailUsed'] == 1)) {
    //                 $arrResult = $this->User_model->getValuesWithEmailId($arrRequestData['emailId']);
    //                 $this->processAfterCheckIsUsedEmailUsed($arrResult, $arrRequestData, $intRandom);
    //             } else if (($arrRequestData['isEmailUsed'] == 0)) {
    //                 $arrResult = $this->User_model->getValuesWithContactNumber($arrRequestData['contactNumber']);
    //                 $this->sendOTPToNumber($arrResult, $arrRequestData, $intRandom);

    //             } else {
    //                 $arrReturn = array(
    //                     code => -6,
    //                     data => 'All fields not send',
    //                 );
    //                 echo json_encode($arrReturn);
    //                 die;
    //             }
    //         }
    //     } catch (Exception $e) {
    //         echo 'Received exception : ', $e->getMessage(), "\n";
    //     }
    // }

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
                try{
                    $this->africastalking->sendMessage($arrRequestData['contactNumber'], $message);
                } catch(Exception $e){
                    // print("Africas talking Error ");
                } finally{
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
                    $to = $arrRequestData['emailId'];

                    $arrOtpData['otp'] = $intRandom;
                    $arrOtpData['userId'] = $arrResult['userId'];
                    $arrOtpData['createDate'] = date("Y-m-d H:i:s");

                    // Sending email
                    $emailData['heading'] = "Hey ". ucfirst($arrResult['name']).',';
                    $emailData['message'] = "<div style='padding:10px 30px;'><p style='text-align: center;'>Seems like you forgot your password for Simplr Post. If this is true, your OTP is - $intRandom</p><p>If you didn't forget your password safely ignore this</p></div>";
                    $emailData['footer'] = '<p style="text-align: center;">If you have any questions or concerns please direct them to <a href="mailto:abizerjafferjee@simplrpost.com?Subject=Forgot%20Password" target="_blank" style="text-decoration:none;color:#1bac71">abizerjafferjee@simplrpost.com</a></p>';
                    // ob_start();
                    // $mail = new PHPMailer;
                    // $mail->SMTPDebug = '';
                    // $mail->IsSMTP();

                    // $mail->Host = 'relay-hosting.secureserver.net';
                    // $mail->Port = 25;
                    // $mail->SMTPAuth = false;
                    // $mail->From = 'davinder.codeapex@gmail.com';
                    // $mail->FromName = 'Simplr Post';
                    // $mail->AddAddress($arrRequestData['emailId'], $arrResult['name']);

                    // $mail->Subject = 'One Time Password(OTP) for account verification, Simplr Post ';
                    // $mail->Body = $this->load->view('email/otpEmailTemplate', $emailData, true);
                    // $mail->AltBody = 'Simplr Post';
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
                    $mail->AddAddress($to, $arrResult['name']);

                    $mail->Subject = 'One Time Password(OTP) for account verification, Simplr Post ';
                    $mail->Body = $this->load->view('email/otpEmailTemplate',$emailData,TRUE);
                    $mail->AltBody = BODY_TITLE;
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
                            if ($arrResult['otpType'] == 0) {
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

                $intRandom = mt_rand(100000, 999999);
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
                // $arrRequestData = $_POST;

                if (isset($arrRequestData['userId'])) {
                    $arrResult = $this->checkUserStatus($arrRequestData['userId']);
                    if ($arrResult[code] == 1) {
                        $arrResult = $this->User_model->getUserDetailWIthUserId($arrRequestData['userId']);
                       
                        if ($arrResult) {

                            $query = $this->db->from('user');
                            $query->where(['userId'=>$arrResult['userId']]);
                            $user = $query->get()->result_array();
                            $arrResult['profilePicURL'] = (!empty($arrResult['profilePicURL']))?$arrResult['profilePicURL']:'user/user_default_image.jpeg';
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
    public function resend_email_otp()
    {
        $userId = $this->input->post('userId');
        $where = array('userId' => $userId);
        $userInfo = $this->User_model->getSingleRow('user',$where);

        // print_r($userInfo->emailId);die();
        $otp = mt_rand(1000,9999);
        $html = "<p style='font-size: 18px;color:#000;margin-bottom:10px;'>Hello ".$userInfo->name.",</p>
        <p style='color : #000;font-size: 14px;'>We have received request for email verification, Use this OTP to verify your account.</p> <br>
        <center style='color : #2dce89;font-size: 24px;'><h3><b>OTP</b> : ".$otp."</h3> </center>";

        $email_data['message'] = $html;
        $email_data['heading'] = '';
        $email_data['footer'] = '';

        $html = $this->load->view('email/otpEmailTemplate',$email_data,TRUE);
        sendEmailTemplate($userInfo->emailId, 'Email Verification', $html);

        $arrOtpData['userId'] = $userId;
        $arrOtpData['otp'] = $otp;
        $arrOtpData['createDate'] = date("Y-m-d H:i:s");
        $arrOtpData['otpType'] = 3;
        $otpIdInfo = $this->User_model->saveOtp($arrOtpData);

        if ($otpIdInfo) {
            $arrReturn = array(
                code => 1,
                data => 'Success',
                'response' =>$otpIdInfo,
            );
            echo json_encode($arrReturn);
            die;
        }else{
            $arrReturn = array(
                code => 1,
                data => 'Failed !',
            );
            echo json_encode($arrReturn);
            die;
        }


    }




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

                $arrRequestData['name'] = $this->input->post('name');
                $arrRequestData['userId'] = $this->input->post('userId');
                $arrRequestData['emailId'] = $this->input->post('emailId');
                $arrRequestData['contactNumber'] = $this->input->post('contactNumber');
                $arrRequestData['security_question'] = $this->input->post('security_question');
                $arrRequestData['security_answer'] = $this->input->post('security_answer');
                @$arrRequestData['profilePicURL'] = $_FILES['profilePicURL'];

                // $where = array('emailId'=>$arrRequestData['emailId']);
                // $checkExistUserByemail = $this->User_model->getSingleRow('user',$where);
                $this->db->from('user');
                $this->db->where('emailId',$arrRequestData['emailId']);
                $this->db->where('userId !=', $arrRequestData['userId']);
                $checkExistUserByemail = $this->db->get()->row();

                if($checkExistUserByemail){
                
                       $arrReturn = array(
                        'resultCode' => 0,
                        'data' => 'Email already taken by another user.',
                        );
                        echo json_encode($arrReturn);
                        die;
                }else{
                   $this->db->from('user');
                   $this->db->where('emailId',$arrRequestData['emailId']);
                   $this->db->where('userId', $arrRequestData['userId']);
                   $checkSelfEmail = $this->db->get()->row();
                   if(!$checkSelfEmail){
                     $this->User_model->update_data('user', array('isEmailIdVerified'=>0), array('userId'=>$arrRequestData['userId']));
                   }

                }
                $where = array('userId'=>$arrRequestData['userId']);
                $userResp = $this->User_model->getSingleRow('user',$where);

                if($userResp->isEmailIdVerified == 0){   
                    $otp = mt_rand(1000,9999);

                    $html = "<p style='font-size: 18px;color:#000;margin-bottom:10px;'>Hello ".$userResp->name.",</p>
                    <p style='color : #000;font-size: 14px;'>We have received request for email verification, Use this OTP to verify your account.</p> <br>
                    <center style='color : #2dce89;font-size: 24px;'><h3><b>OTP</b> : ".$otp."</h3> </center>";

                    $email_data['message'] = $html;
                    $email_data['heading'] = '';
                    $email_data['footer'] = '';

                    $html = $this->load->view('email/otpEmailTemplate',$email_data,TRUE);
                    sendEmailTemplate($arrRequestData['emailId'], 'Email Verification', $html);

                    $arrOtpData['userId'] = $arrRequestData['userId'];
                    $arrOtpData['otp'] = $otp;
                    $arrOtpData['createDate'] = date("Y-m-d H:i:s");
                    $arrOtpData['otpType'] = 3;
                    $otpIdInfo = $this->User_model->saveOtp($arrOtpData);
                     //print_r($otpIdInfo); die();
                }
                else{
                    $otpIdInfo = ['otpId'=>0];
                }

                if (isset($arrRequestData['name']) && isset($arrRequestData['userId']) && isset($arrRequestData['emailId']) && isset($arrRequestData['contactNumber']) && isset($arrRequestData['security_question']) && isset($arrRequestData['security_answer'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);

                    // print_r($userStatus);die();

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
                            

                            if(!empty($_FILES['profilePicURL'])){

                            if($_FILES['profilePicURL']['size'] > 5000000)
                            {
                                echo json_encode(array('responseCode' => 0, 'msg' => 'maximum file size 5mb'));
                                die();
                            }
                            $config['upload_path']          = './uploads/user';
                            $config['allowed_types']        = '*';
                            $config['max_size']             = 5000;
                            $config['max_width']            = 10240;
                            $config['max_height']           = 7680;
                            $this->load->library('upload', $config);
                                if(!$this->upload->do_upload('profilePicURL'))
                                {
                                    $profile_pic_error = $this->upload->display_errors();
                                    echo json_encode(array('responseCode' => 'false', 'msg' => $profile_pic_error));
                                    die();
                                    $this->response = array('responseCode' => 'false', 'msg' => $profile_pic_error);
                                    return true;
                                }
                                else{
                                    $upload_data = $this->upload->data();         
                                    $profile_pic_name = $upload_data['file_name'];    
                                    $image = $profile_pic_name;
                                    $arrRequestData['profilePicURL'] = 'user/' . $image;
                                    // $otpId['otpId'] = $result['otpId'];
                                    $otpId['otpId'] = $otpIdInfo;
                                    $otpId['isEmailVerified'] = $result['isEmailIdVerified'];
                                    // echo "image";
                                    // print_r($arrRequestData); die();
                                    if ($this->User_model->updateUserDetails($arrRequestData)) {
                                        $arrReturn = array(
                                            code => 1,
                                            data => $otpId,
                                        );
                                        echo json_encode($arrReturn);
                                        die;
                                    }
                                }
                            }
                            else
                            {
                                
                                $saveData['name'] = $arrRequestData['name'];
                                $saveData['userId'] = $arrRequestData['userId'];
                                $saveData['emailId'] = $arrRequestData['emailId'];
                                $saveData['contactNumber'] = $arrRequestData['contactNumber'];
                                $saveData['security_question'] = $arrRequestData['security_question'];
                                $saveData['security_answer'] = $arrRequestData['security_answer'];
                                $saveData['isEmailIdVerified'] = $arrRequestData['isEmailIdVerified'];
                                $saveData['isContactNumberVerified'] = $arrRequestData['isContactNumberVerified'];
                                $saveData['profilePicURL'] = $arrRequestData['profilePicURL'];

                                $otpId['otpId'] = $otpIdInfo;
                                $otpId['isEmailVerified'] = $result['isEmailIdVerified'];

                                if ($this->User_model->updateUserDetails($saveData)) {
                                        $arrReturn = array(
                                            code => 1,
                                            data => $otpId,
                                        );
                                        echo json_encode($arrReturn);
                                        die;
                                    }
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
            // if (strtolower($arrRequestData['userName']) != strtolower($arrResult['userName'])) {
            //     if ($this->User_model->validateIfUserNameExisted($arrRequestData['userName'])) {
            //         $arrReturn = array(
            //             code => -4,
            //             data => 'User Name already registered',
            //         );
            //         return $arrReturn;
            //     }
            // }
            // if (strtolower($arrRequestData['emailId']) != strtolower($arrResult['emailId'])) {
            //     if ($this->User_model->validateIfEmailExisted($arrRequestData['emailId'])) {
            //         $arrReturn = array(
            //             code => -3,
            //             data => 'Email Id already registered',
            //         );
            //         return $arrReturn;
            //     } else {
            //         $arrReturn['isEmailIdVerified'] = 0;
            //         /*************** Email Data *******************/
            //         $email_data['email_title'] = 'Confirm Email Id';
            //         $email_data['name'] = ucfirst($arrRequestData['name']);
            //         $email_data['email_id'] = $arrRequestData['emailId'];
            //         $email_data['message'] = "You have to confirm your email id for better user experience.<br>";
            //         $email_data['message'] .= "Click on the link below to confirm your email id.<br>";
            //         $email_data['message'] .= BASE_URL . "confirm-email/" . $data['emailVerificationToken'];
            //         $email_data['view_url'] = 'email/emailTemplate';
            //         $this->sendOTPemail($email_data);
            //         /**********************************************/
            //     }
            // }
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
                    $intRandom = mt_rand(100000, 999999);
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
    // public function changePassword()
    // {
    //     try {
    //         if ($this->getRequestMethod() != "POST") {
    //             $arrReturn = array(
    //                 code => -7,
    //                 data => 'Please check the request method',
    //             );
    //             echo json_encode($arrReturn);
    //             die;
    //         } else {
    //             $arrEntityBody = file_get_contents('php://input');
    //             $arrRequestData = json_decode($arrEntityBody, true);

    //             if (isset($arrRequestData['userId']) && isset($arrRequestData['currentPassword']) && isset($arrRequestData['newPassword'])) {
    //                 $userStatus = $this->checkUserStatus($arrRequestData['userId']);
    //                 $arrRequestData['currentPassword'] = md5($arrRequestData['currentPassword']);
    //                 $arrRequestData['newPassword'] = md5($arrRequestData['newPassword']);
    //                 // print_r($arrRequestData); exit();
    //                 if ($userStatus[code] == 1) {
    //                     $arrResult = $this->User_model->updatePassword($arrRequestData);
    //                     // print_r($arrResult); exit();
    //                     if ($arrResult) {
    //                         $arrReturn = array(
    //                             code => 1,
    //                             data => 'success',
    //                         );
    //                         echo json_encode($arrReturn);
    //                         die;
    //                     } else {
    //                         $arrReturn = array(
    //                             code => -3,
    //                             data => 'current password didn\'t matched',
    //                         );
    //                         echo json_encode($arrReturn);
    //                         die;
    //                     }
    //                 } else {
    //                     echo json_encode($userStatus);
    //                     die;
    //                 }
    //             } else {
    //                 $arrReturn = array(
    //                     code => -6,
    //                     data => 'All fields not sent',
    //                 );
    //                 echo json_encode($arrReturn);
    //                 die;
    //             }
    //         }
    //     } catch (Exception $e) {
    //         echo 'Received exception : ', $e->getMessage(), "\n";
    //     }
    // }

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
                // $arrRequestData = json_decode($arrEntityBody, true);
                $arrRequestData = $this->input->post();
                // echo "<pre>";
                // print_r($arrRequestData);die;
                if (isset($arrRequestData['userId']) && isset($arrRequestData['addressId']) && isset($arrRequestData['isPublic'])) {
                    $userStatus = $this->checkUserStatus($arrRequestData['userId']);
                    // print_r($arrRequestData); exit();
                    if ($userStatus[code] == 1) {
                        $arrResult = $this->User_model->getReceipientList($arrRequestData['userId'], $arrRequestData['addressId'], $arrRequestData['isPublic']);

                        // print_r($arrResult); exit();
                        if ($arrResult) {

                            if(!empty($arrResult['publicAddresses'])){
                                $all = [];
                               foreach ($arrResult['publicAddresses'] as $key => $value) {
                                    $data = (array)$value;
                                   // echo "<pre>";
                                   // print_r($data);die;
                                 
                                    $data['street_image'] = (!empty($data['street_image']))?$data['street_image']:'uploads/address/address_default_image.png';
                                    $data['building_image'] = (!empty($data['building_image']))?$data['building_image']:'uploads/address/address_default_image.png';
                                    $data['entrance_image'] = (!empty($data['entrance_image']))?$data['entrance_image']:'uploads/address/address_default_image.png';
                                $all[] = $data;
                                }
                                $arrResult['publicAddresses'] = $all;
                            }
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

/*users list*/
   public function users(){
    
        try {
            
            // /*******************************/

            $arrResult = $this->User_model->getUsers();
            // print_r($arrResult);exit;
            if (!empty($arrResult)) {
                $return = array(
                    'resultCode' => 1,
                    'resultData' => $arrResult
                );

            } else {
                $return = array(
                    'resultCode' => -3,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }
    //yunush deep linking function 
   /* function redirectLink($param=''){
        $param =  trim($param);
        if(empty($param)){
            $param = 'sha';
        }
       $this->load->view('deep-link', array('param'=>$param));
    }*/
    /*
    yunush global search functionlity
    */
    function privateGlobalSearch(){

        try {

             $data = $this->input->post();
             $searchString = $data['search'];
              $baseUrl = base_url();
             if(strpos($searchString, $baseUrl) !== false){
                $searchStringUrl = "pub.unique_link = '$searchString'";
             }else{
                 // $uniquLink = $baseUrl.$searchString;
             	 // $searchStringUrl = "pub.unique_link = '$uniquLink'";
             	  $searchStringUrl = "pub.unique_link LIKE '%".$searchString."%'";
             }
             $SQL = "SELECT pub.* FROM user LEFT JOIN privateAddresses pub ON pub.userId = user.userId WHERE pub.userId > 0 AND ( user.name LIKE '%".$searchString."%'  OR user.contactNumber LIKE '%".$searchString."%'  OR ".$searchStringUrl." OR pub.street_name LIKE '%".$searchString."%'  OR pub.plusCode = '".$searchString."')";
             $query = $this->db->query($SQL);

             $result = $query->result();
             $all = [];
            if (!empty($result)) {
                   foreach ($result as $key => $value) {
                    $data = (array)$value;
                    $query = $this->db->from('user');
                    $query->where(['userId'=>$data['userId']]);
                    $user = $query->get()->result_array();
                    $data['profilePicURL'] = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
                    $data['name'] = (!empty($user))?$user[0]['name']:'';
                    $data['street_image'] = (!empty($data['street_image']))?$data['street_image']:'uploads/address/address_default_image.png';
                    $data['building_image'] = (!empty($data['building_image']))?$data['building_image']:'uploads/address/address_default_image.png';
                    $data['entrance_image'] = (!empty($data['entrance_image']))?$data['entrance_image']:'uploads/address/address_default_image.png';
                    $all[] = $data;
                }
                $return = array(
                    'resultCode' => 1,
                    'resultData' => $all
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        } 
    }

    function publicGlobalSearch(){

        try {

             $data = $this->input->post();
             $searchString = $data['search'];
             $baseUrl = base_url();
             if(strpos($searchString, $baseUrl) !== false){
                $searchStringUrl = "pub.unique_link = '$searchString'";
             }else{
             	 // $uniquLink = $baseUrl.$searchString;
             	 $searchStringUrl = "pub.unique_link LIKE '%".$searchString."%'";

             }

             $SQL = "SELECT pub.* FROM user LEFT JOIN publicAddresses pub ON pub.userId = user.userId WHERE pub.userId > 0 AND ( user.name LIKE '%".$searchString."%'  OR user.contactNumber LIKE '%".$searchString."%'  OR ".$searchStringUrl." OR pub.street_name LIKE '%".$searchString."%'  OR pub.plusCode = '".$searchString."')";

             $query = $this->db->query($SQL);

                $result = $query->result();

             $all = [];
            if (!empty($result)) {
                  foreach ($result as $key => $value) {
                    $data = (array)$value;
                    $query = $this->db->from('user');
                    $query->where(['userId'=>$data['userId']]);
                    $user = $query->get()->result_array();
                    $data['profilePicURL'] = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
                    $data['name'] = (!empty($user))?$user[0]['name']:'';
                    $data['street_image'] = (!empty($data['street_image']))?$data['street_image']:'uploads/address/address_default_image.png';
                    $data['building_image'] = (!empty($data['building_image']))?$data['building_image']:'uploads/address/address_default_image.png';
                    $data['entrance_image'] = (!empty($data['entrance_image']))?$data['entrance_image']:'uploads/address/address_default_image.png';
                    $all[] = $data;
                }
                $return = array(
                    'resultCode' => 1,
                    'resultData' => $all
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        } 
    }
    /*
    yunush share reciver list
    */
    function getPrivateSharedRecieverList(){

           try {

             $data = $this->input->post();
             $userId = $data['userId']; 
             $this->db->select('privateAddresses.*'); 
             $this->db->from('privateAddresses'); 
             $this->db->join('sharedWithBusiness su', 'su.addressId = privateAddresses.addressId', 'left');
             $this->db->where('su.recipientId =',$userId);    
             $query = $this->db->get();     
             $result = $query->result();
             $all = [];
            if (!empty($result)) {
                 foreach ($result as $key => $value) {
                    $data = (array)$value;
                    $query = $this->db->from('user');
                    $query->where(['userId'=>$data['userId']]);
                    $user = $query->get()->result_array();
                    $data['profilePicURL'] = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
                    $data['name'] = (!empty($user[0]['name']))?$user[0]['name']:'';
                    $data['street_image'] = (!empty($data['street_image']))?$data['street_image']:'uploads/address/address_default_image.png';
                    $data['building_image'] = (!empty($data['building_image']))?$data['building_image']:'uploads/address/address_default_image.png';
                    $data['entrance_image'] = (!empty($data['entrance_image']))?$data['entrance_image']:'uploads/address/address_default_image.png';
                   $all[] = $data;
                }
                $return = array(
                    'resultCode' => 1,
                    'resultData' => $all
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }

     /*
    yunush share reciver list
    */
    function getPublicSharedRecieverList(){

           try {

             $data = $this->input->post();
             $userId = $data['userId']; 
             $this->db->select('publicAddresses.*'); 
             $this->db->from('publicAddresses'); 
             $this->db->join('sharedWithUser su', 'su.addressId = publicAddresses.addressId', 'left');
             $this->db->where('su.recipientId =',$userId);    
             $query = $this->db->get();     
             $result = $query->result();
             $all = [];
            if (!empty($result)) {
                  foreach ($result as $key => $value) {
                    $data = (array)$value;
                    $query = $this->db->from('user');
                    $query->where(['userId'=>$data['userId']]);
                    $user = $query->get()->result_array();
                    $data['profilePicURL'] = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
                    $data['name'] = (!empty($user[0]['name']))?$user[0]['name']:'';
                    $data['street_image'] = (!empty($data['street_image']))?$data['street_image']:'uploads/address/address_default_image.png';
                    $data['building_image'] = (!empty($data['building_image']))?$data['building_image']:'uploads/address/address_default_image.png';
                    $data['entrance_image'] = (!empty($data['entrance_image']))?$data['entrance_image']:'uploads/address/address_default_image.png';
                   $all[] = $data;
                }                $return = array(
                    'resultCode' => 1,
                    'resultData' => $all
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }

function getPrivateSharedSenderList(){

           try {

             $data = $this->input->post();
             $userId = $data['userId']; 
             $this->db->select('privateAddresses.*'); 
             $this->db->from('privateAddresses'); 
             $this->db->join('sharedWithBusiness su', 'su.addressId = privateAddresses.addressId', 'left');
             $this->db->where('su.senderId =',$userId);    
             $query = $this->db->get();     
             $result = $query->result();
             $all = [];
            if (!empty($result)) {
                 foreach ($result as $key => $value) {
                    $data = (array)$value;
                    $query = $this->db->from('user');
                    $query->where(['userId'=>$data['userId']]);
                    $user = $query->get()->result_array();
                    $data['profilePicURL'] = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
                    $data['name'] = (!empty($user))?$user[0]['name']:'';
                    $data['street_image'] = (!empty($data['street_image']))?$data['street_image']:'uploads/address/address_default_image.png';
                    $data['building_image'] = (!empty($data['building_image']))?$data['building_image']:'uploads/address/address_default_image.png';
                    $data['entrance_image'] = (!empty($data['entrance_image']))?$data['entrance_image']:'uploads/address/address_default_image.png';
                   $all[] = $data;
                }
                $return = array(
                    'resultCode' => 1,
                    'resultData' => $all
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }

     /*
    yunush share sender list
    */
    function getPublicSharedSenderList(){

           try {

             $data = $this->input->post();
             $userId = $data['userId']; 
             $this->db->select('publicAddresses.*'); 
             $this->db->from('publicAddresses'); 
             $this->db->join('sharedWithUser su', 'su.addressId = publicAddresses.addressId', 'left');
             $this->db->where('su.senderId =',$userId);    
             $query = $this->db->get();     
             $result = $query->result();
             $all = [];
            if (!empty($result)) {
                  foreach ($result as $key => $value) {
                    $data = (array)$value;
                    $query = $this->db->from('user');
                    $query->where(['userId'=>$data['userId']]);
                    $user = $query->get()->result_array();
                    $data['profilePicURL'] = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
                    $data['name'] = (!empty($user[0]['profilePicURL']))?$user[0]['name']:'';
                    $data['street_image'] = (!empty($data['street_image']))?$data['street_image']:'uploads/address/address_default_image.png';
                    $data['building_image'] = (!empty($data['building_image']))?$data['building_image']:'uploads/address/address_default_image.png';
                    $data['entrance_image'] = (!empty($data['entrance_image']))?$data['entrance_image']:'uploads/address/address_default_image.png';
                   $all[] = $data;
                }                $return = array(
                    'resultCode' => 1,
                    'resultData' => $all
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }
    
/*favorite address*/
    function favoriteAddress(){

           try {

             $data = $this->input->post();
             $post = [];
             $post['userId'] = $data['userId'];
             $post['addressId'] = $data['addressId'];
             $post['type'] = $data['type'];
             $query = $this->db->from('favUnfaveAddresss');
            $query->where(['addressId'=>$data['addressId'],'userId'=>$data['userId']]);
            $fav = $query->get()->result_array();
            if(!empty($fav[0])){
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'Address already have been added.'
                );
                echo json_encode($return);
           die;
            }
             $result = $this->User_model->favoriteAddress($post);
            if (!empty($result)) {
                  $return = array(
                    'resultCode' => 1,
                    'resultData' => 'You have favorite successfully.'
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            print_r($e);die;
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }

    function favoriteAddressList(){

       try {

             $data = $this->input->post();
             // echo "<pre>";
             // print_r($data);die;
             $userId = $data['userId']; 
             $type = $data['type'];

             if($type=='private'){

             $this->db->select('privateAddresses.*,fa.id'); 
             $this->db->from('privateAddresses'); 
             $this->db->join('favUnfaveAddresss fa', 'fa.addressId = privateAddresses.addressId', 'left');
             $this->db->where('fa.userId =',$userId);    
             $this->db->where('fa.type =',$type); 

             }else{

             $this->db->select('publicAddresses.*,fa.id'); 
             $this->db->from('publicAddresses'); 
             $this->db->join('favUnfaveAddresss fa', 'fa.addressId = publicAddresses.addressId', 'left');
             $this->db->where('fa.userId =',$userId);    
             $this->db->where('fa.type =',$type); 
             }
               
             $query = $this->db->get();     
             $result = $query->result();
             $all = [];
            if (!empty($result)) {
                  foreach ($result as $key => $value) {

                    $valData = (array)$value;
                    $query = $this->db->from('user');
                    $query->where(['userId'=>$valData['userId']]);
                    $user = $query->get()->result_array();
                    $valData['profilePicURL'] = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
                    $valData['name'] = (!empty($user[0]['name']))?$user[0]['name']:'';
                    $valData['street_image'] = (!empty($valData['street_image']))?$valData['street_image']:'uploads/address/address_default_image.png';
                    $valData['building_image'] = (!empty($valData['building_image']))?$valData['building_image']:'uploads/address/address_default_image.png';
                    $valData['entrance_image'] = (!empty($valData['entrance_image']))?$valData['entrance_image']:'uploads/address/address_default_image.png';
                   $all[] = $valData;
                }                $return = array(
                    'resultCode' => 1,
                    'resultData' => $all
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }

    function unFavoriteAddress(){

           try {
             $data = $this->input->post();
             $this->db->where('id', $data['fav_id']);
             $response = $this->db->delete('favUnfaveAddresss'); 
            if (!empty($response)) {
                  $return = array(
                    'resultCode' => 1,
                    'resultData' => 'You have unfavorited successfully.'
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            print_r($e);die;
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }

    /*
    yunush shared reciever list
    */
    function getShareRecieverUserList(){

           try {

             $data = $this->input->post();
             $userId = $data['userId']; 
             $addressId = $data['addressId']; 
             $type = $data['type']; 
             $this->db->select('*','su.recordId as id'); 
             $this->db->from('user'); 
             if($type=='public'){
             $this->db->join('sharedWithUser su', 'su.recipientId = user.userId', 'left');	
	         }else{
	          $this->db->join('sharedWithBusiness su', 'su.recipientId = user.userId', 'left');		
	         }
             $this->db->where('su.senderId =',$userId); 
             $this->db->where('su.addressId =',$addressId); 
             $this->db->group_by('su.recipientId');   
             $query = $this->db->get();     
             $result = $query->result();
             $all = [];
            if (!empty($result)) {
                  foreach ($result as $key => $value) {
                    $data = (array)$value;
                    $query = $this->db->from('user');
                    $query->where(['userId'=>$data['userId']]);
                    $user = $query->get()->result_array();
                    $data['profilePicURL'] = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
                    $data['name'] = (!empty($user[0]['name']))?$user[0]['name']:'';
                   $all[] = $data;
                }                $return = array(
                    'resultCode' => 1,
                    'resultData' => $all
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }
	/* 
	api for unshare addresses
	*/
     function unShareAddress(){

           try {
             $data = $this->input->post();
             $this->db->where('recordId', $data['recordId']);
	         if($data['type']=='private'){
	         $response = $this->db->delete('sharedWithBusiness'); 
	         }else{
	          $response = $this->db->delete('sharedWithUser'); 	
	         }
            if (!empty($response)) {
                  $return = array(
                    'resultCode' => 1,
                    'resultData' => 'You have unshared successfully.'
                );

            } else {
                $return = array(
                    'resultCode' => 0,
                    'resultData' => 'No data found'
                );
            }
            echo json_encode($return);
        die;
            
            
        } catch (Exception $e) {
            print_r($e);die;
            echo 'Received exception : ',  $e->getMessage(), "\n";
        }
    }

}
