<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model{
	public function __construct(){
		parent::__construct(); 
	}

	/*********   baseurl   *******/
	public function baseUrl(){
		$this->db->select('*');
        $query = $this->db->get('urls')->result_array();
		foreach($query as $value){
			if($value['name'] == 'apiBaseURL'){
				$key['apiBaseURL'] = $value['url'];
			}
			if($value['name'] == 'imageBaseURL'){
				$key['imageBaseURL'] = $value['url'];
			}
		}
		return $key;
	}
	
	/****************function for signUp************* */
 //    function signUp($arrDatas){
	// 	$query = $this->db->insert('user', $arrDatas);
	// 	$this->db->select(array('userId'));
	// 	$this->db->where('emailId',$arrDatas['emailId']);
	// 	$this->db->where('password',$arrDatas['password']);
	// 	$query = $this->db->get('user')->row()->userId;
	// 	return $query;
	// }


	public function getSingleRow($table, $where)
	{
		$this->db->from($table);
		$this->db->where($where);
		return $this->db->get()->row();
	}

	function signUp($arrDatas)
	{
		$this->db->insert('user', $arrDatas);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}



	public function checkVerificationStatus($token)
	{
		$this->db->select('isEmailIdVerified');
		$this->db->where('emailVerificationToken', $token);
		return $this->db->get('user')->row()->isEmailIdVerified;
	}
	public function verifyEmail($token)
	{
		$this->db->where('emailVerificationToken', $token);
		$this->db->set('isEmailIdVerified', 1);
		$this->db->update('user');
		return $this->db->affected_rows();
	}
	public function updateVerifictionToken($userId, $token)
	{
		$this->db->where('userId', $userId);
		$this->db->set('emailVerificationToken', $token);
		$this->db->update('user');
		return $this->db->affected_rows();
	}
	public function verifyContactNumber($userId)
	{
		$this->db->where('userId', $userId);
		$this->db->set('isContactNumberVerified', 1);
		$this->db->update('user');
		return $this->db->affected_rows();
	}
	/****************if user entered email to login************* */
    function signInWithEmail($email,$password){
        $this->db->select(array('userId', 'name', 'userName', 'emailId', 'contactNumber', 'profilePicURL'));
		$this->db->where('emailId',$email);
		$this->db->where('password',$password);
		$query = $this->db->get('user')->row_array();
		return $query;
	}
	
	/****************if user entered userName to login************* */
    function signInWithUserName($userName,$password){
        $this->db->select(array('userId', 'name', 'userName', 'emailId', 'contactNumber', 'profilePicURL'));
		$this->db->where('userName',$userName);
		$this->db->where('password',$password);
		$query = $this->db->get('user')->row_array();
		return $query;
	}
	/****************if user account is deactivated and user login so we have to reactivate account************* */
	function reactivateAccount($userId){
		$this->db->set('status', 1);
		$this->db->where('userId', $userId);
		$this->db->update('user');
		$query = $this->db->affected_rows();
		if($query > 0){
			$this->db->set('status', 1);
			$this->db->where('userId', $userId);
			$this->db->where('status', 0);
			$this->db->update('publicAddresses');

			$this->db->set('status', 1);
			$this->db->where('userId', $userId);
			$this->db->where('status', 0);
			$this->db->update('privateAddresses');
		}
	}

	/**************get user data with the userId*************/
	public function getUserDetailWIthUserId($userId)
    {
        $this->db->select('userId, name, userName, emailId, contactNumber, profilePicURL, isEmailIdVerified, isContactNumberVerified,security_question, security_answer');
        $this->db->where('userId', $userId);
        $query = $this->db->get('user');
        return $query->row_array();
	}

	public function getUserDetailWIthUserIdEditProfile($userId, $data)
    {
		$this->db->where('userId', $userId);
		$this->db->update('user', $data);
		if($this->db->affected_rows() > 0){
			$this->db->select('userId, userName, emailId, contactNumber, profilePicURL, isEmailIdVerified, isContactNumberVerified');
			$this->db->where('userId', $userId);
			$query = $this->db->get('user');
			return $query->row_array();
		}
	}


	/****************function to validate if entered email id exist**************/
    public function getValuesWithEmailId($emailId)
    {
        $this->db->select('name, userId, status');
		$this->db->where('emailId',$emailId);
		$query = $this->db->get('user')->row_array();
		return $query;
	}
	public function validateIfEmailExisted($emailId)
    {
        $this->db->select('status');
		$this->db->where('emailId',$emailId);
		$query = $this->db->get('user')->row_array();
		return $query;
	}
	
	/****************function to validate if entered userName exist**************/
    public function validateIfUserNameExisted($userName)
    {
        $this->db->select('status');
		$this->db->where('userName',$userName);
		$query = $this->db->get('user')->row_array();
		return $query;
	}

	/****************function to validate if entered contactNumber relate to any user**************/
	public function getValuesWithContactNumber($contactNumber)
    {
        $this->db->select(array('status','name','userId'));
		$this->db->where('contactNumber',$contactNumber);
		$query = $this->db->get('user')->row_array();
		return $query;
	}
	public function validateIfContactNumberExisted($contactNumber)
    {
        $this->db->select(array('status'));
		$this->db->where('contactNumber',$contactNumber);
		$this->db->where_in('status', ['-5', '0', '1']);
		$query = $this->db->get('user')->row_array();
		return $query;
    }

    public function getNumRows($table, $where=array())
      {
          if(count($where) > 0)
          {
              $query = $this->db->select('userId')->where($where)->get($table);
              $numRows = $query->num_rows();
          }
          else
          {
              $query = $this->db->select('userId')->get($table);
              $numRows = $query->num_rows();   
          }
          return $numRows;
      }


    public  function update_data($table, $data=array(), $where=array()) 
    {
        $this->db->where($where);
        $this->db->update($table,$data);
        // echo $this->db->last_query();
        return true;           
    }


	/****************to get the user Id after signUp**************/
    public function getUserMaxId()
    {
        $this->db->select_max('userId');
		$query = $this->db->get('user');
		return $query->row()->userId;
	}
	
    /*********to update password *******/	
	function updatePassword($datas){
		$arrUpdate['password'] = $datas['newPassword'];
		$this->db->set($arrUpdate);
		$this->db->where('userId', $datas['userId']);
		$this->db->where('password', $datas['currentPassword']);
		$this->db->update("user"); 
		$query = $this->db->affected_rows();
		return $query;
	}
	/*********to update password *******/	
	function resetPassword($userId, $password){
		$arrUpdate['password'] = $password;
		$this->db->set($arrUpdate);
		$this->db->where('userId', $userId);
		$this->db->update("user"); 
		$query = $this->db->affected_rows();
		return $query;
	}

	function updateUserDetails($arrDatas){
		$this->db->where('userId', $arrDatas['userId']);
		$query = $this->db->update("user", $arrDatas);  
		return $query;
	}

	/*********to match current password *******/	
	function matchCurrentPassword($userId, $password){
		$this->db->select('status');
		$this->db->where('userId', $userId);
		$this->db->where('password', $password);
		$query = $this->db->get("user")->row_array();  
		return $query; 
	}

	/*********to delete or deativate user account(delete = -1, deactivate = 0) *******/	
	function updateUserStatusToDeleteOrDeactivateAccount($arrDatas){
		$this->db->where('userId',$arrDatas['userId']);
		$arrData['status'] = $arrDatas['status'];
		$this->db->update("user",$arrData); 
		$query = $this->db->affected_rows();
		if($query > 0){
			$this->db->set('status', $arrDatas['status']);
			$this->db->where('userId',$arrDatas['userId']);
			$this->db->update('publicAddresses');

			$this->db->set('status', $arrDatas['status']);
			$this->db->where('userId',$arrDatas['userId']);
			$this->db->update('privateAddresses');

		}
		return $query; 
	}

	/**************To save the generated Otp in otp table**************/
    function saveOtp($data){
		$this->db->insert("otp",$data);
		$query['otpId'] = $this->db->insert_id();
		return $query;
	}
	
	/**************To return the otp id associated with userId**************/
    function checkOtp($userId){
		$this->db->select('otpId');
        $this->db->where('userId',$userId);
		$query = $this->db->get('otp');
		return $query->row_array();
    }

	/**************To validate the otp if correct or not**************/
    function validateOtp($arrDatas){
    	$this->db->select('isUsed, otpType,userId');
        $this->db->where('otpId',$arrDatas['otpId']);
        $this->db->where('otp',$arrDatas['otp']);
        $this->db->where('otpType',$arrDatas['type']);
		$query = $this->db->get('otp');
		return $query->row_array();
    }

	/**************To expire the otp session after being used**************/
    function expireOtp($otpId){
		$this->db->where('otpId',$otpId);
		$datas['isUsed'] = 1;
		$query = $this->db->update("otp",$datas);  
		return $query; 
	}
	
	/**************To expire the otp session after being used**************/
	function updateOtp($arrOtpData){
		$this->db->where('otpId',$arrOtpData['otpId']);
		$datas['otp'] = $arrOtpData['otp'];
		$datas['createDate'] = $arrOtpData['createDate'];
		$datas['isUsed'] = 0;
		$query = $this->db->update("otp",$datas);  
		return $query; 
    }
	
	/**************To get the user Id associated with user id**************/
    function getUserIdWithOtpId($otpId){
		$this->db->select('userId');
        $this->db->where('otpId',$otpId);
		$query = $this->db->get('otp');
		return $query->row_array();
	}
	
	/**************to insert the data in regiterDevices table**************/
	function insertIntoRegisterDeviceTable($arrData){
		$this->db->set('status', 1);
		$this->db->set('pushToken', $arrData['pushToken']);
		$this->db->where('userId', $arrData['userId']);
		$this->db->where('deviceId', $arrData['deviceId']);
		$this->db->where('deviceType', $arrData['deviceType']);
		$this->db->update('registeredDevices');
		$query = $this->db->affected_rows();
		if($query == 0){
			$query = $this->db->insert('registeredDevices',$arrData);
		}
		return $query;
	}

	/**************to check the user account status if user account active or not*************/
	public function checkUserAccountStatus($userId)
    {
        $this->db->select('status');
        $this->db->where('userId', $userId);
        $query = $this->db->get('user');
        return $query->row_array();
	}

	/**************to update the data in regiterDevices table**************/
	function updateRegisterDeviceTable($arrData){
		$this->db->where('userId', $arrData['userId']);
		$this->db->where('deviceId', $arrData['deviceId']);
		$this->db->where('deviceType', $arrData['deviceType']);
		$this->db->set('status', 0);
		$query = $this->db->update("registeredDevices");
		return $query;
	}

    function checkUserStatus($userId){
		$this->db->select('status');
		$this->db->where('userId', $userId);
		$query = $this->db->get('user')->row_array();
		return $query;
	}

	function getAboutUsContent(){
		$this->db->select('content');
		$query = $this->db->get('aboutUs')->row_array();
		return $query;
	}

	function getPrivacyPolicyContent(){
		$this->db->select('content');
		$query = $this->db->get('privacyPolicy')->row_array();
		return $query;
	}

	function getTermsConditionsContent(){
		$this->db->select('content');
		$query = $this->db->get('termsConditions')->row_array();
		return $query;
	}

	function insertUserFeedback($data){
		$query = $this->db->insert('userFeedback', $data);
		return $query;
	}
    /************** insert to saveUserAddress **************/
	function saveUserAddress($savedAddress){
		$this->db->insert("savedAddressList",$savedAddress); 
		$query = $this->db->insert_id();
		return $query;
	}
	/************** insert to updateUserAddress **************/
	function updateUserAddress($savedAddress){
		$this->db->where(array('listId' => $savedAddress['listId'], 'userId' => $savedAddress['userId']));
		$this->db->update("savedAddressList",array('listName' => $savedAddress['listName']));
		$query = $this->db->affected_rows();
		return $query;
	}
	function getUserArray($emailId){
		$this->db->select('userId, name, userName, emailId, contactNumber, profilePicURL');
		$this->db->where('emailId', $emailId);
		$query = $this->db->get('user')->row_array();
		return $query;
	}

	function getFAQ(){
		$this->db->select('questionId, question, answer');
		$this->db->where('status', 1);
		$query = $this->db->get('frequentlyAskedQuestions')->result_array();
		return $query;
	}

	function submitReport($arrRequestData){
		$query = $this->db->insert('addressReports', $arrRequestData);
		return $query;
	}

	function getIssues(){
		$this->db->select('issueId, issue');
		$this->db->where('status', 1);
		$query = $this->db->get('issues')->result_array();
		return $query;
	}

	function getReceipientList($senderId, $addressId, $isPublic){
	
		// $sql = "select publicAddresses.addressId, publicAddresses.logoURL AS pictureURL, publicAddresses.shortName, publicAddresses.plusCode, publicAddresses.categoryId, publicAddresses.referenceCode AS addressReferenceId, publicAddresses.description from publicAddresses where  publicAddresses.addressId in (select addressId from sharedWithBusiness where senderId = $senderId and addressId = $addressId and isAddressPublic = $isPublic and status = 1) and publicAddresses.status = 1";

		$sql = "select privateAddresses.* from privateAddresses where  privateAddresses.addressId in (select recipientId from sharedWithBusiness where senderId = $senderId and addressId = $addressId and isAddressPublic = $isPublic and status = 1)";
		
		$queryPublic = $this->db->query($sql);
		$publicAddresses = $queryPublic->result();

		if(!empty($publicAddresses)){
			$result['publicAddresses'] = $publicAddresses;
		}
		if($isPublic){
			$sql = "select userId, profilePicURL, userName, name from user where userId in (select recipientId from sharedWithUser where senderId = $senderId and addressId = $addressId  and status = 1)";
		}else{
			$sql = "select userId, profilePicURL, userName, name from user where userId in (select recipientId from sharedWithBusiness where senderId = $senderId and addressId = $addressId and status = 1)";
		}
		
		$queryUser = $this->db->query($sql);
		$user = $queryUser->result();
		if(!empty($user)){
			$result['user'] = $user;
		}
		return $result;
	}
	// function getReceipientList($senderId, $addressId, $isPublic){
	// 	$sql = "select publicAddresses.addressId, publicAddresses.logoURL AS pictureURL, publicAddresses.shortName, publicAddresses.plusCode, publicAddresses.categoryId,(select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName, publicAddresses.referenceCode AS addressReferenceId, publicAddresses.description from publicAddresses where  publicAddresses.addressId in (select recipientId from sharedWithBusiness where senderId = $senderId and addressId = $addressId and isAddressPublic = $isPublic and status = 1) and publicAddresses.status = 1";
	// 	$queryPublic = $this->db->query($sql);
	// 	$publicAddresses = $queryPublic->result();
	// 	if(!empty($publicAddresses)){
	// 		$result['publicAddresses'] = $publicAddresses;
	// 	}

	// 	$sql = "select userId, profilePicURL, userName, name from user where userId in (select recipientId from sharedWithUser where senderId = $senderId and addressId = $addressId and isAddressPublic = $isPublic and status = 1)";
	// 	$queryUser = $this->db->query($sql);
	// 	$user = $queryUser->result();
	// 	if(!empty($user)){
	// 		$result['user'] = $user;
	// 	}
	// 	return $result;
	// }
	function getNotificationList($arrRequestData){
		$sql = "select notificationUsers.notificationId, notifications.information, notifications.createDate FROM notificationUsers JOIN notifications on notificationUsers.notificationId = notifications.notificationId where notificationUsers.userId = ".$arrRequestData['userId']." and notificationUsers.status = 1 order by createDate desc limit ".$arrRequestData['start'].", ".$arrRequestData['count'];
		$query = $this->db->query($sql)->result_array();
		return $query;
	}
	function getPublicAddressIdWithReferenceCode($userId, $addressReferenceId){
		$this->db->select("addressId, (select count(*) from privateAddresses where userId = '$userId' and referenceCode = '$addressReferenceId') as isOwn");
		$this->db->where('referenceCode', $addressReferenceId);
		$query = $this->db->get('publicAddresses')->row_array();
		return $query;
	}
	function getPrivateAddressIdWithReferenceCode($userId, $addressReferenceId){
		$this->db->select("addressId, (select count(*) from privateAddresses where userId = '$userId' and referenceCode = '$addressReferenceId') as isOwn");
		$this->db->where('referenceCode', $addressReferenceId);
		$query = $this->db->get('privateAddresses')->row_array();
		return $query;
	}

	function getUsers(){
			$sql = "select * from user order by userId desc";
		$query = $this->db->query($sql)->result_array();
		return $query;
	}

	function favoriteAddress($arrDatas){
		$this->db->insert('favUnfaveAddresss', $arrDatas);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function favoriteAddressList($userId){
			$sql = "select * from favUnfaveAddresss where userId = $userId order by id desc";
		   $query = $this->db->query($sql)->result_array();
		return $query;
	}
}