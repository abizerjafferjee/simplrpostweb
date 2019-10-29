<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Login_model');
		$this->load->library('session');
		$this->load->library('PHPMailer');
	}


	public function sessionSet($data)
	{
		$this->session->set_userdata('adminId', $data['adminId']);
		return true;
	}

	public function index()
	{
		$adminId = $this->session->userdata('adminId');
		if (isset($adminId)) {
			$this->dashboardView();
		} else {

			$this->logInView();
		}
	}

	public function logInView()
	{
		$data['title'] = 'login';
		$this->load->view('includes/header', $data);
		$this->load->view('login');
		$this->load->view('includes/footer');
	}

	public function dashboardView()
	{
		redirect('index.php/admin-dashboard');
	}

	public function verifyUser()
	{
		$email = $_POST['emailId'];
		$password = $_POST['password'];
		$arrResult = $this->Login_model->signInWithEmail($email, $password);
		if ($arrResult > 0) {
			if ($this->sessionSet($arrResult)) {
				print_r($arrResult);
			}
		} else {
			$result = 0;
			print_r($result);
		}
	}

	public function forgotPasswordView()
	{
		$data['title'] = 'forgot password';
		$this->load->view('includes/header', $data);
		$this->load->view('forgot-password');
		$this->load->view('includes/footer');
	}

	public function forgotPasswordLogic()
	{
		$email = $_POST['emailId'];
		$arrResult = $this->Login_model->getValuesWithEmailId($email);
		if ($arrResult > 0) {
			$result = $this->generateOtp($arrResult, $email);
			if ($result > 0) {
				$arrResult['result'] = 1;
				$arrResult['otpId'] = $result;
				print_r(json_encode($arrResult));
			}
		} else {
			$arrResult['result'] = 0;
			print_r(json_encode($arrResult));
		}
	}

	public function generateOtp($arrResult, $email)
	{

		$intRandom = mt_rand(100000, 999999);

		$arrOtpData['otp'] = $intRandom;
		$arrOtpData['userId'] = $arrResult['adminId'];
		$arrOtpData['createDate'] = date("Y-m-d H:i:s");

		$email_data['email_title'] = 'Reset Password';
		$email_data['email_id'] = $email;
		$email_data['heading'] = "Hey, ". ucfirst($arrResult['name']);
		$email_data['message'] = "<p>Seems like you forgot your password for Simplr Post. If this is true, your OTP is - $intRandom</p><p>If you didn't forget your password safely ignore this</p><br>";
		// $email_data['view_url'] = 'email/emailTemplate';
		$email_data['footer'] = '<p>If you have any questions or concerns please direct them to <a href="mailto:abizerjafferjee@simplrpost.com?Subject=Privacy%20Policy" target="_blank">abizerjafferjee@simplrpost.com</a></p>';
		$this->sendEmail($email_data);

		$save = $this->Login_model->saveOtp($arrOtpData);

		return $save;
	}

	public function otpView()
	{
		$data['title'] = 'otp';
		$this->load->view('includes/header', $data);
		$this->load->view('otp', $data);
		$this->load->view('includes/footer');
	}

	public function resendOtp()
	{
		$otpId = $_POST['otpId'];

		$arrUserDetail = $this->Login_model->getUserDetailWithOTPId($otpId);

		$intRandom = mt_rand(100000, 999999);

		$arrOtpData['otp'] = $intRandom;
		$arrOtpData['createDate'] = date("Y-m-d H:i:s");

		$email_data['email_title'] = 'Reset Password OTP resent';
		$email_data['email_id'] = $arrUserDetail['emailId'];
		$email_data['view_url'] = 'email/emailTemplate';
		$email_data['heading'] = "Hey, ". ucfirst($arrUserDetail['name']);
		$email_data['message'] = "<p>Seems like you forgot your password for Simplr Post. If this is true, your OTP is - $intRandom</p><p>If you didn't forget your password safely ignore this</p><br>";
		$email_data['footer'] = '<p>If you have any questions or concerns please direct them to <a href="mailto:abizerjafferjee@simplrpost.com?Subject=Privacy%20Policy" target="_blank">abizerjafferjee@simplrpost.com</a></p>';
		$this->sendEmail($email_data);

		$this->Login_model->updateOtp($otpId, $arrOtpData);
	}

	public function otpValidation()
	{
		$otpId = $_POST['otpId'];
		$otp = $_POST['otp'];

		$userId = $this->Login_model->checkOtp($otpId, $otp);

		if ($userId > 0) {
			print_r(json_encode($userId));
		} else {
			$userId['result'] = 0;
			print_r(json_encode($userId));
		}
	}

	public function resetPasswordView()
	{
		$data['title'] = 'reset password';
		$this->load->view('includes/header', $data);
		$this->load->view('reset-password');
		$this->load->view('includes/footer');
	}

	public function resetPasswordLogic()
	{
		$adminId = $_POST['userId'];
		$password = $_POST['password'];

		$result = $this->Login_model->resetPassword($adminId, $password);
		if ($result > 0) {
			print($result);
		} else {
			$result = 0;
			print($result);
		}
	}

	public function changePasswordView()
	{
		$data['title'] = 'Change password';
		$this->load->view('includes/header', $data);
		$this->load->view('admin/change-password');
		$this->load->view('includes/footer');
	}

	public function changePasswordLogic()
	{
		// echo "password change";
		$data['adminId'] = $this->session->userdata('adminId');
		$data['currentPassword'] = $_POST['currentPassword'];
		$data['newPassword'] = $_POST['newPassword'];
		$result = $this->Login_model->changePassword($data);

		// print_r($result);exit;
		if ($result > 0) {
			print($result);
		} else {
			$result = 0;
			print($result);
		}
	}

	public function logOut()
	{
		$this->session->unset_userdata('adminId');
		$this->index();
	}
	public function sendEmail($email_data)
	{
		ob_start();
		$mail = new PHPMailer;

		$mail->SMTPDebug = '';
		$mail->IsSMTP();

		$mail->Host = HOST_NAME;
		$mail->Port = PORT_NAME;
		$mail->SMTPAuth = false;

		$mail->From = FROM_EMAIL;
		$mail->FromName = FROM_NAME;
		$mail->AddAddress($email_data['email_id'], $email_data['name']);

		$mail->Subject = $email_data['email_title'];
		$mail->Body = $this->load->view('email/otpEmailTemplate', $email_data, TRUE);
		$mail->AltBody = BODY_TITLE;
		$mail->Send();
	}
}
