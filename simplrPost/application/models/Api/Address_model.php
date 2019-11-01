<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Address_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    function getCategories()
    {
        $this->db->select('categoryId,categoryName');
        $this->db->where('status', '1');
        // $this->db->order_by('categoryId', 'DESC');
        $query = $this->db->get('categories')->result_array();
        return $query;
    }
    function getWeekDays()
    {
        $this->db->select('*');
        $this->db->order_by('dayId', 'ASC');
        $query = $this->db->get('weekDays')->result_array();
        return $query;
    }
    function getUserData($id)
    {
        $this->db->select('status');
        $this->db->where('userId', $id);
        $query = $this->db->get('user')->row_array();
        return $query;
    }
    /******** PRIVATE ADDRESS FUNCTIONS *************/
    function privateAddressMaxid()
    {
        $this->db->select_max('addressId');
        $query = $this->db->get('privateAddresses')->row_array();
        return $query;
    }
    /************ Insert Private Address *************/
    function insertPrivateAddress($insertUserData, $contactNumber)
    {
        $query = $this->db->insert('privateAddresses', $insertUserData);
        $getAddressId = $this->db->insert_id();
        $contactData = array();
        foreach ($contactNumber as $key => $value) {
            $contactData['contactNumber'] = $value['phoneNumber'];
            $contactData['addressId'] = $getAddressId;
            $contactData['status'] = 1;
            $contactData['createDate'] = date('Y-m-d h:i:s a');
            $this->db->insert('privateAddressContactNumbers', $contactData);
        }
        return $getAddressId;
    }
    function getPrivateAddresses($userId)
    {
        $this->db->select('addressId,imageURL AS pictureURL,shortName,plusCode,referenceCode AS addressReferenceId');
        $this->db->where('status', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get('privateAddresses')->result_array();
        return $query;
    }
    function getOwnPrivateAddressDetail($userId, $addressId)
    {
        $this->db->select('addressId,imageURL AS pictureURL,qrCodeURL,shortName,plusCode,referenceCode AS addressReferenceId,address,latitude,longitude,emailId');
        $this->db->where('status', 1);
        $this->db->or_where('status', -5);
        $this->db->where('userId', $userId);
        $this->db->where('addressId', $addressId);
        $addressDeatil = $this->db->get('privateAddresses')->row_array();

        if (!empty($addressDeatil)) {
            $this->db->select('contactNumber AS phoneNumber');
            $this->db->where('addressId', $addressId);
            $this->db->where('status', 1);
            $contactNumber = $this->db->get('privateAddressContactNumbers')->result_array();
            $addressDeatil['contactNumber'] = $contactNumber;
        }
        return $addressDeatil;
    }
    function deletePrivateAddress($userId, $addressId)
    {
        $this->db->where('userId', $userId);
        $this->db->where('addressId', $addressId);
        $query = $this->db->update('privateAddresses', array('status' => -1));

        $this->db->where('addressId', $addressId);
        $query1 = $this->db->update('privateAddressContactNumbers', array('status' => 0));
        return $query;
    }
    function deleteContactNumbers($id)
    {
        $this->db->where('addressId', $id);
        $query = $this->db->update('privateAddressContactNumbers', array('status' => 0));
        return $query;
    }
    // function updatePrivateAddress($id, $update_data, $contactNumbers)
    // {
    //     $this->db->where('addressId', $id);
    //     $query = $this->db->update('privateAddresses', $update_data);
    //     $contactData = array();
    //     foreach ($contactNumbers as $key => $value) {
    //         $this->db->where('addressId', $id);
    //         $this->db->where('contactNumber', $value['phoneNumber']);
    //         $this->db->update('privateAddressContactNumbers', array('status' => 1));

    //         if ($this->db->affected_rows() == 0) {
    //             $contactData['contactNumber'] = $value['phoneNumber'];
    //             $contactData['addressId'] = $id;
    //             $contactData['status'] = 1;
    //             $contactData['createDate'] = date('Y-m-d h:i:s a');
    //             $this->db->insert('privateAddressContactNumbers', $contactData);
    //         }
    //     }
    //     return $id;
    // }
    function updatePrivateAddressPrimaryInformation($id, $update_data, $contactNumbers)
    {
        $this->db->where('addressId', $id);
        $query = $this->db->update('privateAddresses', $update_data);
        $contactData = array();
        foreach ($contactNumbers as $key => $value) {
            $this->db->where('addressId', $id);
            $this->db->where('contactNumber', $value['phoneNumber']);
            $this->db->update('privateAddressContactNumbers', array('status' => 1));

            if ($this->db->affected_rows() == 0) {
                $contactData['contactNumber'] = $value['phoneNumber'];
                $contactData['addressId'] = $id;
                $contactData['status'] = 1;
                $contactData['createDate'] = date('Y-m-d h:i:s a');
                $this->db->insert('privateAddressContactNumbers', $contactData);
            }
        }
        return $id;
    }
    function updatePrivateAddressLocationInformation($id, $update_data)
    {
        $this->db->where('addressId', $id);
        $this->db->update('privateAddresses', $update_data);
        $query = $this->db->affected_rows();
        return $query;
    }

    function getPrivateAddress($addressId)
    {
        $this->db->select('addressId');
        $this->db->where('status', 1);
        $this->db->where('addressId', $addressId);
        $query = $this->db->get('privateAddresses')->row_array();
        return $query;
    }
    /********* PRIVATE FUNCTIONS END HERE *************/

    /********* PUBLIC ADDRESS FUNCTIONS ************/
    function publicAddressMaxid()
    {
        $this->db->select_max('addressId');
        $query = $this->db->get('publicAddresses')->row_array();
        return $query;
    }
    public function insertCountryAndGetCountryId($dataCountry)
    {
        $this->db->select('countryId');
        $this->db->where('countryName', $dataCountry['countryName']);
        $query = $this->db->get('countries')->row_array()['countryId'];
        if(empty($query)){
            $this->db->insert('countries', $dataCountry);
            $query = $this->db->insert_id();
        }
        return $query;
    }
    public function insertStateAndGetStateId($dataState)
    {
        $this->db->select('stateId');
        $this->db->where('stateName', $dataState['stateName']);
        $this->db->where('countryId', $dataState['countryId']);
        $query = $this->db->get('states')->row_array()['stateId'];
        if(empty($query)){
            $this->db->insert('states', $dataState);
            $query = $this->db->insert_id();
        }
        return $query;
    }
    /************ Insert Public Address *************/
    function insertPublicAddress($insertData, $contactNumber, $workingHours)
    {
        //print_r($workingHours); exit;
        $query = $this->db->insert('publicAddresses', $insertData);
        $insert_id = $this->db->insert_id();
        $contactData = array();
        foreach ($contactNumber as $key => $value) {
            $contactData['contactNumber'] = $value['phoneNumber'];
            $contactData['addressId'] = $insert_id;
            $contactData['status'] = 1;
            $contactData['createDate'] = date('Y-m-d h:i:s a');
            $this->db->insert('publicAddressContactNumbers', $contactData);
        }

        $hoursData = array();
        foreach ($workingHours as $key => $value) {
            $hoursData['dayId'] = $value['dayId'];
            $hoursData['businessId'] = $insert_id;
            $hoursData['isOpen'] = $value['isOpen'];
            $hoursData['openTime'] = $value['openTime'];
            $hoursData['closeTime'] = $value['closeTime'];
            $this->db->insert('workingDays', $hoursData);
        }
        return $insert_id;
    }
    function getBusinessImagesMaxid()
    {
        $this->db->select_max('imageId');
        $query = $this->db->get('publicAddressImages')->row_array();
        return $query;
    }
    function addBusinessImages($addressImagesData)
    {
        $query = $this->db->insert('publicAddressImages', $addressImagesData);
        return $query;
    }
    function getPublicAddressServicesMaxid()
    {
        $this->db->select_max('serviceId');
        $query = $this->db->get('publicAddressServices')->row_array();
        return $query;
    }
    function addBusinessServices($addressImagesData)
    {
        $query = $this->db->insert('publicAddressServices', $addressImagesData);
        return $query;
    }
    function deletePublicAddress($userId, $addressId)
    {
        $this->db->where('userId', $userId);
        $this->db->where('addressId', $addressId);
        $query = $this->db->update('publicAddresses', array('status' => -1));

        $this->db->where('addressId', $addressId);
        $query1 = $this->db->update('publicAddressContactNumbers', array('status' => 0));
        return $query;
    }
    function getOwnPublicAddressDetail($userId, $addressId)
    {
        $this->db->select('addressId,latitude,longitude,logoURL,qrCodeURL,shortName,plusCode,categoryId,(select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName,referenceCode AS addressReferenceId,address,websiteURL,facebookURL,twitterURL,linkedInURL,instagramURL,description,isDeliveryAvailable,emailId');
        $this->db->where('status', 1);
        $this->db->or_where('status', -5);
        $this->db->where('userId', $userId);
        $this->db->where('addressId', $addressId);
        $addressDetail = $this->db->get('publicAddresses')->row_array();



        /************************************************* */
        $allData = [];
        /************************************************* */


        //print_r($addressDetail); exit;
        if (!empty($addressDetail)) {
            $this->db->select('contactNumber AS phoneNumber');
            $this->db->where('addressId', $addressId);
            $this->db->where('status', 1);
            $contactNumber = $this->db->get('publicAddressContactNumbers')->result_array();

            $this->db->select('serviceId,serviceURL AS serviceFileURL,serviceDocType AS fileExtention');
            $this->db->where('addressId', $addressId);
            $this->db->where('status', 1);
            $services = $this->db->get('publicAddressServices')->result_array();

            $this->db->select('imageId,imageURL');
            $this->db->where('addressId', $addressId);
            $this->db->where('status', 1);
            $images = $this->db->get('publicAddressImages')->result_array();

            
            $sql = "select weekDays.dayId,weekDays.dayName,coalesce(workingDays.isOpen, 0) as isOpen,workingDays.openTime,workingDays.closeTime from weekDays left join workingDays on weekDays.dayId = workingDays.dayId and businessId = $addressId order by weekDays.dayId";
            $query = $this->db->query($sql);
            $workingHours = $query->result_array();


            $allData['addressId'] = $addressDetail['addressId'];
            $allData['pictureURL'] = $addressDetail['logoURL'];
            $allData['qrCodeURL'] = $addressDetail['qrCodeURL'];
            $allData['shortName'] = $addressDetail['shortName'];
            $allData['plusCode'] = $addressDetail['plusCode'];
            $allData['latitude'] = $addressDetail['latitude'];
            $allData['longitude'] = $addressDetail['longitude'];
            $allData['categoryId'] = $addressDetail['categoryId'];
            $allData['categoryName'] = $addressDetail['categoryName'];
            $allData['addressReferenceId'] = $addressDetail['addressReferenceId'];
            $allData['address'] = $addressDetail['address'];
            $allData['deliveryAvailable'] = $addressDetail['isDeliveryAvailable'];
            $allData['description'] = $addressDetail['description'];
            $allData['emailId'] = $addressDetail['emailId'];

            $allData['socialMedia'] = array(
                'website' => $addressDetail['websiteURL'],
                'facebook' => $addressDetail['facebookURL'],
                'twitter' => $addressDetail['twitterURL'],
                'linkedin' => $addressDetail['linkedInURL'],
                'instagram' => $addressDetail['instagramURL'],
            );

            $allData['contactNumber'] = $contactNumber;
            $allData['services'] = $services;
            $allData['images'] = $images;
            $allData['workingHours'] = $workingHours;

        }
        return $allData;
    }
    function getPublicAddresses($userId)
    {
        $this->db->select('publicAddresses.addressId,publicAddresses.logoURL AS pictureURL,publicAddresses.shortName, publicAddresses.description ,publicAddresses.plusCode,categories.categoryName,publicAddresses.referenceCode AS addressReferenceId');
        $this->db->from('publicAddresses');
        $this->db->where('publicAddresses.status', 1);
        $this->db->where('publicAddresses.userId', $userId);
        $this->db->join('categories', 'categories.categoryId = publicAddresses.categoryId');
        $query = $this->db->get()->result_array();
        return $query;
    }
    function getPublicAddress($addressId)
    {
        $this->db->select('addressId,logoURL AS pictureURL,shortName,plusCode,referenceCode AS addressReferenceId');
        $this->db->where('status', 1);
        $this->db->where('addressId', $addressId);
        $query = $this->db->get('publicAddresses')->result_array();
        return $query;
    }
    function deletePublicContactNumbers($id)
    {
        $this->db->where('addressId', $id);
        $this->db->update('publicAddressContactNumbers', array('status' => 0));
    }
    function deleteBusinessImages($id)
    {
        $sql = "update publicAddressImages set status = 0 where addressId = ".$id;
        $query = $this->db->query($sql);
    }
    function updatePublicAddress($id, $update_data)
    {
        $this->db->where('addressId', $id);
        $query = $this->db->update('publicAddresses', $update_data);
        return $id;
    }
    function updatePublicAddressMiscellaneousInformation($id, $update_data, $contactNumbers, $workingHours)
    {
        $this->db->where('addressId', $id);
        $this->db->update('publicAddresses', $update_data);

        $hoursData = array();
        foreach ($workingHours as $key => $value) {
            $hoursData['dayId'] = $value['dayId'];
            $hoursData['isOpen'] = $value['isOpen'];
            $hoursData['openTime'] = $value['openTime'];
            $hoursData['closeTime'] = $value['closeTime'];

            $this->db->where('dayId', $hoursData['dayId']);
            $this->db->where('businessId', $id);
            $query1 = $this->db->update('workingDays', $hoursData);
        }
        $contactData = array();
        foreach ($contactNumbers as $key => $value) {
            $this->db->where('addressId', $id);
            $this->db->where('contactNumber', $value['phoneNumber']);
            $this->db->update('publicAddressContactNumbers', array('status' => 1));

            if ($this->db->affected_rows() == 0) {
                $contactData['contactNumber'] = $value['phoneNumber'];
                $contactData['addressId'] = $id;
                $contactData['status'] = 1;
                $contactData['createDate'] = date('Y-m-d h:i:s a');
                $this->db->insert('publicAddressContactNumbers', $contactData);
            }
        }
        return $id;
    }
    function deleteServicesImages($addressId)
    {
        $this->db->where('addressId', $addressId);
        $query = $this->db->update('publicAddressServices', array('status' => 0));
    }
    function updateBusinessServices($serviceId)
    {
        $this->db->where('serviceId', $serviceId);
        $query = $this->db->update('publicAddressServices', array('status' => 1));
        return $query;
    }
    function updateBusinessImages($imageId)
    {
        $this->db->where('imageId', $imageId);
        $query = $this->db->update('publicAddressImages', array('status' => 1));
        return $query;
    }
    /************** Function for get addressList ************/
    function getAddressArray($userId)
    {
        $this->db->select('listId,listName,isDefault');
        $this->db->where('userId', $userId);
        $this->db->where('status', '1');
        $this->db->order_by('listId', 'DESC');
        $query = $this->db->get('savedAddressList')->result_array();
        return $query;
    }
    /************** Function for getSavedListData ************/
    function getSavedListData($listId)
    {
        $this->db->select('count(listId) as listCount');
        $this->db->where('listId', $listId);
        $this->db->where('status', '1');
        $query = $this->db->get('savedAddressList')->row_array();
        return $query;
    }
    /************** Function for getPublicAddresses ************/
    function getPublicAddressesData($addressId)
    {
        $this->db->select('count(addressId) as count');
        $this->db->where('addressId', $addressId);
        $this->db->where('status', '1');
        $query = $this->db->get('publicAddresses')->row_array();
        return $query;
    }
    /************** Function for savePublicAddressToSavedList ************/
    function savePublicAddressToSavedList($savedAddress)
    {
        $this->db->select('listId');
        $this->db->where('addressId', $savedAddress['addressId']);
        $this->db->where('listId', $savedAddress['listId']);
        $this->db->where('status', 1);
        $query = $this->db->get('savedListPublicAddresses')->row()->listId;

        if(empty($query)){
            $query = $this->db->insert('savedListPublicAddresses', $savedAddress);
            return $query;
        } else {
            return -1;
        }
    }
    /************** Function for getPrivateAddresses ************/
    function getPrivateAddressesData($addressId)
    {
        $this->db->select('count(addressId) as count');
        $this->db->where('addressId', $addressId);
        $this->db->where('status', '1');
        $query = $this->db->get('privateAddresses')->row_array();
        return $query;
    }
    /************** Function for savePrivateAddressToSavedList ************/
    function savePrivateAddressToSavedList($savedAddress)
    {
        $this->db->select('listId');
        $this->db->where('addressId', $savedAddress['addressId']);
        $this->db->where('listId', $savedAddress['listId']);
        $this->db->where('status', 1);
        $query = $this->db->get('savedListPrivateAddresses')->row()->listId;

        if(empty($query)){
            $query = $this->db->insert('savedListPrivateAddresses', $savedAddress);
            return $query;
        } else {
            return -1;
        }
    }
    /************** Function for getSavedListAddresses ************/
    function getSavedListAddresses($listId)
    {
        $this->db->select('publicAddresses.addressId,publicAddresses.logoURL AS pictureURL,publicAddresses.shortName,publicAddresses.plusCode,categories.categoryName,publicAddresses.referenceCode AS addressReferenceId,publicAddresses.description');
        $this->db->from('savedListPublicAddresses');
        $this->db->where('savedListPublicAddresses.status', 1);
        $this->db->where('publicAddresses.status', 1);
        $this->db->where('savedListPublicAddresses.listId', $listId);
        $this->db->join('publicAddresses', 'publicAddresses.addressId = savedListPublicAddresses.addressId');
        $this->db->join('categories', 'categories.categoryId = publicAddresses.categoryId');
        $addressData['publicAddresses'] = $this->db->get()->result_array();

        $this->db->select('privateAddresses.addressId,privateAddresses.imageURL AS pictureURL,privateAddresses.shortName,privateAddresses.plusCode,privateAddresses.referenceCode AS addressReferenceId');
        $this->db->from('savedListPrivateAddresses');
        $this->db->where('savedListPrivateAddresses.status', 1);
        $this->db->where('privateAddresses.status', 1);
        $this->db->where('savedListPrivateAddresses.listId', $listId);
        $this->db->join('privateAddresses', 'privateAddresses.addressId = savedListPrivateAddresses.addressId');
        $query1 = $this->db->get()->result_array();
        $privateAddressdata = array();
        if (!empty($query1)) {
            foreach ($query1 as $k => $value) {
                $privateAddressdata[$k]['addressId'] = $value['addressId'];
                $privateAddressdata[$k]['pictureURL'] = $value['pictureURL'];
                $privateAddressdata[$k]['shortName'] = $value['shortName'];
                $privateAddressdata[$k]['plusCode'] = $value['plusCode'];
                $privateAddressdata[$k]['categoryName'] = '';
                $privateAddressdata[$k]['addressReferenceId'] = $value['addressReferenceId'];
            }
        }
        $addressData['privateAddresses'] = $privateAddressdata;
        // $addressData = array_merge($query, $privateAddressdata);
        return $addressData;
    }
    /************** Function for unsavePublicAddressToSavedList ************/
    function unsavePublicAddressToSavedList($listId, $addressId)
    {
        $this->db->where('listId', $listId);
        $this->db->where('addressId', $addressId);
        $query = $this->db->update('savedListPublicAddresses', array('status' => 0));
        if ($this->db->affected_rows() > 0) {
            return $query;
        }
    }
    /************** Function for unsavePrivateAddressToSavedList ************/
    function unsavePrivateAddressToSavedList($listId, $addressId)
    {
        $this->db->where('listId', $listId);
        $this->db->where('addressId', $addressId);
        $query = $this->db->update('savedListPrivateAddresses', array('status' => 0));
        if ($this->db->affected_rows() > 0) {
            return $query;
        }
    }
    /************** Function for checkIfUserAndListRelated ************/
    function checkIfUserAndListRelated($userId, $listId)
    {
        $this->db->select('listId');
        $this->db->where('listId', $listId);
        $this->db->where('userId', $userId);
        $query = $this->db->get('savedAddressList');
        return $query->row()->listId;
    }
    /************** Function for deleteSavedAddressList ************/
    function deleteSavedAddressList($listId)
    {
        $this->db->where('listId', $listId);
        $this->db->where('isDefault', 0);
        $this->db->update('savedAddressList', array('status' => 0));
        $query = $this->db->affected_rows();
        if($query > 0){
            $this->db->set('status', 0);
            $this->db->where('listId', $listId);
            $this->db->update('savedListPublicAddresses');

            $this->db->set('status', 0);
            $this->db->where('listId', $listId);
            $this->db->update('savedListPrivateAddresses');
        }
        return $query;
    }
    /************** Function for searchBusiness ************/
    function searchBusiness($data)
    {
        $userId = $data['userId'];
        $searchText = $data['searchText'];
        $lat = $data['latitude'];
        $long = $data['longitude'];
        $distance = $data['distance'];
        $categoryId = $data['categoryId'];

        $this->db->select('userId, profilePicURL, userName, name');
        $this->db->where('userName', $searchText);
        $this->db->where('userId !=', $userId);
        $this->db->where('status', 1);
        $query = $this->db->get('user')->result();
        if(!empty($query)){
            $arrResult['userData'] =  $query;
        }

        // use 6371 to find distance in km and 3959 to find distance in miles
        if ($categoryId < 1) {
            $sql = "select addressId, shortName, plusCode, categoryName, logoURL, distance from (select
                    addressId, shortName, userId, plusCode, (select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName, status, logoURL,
                    (
                        6371 * acos 
                        (               
                            cos ( radians($lat) ) * cos( radians( latitude ) ) 
                            * cos( radians( longitude ) - radians($long) )
                            + sin ( radians($lat) ) * sin( radians( latitude ) )
                        )
                    ) 
                    AS distance
                    FROM publicAddresses having (shortName like '%$searchText%' or plusCode = '$searchText') and distance < $distance) as detail where (userId != $userId and status = 1) order by distance";
        } else {
            $sql = "select addressId, shortName, plusCode, categoryName, logoURL, distance from (select
                    addressId, shortName, userId, plusCode, categoryId, (select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName, status, logoURL,
                    (
                        6371 * acos 
                        (               
                            cos ( radians($lat) ) * cos( radians( latitude ) ) 
                            * cos( radians( longitude ) - radians($long) )
                            + sin ( radians($lat) ) * sin( radians( latitude ) )
                        )
                    ) 
                    AS distance
                    FROM publicAddresses having (shortName like '%$searchText%' or plusCode = '$searchText') and distance < $distance) as detail where (userId != $userId and status = 1 and categoryId = $categoryId) order by distance";
        }


        $query = $this->db->query($sql)->result_array();
        if(!empty($query)){
            $arrResult['businessData'] = $query;
        }
        return $arrResult;
    }
    /************** Function for validate qrCode in publicAddresses table ************/
    public function validateQRPublic($referenceNumber)
    {
        $this->db->select('addressId, userId');
        $this->db->where('referenceCode', $referenceNumber);
        $query = $this->db->get('publicAddresses');
        return $query->row_array();
    }
    /************** Function for validate qrCode in privateAddresses table ************/
    public function validateQRPrivate($referenceNumber)
    {
        $this->db->select('addressId, userId');
        $this->db->where('referenceCode', $referenceNumber);
        $query = $this->db->get('privateAddresses');
        return $query->row_array();
    }
    /************** Function for update count + 1 in publicAddressViews ************/
    public function updatePublicAddressViews($userId, $addressId)
    {
        $sql = "update publicAddressViews set count=count+1 WHERE userId = $userId and addressId = $addressId";
        $this->db->query($sql);
        $query = $this->db->affected_rows();
        return $query;
    }
    /************** Function for insert new entry with count = 0 in publicAddressViews ************/
    public function insertPublicAddressViews($data)
    {
        $query = $this->db->insert('publicAddressViews', $data);
        return $query;
    }
    /************** Function for getting top trending 6 categories ************/
    public function getTrendingCategories()
    {
        $sql = "select count(addressId) as totalCount, categoryId, (select categoryName from categories where categoryId = publicAddresses.CategoryId) as categoryName from publicAddresses group by categoryId order by totalCount desc limit 6";
        $query = $this->db->query($sql);
        return $query->result();
    }
    /************** Function for getting recentlu added 15 businesses ************/
    public function recentlyAddedBusinesses()
    {
        $sql = "select addressId, logoURL as pictureURL, shortName, plusCode, categoryId, (select categoryName from categories where categoryId = publicAddresses.CategoryId) as categoryName, referenceCode as addressReferenceId from publicAddresses order by addressId desc limit 15";
        $query = $this->db->query($sql);
        return $query->result();
    }
    /************** Function for getting top trending 15 businesses ************/
    public function getTrendingBusinesses()
    {
        $sql = "select PAV.addressId, sum(PAV.count) as totalCount, PA.shortName, PA.logoURL as pictureURL, PA.categoryId, (select categoryName from categories where categoryId = PA.categoryId) as categoryName, PA.plusCode, PA.referenceCode as addressReferenceId from publicAddressViews as PAV JOIN publicAddresses as PA ON PAV.addressId = PA.addressId group by addressId order by totalCount desc limit 15";
        $query = $this->db->query($sql);
        return $query->result();
    }
    function getPublicAddressDetail($userId, $addressId)
    {
        $this->db->select('addressId,latitude,longitude, landmark, locationPictureURL, serviceDescription,logoURL,qrCodeURL,shortName,plusCode,categoryId,(select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName,referenceCode AS addressReferenceId,address,websiteURL,facebookURL,twitterURL,linkedInURL,instagramURL,description,isDeliveryAvailable,emailId');
        $this->db->where('status', 1);
        $this->db->where('addressId', $addressId);
        $addressDetail = $this->db->get('publicAddresses')->row_array();

        if(!empty($addressDetail)){
            $sql = "select count(listId) as count from savedListPublicAddresses where listId IN (select listId from savedAddressList WHERE userId = $userId and status = 1) and addressId = $addressId and status = 1";
            $query = $this->db->query($sql);

            $addressDetail['isSaved'] = $query->row()->count;
        }
        /************************************************* */
        $allData = [];
        /************************************************* */


        //print_r($addressDetail); exit;
        if (!empty($addressDetail)) {
            $this->db->select('contactNumber AS phoneNumber');
            $this->db->where('addressId', $addressId);
            $this->db->where('status', 1);
            $contactNumber = $this->db->get('publicAddressContactNumbers')->result_array();

            $this->db->select('serviceId,serviceURL AS serviceFileURL,serviceDocType AS fileExtention');
            $this->db->where('addressId', $addressId);
            $this->db->where('status', 1);
            $services = $this->db->get('publicAddressServices')->result_array();

            $this->db->select('imageId,imageURL');
            $this->db->where('addressId', $addressId);
            $this->db->where('status', 1);
            $images = $this->db->get('publicAddressImages')->result_array();

            $sql = "select weekDays.dayId,weekDays.dayName,coalesce(workingDays.isOpen, 0) as isOpen,workingDays.openTime,workingDays.closeTime from weekDays left join workingDays on weekDays.dayId = workingDays.dayId and businessId = $addressId order by weekDays.dayId";
            $query = $this->db->query($sql);
            $workingHours = $query->result_array();

            $allData['addressId'] = $addressDetail['addressId'];
            $allData['pictureURL'] = $addressDetail['logoURL'];
            $allData['qrCodeURL'] = $addressDetail['qrCodeURL'];
            $allData['shortName'] = $addressDetail['shortName'];
            $allData['plusCode'] = $addressDetail['plusCode'];
            $allData['latitude'] = $addressDetail['latitude'];
            $allData['isSaved'] = $addressDetail['isSaved'];
            $allData['longitude'] = $addressDetail['longitude'];
            $allData['landmark'] = $addressDetail['landmark'];
            $allData['locationPictureURL'] = $addressDetail['locationPictureURL'];
            $allData['serviceDescription'] = $addressDetail['serviceDescription'];
            $allData['categoryId'] = $addressDetail['categoryId'];
            $allData['categoryName'] = $addressDetail['categoryName'];
            $allData['addressReferenceId'] = $addressDetail['addressReferenceId'];
            $allData['address'] = $addressDetail['address'];
            $allData['deliveryAvailable'] = $addressDetail['isDeliveryAvailable'];
            $allData['description'] = $addressDetail['description'];
            $allData['emailId'] = $addressDetail['emailId'];

            $allData['socialMedia'] = array(
                'website' => $addressDetail['websiteURL'],
                'facebook' => $addressDetail['facebookURL'],
                'twitter' => $addressDetail['twitterURL'],
                'linkedin' => $addressDetail['linkedInURL'],
                'instagram' => $addressDetail['instagramURL'],
            );
            $allData['contactNumber'] = $contactNumber;
            $allData['services'] = $services;
            $allData['images'] = $images;
            $allData['workingHours'] = $workingHours;
        }
        return $allData;
    }
    function getPrivateAddressDetail($userId, $addressId)
    {
        $this->db->select('addressId,imageURL AS pictureURL, landmark,qrCodeURL,shortName,plusCode,referenceCode AS addressReferenceId,address,latitude,longitude,emailId');
        $this->db->where('status', 1);
        $this->db->where('addressId', $addressId);
        $addressDeatil = $this->db->get('privateAddresses')->row_array();

        if (!empty($addressDeatil)) {
            $sql = "select count(listId) as count from savedListPrivateAddresses where listId IN (select listId from savedAddressList WHERE userId = $userId and status = 1) and addressId = $addressId and status = 1";
            // $sql = "select count(listId) as count from savedListPublicAddresses where listId IN (select listId from savedAddressList WHERE userId = $userId and status = 1) and addressId = $addressId and status = 1";
            $query = $this->db->query($sql);

            $addressDeatil['isSaved'] = $query->row()->count;
        }

        if (!empty($addressDeatil)) {
            $this->db->select('contactNumber AS phoneNumber');
            $this->db->where('addressId', $addressId);
            $this->db->where('status', 1);
            $contactNumber = $this->db->get('privateAddressContactNumbers')->result_array();
            $addressDeatil['contactNumber'] = $contactNumber;
        }
        return $addressDeatil;
    }
    public function checkIfAddressAlreadySharedWithBusiness($data)
    {
        $this->db->select('status');
        $this->db->where($data);
        $query = $this->db->get('sharedWithBusiness');
        return $query->row()->status;
    }
    public function shareAddressWithBusiness($data)
    {
        $this->db->insert('sharedWithBusiness', $data);
        $query = $this->db->insert_id();
        return $query;
    }
    public function unshareAddressWithBusiness($data)
    {
        $this->db->where($data);
        $this->db->update('sharedWithBusiness', array('status' => 0));
        $query = $this->db->affected_rows();
        return $query;
    }
    public function checkIfAddressAlreadySharedWithUser($data)
    {
        $this->db->select('count(status) as status');
        $this->db->where($data);
        $this->db->where('status', 1);
        $query = $this->db->get('sharedWithUser');
        return $query->row()->status;
    }
    public function shareAddressWithUser($data)
    {
        $this->db->insert('sharedWithUser', $data);
        $query = $this->db->insert_id();
        return $query;
    }
    public function unshareAddressWithUser($data)
    {
        $this->db->where($data);
        $this->db->update('sharedWithUser', array('status' => 0));
        $query = $this->db->affected_rows();
        return $query;
    }
    public function getBusinessSharedAddresses($addressId)
    {
        $sql = "select publicAddresses.addressId,publicAddresses.logoURL AS pictureURL,publicAddresses.shortName,publicAddresses.plusCode,publicAddresses.categoryId,(select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName,publicAddresses.referenceCode AS addressReferenceId,publicAddresses.description from publicAddresses where publicAddresses.addressId in (select sharedWithBusiness.addressId from sharedWithBusiness where sharedWithBusiness.recipientId = $addressId and sharedWithBusiness.isAddressPublic = 1 and sharedWithBusiness.status = 1) and publicAddresses.status = 1";
        $query = $this->db->query($sql);
        $resultPublic = $query->result_array();
        if(!empty($resultPublic)){
            $result['publicAddresses'] = $resultPublic;
        }

        $sql = "select addressId,imageURL AS pictureURL,shortName,plusCode,referenceCode AS addressReferenceId from privateAddresses where addressId in (select addressId from sharedWithBusiness where recipientId = $addressId and isAddressPublic = 0 and sharedWithBusiness.status = 1) and privateAddresses.status = 1";
        $query = $this->db->query($sql);
        $resultPrivate = $query->result_array();
        if(!empty($resultPrivate)){
            $result['privateAddresses'] = $resultPrivate;
        }

        return $result;
    }
    public function checkUserAddressRelation($userId, $addressId)
    {
        $this->db->select('addressId');
        $this->db->where('userId', $userId);
        $this->db->where('addressId', $addressId);
        $query = $this->db->get('publicAddresses');
        return $query->result();
    }
    public function getUserSharedAddresses($userId)
    {
        $sql = "select publicAddresses.addressId, publicAddresses.logoURL AS pictureURL, publicAddresses.shortName, publicAddresses.plusCode,publicAddresses.categoryId,(select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName,publicAddresses.referenceCode AS addressReferenceId, publicAddresses.description from publicAddresses where publicAddresses.addressId in (select addressId from sharedWithUser where recipientId = $userId and isAddressPublic = 1 and status = 1) and publicAddresses.status = 1";
        $query = $this->db->query($sql);
        $resultPublic = $query->result_array();
        if(!empty($resultPublic)){
            $result['publicAddresses'] = $resultPublic;
        }

        $sql = "select privateAddresses.addressId, privateAddresses.imageURL AS pictureURL, privateAddresses.shortName, privateAddresses.plusCode, privateAddresses.referenceCode AS addressReferenceId from privateAddresses where privateAddresses.addressId in (select addressId from sharedWithUser where recipientId = $userId and isAddressPublic = 0 and status = 1) and privateAddresses.status = 1";
        $query = $this->db->query($sql);
        $resultPrivate = $query->result_array();
        if(!empty($resultPrivate)){
            $result['privateAddresses'] = $resultPrivate;
        }
        
        return $result;
    }

    public function getCategoryBusiness($data)
    {
        $lat = $data['currentLatitude'];
        $lng = $data['currentLongitude'];
        $categoryId = $data['categoryId'];
        $userId = $data['userId'];
        $start = $data['start'];
        $count = $data['count'];
        $sql = "select addressId, logoURL as pictureURL, shortName, description, plusCode, (select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName, referenceCode as addressReferenceId, ( 6371 * acos ( cos ( radians($lat) ) * cos( radians( latitude ) ) * cos(radians( longitude ) - radians($lng) )+ sin ( radians($lat) ) * sin( radians( latitude ) )))AS distance FROM publicAddresses where categoryId = $categoryId and userId != $userId and status = 1 order by distance limit $start,$count";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getPrimaryCategories()
    {
        $sql = "select categoryId, (select categoryName from categories where categoryId = primaryCategories.categoryId) as categoryName, iconImageURL as iconURL from primaryCategories where status = 1";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getRecipientPushToken($receiverId)
    {
        $this->db->select('pushToken');
        $this->db->where('userId', $receiverId);
        $this->db->where('status', 1);
        $query = $this->db->get('registeredDevices');
        return $query->result_array();
    }
    public function getRecipientUserId($businessId)
    {
        $this->db->select('userId');
        $this->db->where('addressId', $businessId);
        $query = $this->db->get('publicAddresses');
        return $query->row_array();
    }
}
