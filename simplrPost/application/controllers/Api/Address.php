<?php
defined('BASEPATH') or exit('No direct script access allowed');

use OpenLocationCode\OpenLocationCode;

require_once APPPATH . 'libraries/vendor/open-location-code-master/src/OpenLocationCode.php';
require APPPATH . 'libraries/vendor/autoload.php';
class Address extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Api/User_model');
		$this->load->model('Api/Address_model');
	}
	public function get_request_method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	/************** Function for Get Categories *************/
	public function getCategories()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method.'
				);

				echo json_encode($return);
				die;
			} else {
				$return;
				$datas = $this->Address_model->getCategories();
				if (!empty($datas)) {
					$return = array('resultCode' => 1, 'resultData' => $datas);
				} else {
					$return = array('resultCode' => -3, 'resultData' => 'No data found');
				}
				echo json_encode($return);
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/************** Function for Get Categories *************/
	public function getWeekDays()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method.'
				);

				echo json_encode($return);
				die;
			} else {
				$return;
				$datas = $this->Address_model->getWeekDays();
				if (!empty($datas)) {
					$return = array('resultCode' => 1, 'resultData' => $datas);
				} else {
					$return = array('resultCode' => -3, 'resultData' => 'No data found');
				}
				echo json_encode($return);
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/************** Function for Generate random number *************/
	function generateRandomString($size)
	{
		$size = intval($size);
		if ($size == 0) {
			return NULL;
		}
		$charSet = "ABCHEFGHJKMNPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz0";
		$len = strlen($charSet);
		$str = '';
		$i = 0;
		while (strlen($str) < $size) {
			$num = rand(0, ($len - 1));
			$tmp = substr($charSet, $num, 1);
			$str = $str . $tmp;
			$i++;
		}
		return $str;
	}
	/************ PRIVATE ADDRESS FUNCTION START HERE ************/
	public function checkUserStatus($userId)
	{
		$getUserData = $this->Address_model->getUserData($userId);
		if ($getUserData['status'] == -1) {
			$return = array(
				'resultCode' => -1,
				'resultData' => 'This account has been deleted'
			);
			echo json_encode($return);
			exit;
		} else if ($getUserData['status'] == -5) {
			$return = array(
				'resultCode' => -5,
				'resultData' => 'This account has been blocked'
			);
			echo json_encode($return);
			exit;
		} else if ($getUserData['status'] == 0) {
			$return = array(
				'resultCode' => 0,
				'resultData' => 'This account has been deactivated'
			);
			echo json_encode($return);
			exit;
		}
	}
	/*********	Function for addAddressPrivate  *******/
	// public function addAddressPrivate()
	// {
	// 	// echo "<pre>";
	// 	// print_r($this->input->post());die;
	// 	try {
	// 		if ($this->get_request_method() != "POST") {
	// 			$return = array(
	// 				'resultCode' => -7,
	// 				'resultData' => 'Please check the request method'
	// 			);
	// 			echo json_encode($return);
	// 			die;
	// 		} else {
	// 			$return;
	// 			$entityBody = file_get_contents('php://input');
	
	// 			$data = json_decode($entityBody, true);
	// 			if (isset($data['addressPicture']) && isset($data['userId']) && isset($data['shortName']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['address']) && isset($data['emailId']) && isset($data['contactNumber']) && isset($data['landmark'])) {
	// 				/******* userStatusCheck ********/
	// 				$this->checkUserStatus($data['userId']);
	// 				/*******************************/

	// 				/********** Upload imageUrl **********/
	// 				$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
	// 				file_put_contents($temp_file_path, base64_decode($data['addressPicture']));
	// 				$image_info = getimagesize($temp_file_path);
	// 				$_FILES['img'] = array(
	// 					'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
	// 					'tmp_name' => $temp_file_path,
	// 					'size'  => filesize($temp_file_path),
	// 					'error' => UPLOAD_ERR_OK,
	// 					'type'  => $image_info['mime'],
	// 				);
	// 				ADDRESS_UPLOAD_DIR;
	// 				if(!empty($data['addressPicture'])){
	// 					$img = $data['addressPicture'];
	// 					$img = str_replace('data:image/png;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$img = str_replace('data:image/jpg;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$img = str_replace('data:image/jpeg;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$imgdata = base64_decode($img);
	// 					$getMaxId = $this->Address_model->privateAddressMaxid();
	// 					$maxId = $getMaxId['addressId'] + 1;
	// 					$file = $maxId . '.png';
	// 					$files = ADDRESS_UPLOAD_DIR . 'privateAddress/' . $file;
	// 					$success = file_put_contents($files, $imgdata);
	// 					$insertUserData['imageURL'] = 'address/privateAddress/' . $file;
	// 				} else {
	// 					$insertUserData['imageURL'] = '';
	// 				}
	// 				/********* Generate Plus Code ************/
	// 				$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
	// 				/***************************************/
	// 				// get Country and state names with api an save them to db
	// 				$addressData = file_get_contents('https://api.opencagedata.com/geocode/v1/json?q='.$data['latitude'].'+'.$data['longitude'].'&key=0d21545c40174c14a4371a80e9fb222d');
	// 				$decodeAddress = json_decode($addressData, true);
	// 				$dataCountry['countryName'] = strtolower($decodeAddress['results'][0]['components']['country']);
	// 				$dataCountry['createDate'] = date('Y-m-d H:i:s a');
	// 				$dataState['stateName'] = $decodeAddress['results'][0]['components']['state'];
	// 				$dataState['createDate'] = $dataCountry['createDate'];
	// 				*************************************
	// 				$insertUserData['countryId'] = $dataState['countryId'] = $this->Address_model->insertCountryAndGetCountryId($dataCountry);
	// 				$insertUserData['stateId'] = $this->Address_model->insertStateAndGetStateId($dataState);

	// 				/********* Generate reference Code ************/
	// 				$referenceCode = $this->generateRandomString(10);
	// 				/***************************************/

	// 				/********* Generate QR Code ************/

	// 				$dataToGenerateQrCode = isset($_GET['data']) ? $_GET['data'] : $referenceCode . '-PRI';
	// 				$sizeOfQrCode = isset($_GET['size']) ? $_GET['size'] : '500x500';
	// 				$logoToShowInQrCode = isset($_GET['logo']) ? $_GET['logo'] : BASE_URL . "assets/img/icons/appicon.png";


	// 				$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $sizeOfQrCode . '&chl=' . urlencode($dataToGenerateQrCode));

	// 				$logoToShowInQrCode = imagecreatefromstring(file_get_contents($logoToShowInQrCode));
	// 				$QR_width = imagesx($QR);
	// 				$QR_height = imagesy($QR);

	// 				$logo_width = imagesx($logoToShowInQrCode);
	// 				$logo_height = imagesy($logoToShowInQrCode);

	// 				$logo_qr_width = $QR_width / 3;
	// 				$scale = $logo_width / $logo_qr_width;
	// 				$logo_qr_height = $logo_height / $scale;

	// 				imagecopyresampled($QR, $logoToShowInQrCode, $QR_width / 3, $QR_height / 3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
	// 				$qrCodeURL = $maxId . '.png';
	// 				$qrCodeFilePath = ADDRESS_UPLOAD_DIR . 'privateAddress/qrCodes/' . $qrCodeURL;
	// 				imagepng($QR, $qrCodeFilePath);

	// 				/***************************************/

	// 				$insertUserData['qrCodeURL'] = 'address/privateAddress/qrCodes/' . $qrCodeURL;
	// 				$insertUserData['userId'] = $data['userId'];
	// 				$insertUserData['shortName'] = $data['shortName'];
	// 				$insertUserData['address'] = $data['address'];
	// 				$insertUserData['landmark'] = $data['landmark'];
	// 				$insertUserData['latitude'] = $data['latitude'];
	// 				$insertUserData['longitude'] = $data['longitude'];
	// 				$insertUserData['emailId'] = $data['emailId'];
	// 				$insertUserData['plusCode'] = $generatePlusCode;
	// 				$insertUserData['referenceCode'] = $referenceCode;
	// 				$insertUserData['status']  = '1';
	// 				$insertUserData['createDate'] = date('Y-m-d H:i:s a');
	// 				$contactNumber	= $data['contactNumber'];
	// 				$successData = $this->Address_model->insertPrivateAddress($insertUserData, $contactNumber);

	// 				if (!empty($successData)) {

	// 					$return = array(
	// 						'resultCode' => 1,
	// 						'resultData' => array('addressId' => $successData)
	// 					);
	// 				} else {
	// 					$return = array(
	// 						'resultCode' => -2,
	// 						'resultData' => 'Something went wrong'
	// 					);
	// 				}
	// 			} else {
	// 				$return = array(
	// 					'resultCode' => -6,
	// 					'resultData' => 'All fields not send'
	// 				);
	// 			}
	// 			echo json_encode($return);
	// 			die;
	// 		}
	// 	} catch (Exception $e) {
	// 		echo 'Received exception : ',  $e->getMessage(), "\n";
	// 	}
	// }
	public function addAddressPrivate()
	{
	
	
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = $this->input->post();
	
				$data = $this->input->post();
				if (isset($data['address_tag']) && isset($data['userId']) && isset($data['plus_code']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['unique_link']) && isset($data['country']) && isset($data['city']) && isset($data['street_name']) && isset($data['building_name']) && isset($data['entrance_name']) && isset($data['direction_text']) ) {
					
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					
					    $linkData = $data['unique_link'];
						$unique_url = base_url().$linkData;
						
						$query = $this->db->from('privateAddresses');
						$query->where("unique_link =",$unique_url);
						$query->where("status =",1);
					    $address = $query->get()->result_array();
					   
					    if(!empty($address[0])){
					    	$return = array(
							'resultCode' => 0,
							'resultData' => 'link already has been taken by another.'
							);
							echo json_encode($return);
							die;
					    }
						//print_r($_FILES['street_image']); die();
					/********** Upload imageUrl **********/
					    if(!empty($_FILES['street_image'])){
                        $config['upload_path']          = './uploads/address/privateAddress/';
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                         if(!$this->upload->do_upload('street_image'))
			                {
			                    $profile_pic_error = $this->upload->display_errors();
			                    print_r($profile_pic_error);
			                }
                        $street_image = $img_upload['file_name']; 
                        $upload_data = $this->upload->data();         
                    	$profile_pic_name = $upload_data['file_name'];
                        $image = $street_image;
                        $insertUserData['street_image'] = 'uploads/address/privateAddress/' . $profile_pic_name;
                             
                        }

                        if(!empty($_FILES['building_image'])){
                        $config['upload_path']          = './uploads/address/privateAddress/';
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                        $img_upload = $this->upload->do_upload('building_image');    
                        $upload_data = $this->upload->data();         
                        $building_image = $img_upload['file_name'];    
                        $image = $building_image;
                        $insertUserData['building_image'] = 'uploads/address/privateAddress/' . $_FILES['building_image']['name'];
                        }

                        if(!empty($_FILES['entrance_image'])){
                        $config['upload_path']          = './uploads/address/privateAddress/';
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                        $this->upload->do_upload('entrance_image');    
                        $upload_data = $this->upload->data();         
                        $entrance_image = $upload_data['file_name'];    
                        $image = $entrance_image;
                        $insertUserData['entrance_image'] = 'uploads/address/privateAddress/' . $_FILES['entrance_image']['name'];
                        }
					/********* Generate Plus Code ************/
					$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
					/***************************************/
					// get Country and state names with api an save them to db
					$addressData = file_get_contents('https://api.opencagedata.com/geocode/v1/json?q='.$data['latitude'].'+'.$data['longitude'].'&key=0d21545c40174c14a4371a80e9fb222d');
					$decodeAddress = json_decode($addressData, true);
					$dataCountry['countryName'] = strtolower($decodeAddress['results'][0]['components']['country']);
					$dataCountry['createDate'] = date('Y-m-d H:i:s a');
					$dataState['stateName'] = $decodeAddress['results'][0]['components']['state'];
					$dataState['createDate'] = $dataCountry['createDate'];
					/***************************************/
					$insertUserData['countryId'] = $dataState['countryId'] = $this->Address_model->insertCountryAndGetCountryId($dataCountry);
					$insertUserData['stateId'] = $this->Address_model->insertStateAndGetStateId($dataState);

					/********* Generate reference Code ************/
					// $referenceCode = $this->generateRandomString(10);
					$referenceCode = $unique_url;
					/***************************************/

					/********* Generate QR Code ************/

					// $dataToGenerateQrCode = isset($_GET['data']) ? $_GET['data'] : $referenceCode . '-PRI';
					$dataToGenerateQrCode = isset($_GET['data']) ? $_GET['data'] : $referenceCode;
					$sizeOfQrCode = isset($_GET['size']) ? $_GET['size'] : '500x500';
					$logoToShowInQrCode = isset($_GET['logo']) ? $_GET['logo'] : BASE_URL . "assets/img/icons/appicon.png";


					$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $sizeOfQrCode . '&chl=' . urlencode($dataToGenerateQrCode));

					$logoToShowInQrCode = imagecreatefromstring(file_get_contents($logoToShowInQrCode));
					$QR_width = imagesx($QR);
					$QR_height = imagesy($QR);

					$logo_width = imagesx($logoToShowInQrCode);
					$logo_height = imagesy($logoToShowInQrCode);

					$logo_qr_width = $QR_width / 3;
					$scale = $logo_width / $logo_qr_width;
					$logo_qr_height = $logo_height / $scale;

					imagecopyresampled($QR, $logoToShowInQrCode, $QR_width / 3, $QR_height / 3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
					$qrCodeURL = $maxId . '.png';
					$qrCodeFilePath = ADDRESS_UPLOAD_DIR . 'privateAddress/qrCodes/' . $qrCodeURL;
					imagepng($QR, $qrCodeFilePath);

					/***************************************/

					$insertUserData['qrCodeURL'] = 'address/privateAddress/qrCodes/' . $qrCodeURL;
					$insertUserData['userId'] = $data['userId'];
					$insertUserData['shortName'] = $data['shortName'];
					$insertUserData['address'] = $data['address'];
					$insertUserData['landmark'] = $data['landmark'];
					$insertUserData['latitude'] = $data['latitude'];
					$insertUserData['longitude'] = $data['longitude'];
					$insertUserData['address_tag'] = $data['address_tag'];
					$insertUserData['plus_code'] = $data['plus_code'];
					$insertUserData['unique_link'] = $unique_url;
					$insertUserData['street_name'] = $data['street_name'];
					$insertUserData['emailId'] = $data['emailId'];
					$insertUserData['building_name'] = $data['building_name'];
					$insertUserData['entrance_name'] = $data['entrance_name'];
					$insertUserData['direction_text'] = $data['direction_text'];
					$insertUserData['city'] = $data['city'];
					$insertUserData['country'] = $data['country'];
					$insertUserData['street_img_type'] = $data['street_img_type'];
					$insertUserData['building_img_type'] = $data['building_img_type'];
					$insertUserData['entrance_img_type'] = $data['entrance_img_type'];
					$insertUserData['plusCode'] = $generatePlusCode;
					$insertUserData['referenceCode'] = $referenceCode;
					$insertUserData['status']  = '1';
					$insertUserData['createDate'] = date('Y-m-d H:i:s a');
					$contactNumber	= $data['contactNumber'];
					$successData = $this->Address_model->insertPrivateAddress($insertUserData, $contactNumber);

					if (!empty($successData)) {

						$return = array(
							'resultCode' => 1,
							'resultData' => array('addressId' => $successData)
						);
					} else {
						$return = array(
							'resultCode' => -2,
							'resultData' => 'Something went wrong'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getPrivateAddresses  *******/
	public function getPrivateAddresses()
	{

		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				// $data = json_decode($entityBody, true);
				$data = $this->input->post();
				if (isset($data['userId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$getAddressArray = $this->Address_model->getPrivateAddresses($data['userId']);

					foreach ($getAddressArray as $key => $value) {
				

					    $query = $this->db->from('user');
					    $query->where(['userId'=>$value['userId']]);
					    $user = $query->get()->result_array();
					    $userImage = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
					    $getAddressArray[$key]['profilePicURL'] = $userImage;
					     $getAddressArray[$key]['profilePicURL'] = $userImage;
					    $addressDeafultImage = 'uploads/address/address_default_image.png';
					    $getAddressArray[$key]['street_image'] = (!empty($value['street_image']))?$value['street_image']:$addressDeafultImage;
					    $getAddressArray[$key]['building_image'] = (!empty($value['building_image']))?$value['building_image']:$addressDeafultImage;
					    $getAddressArray[$key]['entrance_image'] = (!empty($value['entrance_image']))?$value['entrance_image']:$addressDeafultImage;
					    $getAddressArray[$key]['userName'] = $user[0]['name'];
					}
					
					if (!empty($getAddressArray)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $getAddressArray
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getOwnPrivateAddressDetail  *******/
	public function getOwnPrivateAddressDetail()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$getAddressDeatilArray = $this->Address_model->getOwnPrivateAddressDetail($data['userId'], $data['addressId']);

					if (!empty($getAddressDeatilArray)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $getAddressDeatilArray
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for deletePrivateAddress  *******/
	public function deletePrivateAddress()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				// $data = json_decode($entityBody, true);
				$data = $this->input->post();
				if (isset($data['userId']) && isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$deleteAddressData = $this->Address_model->deletePrivateAddress($data['userId'], $data['addressId']);

					if (!empty($deleteAddressData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => 'Address deleted successfully'
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

	/*********	Function for updatePrivateAddressPrimaryInformation  *******/
	public function updatePrivateAddressPrimaryInformation()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['addressId']) && isset($data['userId']) && isset($data['addressPicture'])  && isset($data['shortName']) && isset($data['emailId']) && isset($data['contactNumber'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$getPrivateAddress = $this->Address_model->getPrivateAddress($data['addressId']);
					// print_r($getPrivateAddress);exit;
					if ($getPrivateAddress != 0) {
						/********** Upload imageUrl **********/
						$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
						file_put_contents($temp_file_path, base64_decode($data['addressPicture']));
						$image_info = getimagesize($temp_file_path);
						$_FILES['img'] = array(
							'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
							'tmp_name' => $temp_file_path,
							'size'  => filesize($temp_file_path),
							'error' => UPLOAD_ERR_OK,
							'type'  => $image_info['mime'],
						);
						ADDRESS_UPLOAD_DIR;
						$img = $data['addressPicture'];
						$img = str_replace('data:image/png;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = str_replace('data:image/jpg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = str_replace('data:image/jpeg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$imgdata = base64_decode($img);
						$file = $data['addressId'] . '.png';
						$files = ADDRESS_UPLOAD_DIR . 'privateAddress/' . $file;
						file_put_contents($files, $imgdata);

						$updateUserData['imageURL'] = 'address/privateAddress/' . $file;
						$updateUserData['shortName'] = $data['shortName'];
						$updateUserData['emailId'] = $data['emailId'];
						$contactNumber	= $data['contactNumber'];
						// if(!empty($contactNumber)){
						$deleteContactNumber = $this->Address_model->deleteContactNumbers($data['addressId']);
						// }
						$successData = $this->Address_model->updatePrivateAddressPrimaryInformation($data['addressId'], $updateUserData, $contactNumber);

						if (!empty($successData)) {
							$return = array(
								'resultCode' => 1,
								'resultData' => 'Address updated successfully'
							);
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'Address not matched'
						);
						echo json_encode($return);
						die;
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

	/*********	Function for updatePrivateAddressLocationInformation  *******/
	public function updatePrivateAddressLocationInformation()
	{

		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				// $data = json_decode($entityBody, true);
				$data = $this->input->post();
				// if (isset($data['addressId']) && isset($data['userId']) && isset($data['landmark']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['address'])) {
				if (isset($data['address_tag']) && isset($data['userId']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['plus_code']) && isset($data['unique_link']) && isset($data['country']) && isset($data['city'])&& isset($data['street_name'])&& isset($data['building_name'])&& isset($data['entrance_name'])&& isset($data['direction_text'])&& isset($data['userId'])&& isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$getPrivateAddress = $this->Address_model->getPrivateAddress($data['addressId']);
					if ($getPrivateAddress != 0) {
						/********* Generate Plus Code ************/
						$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
						/***************************************/
						// get Country and state names with api an save them to db
						$addressData = file_get_contents('https://api.opencagedata.com/geocode/v1/json?q='.$data['latitude'].'+'.$data['longitude'].'&key=0d21545c40174c14a4371a80e9fb222d');
						$decodeAddress = json_decode($addressData, true);
						$dataCountry['countryName'] = strtolower($decodeAddress['results'][0]['components']['country']);
						$dataCountry['createDate'] = date('Y-m-d H:i:s a');
						$dataState['stateName'] = $decodeAddress['results'][0]['components']['state'];
						$dataState['createDate'] = $dataCountry['createDate'];
						/***************************************/
						$updateUserData['countryId'] = $dataState['countryId'] = $this->Address_model->insertCountryAndGetCountryId($dataCountry);
						$updateUserData['stateId'] = $this->Address_model->insertStateAndGetStateId($dataState);
						$updateUserData['address'] = $data['address'];
						$updateUserData['landmark'] = $data['landmark'];
						$updateUserData['latitude'] = $data['latitude'];
						$updateUserData['longitude'] = $data['longitude'];
						$updateUserData['address_tag'] = $data['address_tag'];
					    $updateUserData['plus_code'] = $data['plus_code'];
					    $updateUserData['unique_link'] = $data['unique_link'];
						$updateUserData['street_name'] = $data['street_name'];
						$updateUserData['emailId'] = $data['emailId'];
						$updateUserData['building_name'] = $data['building_name'];
						$updateUserData['entrance_name'] = $data['entrance_name'];
						$updateUserData['direction_text'] = $data['direction_text'];
						$updateUserData['city'] = $data['city'];
						$updateUserData['plusCode'] = $generatePlusCode;
						// }
						$this->Address_model->updatePrivateAddressLocationInformation($data['addressId'], $updateUserData);

						$return = array(
							'resultCode' => 1,
							'resultData' => 'Address updated successfully'
						);
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'Address not matched'
						);
						echo json_encode($return);
						die;
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

  public function updateAddress()
	{

	
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				// $data = json_decode($entityBody, true);
				$data = $this->input->post();
				if (isset($data['address_tag']) && isset($data['userId']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['plus_code']) && isset($data['unique_link']) && isset($data['country']) && isset($data['city']) && isset($data['street_name'])&& isset($data['building_name']) && isset($data['entrance_name'])&& isset($data['direction_text']) && isset($data['userId'])&& isset($data['addressId']) && isset($data['type'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/****UNIQUE URL***/

						if(strpos($data['unique_link'], 'http://') !== 0) {
						 $unique_url = base_url().$data['unique_link'];
						} else {
						  $unique_url = $data['unique_link'];
						}

						if($data['type']=='private'){
						 $query = $this->db->from('privateAddresses');
						 }else{
						 $query = $this->db->from('publicAddresses');	
						 }
					    $query->where('userId !=', $data['userId']);
					    $query->where("unique_link =",$unique_url);
					    $query->where("status =",1);
					    $address = $query->get()->result_array();


					    if(!empty($address[0])){
					    	$return = array(
							'resultCode' => 0,
							'resultData' => 'link already has been taken by another.'
							);
							echo json_encode($return);
							die;
					    }
					   
					/*get address detail*/

						if($data['type']=='private'){
						$getAddress = $this->Address_model->getPrivateAddress($data['addressId']);	
						}else{
						$getAddress = $this->Address_model->getPublicAddres($data['addressId']);	
				
						}
					

					/*upload images*/
					  if(!empty($_FILES['street_image'])){
					  	if($data['type']=='private'){
                    		$path = './uploads/address/privateAddress/';
                    	}else{
                    		$path = './uploads/address/publicAddress/';
                    	}
                        $config['upload_path']          = $path;
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                         if(!$this->upload->do_upload('street_image'))
			                {
			                    $profile_pic_error = $this->upload->display_errors();
			                    print_r($profile_pic_error);
			                }
                        $street_image = $img_upload['file_name']; 
                        $upload_data = $this->upload->data();         
                    	$profile_pic_name = $upload_data['file_name'];
                    	if($data['type']=='private'){
                    		$imageUrl = 'uploads/address/privateAddress/' . $profile_pic_name;
                    	}else{
                    		$imageUrl = './uploads/address/publicAddress/'. $profile_pic_name;
                    	}
                        $updateUserData['street_image'] = $imageUrl;
                             
                        }else{
                        	if($this->input->post('street_img_remove')=='remove'){
                        	 $updateUserData['street_image'] = null;	
                        	}
                        }

                        if(!empty($_FILES['building_image'])){
	                    	if($data['type']=='private'){
	                    		$path = './uploads/address/privateAddress/';
	                    	}else{
	                    		$path = './uploads/address/publicAddress/';
	                    	}
                        $config['upload_path']          = $path;
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                        $img_upload = $this->upload->do_upload('building_image');    
                        $upload_data = $this->upload->data();         
                        $building_image = $upload_data['file_name'];    
	                     	if($data['type']=='private'){
	                    		$imageUrl = 'uploads/address/privateAddress/' . $building_image;
	                    	}else{
	                    		$imageUrl = './uploads/address/publicAddress/'. $building_image;
	                    	}
                          $updateUserData['building_image'] = $imageUrl;
                        }else{
                        	if($this->input->post('building_img_remove')=='remove'){
                        	 $updateUserData['building_image'] = null;	
                        	}
                        }


                         if(!empty($_FILES['entrance_image'])){
	                    	if($data['type']=='private'){
	                    		$path = './uploads/address/privateAddress/';
	                    	}else{
	                    		$path = './uploads/address/publicAddress/';
	                    	}
                        $config['upload_path']          = $path;
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                        $img_upload = $this->upload->do_upload('entrance_image');    
                        $upload_data = $this->upload->data();         
                        $entrance_image = $upload_data['file_name'];    
	                     	if($data['type']=='private'){
	                    		$imageUrl = 'uploads/address/privateAddress/' . $entrance_image;
	                    	}else{
	                    		$imageUrl = './uploads/address/publicAddress/'. $entrance_image;
	                    	}
                        $updateUserData['entrance_image'] = $imageUrl;
                        }else{
                        	if($this->input->post('entrance_img_remove')=='remove'){
                        	 $updateUserData['entrance_image'] = null;	
                        	}
                        }

                      
                        /********* Generate Plus Code ************/
						$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
						/***************************************/
						// get Country and state names with api an save them to db
						$addressData = file_get_contents('https://api.opencagedata.com/geocode/v1/json?q='.$data['latitude'].'+'.$data['longitude'].'&key=0d21545c40174c14a4371a80e9fb222d');
						$decodeAddress = json_decode($addressData, true);
						$dataCountry['countryName'] = strtolower($decodeAddress['results'][0]['components']['country']);
						$dataCountry['createDate'] = date('Y-m-d H:i:s a');
						$dataState['stateName'] = $decodeAddress['results'][0]['components']['state'];
						$dataState['createDate'] = $dataCountry['createDate'];
						/***************************************/
						$updateUserData['countryId'] = $dataState['countryId'] = $this->Address_model->insertCountryAndGetCountryId($dataCountry);
						$updateUserData['stateId'] = $this->Address_model->insertStateAndGetStateId($dataState);
						$updateUserData['address'] = $data['address'];
						$updateUserData['landmark'] = $data['landmark'];
						$updateUserData['latitude'] = $data['latitude'];
						$updateUserData['longitude'] = $data['longitude'];
						$updateUserData['address_tag'] = $data['address_tag'];
					    $updateUserData['plus_code'] = $data['plus_code'];
					    $updateUserData['unique_link'] = $unique_url;
						$updateUserData['street_name'] = $data['street_name'];
						$updateUserData['emailId'] = $data['emailId'];
						$updateUserData['building_name'] = $data['building_name'];
						$updateUserData['entrance_name'] = $data['entrance_name'];
						$updateUserData['direction_text'] = $data['direction_text'];
						$updateUserData['city'] = $data['city'];
						$updateUserData['country'] = $data['country'];
						$updateUserData['street_img_type'] = $data['street_img_type'];
					    $updateUserData['building_img_type'] = $data['building_img_type'];
					    $updateUserData['entrance_img_type'] = $data['entrance_img_type'];
						$updateUserData['plusCode'] = $generatePlusCode;

					if (!empty($getAddress) && ($getAddress['userId']== $data['userId'])) {
						if($data['type']=='private'){
						$this->Address_model->updatePrivateAddress($data['addressId'], $updateUserData);
				    	}else{
				    	$this->Address_model->updatePublicAddress($data['addressId'], $updateUserData);
				    	}

						$return = array(
							'resultCode' => 1,
							'resultData' => 'Address updated successfully'
						);
					} else {

						$updateUserData['userId'] = $data['userId'];
						$updateUserData['status'] = 1;
						$updateUserData['entrance_image'] = $getAddress['entrance_image'];
						$updateUserData['building_image'] = $getAddress['building_image'];
						$updateUserData['street_image'] = $getAddress['street_image'];
						if($data['type']=='private'){
						$this->Address_model->insertPrivateAddress($updateUserData, []);
						$this->Address_model->addressDeletePublic($data['userId'],$data['addressId']);
				    	}else{
				    	$this->Address_model->insertPublicAddress($updateUserData,[],[]);
				    	$this->Address_model->addressDeletePrivate($data['userId'],$data['addressId']);
				    	}
						$return = array(
							'resultCode' => 1,
							'resultData' => 'Address updated successfully.'
						);
						echo json_encode($return);
						die;
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

	/*********	Function for editPrivateAddress  *******/
	// public function editPrivateAddress()
	// {
	// 	try {
	// 		if ($this->get_request_method() != "POST") {
	// 			$return = array(
	// 				'resultCode' => -7,
	// 				'resultData' => 'Please check the request method'
	// 			);
	// 			echo json_encode($return);
	// 			die;
	// 		} else {
	// 			$return;
	// 			$entityBody = file_get_contents('php://input');
	// 			$data = json_decode($entityBody, true);
	// 			if (isset($data['addressId']) && isset($data['userId']) && isset($data['addressPicture']) && isset($data['shortName']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['address']) && isset($data['emailId']) && isset($data['contactNumber'])) {
	// 				/******* userStatusCheck ********/
	// 				$this->checkUserStatus($data['userId']);
	// 				/*******************************/

	// 				$getPrivateAddress = $this->Address_model->getPrivateAddress($data['addressId']);
	// 				if ($getPrivateAddress != 0) {
	// 					/********** Upload imageUrl **********/
	// 					$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
	// 					file_put_contents($temp_file_path, base64_decode($data['addressPicture']));
	// 					$image_info = getimagesize($temp_file_path);
	// 					$_FILES['img'] = array(
	// 						'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
	// 						'tmp_name' => $temp_file_path,
	// 						'size'  => filesize($temp_file_path),
	// 						'error' => UPLOAD_ERR_OK,
	// 						'type'  => $image_info['mime'],
	// 					);
	// 					ADDRESS_UPLOAD_DIR;
	// 					$img = $data['addressPicture'];
	// 					$img = str_replace('data:image/png;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$img = str_replace('data:image/jpg;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$img = str_replace('data:image/jpeg;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$imgdata = base64_decode($img);
	// 					$file = $data['addressId'] . '.png';
	// 					$files = ADDRESS_UPLOAD_DIR . 'privateAddress/' . $file;
	// 					file_put_contents($files, $imgdata);
	// 					/********* Generate Plus Code ************/
	// 					$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
	// 					/***************************************/
	// 					$updateUserData['imageURL'] = 'address/privateAddress/' . $file;
	// 					$updateUserData['shortName'] = $data['shortName'];
	// 					$updateUserData['address'] = $data['address'];
	// 					$updateUserData['latitude'] = $data['latitude'];
	// 					$updateUserData['longitude'] = $data['longitude'];
	// 					$updateUserData['emailId'] = $data['emailId'];
	// 					$updateUserData['plusCode'] = $generatePlusCode;

	// 					$contactNumber	= $data['contactNumber'];
	// 					// if(!empty($contactNumber)){
	// 					$deleteContactNumber = $this->Address_model->deleteContactNumbers($data['addressId']);
	// 					// }
	// 					$successData = $this->Address_model->updatePrivateAddress($data['addressId'], $updateUserData, $contactNumber);

	// 					if (!empty($successData)) {
	// 						$return = array(
	// 							'resultCode' => 1,
	// 							'resultData' => 'Address updated successfully'
	// 						);
	// 					} else {
	// 						$return = array(
	// 							'resultCode' => -3,
	// 							'resultData' => 'No data found'
	// 						);
	// 					}
	// 				} else {
	// 					$return = array(
	// 						'resultCode' => -4,
	// 						'resultData' => 'Address not matched'
	// 					);
	// 					echo json_encode($return);
	// 					die;
	// 				}
	// 			} else {
	// 				$return = array(
	// 					'resultCode' => -6,
	// 					'resultData' => 'All fields not send'
	// 				);
	// 			}
	// 			echo json_encode($return);
	// 			die;
	// 		}
	// 	} catch (Exception $e) {
	// 		echo 'Received exception : ',  $e->getMessage(), "\n";
	// 	}
	// }
	/************ PRIVATE ADDRESS FUNCTION END HERE ************/



	/*********	Function for addAddressPrivate  *******/
	// public function addAddressPublic()
	// {
	// 	try {
	// 		if ($this->get_request_method() != "POST") {
	// 			$return = array(
	// 				'resultCode' => -7,
	// 				'resultData' => 'Please check the request method'
	// 			);
	// 			echo json_encode($return);
	// 			die;
	// 		} else {
	// 			$return;
	// 			$entityBody = file_get_contents('php://input');
	// 			$data = json_decode($entityBody, true);
	// 			if (isset($data['logoPicture']) && isset($data['userId']) && isset($data['shortName']) && isset($data['categoryId']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['address']) && isset($data['landmark']) && isset($data['emailId']) && isset($data['socialMedia']) && isset($data['description']) && isset($data['locationPictureURL']) && isset($data['serviceDescription']) && isset($data['contactNumber']) && isset($data['deliveryAvailable']) && isset($data['workingHours'])) {
	// 				/******* userStatusCheck ********/
	// 				$this->checkUserStatus($data['userId']);
	// 				/*******************************/

	// 				/********** Upload pictureURL **********/
	// 				$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
	// 				file_put_contents($temp_file_path, base64_decode($data['logoPicture']));
	// 				$image_info = getimagesize($temp_file_path);
	// 				$_FILES['img'] = array(
	// 					'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
	// 					'tmp_name' => $temp_file_path,
	// 					'size'  => filesize($temp_file_path),
	// 					'error' => UPLOAD_ERR_OK,
	// 					'type'  => $image_info['mime'],
	// 				);
	// 				ADDRESS_UPLOAD_DIR;
	// 				// ****************logo URL *******************/
	// 				if(!empty($data['logoPicture'])){
	// 					$img = $data['logoPicture'];
	// 					$img = str_replace('data:image/png;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$img = str_replace('data:image/jpg;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$img = str_replace('data:image/jpeg;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$imgdata = base64_decode($img);
	// 					$getMaxId = $this->Address_model->publicAddressMaxid();
	// 					$maxId = $getMaxId['addressId'] + 1;
	// 					$file = $maxId . '.png';
	// 					$files = ADDRESS_UPLOAD_DIR . 'publicAddress/' . $file;
	// 					$success = file_put_contents($files, $imgdata);

	// 					$insertData['logoURL'] = 'address/publicAddress/' . $file;
	// 				} else {
	// 					$insertData['logoURL'] = '';
	// 				}
	// 				//*********************************************** */

	// 				// ****************logo URL *******************/
	// 				file_put_contents($temp_file_path, base64_decode($data['locationPictureURL']));
	// 				$image_info = getimagesize($temp_file_path);
	// 				$_FILES['img'] = array(
	// 					'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
	// 					'tmp_name' => $temp_file_path,
	// 					'size'  => filesize($temp_file_path),
	// 					'error' => UPLOAD_ERR_OK,
	// 					'type'  => $image_info['mime'],
	// 				);
	// 				ADDRESS_UPLOAD_DIR;
	// 				if(!empty($data['locationPictureURL'])){
	// 					$img1 = $data['locationPictureURL'];
	// 					$img1 = str_replace('data:image/png;base64,', '', $img1);
	// 					$img1 = str_replace(' ', '+', $img1);
	// 					$img1 = str_replace('data:image/jpg;base64,', '', $img1);
	// 					$img1 = str_replace(' ', '+', $img1);
	// 					$img1 = str_replace('data:image/jpeg;base64,', '', $img1);
	// 					$img1 = str_replace(' ', '+', $img1);
	// 					$imgdata1 = base64_decode($img1);
	// 					$file1 = $maxId . '.png';
	// 					$files1 = ADDRESS_UPLOAD_DIR . 'publicAddress/locationImages/' . $file1;
	// 					$success1 = file_put_contents($files1, $imgdata1);

	// 					$insertData['locationPictureURL'] = 'address/publicAddress/locationImages/' . $file1;
	// 				} else {
	// 					$insertData['locationPictureURL'] = '';
	// 				}
						
	// 				//*********************************************** */

	// 				/********* Generate Plus Code ************/
	// 				$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
	// 				/***************************************/

	// 				// get Country and state names with api an save them to db
	// 				$addressData = file_get_contents('https://api.opencagedata.com/geocode/v1/json?q='.$data['latitude'].'+'.$data['longitude'].'&key=0d21545c40174c14a4371a80e9fb222d');
	// 				$decodeAddress = json_decode($addressData, true);
	// 				$dataCountry['countryName'] = strtolower($decodeAddress['results'][0]['components']['country']);
	// 				$dataCountry['createDate'] = date('Y-m-d H:i:s a');
	// 				$dataState['stateName'] = $decodeAddress['results'][0]['components']['state'];
	// 				$dataState['createDate'] = $dataCountry['createDate'];
	// 				/***************************************/
	// 				$insertData['countryId'] = $dataState['countryId'] = $this->Address_model->insertCountryAndGetCountryId($dataCountry);
	// 				$insertData['stateId'] = $this->Address_model->insertStateAndGetStateId($dataState);
	// 				// print_r($countryId . ' ' . $stateId);exit;


	// 				/********* Generate reference Code ************/
	// 				$referenceCode = $this->generateRandomString(10);
	// 				/***************************************/

	// 				/********* Generate QR Code ************/

	// 				$dataToGenerateQrCode = isset($_GET['data']) ? $_GET['data'] : $referenceCode . '-PUB';
	// 				$sizeOfQrCode = isset($_GET['size']) ? $_GET['size'] : '500x500';
	// 				$logoToShowInQrCode = isset($_GET['logo']) ? $_GET['logo'] : BASE_URL . "assets/img/icons/appicon.png";


	// 				$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $sizeOfQrCode . '&chl=' . urlencode($dataToGenerateQrCode));

	// 				$logoToShowInQrCode = imagecreatefromstring(file_get_contents($logoToShowInQrCode));
	// 				$QR_width = imagesx($QR);
	// 				$QR_height = imagesy($QR);

	// 				$logo_width = imagesx($logoToShowInQrCode);
	// 				$logo_height = imagesy($logoToShowInQrCode);

	// 				$logo_qr_width = $QR_width / 3;
	// 				$scale = $logo_width / $logo_qr_width;
	// 				$logo_qr_height = $logo_height / $scale;

	// 				imagecopyresampled($QR, $logoToShowInQrCode, $QR_width / 3, $QR_height / 3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
	// 				$qrCodeURL = $maxId . '.png';
	// 				$qrCodeFilePath = ADDRESS_UPLOAD_DIR . 'publicAddress/qrCodes/' . $qrCodeURL;

	// 				imagepng($QR, $qrCodeFilePath);

	// 				/***************************************/

	// 				$insertData['qrCodeURL'] = 'address/publicAddress/qrCodes/' . $qrCodeURL;
	// 				$insertData['userId'] = $data['userId'];
	// 				$insertData['shortName'] = $data['shortName'];
	// 				$insertData['address'] = $data['address'];
	// 				$insertData['landmark'] = $data['landmark'];
	// 				$insertData['latitude'] = $data['latitude'];
	// 				$insertData['longitude'] = $data['longitude'];
	// 				$insertData['emailId'] = $data['emailId'];
	// 				$insertData['plusCode'] = $generatePlusCode;
	// 				$insertData['referenceCode'] = $referenceCode;
	// 				$insertData['isDeliveryAvailable'] = $data['deliveryAvailable'];
	// 				$insertData['categoryId'] = $data['categoryId'];
	// 				$insertData['description'] = $data['description'];
	// 				$insertData['serviceDescription'] = $data['serviceDescription'];
	// 				/***** Social media data ******/
	// 				if(!empty($data['socialMedia']['website'])){
	// 					$insertData['websiteURL'] = $this->createWebsiteURL($data['socialMedia']['website']);
	// 				} else {
	// 					$insertData['websiteURL'] = '';
	// 				}
	// 				if(!empty($data['socialMedia']['facebook'])){
	// 					$insertData['facebookURL'] = $this->createSocialURL($data['socialMedia']['facebook']);
	// 				} else {
	// 					$insertData['facebookURL'] = '';
	// 				}
	// 				if(!empty($data['socialMedia']['twitter'])){
	// 					$insertData['twitterURL'] = $this->createSocialURL($data['socialMedia']['twitter']);
	// 				} else {
	// 					$insertData['twitterURL'] = '';
	// 				}
	// 				if(!empty($data['socialMedia']['linkedin'])){
	// 					$insertData['linkedInURL'] = $this->createSocialURL($data['socialMedia']['linkedin']);
	// 				} else {
	// 					$insertData['linkedInURL'] = '';
	// 				}
	// 				if(!empty($data['socialMedia']['instagram'])){
	// 					$insertData['instagramURL'] = $this->createSocialURL($data['socialMedia']['instagram']);
	// 				} else {
	// 					$insertData['instagramURL'] = '';
	// 				}
					
	// 				/***********************/
	// 				$insertData['status']  = '1';
	// 				$insertData['createDate'] = date('Y-m-d H:i:s a');
	// 				$contactNumber	= $data['contactNumber'];
	// 				$workingHours	= $data['workingHours'];
	// 				$successData = $this->Address_model->insertPublicAddress($insertData, $contactNumber, $workingHours);

	// 				if (!empty($successData)) {
	// 					$return = array(
	// 						'resultCode' => 1,
	// 						'resultData' => array('addressId' => $successData)
	// 					);
	// 				} else {
	// 					$return = array(
	// 						'resultCode' => -3,
	// 						'resultData' => 'No data found'
	// 					);
	// 				}
	// 			} else {
	// 				$return = array(
	// 					'resultCode' => -6,
	// 					'resultData' => 'All fields not send'
	// 				);
	// 			}
	// 			echo json_encode($return);
	// 			die;
	// 		}
	// 	} catch (Exception $e) {
	// 		echo 'Received exception : ',  $e->getMessage(), "\n";
	// 	}
	// }
	public function addAddressPublic()
	{
		
		// echo "<pre>";
		// print_r($this->input->post());die;
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				// $data = json_decode($entityBody, true);
				$data = $this->input->post();
				if (isset($data['address_tag']) && isset($data['userId']) && isset($data['plus_code']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['unique_link']) && isset($data['country']) && isset($data['city']) && isset($data['street_name']) && isset($data['building_name']) && isset($data['entrance_name']) && isset($data['direction_text'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					    $linkData = $data['unique_link'];
						$unique_url = base_url().$linkData;
						
						$query = $this->db->from('publicAddresses');
						$query->where("unique_link =",$unique_url);
						$query->where("status =",1);
					    $address = $query->get()->result_array();
					   
					    if(!empty($address[0])){
					    	$return = array(
							'resultCode' => 0,
							'resultData' => 'link already has been taken by another.'
							);
							echo json_encode($return);
							die;
					    }
					/********** Upload pictureURL **********/
					    if(!empty($_FILES['street_image'])){
                        $config['upload_path']          = './uploads/address/publicAddress/';
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                         if(!$this->upload->do_upload('street_image'))
			                {
			                    $profile_pic_error = $this->upload->display_errors();
			                    print_r($profile_pic_error);
			                }
                        $street_image = $img_upload['file_name']; 
                        $upload_data = $this->upload->data();         
                    	$profile_pic_name = $upload_data['file_name'];
                        $image = $street_image;
                        $insertData['street_image'] = 'uploads/address/publicAddress/' . $profile_pic_name;
                             
                        }

                        if(!empty($_FILES['building_image'])){
                        $config['upload_path']          = './uploads/address/publicAddress/';
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                        $img_upload = $this->upload->do_upload('building_image');    
                        $upload_data = $this->upload->data();         
                        $building_image = $img_upload['file_name'];    
                        $image = $building_image;
                        $insertData['building_image'] = 'uploads/address/publicAddress/' . $_FILES['building_image']['name'];
                        }

                        if(!empty($_FILES['entrance_image'])){
                        $config['upload_path']          = './uploads/address/publicAddress/';
                        $config['allowed_types']        = '*';
                        $config['max_size']             = 5000;
                        $config['max_width']            = 10240;
                        $config['max_height']           = 7680;
                        $this->load->library('upload', $config);
                        $this->upload->do_upload('entrance_image');    
                        $upload_data = $this->upload->data();         
                        $entrance_image = $upload_data['file_name'];    
                        $image = $entrance_image;
                        $insertData['entrance_image'] = 'uploads/address/publicAddress/' . $_FILES['entrance_image']['name'];
                        }
					// ****************logo URL *******************/
					if(!empty($data['logoPicture'])){
						$img = $data['logoPicture'];
						$img = str_replace('data:image/png;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = str_replace('data:image/jpg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = str_replace('data:image/jpeg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$imgdata = base64_decode($img);
						$getMaxId = $this->Address_model->publicAddressMaxid();
						$maxId = $getMaxId['addressId'] + 1;
						$file = $maxId . '.png';
						$files = ADDRESS_UPLOAD_DIR . 'publicAddress/' . $file;
						$success = file_put_contents($files, $imgdata);

						$insertData['logoURL'] = 'address/publicAddress/' . $file;
					} else {
						$insertData['logoURL'] = '';
					}
					//*********************************************** */

					// ****************logo URL *******************/
					file_put_contents($temp_file_path, base64_decode($data['locationPictureURL']));
					$image_info = getimagesize($temp_file_path);
					$_FILES['img'] = array(
						'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
						'tmp_name' => $temp_file_path,
						'size'  => filesize($temp_file_path),
						'error' => UPLOAD_ERR_OK,
						'type'  => $image_info['mime'],
					);
					ADDRESS_UPLOAD_DIR;
					if(!empty($data['locationPictureURL'])){
						$img1 = $data['locationPictureURL'];
						$img1 = str_replace('data:image/png;base64,', '', $img1);
						$img1 = str_replace(' ', '+', $img1);
						$img1 = str_replace('data:image/jpg;base64,', '', $img1);
						$img1 = str_replace(' ', '+', $img1);
						$img1 = str_replace('data:image/jpeg;base64,', '', $img1);
						$img1 = str_replace(' ', '+', $img1);
						$imgdata1 = base64_decode($img1);
						$file1 = $maxId . '.png';
						$files1 = ADDRESS_UPLOAD_DIR . 'publicAddress/locationImages/' . $file1;
						$success1 = file_put_contents($files1, $imgdata1);

						$insertData['locationPictureURL'] = 'address/publicAddress/locationImages/' . $file1;
					} else {
						$insertData['locationPictureURL'] = '';
					}
						
					//*********************************************** */

					/********* Generate Plus Code ************/
					$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
					/***************************************/

					// get Country and state names with api an save them to db
					$addressData = file_get_contents('https://api.opencagedata.com/geocode/v1/json?q='.$data['latitude'].'+'.$data['longitude'].'&key=0d21545c40174c14a4371a80e9fb222d');
					$decodeAddress = json_decode($addressData, true);
					$dataCountry['countryName'] = strtolower($decodeAddress['results'][0]['components']['country']);
					$dataCountry['createDate'] = date('Y-m-d H:i:s a');
					$dataState['stateName'] = $decodeAddress['results'][0]['components']['state'];
					$dataState['createDate'] = $dataCountry['createDate'];
					/***************************************/
					$insertData['countryId'] = $dataState['countryId'] = $this->Address_model->insertCountryAndGetCountryId($dataCountry);
					$insertData['stateId'] = $this->Address_model->insertStateAndGetStateId($dataState);
					// print_r($countryId . ' ' . $stateId);exit;


					/********* Generate reference Code ************/
					// $referenceCode = $this->generateRandomString(10);
					$referenceCode = $unique_url;
					/***************************************/

					/********* Generate QR Code ************/

					$dataToGenerateQrCode = isset($_GET['data']) ? $_GET['data'] : $referenceCode;
					// $dataToGenerateQrCode = isset($_GET['data']) ? $_GET['data'] : $referenceCode . '-PUB';
					$sizeOfQrCode = isset($_GET['size']) ? $_GET['size'] : '500x500';
					$logoToShowInQrCode = isset($_GET['logo']) ? $_GET['logo'] : BASE_URL . "assets/img/icons/appicon.png";


					$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $sizeOfQrCode . '&chl=' . urlencode($dataToGenerateQrCode));

					$logoToShowInQrCode = imagecreatefromstring(file_get_contents($logoToShowInQrCode));
					$QR_width = imagesx($QR);
					$QR_height = imagesy($QR);

					$logo_width = imagesx($logoToShowInQrCode);
					$logo_height = imagesy($logoToShowInQrCode);

					$logo_qr_width = $QR_width / 3;
					$scale = $logo_width / $logo_qr_width;
					$logo_qr_height = $logo_height / $scale;

					imagecopyresampled($QR, $logoToShowInQrCode, $QR_width / 3, $QR_height / 3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
					$qrCodeURL = $maxId . '.png';
					$qrCodeFilePath = ADDRESS_UPLOAD_DIR . 'publicAddress/qrCodes/' . $qrCodeURL;

					imagepng($QR, $qrCodeFilePath);

					/***************************************/

					$insertData['qrCodeURL'] = 'address/publicAddress/qrCodes/' . $qrCodeURL;
					$insertData['userId'] = $data['userId'];
					$insertData['shortName'] = $data['shortName'];
					$insertData['address'] = $data['address'];
					$insertData['landmark'] = $data['landmark'];
					$insertData['latitude'] = $data['latitude'];
					$insertData['longitude'] = $data['longitude'];
					$insertData['emailId'] = $data['emailId'];
					$insertData['plusCode'] = $generatePlusCode;
					$insertData['referenceCode'] = $referenceCode;
					$insertData['isDeliveryAvailable'] = $data['deliveryAvailable'];
					$insertData['categoryId'] = $data['categoryId'];
					$insertData['description'] = $data['description'];
					$insertData['serviceDescription'] = $data['serviceDescription'];
					$insertData['address_tag'] = $data['address_tag'];
					$insertData['plus_code'] = $data['plus_code'];
					$insertData['unique_link'] = $unique_url;
					$insertData['street_name'] = $data['street_name'];
					$insertData['emailId'] = $data['emailId'];
					$insertData['country'] = $data['country'];
					$insertData['street_img_type'] = $data['street_img_type'];
					$insertData['building_img_type'] = $data['building_img_type'];
					$insertData['entrance_img_type'] = $data['entrance_img_type'];
					$insertData['city'] = $data['city'];
					$insertData['building_name'] = $data['building_name'];
					$insertData['entrance_name'] = $data['entrance_name'];
					$insertData['direction_text'] = $data['direction_text'];
					/***** Social media data ******/
					if(!empty($data['socialMedia']['website'])){
						$insertData['websiteURL'] = $this->createWebsiteURL($data['socialMedia']['website']);
					} else {
						$insertData['websiteURL'] = '';
					}
					if(!empty($data['socialMedia']['facebook'])){
						$insertData['facebookURL'] = $this->createSocialURL($data['socialMedia']['facebook']);
					} else {
						$insertData['facebookURL'] = '';
					}
					if(!empty($data['socialMedia']['twitter'])){
						$insertData['twitterURL'] = $this->createSocialURL($data['socialMedia']['twitter']);
					} else {
						$insertData['twitterURL'] = '';
					}
					if(!empty($data['socialMedia']['linkedin'])){
						$insertData['linkedInURL'] = $this->createSocialURL($data['socialMedia']['linkedin']);
					} else {
						$insertData['linkedInURL'] = '';
					}
					if(!empty($data['socialMedia']['instagram'])){
						$insertData['instagramURL'] = $this->createSocialURL($data['socialMedia']['instagram']);
					} else {
						$insertData['instagramURL'] = '';
					}
					
					/***********************/
					$insertData['status']  = '1';
					$insertData['createDate'] = date('Y-m-d H:i:s a');
					$contactNumber	= $data['contactNumber'];
					$workingHours	= $data['workingHours'];
					$successData = $this->Address_model->insertPublicAddress($insertData, $contactNumber, $workingHours);

					if (!empty($successData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => array('addressId' => $successData)
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	// strip and create social URL
	public function createWebsiteURL($url)
	{
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);
		$url = 'http://'.$url;
		return $url;
	}
	public function createSocialURL($url)
	{
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);
		$url = 'https://'.$url;
		return $url;
	}
	/******* Add addBusinessServices ************/
	public function addBusinessServices()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				$header = $this->input->request_headers();
				if (!($this->input->post('addressId')) && !($this->input->post('addressId')) && !($this->input->post('serviceId')) && !($this->input->post('attachment'))) {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
					echo json_encode($return);
					die;
				} else {
					if ($this->input->post('serviceId') == '-1') {
						$getMaxId = $this->Address_model->getPublicAddressServicesMaxid();
						$maxId = $getMaxId['serviceId'] + 1;
						$proof_id_name = $_FILES["attachment"]["name"];
						$image = explode(".", $proof_id_name);
						/**************************/
						$lastKey = end($image);
						/**************************/
						$config['upload_path'] = ADDRESS_UPLOAD_DIR . 'publicAddress/servicesDoc/';
						$config['allowed_types'] = 'jpg|png|jpeg|pdf|doc|docx';
						$config['file_name'] = $maxId . '.' . $lastKey;
						$config['overwrite'] = TRUE;

						$this->load->library('upload', $config);
						$this->upload->do_upload('attachment');
						$image_upload = $this->upload->data();
						/*************************** */
						if ($_FILES['attachment']['name'] == '') {
							$attachmentUrl = '';
						} else {
							$attachmentUrl = 'address/publicAddress/servicesDoc/' . $maxId . '.' . $lastKey;
						}
						//print_r($attachmentUrl); exit;
						$addressImagesData['addressId'] = $this->input->post('addressId');
						$addressImagesData['serviceURL'] = $attachmentUrl;
						$addressImagesData['serviceDocType'] = $lastKey;
						$addressImagesData['status']  = '1';
						$addressImagesData['createDate'] = date('Y-m-d H:i:s a');

						$successData = $this->Address_model->addBusinessServices($addressImagesData);
					} else {
						$successData = $this->Address_model->updateBusinessServices($this->input->post('serviceId'));
					}
					if (!empty($successData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => 'success'
						);
						echo json_encode($return);
						die;
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
						echo json_encode($return);
						die;
					}
				}
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for addBusinessImages  *******/
	public function addBusinessImages()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['image']) && isset($data['addressId']) && isset($data['imageId'])) {
					/******* userStatusCheck ********/
					//$this->checkUserStatus($data['userId']);
					/*******************************/
					if ($data['imageId'] == '-1') {
						/********** Upload imageUrl **********/
						$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
						file_put_contents($temp_file_path, base64_decode($data['image']));
						$image_info = getimagesize($temp_file_path);
						$_FILES['img'] = array(
							'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
							'tmp_name' => $temp_file_path,
							'size'  => filesize($temp_file_path),
							'error' => UPLOAD_ERR_OK,
							'type'  => $image_info['mime'],
						);
						ADDRESS_UPLOAD_DIR;
						$img = $data['image'];
						$img = str_replace('data:image/png;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = str_replace('data:image/jpg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = str_replace('data:image/jpeg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$imgdata = base64_decode($img);
						$getMaxId = $this->Address_model->getBusinessImagesMaxid();
						$maxId = $getMaxId['imageId'] + 1;
						$file = $maxId . '.png';
						$files = ADDRESS_UPLOAD_DIR . 'publicAddress/businessImages/' . $file;
						$success = file_put_contents($files, $imgdata);
						/********* Generate Plus Code ************/
						$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
						/***************************************/
						$insertData['addressId'] = $data['addressId'];
						$insertData['imageURL'] = 'address/publicAddress/businessImages/' . $file;
						$insertData['status']  = '1';
						$insertData['createDate'] = date('Y-m-d H:i:s a');
						$successData = $this->Address_model->addBusinessImages($insertData);
					} else {
						$successData = $this->Address_model->updateBusinessImages($data['imageId']);
					}
					if (!empty($successData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => 'success'
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for deletePublicAddress  *******/
	public function deletePublicAddress()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				// $data = json_decode($entityBody, true);
				$data = $this->input->post();
				if (isset($data['userId']) && isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$deleteAddressData = $this->Address_model->deletePublicAddress($data['userId'], $data['addressId']);

					if (!empty($deleteAddressData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => 'Address deleted successfully'
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getOwnPublicAddressDetail  *******/
	public function getOwnPublicAddressDetail()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$getAddressDeatilArray = $this->Address_model->getOwnPublicAddressDetail($data['userId'], $data['addressId']);

					if (!empty($getAddressDeatilArray)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $getAddressDeatilArray
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getPublicAddresses  *******/
	public function getPublicAddresses()
	{
		
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				// $data = json_decode($entityBody, true);
				$data = $this->input->post();
				if (isset($data['userId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$getAddressArray = $this->Address_model->getAddressesPublic($data['userId']);

					foreach ($getAddressArray as $key => $value) {
						// $query = $this->db->from('countries');
					 //    $query->where(['countryId'=>$value['countryId']]);
					 //    $country = $query->get()->result_array();
					 //    $getAddressArray[$key]['country'] = $country[0]['countryName'];

					 //    $query = $this->db->from('states');
					 //    $query->where(['stateId'=>$value['stateId']]);
					 //    $state = $query->get()->result_array();
					 //    $getAddressArray[$key]['state'] = $state[0]['stateName'];

					    $query = $this->db->from('user');
					    $query->where(['userId'=>$value['userId']]);
					    $user = $query->get()->result_array();
					    $userImage = (!empty($user[0]['profilePicURL']))?$user[0]['profilePicURL']:'uploads/user/user_default_image.jpeg';
					    $getAddressArray[$key]['profilePicURL'] = $userImage;
					    $addressDeafultImage = 'uploads/address/address_default_image.png';
					    $getAddressArray[$key]['street_image'] = (!empty($value['street_image']))?$value['street_image']:$addressDeafultImage;
					    $getAddressArray[$key]['building_image'] = (!empty($value['building_image']))?$value['building_image']:$addressDeafultImage;
					    $getAddressArray[$key]['entrance_image'] = (!empty($value['entrance_image']))?$value['entrance_image']:$addressDeafultImage;
					    $getAddressArray[$key]['userName'] = $user[0]['name'];
					}
					if (!empty($getAddressArray)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $getAddressArray
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

	// /*********	Function for updatePublicAddressPrimaryInformation  *******/
	public function updatePublicAddressPrimaryInformation()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['addressId']) && isset($data['userId']) && isset($data['shortName']) && isset($data['categoryId']) && isset($data['description'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$getPublicAddress = $this->Address_model->getPublicAddress($data['addressId']);

					if (!empty($getPublicAddress)) {
						$updateData['shortName'] = $data['shortName'];
						$updateData['categoryId'] = $data['categoryId'];
						$updateData['description'] = $data['description'];

						$successData = $this->Address_model->updatePublicAddress($data['addressId'], $updateData);
						if (!empty($successData)) {
							$return = array(
								'resultCode' => 1,
								'resultData' => 'Address updated successfully'
							);
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'Address not matched'
						);
						echo json_encode($return);
						die;
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

	// /*********	Function for updatePublicAddressLocationInformation  *******/
	public function updatePublicAddressLocationInformation()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['addressId']) && isset($data['userId']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['address']) && isset($data['landmark']) && isset($data['locationPictureURL']) && isset($data['logoPicture'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$getPublicAddress = $this->Address_model->getPublicAddress($data['addressId']);

					if (!empty($getPublicAddress)) {
						/********** Upload imageUrl **********/

						$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
						file_put_contents($temp_file_path, base64_decode($data['logoPicture']));
						$image_info = getimagesize($temp_file_path);
						$_FILES['img'] = array(
							'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
							'tmp_name' => $temp_file_path,
							'size'  => filesize($temp_file_path),
							'error' => UPLOAD_ERR_OK,
							'type'  => $image_info['mime'],
						);
						ADDRESS_UPLOAD_DIR;
						// ******************** logo URL *****************/
						$img = $data['logoPicture'];
						$img = str_replace('data:image/png;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = str_replace('data:image/jpg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = str_replace('data:image/jpeg;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$imgdata = base64_decode($img);
						$file = $data['addressId'] . '.png';
						$files = ADDRESS_UPLOAD_DIR . 'publicAddress/' . $file;
						file_put_contents($files, $imgdata);

						// ********************** locationPictureURL ************
						file_put_contents($temp_file_path, base64_decode($data['locationPictureURL']));
						$image_info = getimagesize($temp_file_path);
						$_FILES['img'] = array(
							'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
							'tmp_name' => $temp_file_path,
							'size'  => filesize($temp_file_path),
							'error' => UPLOAD_ERR_OK,
							'type'  => $image_info['mime'],
						);
						ADDRESS_UPLOAD_DIR;
						$img1 = $data['locationPictureURL'];
						$img1 = str_replace('data:image/png;base64,', '', $img1);
						$img1 = str_replace(' ', '+', $img1);
						$img1 = str_replace('data:image/jpg;base64,', '', $img1);
						$img1 = str_replace(' ', '+', $img1);
						$img1 = str_replace('data:image/jpeg;base64,', '', $img1);
						$img1 = str_replace(' ', '+', $img1);
						$imgdata1 = base64_decode($img1);
						$file1 = $data['addressId'] . '.png';
						$files1 = ADDRESS_UPLOAD_DIR . 'publicAddress/locationImages/' . $file1;
						file_put_contents($files1, $imgdata1);

						/********* Generate Plus Code ************/
						$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
						/***************************************/
						// get Country and state names with api an save them to db
						$addressData = file_get_contents('https://api.opencagedata.com/geocode/v1/json?q='.$data['latitude'].'+'.$data['longitude'].'&key=0d21545c40174c14a4371a80e9fb222d');
						$decodeAddress = json_decode($addressData, true);
						$dataCountry['countryName'] = strtolower($decodeAddress['results'][0]['components']['country']);
						$dataCountry['createDate'] = $dataState['createDate'] = date('Y-m-d H:i:s a');
						$dataState['stateName'] = $decodeAddress['results'][0]['components']['state'];
						/***************************************/
						$updateData['countryId'] = $dataState['countryId'] = $this->Address_model->insertCountryAndGetCountryId($dataCountry);
						$updateData['stateId'] = $this->Address_model->insertStateAndGetStateId($dataState);
						$updateData['logoURL'] = 'address/publicAddress/' . $file;
						$updateData['address'] = $data['address'];
						$updateData['latitude'] = $data['latitude'];
						$updateData['longitude'] = $data['longitude'];
						$updateData['landmark'] = $data['landmark'];
						$updateData['plusCode'] = $generatePlusCode;
						$updateData['locationPictureURL'] = 'address/publicAddress/locationImages/' . $file;
						/***********************/
						$this->Address_model->deleteBusinessImages($data['addressId']);
						$successData = $this->Address_model->updatePublicAddress($data['addressId'], $updateData);
						if (!empty($successData)) {
							$return = array(
								'resultCode' => 1,
								'resultData' => 'Address updated successfully'
							);
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'Address not matched'
						);
						echo json_encode($return);
						die;
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	// /*********	Function for updatePublicAddressServices  *******/
	public function updatePublicAddressServices()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['addressId']) && isset($data['userId']) && isset($data['serviceDescription'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$getPublicAddress = $this->Address_model->getPublicAddress($data['addressId']);

					if (!empty($getPublicAddress)) {
						$updateData['serviceDescription'] = $data['serviceDescription'];

						$successData = $this->Address_model->updatePublicAddress($data['addressId'], $updateData);

						if (!empty($successData)) {
							$this->Address_model->deleteServicesImages($data['addressId']);
							$return = array(
								'resultCode' => 1,
								'resultData' => 'Address updated successfully'
							);
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'Address not matched'
						);
						echo json_encode($return);
						die;
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	// /*********	Function for updatePublicAddressMiscellaneousInformation  *******/
	public function updatePublicAddressMiscellaneousInformation()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['addressId']) && isset($data['userId']) && isset($data['emailId'])  && isset($data['deliveryAvailable']) && isset($data['contactNumber']) && isset($data['socialMedia']) && isset($data['workingHours'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$getPublicAddress = $this->Address_model->getPublicAddress($data['addressId']);

					if (!empty($getPublicAddress)) {
						$updateData['emailId'] = $data['emailId'];
						$updateData['isDeliveryAvailable'] = $data['deliveryAvailable'];
						/***** Social media data ******/
						if(!empty($data['socialMedia']['website'])){
							$insertData['websiteURL'] = $this->createWebsiteURL($data['socialMedia']['website']);
						} else {
							$insertData['websiteURL'] = '';
						}
						if(!empty($data['socialMedia']['facebook'])){
							$insertData['facebookURL'] = $this->createSocialURL($data['socialMedia']['facebook']);
						} else {
							$insertData['facebookURL'] = '';
						}
						if(!empty($data['socialMedia']['twitter'])){
							$insertData['twitterURL'] = $this->createSocialURL($data['socialMedia']['twitter']);
						} else {
							$insertData['twitterURL'] = '';
						}
						if(!empty($data['socialMedia']['linkedin'])){
							$insertData['linkedInURL'] = $this->createSocialURL($data['socialMedia']['linkedin']);
						} else {
							$insertData['linkedInURL'] = '';
						}
						if(!empty($data['socialMedia']['instagram'])){
							$insertData['instagramURL'] = $this->createSocialURL($data['socialMedia']['instagram']);
						} else {
							$insertData['instagramURL'] = '';
						}
						/***********************/
						$this->Address_model->deletePublicContactNumbers($data['addressId']);

						$successData = $this->Address_model->updatePublicAddressMiscellaneousInformation($data['addressId'], $updateData, $data['contactNumber'], $data['workingHours']);

						if (!empty($successData)) {
							$return = array(
								'resultCode' => 1,
								'resultData' => 'Address updated successfully'
							);
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'Address not matched'
						);
						echo json_encode($return);
						die;
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	// /*********	Function for editPublicAddress  *******/
	// public function editPublicAddress()
	// {
	// 	try {
	// 		if ($this->get_request_method() != "POST") {
	// 			$return = array(
	// 				'resultCode' => -7,
	// 				'resultData' => 'Please check the request method'
	// 			);
	// 			echo json_encode($return);
	// 			die;
	// 		} else {
	// 			$return;
	// 			$entityBody = file_get_contents('php://input');
	// 			$data = json_decode($entityBody, true);
	// 			if (isset($data['addressId']) && isset($data['userId']) && isset($data['logoPicture']) && isset($data['shortName']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['address']) && isset($data['emailId']) && isset($data['contactNumber']) && isset($data['socialMedia']) && isset($data['workingHours'])) {
	// 				/******* userStatusCheck ********/
	// 				$this->checkUserStatus($data['userId']);
	// 				/*******************************/

	// 				$getPublicAddress = $this->Address_model->getPublicAddress($data['addressId']);

	// 				if (!empty($getPublicAddress)) {
	// 					/********** Upload imageUrl **********/

	// 					$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
	// 					file_put_contents($temp_file_path, base64_decode($data['logoPicture']));
	// 					$image_info = getimagesize($temp_file_path);
	// 					$_FILES['img'] = array(
	// 						'name' => uniqid() . '.' . preg_replace('!\w+/!', '', $image_info['mime']),
	// 						'tmp_name' => $temp_file_path,
	// 						'size'  => filesize($temp_file_path),
	// 						'error' => UPLOAD_ERR_OK,
	// 						'type'  => $image_info['mime'],
	// 					);
	// 					ADDRESS_UPLOAD_DIR;
	// 					$img = $data['logoPicture'];
	// 					$img = str_replace('data:image/png;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$img = str_replace('data:image/jpg;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$img = str_replace('data:image/jpeg;base64,', '', $img);
	// 					$img = str_replace(' ', '+', $img);
	// 					$imgdata = base64_decode($img);
	// 					$file = $data['addressId'] . '.png';
	// 					$files = ADDRESS_UPLOAD_DIR . 'publicAddress/' . $file;
	// 					file_put_contents($files, $imgdata);
	// 					/********* Generate Plus Code ************/
	// 					$generatePlusCode = OpenLocationCode::encode($data['latitude'], $data['longitude']);
	// 					/***************************************/
	// 					$updateData['logoURL'] = 'address/publicAddress/' . $file;
	// 					$updateData['shortName'] = $data['shortName'];
	// 					$updateData['address'] = $data['address'];
	// 					$updateData['latitude'] = $data['latitude'];
	// 					$updateData['longitude'] = $data['longitude'];
	// 					$updateData['emailId'] = $data['emailId'];
	// 					$updateData['plusCode'] = $generatePlusCode;
	// 					$updateData['isDeliveryAvailable'] = $data['deliveryAvailable'];
	// 					$updateData['description'] = $data['description'];
	// 					/***** Social media data ******/
	// 					$updateData['websiteURL'] = $data['socialMedia']['website'];
	// 					$updateData['facebookURL'] = $data['socialMedia']['facebook'];
	// 					$updateData['twitterURL'] = $data['socialMedia']['twitter'];
	// 					$updateData['linkedInURL'] = $data['socialMedia']['linkedin'];
	// 					$updateData['instagramURL'] = $data['socialMedia']['instagram'];
	// 					/***********************/

	// 					// if(!empty($data['contactNumber'])){
	// 					$this->Address_model->deletePublicContactNumbers($data['addressId']);
	// 					// }
	// 					$successData = $this->Address_model->updatePublicAddress($data['addressId'], $updateData, $data['contactNumber'], $data['workingHours']);
	// 					// echo"<pre>";print_r($successData);exit();
	// 					if (!empty($successData)) {
	// 						$deleteServicesImages = $this->Address_model->deleteServicesImages($data['addressId']);
	// 						$return = array(
	// 							'resultCode' => 1,
	// 							'resultData' => 'Address updated successfully'
	// 						);
	// 					} else {
	// 						$return = array(
	// 							'resultCode' => -3,
	// 							'resultData' => 'No data found'
	// 						);
	// 					}
	// 				} else {
	// 					$return = array(
	// 						'resultCode' => -4,
	// 						'resultData' => 'Address not matched'
	// 					);
	// 					echo json_encode($return);
	// 					die;
	// 				}
	// 			} else {
	// 				$return = array(
	// 					'resultCode' => -6,
	// 					'resultData' => 'All fields not send'
	// 				);
	// 			}
	// 			echo json_encode($return);
	// 			die;
	// 		}
	// 	} catch (Exception $e) {
	// 		echo 'Received exception : ',  $e->getMessage(), "\n";
	// 	}
	// }
	/*********	Function for addSavedList  *******/
	public function addSavedList()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['listName'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$savedAddress['listName'] = $data['listName'];
					$savedAddress['userId'] = $data['userId'];
					$savedAddress['createDate'] = date('Y-m-d H:i:s a');
					$savedAddress['status'] = 1;
					$addAddressData = $this->User_model->saveUserAddress($savedAddress);

					if (!empty($addAddressData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $addAddressData
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for editSavedList  *******/
	public function editSavedList()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['listId']) && isset($data['listName'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$savedAddress['listName'] = $data['listName'];
					$savedAddress['userId'] = $data['userId'];
					$savedAddress['listId'] = $data['listId'];
					$savedAddress['createDate'] = date('Y-m-d H:i:s a');
					$savedAddress['status'] = 1;
					$addAddressData = $this->User_model->updateUserAddress($savedAddress);

					if (!empty($addAddressData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => 'Address updated successfully'
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getSavedList  *******/
	public function getSavedList()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				// $data = json_decode($entityBody, true);
				$data = $this->input->post();
				if (isset($data['userId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$getAddressList = $this->Address_model->getAddressArray($data['userId']);

					if (!empty($getAddressList)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $getAddressList
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for saveAddressToSavedList  *******/
	public function saveAddressToSavedList()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['addressId']) && isset($data['isPublic']) && isset($data['listId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$findListData = $this->Address_model->getSavedListData($data['listId']);
					if ($findListData['listCount'] > 0) {
						$savedAddress['listId'] = $data['listId'];
						$savedAddress['addressId'] = $data['addressId'];
						$savedAddress['createDate'] = date('Y-m-d H:i:s a');
						$savedAddress['status'] = 1;
						if ($data['isPublic'] == 1) {
							$getPublicAddress = $this->Address_model->getPublicAddressesData($data['addressId']);
							if ($getPublicAddress['count'] > 0) {
								$insertData =  $this->Address_model->savePublicAddressToSavedList($savedAddress);
								// print_r($insertData);exit;
								if($insertData > 0){
									$return = array(
										'resultCode' => 1,
										'resultData' => 'Address list saved successfully'
									);
								} else {
									$return = array(
										'resultCode' => -11,
										'resultData' => 'Address is already saved'
									);
								}
							} else {
								$return = array(
									'resultCode' => -8,
									'resultData' => 'Address does not exist'
								);
							}
						} else {
							$getPrivateAddress = $this->Address_model->getPrivateAddressesData($data['addressId']);
							if (!empty($getPrivateAddress)) {
								$insertData =  $this->Address_model->savePrivateAddressToSavedList($savedAddress);
								if($insertData > 0){
									$return = array(
										'resultCode' => 1,
										'resultData' => 'Address list saved successfully'
									);
								} else {
									$return = array(
										'resultCode' => -11,
										'resultData' => 'Address is already saved'
									);
								}
							} else {
								$return = array(
									'resultCode' => -8,
									'resultData' => 'Address does not exist'
								);
							}
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'List does not exist'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getSavedListAddresses  *******/
	public function getSavedListAddresses()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['listId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$findListData = $this->Address_model->getSavedListData($data['listId']);
					if (!empty($findListData)) {
						$getSavedListAddresses = $this->Address_model->getSavedListAddresses($data['listId']);

						if (empty($getSavedListAddresses['publicAddresses']) && empty($getSavedListAddresses['privateAddresses'])) {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						} else {
							$return = array(
								'resultCode' => 1,
								'resultData' => $getSavedListAddresses
							);
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'List does not exist'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for unsaveAddressToSavedList  *******/
	public function unsaveAddressToSavedList()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['addressId']) && isset($data['isPublic']) && isset($data['listId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$findListData = $this->Address_model->getSavedListData($data['listId']);
					if (!empty($findListData)) {
						if ($data['isPublic'] == 1) {
							$unsaveList =  $this->Address_model->unsavePublicAddressToSavedList($data['listId'], $data['addressId']);
						} else {
							$unsaveList =  $this->Address_model->unsavePrivateAddressToSavedList($data['listId'], $data['addressId']);
						}
						if (!empty($unsaveList)) {
							$return = array(
								'resultCode' => 1,
								'resultData' => 'Address deleted successfully'
							);
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'List does not exist'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for deleteSavedAddressList  *******/
	public function deleteSavedAddressList()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['listId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$findListData = $this->Address_model->getSavedListData($data['listId']);
					if (!empty($findListData)) {
						$listAndUserRelationStatus = $this->Address_model->checkIfUserAndListRelated($data['userId'], $data['listId']);
						if (!empty($listAndUserRelationStatus)) {
							$deleteSavedAddressList = $this->Address_model->deleteSavedAddressList($listAndUserRelationStatus);
							if ($deleteSavedAddressList > 0) {
								$return = array(
									'resultCode' => 1,
									'resultData' => 'List deleted successfully'
								);
							} else {
								$return = array(
									'resultCode' => -9,
									'resultData' => 'Default List Can\'t be deleted'
								);
							}
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					} else {
						$return = array(
							'resultCode' => -4,
							'resultData' => 'List does not exist'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for searchBusiness  *******/
	public function searchBusiness()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);

				if (isset($data['userId']) && isset($data['searchText']) && isset($data['distance']) && isset($data['categoryId']) && isset($data['latitude']) && isset($data['longitude'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$searchBusinessData = $this->Address_model->searchBusiness($data);
					// print_r($searchBusinessData);exit;
					if (empty($searchBusinessData['userData']) && empty($searchBusinessData['businessData'])) {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					} else {
						$return = array(
							'resultCode' => 1,
							'resultData' => $searchBusinessData
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

	/*********	Function for validateQR  *******/
	public function validateQR()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				// print_r($data['referenceNumber']);exit;
				if (isset($data['userId']) && isset($data['referenceNumber'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$arrReferenceNumber = explode('-', $data['referenceNumber']);
					// echo $arrReferenceNumber[1];exit;
					if ($arrReferenceNumber[1] == 'PUB') {
						$result = $this->Address_model->validateQRPublic($arrReferenceNumber[0]);
						$result['isPublic'] = 1;
					} else {
						$result = $this->Address_model->validateQRPrivate($arrReferenceNumber[0]);
						$result['isPublic'] = 0;
					}
					if (!empty($result['addressId'])) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $result
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for addBusinessView  *******/
	public function addBusinessView()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');

				$data = json_decode($entityBody, true);

				if (isset($data['userId']) && isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$rowCount = $this->Address_model->updatePublicAddressViews($data['userId'], $data['addressId']);
					// print_r($rowCount);exit;
					if ($rowCount > 0) {
						$return = array(
							'resultCode' => 1,
							'resultData' => 'count updated'
						);
					} else if ($rowCount == 0) {
						$data['createDate'] = date('Y-m-d h:i:s');
						$data['count'] = 1;
						$data['status'] = 1;
						if ($this->Address_model->insertPublicAddressViews($data)) {
							// print_r($data);exit;
							$return = array(
								'resultCode' => 1,
								'resultData' => 'inserted data'
							);
						}
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getTrendingBusinesses  *******/
	public function getTrendingBusinesses()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);

				if (isset($data['userId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$trendingBusinessesData = $this->Address_model->getTrendingBusinesses();
					if (!empty($trendingBusinessesData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $trendingBusinessesData
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

	/*********	Function for getTrendingCategories  *******/
	public function getTrendingCategories()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');

				$data = json_decode($entityBody, true);

				if (isset($data['userId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$trendingCategoriesData = $this->Address_model->getTrendingCategories();
					if (!empty($trendingCategoriesData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $trendingCategoriesData
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for recentlyAddedBusinesses  *******/
	public function recentlyAddedBusinesses()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');

				$data = json_decode($entityBody, true);

				if (isset($data['userId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$recentBusinessData = $this->Address_model->recentlyAddedBusinesses();
					if (!empty($recentBusinessData)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $recentBusinessData
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getPrivateAddressDetail  *******/
	public function getPrivateAddressDetail()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$getPrivatecAddressDetailArray = $this->Address_model->getPrivateAddressDetail($data['userId'], $data['addressId']);
					if (!empty($getPrivatecAddressDetailArray)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $getPrivatecAddressDetailArray
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getPublicAddressDetail  *******/
	public function getPublicAddressDetail()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$getPublicAddressDetailArray = $this->Address_model->getPublicAddressDetail($data['userId'], $data['addressId']);
					if (!empty($getPublicAddressDetailArray)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => $getPublicAddressDetailArray
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for shareAddressWithBusiness  *******/
	public function shareAddressWithBusiness()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['receiverId']) && isset($data['addressId']) && isset($data['isAddressPublic'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$arrInsertData['recipientId'] = $data['receiverId'];
					$arrInsertData['senderId'] = $data['userId'];
					$arrInsertData['addressId'] = $data['addressId'];
					$arrInsertData['isAddressPublic'] = ($data['isAddressPublic']=='public')?1:0;
					// $result = $this->Address_model->checkIfAddressAlreadyShared($arrInsertData);
					// print_r($result);exit;
					if($this->Address_model->checkIfAddressAlreadySharedWithBusiness($arrInsertData) == 1){
						$return = array(
							'resultCode' => -11,
							'resultData' => 'This address is already shared'
						);
					} else {
						$arrInsertData['createDate'] = date('Y-m-d H:i:s a');
						$arrInsertData['status'] = 1;
						$result = $this->Address_model->shareAddressWithBusiness($arrInsertData);
						if (!empty($result)) {
							$recipientUserId = $this->Address_model->getRecipientUserId($data['receiverId']);
							$arrPushToken = $this->Address_model->getRecipientPushToken($recipientUserId['userId']);
							if(!empty($arrPushToken)){
								for($j = 0; $j < count($arrPushToken); $j++){
									$fields = array(
										'to' 			=> $arrPushToken[$j]['pushToken'], 
										'notification'  => array(
											'title' 	    	=> 'You have a new notification',
											'body'				=> 'address shared with Business'
										),
										'data'			=> array(
											'type'				=> 'businessShare',
											'isAddressPublic'	=> $data['isAddressPublic'],
											'recipientId'		=> $data['receiverId'],
											'addressId'			=> $data['addressId']
										)
									);
									$fields = json_encode($fields);
									$headers = array( 
										'Authorization: key='.API_ACCESS_KEY, 
										'Content-Type: application/json'
									);
									$ch = curl_init();
									curl_setopt( $ch,CURLOPT_URL,FCM_URL);
									curl_setopt( $ch,CURLOPT_POST,true);
									curl_setopt( $ch,CURLOPT_HTTPHEADER,$headers);
									curl_setopt( $ch,CURLOPT_RETURNTRANSFER,true);
									curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,false);
									curl_setopt( $ch,CURLOPT_POSTFIELDS,$fields);
									$result = curl_exec($ch);
									curl_close($ch);
								}
							}
							$return = array(
								'resultCode' => 1,
								'resultData' => 'success'
							);
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					}

				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for unshareAddressWithBusiness  *******/
	public function unshareAddressWithBusiness()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['receiverId']) && isset($data['addressId']) && isset($data['isAddressPublic'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$arrInsertData['recipientId'] = $data['receiverId'];
					$arrInsertData['senderId'] = $data['userId'];
					$arrInsertData['addressId'] = $data['addressId'];
					$arrInsertData['isAddressPublic'] = $data['isAddressPublic'];
					
					$result = $this->Address_model->unshareAddressWithBusiness($arrInsertData);
					if (!empty($result)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => 'deleted'
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for shareAddressWithUser  *******/
	public function shareAddressWithUser()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['receiverId']) && isset($data['addressId']) && isset($data['isAddressPublic'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					$arrInsertData['recipientId'] = $data['receiverId'];
					$arrInsertData['senderId'] = $data['userId'];
					$arrInsertData['addressId'] = $data['addressId'];
					$arrInsertData['isAddressPublic'] = ($data['isAddressPublic']=='public')?1:0;

					if($this->Address_model->checkIfAddressAlreadySharedWithUser($arrInsertData) > 0){
						$return = array(
							'resultCode' => -11,
							'resultData' => 'This address is already shared'
						);
					} else{
						$arrInsertData['createDate'] = date('Y-m-d H:i:s a');
						$arrInsertData['status'] = 1;
						$result = $this->Address_model->shareAddressWithUser($arrInsertData);
						if (!empty($result)) {
							$arrPushToken = $this->Address_model->getRecipientPushToken($data['receiverId']);
							if(!empty($arrPushToken)){
								for($j = 0; $j < count($arrPushToken); $j++){
									$fields = array(
										'to' 			=> $arrPushToken[$j]['pushToken'], 
										'notification'  => array(
											'title' 	    	=> 'You have a new notification',
											'body'				=> 'address shared with Business'
										),
										'data'			=> array(
											'type'				=> 'userShare',
											'isAddressPublic'	=> $data['isAddressPublic'],
											'addressId'			=> $data['addressId']
										)
									);
									$fields = json_encode($fields);
									$headers = array( 
										'Authorization: key='.API_ACCESS_KEY, 
										'Content-Type: application/json'
									);
									$ch = curl_init();
									curl_setopt( $ch,CURLOPT_URL,FCM_URL);
									curl_setopt( $ch,CURLOPT_POST,true);
									curl_setopt( $ch,CURLOPT_HTTPHEADER,$headers);
									curl_setopt( $ch,CURLOPT_RETURNTRANSFER,true);
									curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,false);
									curl_setopt( $ch,CURLOPT_POSTFIELDS,$fields);
									$result = curl_exec($ch);
									curl_close($ch);
								}
							}
							$return = array(
								'resultCode' => 1,
								'resultData' => 'success'
							);
						} else {
							$return = array(
								'resultCode' => -3,
								'resultData' => 'No data found'
							);
						}
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for unshareAddressWithUser  *******/
	public function unshareAddressWithUser()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['receiverId']) && isset($data['addressId']) && isset($data['isAddressPublic'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$arrInsertData['recipientId'] = $data['receiverId'];
					$arrInsertData['senderId'] = $data['userId'];
					$arrInsertData['addressId'] = $data['addressId'];
					$arrInsertData['isAddressPublic'] = $data['isAddressPublic'];

					$result = $this->Address_model->unshareAddressWithUser($arrInsertData);
					if (!empty($result)) {
						$return = array(
							'resultCode' => 1,
							'resultData' => 'deleted'
						);
					} else {
						$return = array(
							'resultCode' => -3,
							'resultData' => 'No data found'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getBusinessSharedAddresses  *******/
	public function getBusinessSharedAddresses()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['addressId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/
					$result = $this->Address_model->checkUserAddressRelation($data['userId'], $data['addressId']);
					if(!empty($result)){
						$arrResult = $this->Address_model->getBusinessSharedAddresses($data['addressId']);
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
					}
					else{
						$return = array(
							'resultCode' => -10,
							'resultData' => 'This address is not related to this user'
						);
					}
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getUserSharedAddresses  *******/
	public function getUserSharedAddresses()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = $this->input->post();
				// $data = json_decode($entityBody, true);
				if (isset($data['userId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					/*******************************/

					$arrResult = $this->Address_model->getUserSharedAddresses($data['userId']);
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
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getCategoryBusiness  *******/
	public function getCategoryBusiness()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId']) && isset($data['categoryId']) && isset($data['start']) && isset($data['count']) && isset($data['currentLatitude']) && isset($data['currentLongitude'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					// /*******************************/

					$arrResult = $this->Address_model->getCategoryBusiness($data);
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
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}
	/*********	Function for getPrimaryCategories  *******/
	public function getPrimaryCategories()
	{
		try {
			if ($this->get_request_method() != "POST") {
				$return = array(
					'resultCode' => -7,
					'resultData' => 'Please check the request method'
				);
				echo json_encode($return);
				die;
			} else {
				$return;
				$entityBody = file_get_contents('php://input');
				$data = json_decode($entityBody, true);
				if (isset($data['userId'])) {
					/******* userStatusCheck ********/
					$this->checkUserStatus($data['userId']);
					// /*******************************/

					$arrResult = $this->Address_model->getPrimaryCategories();
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
				} else {
					$return = array(
						'resultCode' => -6,
						'resultData' => 'All fields not send'
					);
				}
				echo json_encode($return);
				die;
			}
		} catch (Exception $e) {
			echo 'Received exception : ',  $e->getMessage(), "\n";
		}
	}

	/*yunush get unique link record*/

	public function getAddressLink(){

			$data = $this->input->post();

	    	if (isset($data['unique_link'])) {
					// /*******************************/

	    			$unique_url = $data['unique_link'];
	    			$type = $data['type'];
					$arrResult = $this->Address_model->getUniqueAddress($unique_url);
					$userDetail = $this->User_model->getUserDetailWIthUserId($arrResult[0]['userId']);
					// echo "<pre>";
					// print_r($userDetail);die;
					if (!empty($arrResult)) {
						$data = $arrResult[0];
						$data['profilePicURL'] = $userDetail['profilePicURL'];
						$data['name'] = $userDetail['name'];
						$return = array(
							'resultCode' => 1,
							'resultData' => $data
						);
					} else {
						$return = array(
							'resultCode' => 0,
							'resultData' => 'No data found'
						);
					}
					echo json_encode($return);
				die;
				}
	}

	/*yunush get address detail*/

	public function getAddressDetail(){

			$data = $this->input->post();

	    	if (isset($data['type']) && isset($data['addressId'])) {
					// /*******************************/

	    			$type = $data['type'];
	    			if($data['type']=='private'){
	    			$arrResult = $this->Address_model->getPrivateDetailAddress($data['addressId']);

	    			}else{
	    			$arrResult = $this->Address_model->getPublicDetailAddress($data['addressId']);	
	    			}
					
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
				}
				$return = array(
							'resultCode' => -1,
							'resultData' => 'All field not send.'
						);
					echo json_encode($return);
				die;
	}
}
