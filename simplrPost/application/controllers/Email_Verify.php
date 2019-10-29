<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email_Verify extends CI_Controller
{
    public function index()
    {
        $data['heading'] = 'Thank You';
        $data['headingColor'] = '#31ab72';
        $data['msg'] = 'Your Account Is Now Verified.';
        $data['msgColor'] = '#31ab72';
        $data['img'] = BASE_URL.'assets/errorMessagesAssets/images/verified.png';
        $this->load->view('emailVerify', $data);
    }
    public function verificationError()
    {
        $data['heading'] = 'Error !!!';
        $data['headingColor'] = '#d13a3c';
        $data['msg'] = 'There Seems To Be A Problem With The Verification Code.';
        $data['msgColor'] = '#d13a3c';
        $data['img'] = BASE_URL.'assets/errorMessagesAssets/images/error.png';
        $this->load->view('emailVerify', $data);
    }
    public function alreadyVerified()
    {
        $data['heading'] = 'Hey there';
        $data['headingColor'] = '#398df0';
        $data['msg'] = 'Your Account Is Already Verified.';
        $data['msgColor'] = '#398df0';
        $data['img'] = BASE_URL.'assets/errorMessagesAssets/images/already.png';
        $this->load->view('emailVerify', $data);
    }
}