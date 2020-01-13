<?php

class Login_model extends CI_Model{

    public function __construct()
	{
		parent::__construct();
    }

    /****************if user entered email to login************* */
    function signInWithEmail($email,$password){
        $this->db->select(array('adminId'));
		$this->db->where('emailId',$email);
		$this->db->where('password',$password);
		// echo $this->db->last_query();die();
		$query = $this->db->get('admin')->row_array();
		return $query;
	}

	function getValuesWithEmailId($emailId)
    {
        $this->db->select('name, adminId');
		$this->db->where('emailId',$emailId);
		$query = $this->db->get('admin')->row_array();
		return $query;
	}

	function saveOtp($data){
		$this->db->insert("otp",$data);
		$query = $this->db->insert_id();
		return $query;
	}

	function updateOtp($otpId, $data){
		$this->db->where("otpId",$otpId);
		$this->db->update('otp', $data);
		$query = $this->db->affected_rows();
		return $query;
	}

	function getUserDetailWithOTPId($otpId){
		$sql = "select otp.userId, admin.emailId, admin.name from otp join admin on otp.userId = admin.adminId where otpId = $otpId";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function checkOtp($otpId, $otp)
	{
		$this->db->select('userId');
		$this->db->where('otpId', $otpId);
		$this->db->where('otp', $otp);
		$query = $this->db->get('otp');
		return $query->row_array();
	}

	function resetPassword($userId, $password)
	{
		$data['password'] = $password;
		$this->db->where('adminId', $userId);
		$this->db->update('admin', $data);
		$query = $this->db->affected_rows();
		return $query;
	}

	function changePassword($datas)
	{
		$datas['msg'] = 'model';
		$data['password'] = $datas['newPassword'];
		$this->db->where('adminId', $datas['adminId']);
		$this->db->where('password', $datas['currentPassword']);
		$this->db->update('admin', $data);
		$query = $this->db->affected_rows();
		return $query;
	}
}

?>