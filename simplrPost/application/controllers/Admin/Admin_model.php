<?php

class Admin_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("pagination");
    }
    public function getMaxId()
    {
        $sql = "select `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'Simplr_Post' AND TABLE_NAME = 'primaryCategories'";
        $query = $this->db->query($sql);
        return $query->row()->AUTO_INCREMENT;
    }
    public function getAdminData($adminId)
    {
        $this->db->select('adminId, name, userName, emailId, contactNumber, profilePicURL');
        $this->db->where('adminId', $adminId);
        $query = $this->db->get('admin')->row();
        return $query;
    }

    public function getAllUsersData($rowNo, $rowPerPage, $date, $sorting, $search)
    {
            if($sorting == 'oldToNew'){
                $this->db->order_by("createDate", 'asc');
            } else{
                $this->db->order_by("createDate", 'desc');
            }
            if(!empty($date)){
                $this->db->like('createDate', $date);
            }
            if(!empty($search)){
                $this->db->like('name',$search);
                $this->db->or_like('userName',$search);
            }
            
            $this->db->select('userId, profilePicURL, name, emailId');
            $this->db->from('user');
            $this->db->where('status', 1);
            $this->db->or_where('status', -5);
            $this->db->limit($rowPerPage, $rowNo);
            $query = $this->db->get();
            return $query->result_array();
    }
    public function getUsersTotalCount($date, $search)
    {
        if(!empty($date)){
            $this->db->like('createDate', $date);
        }
        if(!empty($search)){
            $this->db->like('name',$search);
            $this->db->or_like('userName',$search);
        }
        $this->db->select('count(userId) as allcount');
        $this->db->from('user');
        $this->db->where('status', 1);
        $this->db->or_where('status', -5);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }

    public function getUserDetail($userId)
    {
        $this->db->select('userId, profilePicURL, name, userName, emailId, contactNumber, status');
        $this->db->where('userId', $userId);
        $query = $this->db->get('user');
        return $query->result();
    }

    public function deleteUser($userId)
    {
        $this->db->where('userId', $userId);
        $this->db->set('status', -1);
        $this->db->update('user');
        $query['user'] = $this->db->affected_rows();

        // delete all private and public addresses associated with user
        $this->db->where('userId', $userId);
        $this->db->set('status', -1);
        $this->db->update('privateAddresses');
        $query['private'] = $this->db->affected_rows();

        $this->db->where('userId', $userId);
        $this->db->set('status', -1);
        $this->db->update('publicAddresses');
        $query['public'] = $this->db->affected_rows();

        return $query;
    }
    public function blockUserFromUserDetail($userId)
    {
        $this->db->where('userId', $userId);
        $this->db->set('status', -5);
        $this->db->update('user');
        $query['user'] = $this->db->affected_rows();

        // delete all private and public addresses associated with user
        $this->db->where('userId', $userId);
        $this->db->set('status', -5);
        $this->db->update('privateAddresses');
        $query['private'] = $this->db->affected_rows();

        $this->db->where('userId', $userId);
        $this->db->set('status', -5);
        $this->db->update('publicAddresses');
        $query['public'] = $this->db->affected_rows();

        return $query;
    }
    public function unblockUser($userId)
    {
        $this->db->where('userId', $userId);
        $this->db->set('status', 1);
        $this->db->update('user');
        $query['user'] = $this->db->affected_rows();

        // delete all private and public addresses associated with user
        $this->db->where('userId', $userId);
        $this->db->set('status', 1);
        $this->db->update('privateAddresses');
        $query['private'] = $this->db->affected_rows();

        $this->db->where('userId', $userId);
        $this->db->set('status', 1);
        $this->db->update('publicAddresses');
        $query['public'] = $this->db->affected_rows();

        return $query;
    }
    public function getUserDetailForEmail($userId)
    {
        $this->db->select('name, emailId');
        $this->db->where('userId', $userId);
        $query = $this->db->get('user');
        return $query->row_array();   
    }
    public function getUserMailDetail($addressId)
    {
        $sql = "select publicAddresses.shortName, publicAddresses.address, user.name, user.emailId from publicAddresses join user on publicAddresses.userId = user.userId where addressId = $addressId";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    public function getUserPrivateAddresses($userId)
    {
        $this->db->select('privateAddresses.addressId, shortName, plusCode, latitude, longitude, imageURL, qrCodeURL, emailId, address,(SELECT GROUP_CONCAT(contactNumber) FROM privateAddressContactNumbers WHERE privateAddressContactNumbers.addressId = privateAddresses.addressId and privateAddressContactNumbers.status = 1) AS contactNumbers FROM privateAddresses');
        $this->db->where('userId', $userId);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserPublicAddresses($userId)
    {
        $this->db->select('addressId, shortName, logoURL, address, categoryId, (select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName');
        $this->db->from('publicAddresses');
        $this->db->where('userId', $userId);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getPublicAddressDetails($addressId)
    {
        $this->db->select("addressId, userId, (select profilePicURL from user where userId = publicAddresses.userId) as profilePicURL, (select name from user where userId = publicAddresses.userId) as name, (select userName from user where userId = publicAddresses.userId) as userName, (select emailId from user where userId = publicAddresses.userId) as userEmailId, (select contactNumber from user where userId = publicAddresses.userId) as userContactNumber, logoURL, qrCodeURL, shortName, plusCode, latitude, longitude, emailId, categoryId, blockDuration, isUnblockable, DATE_FORMAT(blockDate, '%d-%b-%Y') as blockDate, status, address, landmark, description, serviceDescription, isDeliveryAvailable, facebookURL, twitterURL, linkedInURL, instagramURL, websiteURL, (SELECT GROUP_CONCAT(contactNumber) FROM publicAddressContactNumbers WHERE addressId = $addressId and status = 1) AS contactNumbers , (select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName,(SELECT GROUP_CONCAT(serviceURL) FROM publicAddressServices WHERE addressId = $addressId and status = 1) AS serviceURL ,(SELECT GROUP_CONCAT(imageURL) FROM publicAddressImages WHERE addressId = $addressId and status = 1) AS imageURL from publicAddresses");
        $this->db->where('publicAddresses.addressId', $addressId);
        $query['address'] = $this->db->get()->result();


        $this->db->select("dayId, isOpen, openTime, closeTime,(SELECT dayName FROM weekDays WHERE dayId = workingDays.dayId) AS dayName FROM workingDays");
        $this->db->where('businessId', $addressId);
        $query['weekDays'] = $this->db->get()->result();

        return $query;
    }
    public function unblockAddress($addressId)
    {
        $this->db->where('addressId', $addressId);
        $this->db->set('status', 1);
        $this->db->update('publicAddresses');
        $query = $this->db->affected_rows();
        return $query;
    }
    public function getAddressReportCount($addressId)
    {
        $this->db->select('count(businessId) as allcount');
        $this->db->from('addressReports');
        $this->db->where('businessId', $addressId);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }

    public function getAllAddressReports($rowNo, $rowPerPage, $addressId)
    {
        $this->db->select('reportId, reporterName, reporterEmailId, businessId, (select shortName from publicAddresses where addressId = businessId) as businessName, (select address from publicAddresses where addressId = businessId) as businessAddress, issueId, (select issue from issues where issueId = addressReports.issueId) as issue, DATE_FORMAT(createDate, "%d-%b-%Y") as createDate');
        // $this->db->from('addressReports');
        $this->db->where('businessId', $addressId);
        $this->db->order_by('createDate', 'desc');
        $this->db->limit($rowPerPage, $rowNo);
        $query = $this->db->get('addressReports');

        return $query->result_array();
    }

    public function getAllBusinessesData($rowNo, $rowPerPage, $date, $sorting, $sortingByName, $categoryId)
    {
        if(!empty($date)){
            $this->db->like('createDate', $date);
        }

        if($sorting == 'oldToNew'){
            $order = 'createDate asc';
        } else if($sorting == 'newToOld') {
            $order = 'createDate desc';
        }
        if($sortingByName == 'zToA'){
            $order1 = 'shortName desc';
        } else if($sortingByName == 'aToZ'){
            $order1 = 'shortName asc';
        }
        if(!empty($order) && !empty($order1)){
            $this->db->order_by($order.','.$order1);
        } else if(!empty($order) && empty($order1)){
            $this->db->order_by($order);
        } else if(empty($order) && !empty($order1)){
            $this->db->order_by($order1);
        } else {
            $this->db->order_by('createDate', 'desc');
        }

        if(!empty($categoryId)){
            $this->db->where('categoryId', $categoryId);
        }
        $this->db->select('addressId, logoURL, shortName, categoryID, (select categoryName from categories where categoryId = publicAddresses.categoryId) as categoryName');
        $this->db->from('publicAddresses');
        $this->db->where('status', 1);
        $this->db->limit($rowPerPage, $rowNo);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getCategoryNames()
    {
        $this->db->order_by('categoryName', 'asc');
        $this->db->select('categoryId, categoryName');
        $this->db->from('categories');
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAllCategoryNames()
    {
        $this->db->order_by('categoryName', 'asc');
        $this->db->select('categoryId, categoryName');
        $this->db->from('categories');
        $this->db->where('status', 0);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getBusinessListTotalCount($date, $categoryId)
    {
        if(!empty($date)){
            $this->db->like('createDate', $date);
        }
        if(!empty($categoryId)){
            $this->db->where('categoryId', $categoryId);
        }
        $this->db->select('count(addressId) as allcount');
        $this->db->from('publicAddresses');
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }
    public function deleteBusiness($businessId)
    {
        $this->db->where('addressId', $businessId);
        $this->db->set('status', -1);
        $this->db->update('publicAddresses');
        $query = $this->db->affected_rows();
        return $query;
    }

    function getCategoriesList($rowNo, $rowPerPage)
    {
        $this->db->order_by("categoryName", "asc");
        $this->db->select('categoryId, categoryName');
        $this->db->from('categories');
        $this->db->where('status !=', -1);
        $this->db->limit($rowPerPage, $rowNo);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getCategoriesRecordCount()
    {

        $this->db->select('count(categoryId) as allcount');
        $this->db->from('categories');
        $this->db->where('status !=', -1);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }

    public function deleteCategtory($categoryId)
    {
        $this->db->where('categoryId', $categoryId);
        $this->db->set('status', -1);
        $this->db->update('categories');
        $query = $this->db->affected_rows();
        return $query;
    }

    public function getCategoryName($categoryId)
    {
        $this->db->select('categoryName');
        $this->db->where('categoryId', $categoryId);
        $query = $this->db->get('categories');
        return $query->row();
    }

    public function editCategory($categoryId, $categoryName)
    {
        $this->db->where('categoryId', $categoryId);
        $this->db->update('categories', array('categoryName' => $categoryName));
        $query = $this->getCategoryPositon($categoryName);
        return $query->row();
    }

    public function getCategoryPositon($categoryName)
    {
        $sql = "select COUNT(*) AS position FROM categories WHERE categoryName <= '$categoryName' ORDER BY categoryName";
        return ($this->db->query($sql));
    }

    public function addNewCategory($data)
    {
        $query = $this->db->insert('categories', $data);
        return $query;
    }

    // -------------------------------------------------------------------------------
    function getPrimaryCategoriesList($rowNo, $rowPerPage)
    {
        $sql = "select primaryCategoryId, categoryId, (select categoryName from categories where categoryId = primaryCategories.categoryId) as categoryName , iconImageURL from primaryCategories where status = 1 order by createdate limit $rowNo, $rowPerPage";
        $query = $this->db->query($sql);

        return $query->result_array();
    }
    public function getPrimaryCategoriesRecordCount()
    {
        $this->db->select('count(categoryId) as allcount');
        $this->db->from('primaryCategories');
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }
    public function primaryCategoryCount($categoryId){
        $sql = "select count(categoryId) as count from primaryCategories where categoryId = $categoryId and status = 1";
        $query = $this->db->query($sql);
        return $query->row()->count;
    }
    public function addNewPrimaryCategory($data){
        $this->db->set('status', 1);
        $this->db->set('iconImageURL', $data['iconImageURL']);
        $this->db->where('categoryid', $data['categoryId']);
        $this->db->update('primaryCategories');
        $query = $this->db->affected_rows();
        if($query == 0){
            $query = $this->db->insert('primaryCategories', $data);
        }

        $this->db->set('status', 1);
        $this->db->where('categoryId', $data['categoryId']);
        $this->db->update('categories');

        return $query;
    }
    public function deletePrimaryCategory($categoryId)
    {
        $this->db->where('categoryId', $categoryId);
        $this->db->set('status', 0);
        $this->db->update('primaryCategories');
        $query = $this->db->affected_rows();
        if($query > 0){
            $this->db->where('categoryId', $categoryId);
            $this->db->set('status', 0);
            $this->db->update('categories');
            $query = $this->db->affected_rows();
            return $query;
        }
    }
    public function getPrimaryCategoryValues($primaryCategoryId)
    {
        $sql = "select primaryCategoryId, categoryId, (select categoryName from categories where categoryId = primaryCategories.categoryId) as categoryName, iconImageURL from primaryCategories where categoryId = $primaryCategoryId";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    public function editPrimaryCategory($categoryId, $data)
    {
        $this->db->where('categoryId', $data['categoryId']);
        $this->db->where('status', 0);
        $this->db->delete('primaryCategories');

        $this->db->where('categoryId', $categoryId);
        $this->db->set('status', 0);
        $this->db->update('categories');

        $this->db->where('categoryId', $data['categoryId']);
        $this->db->set('status', 1);
        $this->db->update('categories');

        $this->db->where('categoryId', $categoryId);
        $this->db->update('primaryCategories', $data);
        $query = $this->db->affected_rows();
        return $query;
    }


    // ------------------------------------------------
    function getFeedbackList($rowNo, $rowPerPage)
    {

        $this->db->select('userId, (select name from user where userId = userFeedback.userId) as userName, (select profilePicURL from user where userId = userFeedback.userId) as profilePicURL, rating, content, DATE_FORMAT(createDate, "%d-%b-%Y") as createDate');
        $this->db->from('userFeedback');
        $this->db->where('status', 1);
        $this->db->order_by('createDate', 'desc');
        $this->db->limit($rowPerPage, $rowNo);
        $query = $this->db->get();

        return $query->result_array();
    }
    public function getFeedbackRecordCount()
    {
        $this->db->select('count(id) as allcount');
        $this->db->from('userFeedback');
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }

    function getReportList($rowNo, $rowPerPage)
    {
        $this->db->select('reportId, reporterName, reporterEmailId, businessId, (select shortName from publicAddresses where addressId = businessId) as businessName, (select address from publicAddresses where addressId = businessId) as businessAddress, issueId, (select issue from issues where issueId = addressReports.issueId) as issue, DATE_FORMAT(createDate, "%d-%b-%Y") as createDate');
        $this->db->from('addressReports');
        $this->db->where('status', 1);
        $this->db->order_by('createDate', 'desc');
        $this->db->limit($rowPerPage, $rowNo);
        $query = $this->db->get();

        return $query->result_array();
    }
    function getAddressReportList($addressId)
    {
        $this->db->select('reportId, reporterName, reporterEmailId, businessId, (select shortName from publicAddresses where addressId = businessId) as businessName, (select address from publicAddresses where addressId = businessId) as businessAddress, issueId, (select issue from issues where issueId = addressReports.issueId) as issue, createDate');
        $this->db->where('businessId', $addressId);
        $this->db->where('status', 1);
        $this->db->from('addressReports');
        $this->db->order_by('createDate', 'desc');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getReportRecordCount()
    {
        $this->db->select('count(reportId) as allcount');
        $this->db->where('status', 1);
        $this->db->from('addressReports');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }
    public function getReportDetail($reportId)
    {
        // $this->db->select('reportId, reporterUserId, reporterName, reporterEmailId, reporterContactNumber, (select userName from user where userId = reporterUserId) as reporterUserName, (select profilePicURL from user where userId = reporterUserId) as profilePicURL, businessId, issueId, (select issue from issues where issueId = addressReports.issueId) as issue, description');
        // $this->db->where('reportId', $reportId);
        // $this->db->where('status', 1);
        // $query = $this->db->get('addressReports');
        // return $query->result();
        $sql = "select addressReports.reportId, addressReports.reporterUserId, addressReports.reporterName, addressReports.reporterEmailId, addressReports.reporterContactNumber, addressReports.businessId, user.name, user.userName, user.emailId, user.contactNumber, user.profilePicURL, addressReports.issueId, (select issues.issue from issues where issues.issueId = addressReports.issueId) as issue, addressReports.description from addressReports join publicAddresses on addressReports.businessId = publicAddresses.addressId join user on publicAddresses.userId = user.userId where addressReports.reportId = $reportId";
        $query = $this->db->query($sql);
        return $query->result();

    }

    public function blockBusiness($reportId, $addressId, $data)
    {
        $this->db->set('status', 0);
        $this->db->where('reportId', $reportId);
        $this->db->update('addressReports');

        $this->db->where('addressId', $addressId);
        $this->db->update('publicAddresses', $data);
        $query = $this->db->affected_rows();
        return $query;
    }
    public function blockUser($reportId, $addressId, $blockDate)
    {
        $this->db->set('status', 0);
        $this->db->where('reportId', $reportId);
        $this->db->update('addressReports');

        $sql = "select userId from publicAddresses where addressId = $addressId";
        $query = $this->db->query($sql);
        $userId = $query->row()->userId;

        $this->db->where('userId', $userId);
        $this->db->set('blockDate', $blockDate);
        $this->db->set('status', -5);
        $this->db->update('user');

        $this->db->where('userId', $userId);
        $this->db->set('blockDate', $blockDate);
        $this->db->set('status', -5);
        $this->db->set('isUnblockable', 0);
        $this->db->update('publicAddresses');

        $this->db->where('userId', $userId);
        $this->db->set('blockDate', $blockDate);
        $this->db->set('status', -5);
        $this->db->update('privateAddresses');

        return $userId;
    }
    public function getReportedBusinessListTotalCount()
    {
        $this->db->select('count(reportId) as allcount');
        $this->db->from('addressReports');
        $this->db->where('status', 0);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }
    public function getAllReportedBusinessesData($rowNo, $rowPerPage)
    {
        $sql = "select addressReports.reportId, addressReports.businessId, publicAddresses.shortName, publicAddresses.logoURL, DATE_FORMAT(publicAddresses.blockDate, '%d-%b-%Y') as blockDate, publicAddresses.categoryId, (select categoryName from categories where categoryid = publicAddresses.categoryId) as categoryName, publicAddresses.status from addressReports join publicAddresses on addressReports.businessId = publicAddresses.addressId where addressReports.status = 0";
        $query = $this->db->query($sql)->result_array();
        return $query;
    }
    public function getReportedBusinessDetail($addressId)
    {
        $sql = "select publicAddresses.addressId, publicAddresses.shortName, publicAddresses.logoURL, DATE_FORMAT(publicAddresses.blockDate, '%d-%b-%Y') as blockDate, publicAddresses.categoryId, publicAddresses.blockDuration, publicAddresses.isUnblockable, (select categoryName from categories where categoryid = publicAddresses.categoryId) as categoryName, user.name, user.userName, user.emailId, user.contactNumber, user.profilePicURL from publicAddresses join user on publicAddresses.userId = user.userId where addressId = $addressId";
        $query = $this->db->query($sql)->row_array();
        return $query;
    }
    
    public function getFAQRecordCount()
    {
        $this->db->select('count(questionId) as allcount');
        $this->db->from('frequentlyAskedQuestions');
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }
    function getFAQList($rowNo, $rowPerPage)
    {

        $this->db->select('questionId, question');
        $this->db->from('frequentlyAskedQuestions');
        $this->db->where('status', 1);
        $this->db->order_by('createDate', 'desc');
        $this->db->limit($rowPerPage, $rowNo);
        $query = $this->db->get();

        return $query->result_array();
    }
    public function getQuestionValues($questionId)
    {
        $this->db->select('question, answer');
        $this->db->where('questionId', $questionId);
        $query = $this->db->get('frequentlyAskedQuestions');
        return $query->row();
    }
    public function deleteFAQ($questionId)
    {
        $this->db->set('status', 0);
        $this->db->where('questionId', $questionId);
        $this->db->update('frequentlyAskedQuestions');
        $query = $this->db->affected_rows();
        return $query;
    }
    public function addNewFAQ($data)
    {
        $query = $this->db->insert('frequentlyAskedQuestions', $data);
        return $query;
    }
    public function editFAQ($questionId, $data)
    {
        $this->db->where('questionId', $questionId);
        $this->db->update('frequentlyAskedQuestions', $data);
        $query = $this->db->affected_rows();
        return $query;
    }
    
    public function getIssueRecordCount()
    {
        $this->db->select('count(issueId) as allcount');
        $this->db->from('issues');
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]['allcount'];
    }
    function getIssuesList($rowNo, $rowPerPage)
    {

        $this->db->select('issueId, issue');
        $this->db->from('issues');
        $this->db->where('status', 1);
        $this->db->order_by('createDate', 'desc');
        $this->db->limit($rowPerPage, $rowNo);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function deleteIssue($issueId)
    {
        $this->db->set('status', 0);
        $this->db->where('issueId', $issueId);
        $this->db->update('issues');
        $query = $this->db->affected_rows();
        return $query;
    }
    public function addNewIssue($data)
    {
        $query = $this->db->insert('issues', $data);
        return $query;
    }
    public function getIssueValues($issueId)
    {
        $this->db->select('issue');
        $this->db->where('issueId', $issueId);
        $query = $this->db->get('issues');
        return $query->row();
    }
    public function editIssue($issueId, $data)
    {
        $this->db->where('issueId', $issueId);
        $this->db->update('issues', $data);
        $query = $this->db->affected_rows();
        return $query;
    }

    public function getAllUserData()
    {
        $sql = 'select monthname(createDate) as monthName, count(userId) as registeredUser from user group by month(createDate)';
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getDataFromUserForThisWeek()
    {
        $sql = 'select date(createDate) as date,count(userId) as registeredUsers from user where WEEKOFYEAR(createDate)=WEEKOFYEAR(NOW())  group by date order by date';
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getDataFromUserForThisMonth()
    {
        $sql = 'select date(createDate) as date,count(userId) as registeredUsers from user where month(createDate)=month(NOW()) group by date order by date';
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getDataFromUserForThisYear()
    {
        $sql = 'select date(createDate) as date,count(userId) as registeredUsers from user where year(createDate)=year(NOW()) group by date order by date';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getAllBusinessData()
    {
        $sql = 'select monthname(createDate) as monthName, count(addressId) as registeredBusinesses from publicAddresses group by month(createDate)';
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getDataFromBusinessForThisWeek()
    {
        $sql = 'select date(createDate) as date,count(addressId) as registeredBusinesses from publicAddresses where WEEKOFYEAR(createDate)=WEEKOFYEAR(NOW())  group by date order by date';
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getDataFromBusinessForThisMonth()
    {
        $sql = 'select date(createDate) as date, count(addressId) as registeredBusinesses from publicAddresses where month(createDate)=month(NOW()) group by date order by date';
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function getDataFromBusinessForThisYear()
    {
        $sql = 'select date(createDate) as date, count(addressId) as registeredBusinesses from publicAddresses where year(createDate)=year(NOW()) group by date order by date';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getAllUsersStatusData()
    {
        $sql = 'select status, count(userId) as userCount from user group by status order by status desc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function updateAdminData($adminId, $data)
    {
        $this->db->where('adminId', $adminId);
        $this->db->update('admin', $data);
        $query = $this->db->affected_rows();
        return $query;
    }
    public function removeProfilePic($adminId)
    {
        $this->db->set('profilePicURL', 'admin/admin_placeholder.jpg');
        $this->db->where('adminId', $adminId);
        $this->db->update('admin');
        $query = $this->db->affected_rows();
        return $adminId;
    }
    public function getCountries()
    {
        $sql = 'select countryId, countryName from countries order by countryName';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPrimaryCategories()
    {
        $sql = 'select categoryId, categoryName from categories where status = 1 order by categoryName';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function getStates($countryId)
    {
        $sql = "select stateId, stateName from states where countryId = $countryId order by stateName";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function saveNotificationAndGetNotificationId($data)
    {
        $this->db->insert('notifications', $data);
        $query = $this->db->insert_id();
        return $query;
    }
    public function getUsersAudience($data)
    {
        $this->db->distinct();
        $this->db->select('userId');
        if($data['countryId'] > 0){
            $this->db->where('countryId', $data['countryId']);
        }
        if($data['stateId'] > 0){
            $this->db->where('stateId', $data['stateId']);
        }
        $this->db->where('status', 1);
        $query = $this->db->get('privateAddresses');
        return $query->result_array();
    }
    public function getBusinessesAudience($data)
    {
        $this->db->distinct();
        $this->db->select('userId');
        if($data['countryId'] > 0){
            $this->db->where('countryId', $data['countryId']);
        }
        if($data['stateId'] > 0){
            $this->db->where('stateId', $data['stateId']);
        }
        if($data['categoryId'] > 0){
            $this->db->where('categoryId', $data['categoryId']);
        }
        $this->db->where('status', 1);
        $query = $this->db->get('publicAddresses');
        return $query->result_array();
    }
    public function saveNotificationUser($dataNotifyUsers)
    {
        $query = $this->db->insert('notificationUsers', $dataNotifyUsers);
        return $query;
    }
    public function getPushToken($userId)
    {
        $this->db->select('pushToken, userId');
        $this->db->where('userId', $userId);
        $this->db->where('status', 1);
        $query = $this->db->get('registeredDevices');
        return $query->result_array();
    }
    public function getNotificationRecordCount($date, $sorting, $search)
    {
        if(!empty($date)){
            $this->db->like('createDate', $date);
        }
        if(!empty($search)){
            $this->db->like('information', $search);
        }
        $this->db->select('count(notificationId) as allcount');
        $this->db->from('notifications');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }
    public function getNotificationList($rowNo, $rowPerPage, $date, $sorting, $search)
    {
        if(!empty($date)){
            $this->db->like('createDate', $date);
        }

        if($sorting == 'oldToNew'){
            $this->db->order_by('createDate', 'asc');
        } else{
            $this->db->order_by('createDate', 'desc');
        }

        if(!empty($search)){
            $this->db->like('information', $search);
        }
        $this->db->select('notificationId, information, notificationAudience');
        $this->db->from('notifications');
        $this->db->limit($rowPerPage, $rowNo);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getNotificationDetail($notificationId){
        $this->db->select('countryId, stateId, categoryId, information, notificationAudience');
        $this->db->from('notifications');
        $this->db->where('notificationId', $notificationId);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getAboutContent()
    {
        $this->db->select('content');
        $query = $this->db->get('aboutUs');
        return $query->row_array();
    }
    public function getPrivacyPolicyContent()
    {
        $this->db->select('content');
        $query = $this->db->get('privacyPolicy');
        return $query->row_array();
    }
    public function getTermsConditionsContent()
    {
        $this->db->select('content');
        $query = $this->db->get('termsConditions');
        return $query->row_array();
    }
    public function updateAboutUs($content)
    {
        $this->db->set('content', $content);
        $this->db->where('id', 1);
        $this->db->update('aboutUs');
        $query = $this->db->affected_rows();
        return $query;
    }
    public function updatePrivacyPolicy($content)
    {
        $this->db->set('content', $content);
        $this->db->where('id', 1);
        $this->db->update('privacyPolicy');
        $query = $this->db->affected_rows();
        return $query;
    }
    public function updateTermsConditions($content)
    {
        $this->db->set('content', $content);
        $this->db->where('id', 1);
        $this->db->update('termsConditions');
        $query = $this->db->affected_rows();
        return $query;
    }
}
