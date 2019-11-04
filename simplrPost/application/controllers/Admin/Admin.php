<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Admin_model');
        $this->load->library('session');
        $this->load->library('PHPMailer');
    }

    public function index()
    {
        if ($this->session->userdata('adminId') != null) {
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrAdminData['title'] = 'dashboard';

            // print_r($arrAdminData);exit;

            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/dashboard');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }
    public function userListingView()
    {
        if ($this->session->userdata('adminId') != null) {
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrAdminData['title'] = 'Users List';

            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/user_listing');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }

    public function loadUserListing($rowNo = 0)
    {
        $date = $_POST['date'];
        $sorting = $_POST['sorting'];
        $search = $_POST['search'];
        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 10;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getUsersTotalCount($date, $search);

        // Get records
        $usersRecord = $this->Admin_model->getAllUsersData($rowNo, $rowPerPage, $date, $sorting, $search);
        // print_r($usersRecord);exit;

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadUserListing';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;
        // $data['date'] = $date;

        echo json_encode($data);
    }

    public function userDetailView($userId = null)
    {
        if ($this->session->userdata('adminId') != null) {
            $userId = base64_decode(urldecode($userId));
            $adminId = $this->session->userdata('adminId');
            $arrResult['data'] = $this->Admin_model->getAdminData($adminId);
            $arrResult['userData'] = $this->Admin_model->getUserDetail($userId);
            $arrResult['privateAddressDetails'] = $this->Admin_model->getUserPrivateAddresses($userId);
            $arrResult['publicAddressDetails'] = $this->Admin_model->getUserPublicAddresses($userId);
            $arrResult['title'] = 'User Detail';

            $this->load->view('admin/includes/header', $arrResult);
            $this->load->view('admin/user_detail');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }
    public function deleteUser()
    {
        $userId = $_POST['userId'];
        $result = $this->Admin_model->deleteUser($userId);
        if(!empty($result)){
            $this->sendUserEmail($userId);
        }
        print_r($result);
    }
    public function getBusinessDataInModal()
    {
        $addressId = $_POST['addressId'];
        $arrResult = $this->Admin_model->getPublicAddressDetails($addressId);

        print_r(json_encode($arrResult));
    }

    public function businessListingView()
    {
        if ($this->session->userdata('adminId') != null) {
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrAdminData['title'] = 'Businesses List';

            $data['categories'] = $this->Admin_model->getCategoryNames();
            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/business_listing', $data);
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }

    public function loadBusinessListing($rowNo = 0)
    {
        $date = $_POST['date'];
        $sorting = $_POST['sorting'];
        $sortingByName = $_POST['sortingByName'];
        $categoryId = $_POST['categoryId'];

        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 10;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getBusinessListTotalCount($date, $categoryId);

        $usersRecord = $this->Admin_model->getAllBusinessesData($rowNo, $rowPerPage, $date, $sorting, $sortingByName, $categoryId);

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadBusinessListing';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }

    public function businessDetailView($addressId = null)
    {
        if ($this->session->userdata('adminId') != null) {
            $addressId = base64_decode(urldecode($addressId));
            $adminId = $this->session->userdata('adminId');
            $arrResult = $this->Admin_model->getPublicAddressDetails($addressId);
            $arrResult['data'] = $this->Admin_model->getAdminData($adminId);
            $arrResult['title'] = 'Business Detail';
            $arrResult['reportCount'] = $this->Admin_model->getAddressReportCount($addressId);
            $arrResult['report'] = 0;

            // print_r($arrResult);exit;
            $this->load->view('admin/includes/header', $arrResult);
            $this->load->view('admin/business_detail');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }
    public function deleteBusiness()
    {
        $businessId = $_POST['businessId'];
        $result = $this->Admin_model->deleteBusiness($businessId);
        if(!empty($result)){
            $this->sendAddressMail($businessId, 'address', 0 ,'delete');
        }
        print_r($result);
    }
    public function businessReportsView($addressId = null)
    {
        if ($this->session->userdata('adminId') != null) {
            $addressId = base64_decode(urldecode($addressId));
            $adminId = $this->session->userdata('adminId');
            $arrResult['data'] = $this->Admin_model->getAdminData($adminId);
            $arrResult['title'] = 'Business Reports';
            $arrResult['addressId'] = $addressId;
            $this->load->view('admin/address_report_listing', $arrResult);
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }

    public function loadAddressReports($rowNo = 0)
    {
        $addressId = $_POST['addressId'];
        // $adressReports = $this->Admin_model->getAddressReportList($addressId);
        // echo json_encode($adressReports);
        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 10;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getAddressReportCount($addressId);

        $usersRecord = $this->Admin_model->getAllAddressReports($rowNo, $rowPerPage, $addressId);

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadAddressReports';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }

    public function manageCategoryView()
    {
        if ($this->session->userdata('adminId') == null) {
            redirect('index.php/login');
        }
        $adminId = $this->session->userdata('adminId');
        $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
        $arrAdminData['title'] = 'Categories';

        $this->load->view('admin/includes/header', $arrAdminData);
        $this->load->view('admin/manage_category');
        $this->load->view('admin/includes/footer');
    }

    public function loadCategories($rowNo = 0)
    {
        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 50;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getCategoriesRecordCount();

        // Get records
        $usersRecord = $this->Admin_model->getCategoriesList($rowNo, $rowPerPage);
        // print_r($usersRecord);exit;

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadCategories';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }

    public function deleteCategtory()
    {
        $categoryId = $_POST['categoryId'];
        $result = $this->Admin_model->deleteCategtory($categoryId);
        echo $result;
    }

    public function getCategoryValues()
    {
        $categoryId = $_POST['categoryId'];
        $result = $this->Admin_model->getCategoryName($categoryId);
        print_r(json_encode($result));
    }

    public function editCategory()
    {
        $categoryId = $_POST['categoryId'];
        $categoryName = $_POST['categoryName'];
        $result = $this->Admin_model->editCategory($categoryId, $categoryName);
        print_r(json_encode($result));
    }

    public function addNewCategory()
    {
        $data['categoryName'] = $_POST['categoryName'];
        $data['createDate'] = date('Y-m-d h:i:s');
        $data['status'] = 1;
        $result = $this->Admin_model->addNewCategory($data);
        echo $result;
    }
    // -------------------------------------------------------------
    public function getAllCategoryNames()
    {
        $categories = $this->Admin_model->getAllCategoryNames();
        echo json_encode($categories);
    }
    public function managePrimaryCategoryView()
    {
        if ($this->session->userdata('adminId') == null) {
            redirect('index.php/login');
        }
        $adminId = $this->session->userdata('adminId');
        $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
        $arrAdminData['title'] = 'Primary Categories';

        // $data['categories'] = $this->Admin_model->getAllCategoryNames();
        $this->load->view('admin/includes/header', $arrAdminData);
        $this->load->view('admin/manage_primary_category');
        $this->load->view('admin/includes/footer');
    }
    public function loadPrimaryCategories($rowNo = 0)
    {
        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 10;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getPrimaryCategoriesRecordCount();

        // Get records
        $usersRecord = $this->Admin_model->getPrimaryCategoriesList($rowNo, $rowPerPage);
        // print_r($usersRecord);exit;

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadPrimaryCategories';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }
    public function addNewPrimaryCategory()
    {
        $uploadDir = './uploads/categoryIcons/';
        $data['categoryId'] = $_POST['categoryId'];
        if($this->Admin_model->primaryCategoryCount($data['categoryId']) > 0){
            $result = 2;
        } else {
            $imageTmpName = $_FILES['file']['tmp_name'];
            $arrImage = explode('.',$_FILES['file']['name']);
            $extension = end($arrImage);
            $maxId = $this->Admin_model->getMaxId();
            $newImageName = $maxId. '.'. $extension;

            if(move_uploaded_file($imageTmpName, $uploadDir.$newImageName)){
                $data['iconImageURL'] = 'categoryIcons/'.$newImageName;
                $data['createDate'] = date('Y-m-d h:i:s');
                $data['status'] = 1;
                $result = $this->Admin_model->addNewPrimaryCategory($data);
            } else{
                $result = -1;
            }
        }
        echo $result;
    }
    public function deletePrimaryCategory()
    {
        $categoryId = $_POST['primaryCategoryId'];
        $result = $this->Admin_model->deletePrimaryCategory($categoryId);
        print_r($result);
    }
    public function getPrimaryCategoryValues()
    {
        $primaryCategoryId = $_POST['primaryCategoryId'];
        $result = $this->Admin_model->getPrimaryCategoryValues($primaryCategoryId);
        echo json_encode($result);
    }
    public function editPrimaryCategory()
    {
        $uploadDir = './uploads/categoryIcons/';
        $primaryCategoryId = $_POST['primaryCategoryId'];
        $categoryId = $_POST['categoryId'];
        $newCategoryId = $_POST['newCategoryId'];
        if(!empty($newCategoryId)){
            $data['categoryId'] = $newCategoryId;
        }
        $imageTmpName = $_FILES['file']['tmp_name'];
        if(!empty($imageTmpName)){
            // echo $imageTmpName;exit;
            $arrImage = explode('.',$_FILES['file']['name']);
            $extension = end($arrImage);
            $newImageName = $primaryCategoryId. '.'. $extension;
            move_uploaded_file($imageTmpName, $uploadDir.$newImageName);
            $data['iconImageURL'] = 'categoryIcons/'.$newImageName;
        }
        $result = $this->Admin_model->editPrimaryCategory($categoryId, $data);
        print_r(json_encode($result));
    }

    // ----------------------------------------------------
    public function notificationListView()
    {
        if ($this->session->userdata('adminId') == null) {
            redirect('index.php/login');
        }
        $adminId = $this->session->userdata('adminId');
        $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
        $arrAdminData['title'] = 'Notifications';

        $this->load->view('admin/includes/header', $arrAdminData);
        $this->load->view('admin/notification_list');
        $this->load->view('admin/includes/footer');
    }

    public function loadNotificationListing($rowNo = 0)
    {
        $date = $_POST['date'];
        $sorting = $_POST['sorting'];
        $search = $_POST['search'];
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 10;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getNotificationRecordCount($date, $sorting, $search);

        // Get records
        $usersRecord = $this->Admin_model->getNotificationList($rowNo, $rowPerPage, $date, $sorting, $search);
        // print_r($usersRecord);exit;

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadNotificationListing';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }

    public function sendNotificationView()
    {
        if ($this->session->userdata('adminId') == null) {
            redirect('index.php/login');
        }
        $adminId = $this->session->userdata('adminId');
        $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
        $arrAdminData['title'] = 'Send Notification';

        $data['arrCountries'] = $this->Admin_model->getCountries();
        $data['arrPrimaryCategories'] = $this->Admin_model->getPrimaryCategories();

        $this->load->view('admin/includes/header', $arrAdminData);
        $this->load->view('admin/send_notification', $data);
        $this->load->view('admin/includes/footer');
    }

    public function feedbackView()
    {
        if ($this->session->userdata('adminId') == null) {
            redirect('index.php/login');
        }
        $adminId = $this->session->userdata('adminId');
        $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
        $arrAdminData['title'] = 'feedback';

        $this->load->view('admin/includes/header', $arrAdminData);
        $this->load->view('admin/feedback');
        $this->load->view('admin/includes/footer');
    }

    public function loadFeedback($rowNo = 0)
    {
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 10;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getFeedbackRecordCount();

        // Get records
        $usersRecord = $this->Admin_model->getFeedbackList($rowNo, $rowPerPage);
        // print_r($usersRecord);exit;

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadFeedback';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }

    public function reportListingView()
    {
        if ($this->session->userdata('adminId') == null) {
            redirect('index.php/login');
        }
        $adminId = $this->session->userdata('adminId');
        $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
        $arrAdminData['title'] = 'Reports';

        $this->load->view('admin/includes/header', $arrAdminData);
        $this->load->view('admin/report_listing');
        $this->load->view('admin/includes/footer');
    }

    public function loadReport($rowNo = 0)
    {
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 10;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getReportRecordCount();

        // Get records
        $usersRecord = $this->Admin_model->getReportList($rowNo, $rowPerPage);
        // print_r($usersRecord);exit;

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadReport';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }

    public function reportDetailView($reportId = null)
    {
        if ($this->session->userdata('adminId') != null) {
            $reportId = base64_decode(urldecode($reportId));
            $adminId = $this->session->userdata('adminId');
            $arrResult['data'] = $this->Admin_model->getAdminData($adminId);
            $arrResult['reportData'] = $this->Admin_model->getReportDetail($reportId);
            $arrResult['title'] = 'Report Detail';
            $this->load->view('admin/includes/header', $arrResult);
            $this->load->view('admin/report_detail');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }
    public function reportedBusinessesListingView()
    {
        if ($this->session->userdata('adminId') == null) {
            redirect('index.php/login');
        }
        $adminId = $this->session->userdata('adminId');
        $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
        $arrAdminData['title'] = 'Reported Users Listing';

        $this->load->view('admin/includes/header', $arrAdminData);
        $this->load->view('admin/reported_businesses');
        $this->load->view('admin/includes/footer');
    }
    public function loadReportedBusinesses($rowNo = 0)
    {
        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 10;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getReportedBusinessListTotalCount();

        $usersRecord = $this->Admin_model->getAllReportedBusinessesData($rowNo, $rowPerPage);

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadReportedBusinesses';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }
    public function reportedBusinessDetailView($addressId = null)
    {
        if ($this->session->userdata('adminId') != null) {
            $addressId = base64_decode(urldecode($addressId));
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrResult = $this->Admin_model->getPublicAddressDetails($addressId);
            $arrResult['report'] = 1;
            $arrResult['title'] = 'Reported Business Detail';
            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/business_detail', $arrResult);
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }
    public function unblockAddress()
    {
        $addressId = $_POST['addressId'];
        $result = $this->Admin_model->unblockAddress($addressId);
        echo $result;
    }
    public function profileView()
    {
        if ($this->session->userdata('adminId') != null) {
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrAdminData['title'] = 'Profile';

            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/profile');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }

    public function manageFAQView()
    {
        if ($this->session->userdata('adminId') != null) {
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrAdminData['title'] = 'FAQ List';

            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/faq_listing');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }

    public function loadFAQ($rowNo = 0)
    {
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 20;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getFAQRecordCount();

        // Get records
        $usersRecord = $this->Admin_model->getFAQList($rowNo, $rowPerPage);
        // print_r($usersRecord);exit;

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadFAQ';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }
    public function getQuestionValues()
    {
        $questionId = $_POST['questionId'];
        $result = $this->Admin_model->getQuestionValues($questionId);
        print_r(json_encode($result));
    }
    public function deleteFAQ()
    {
        $questionId = $_POST['questionId'];
        $result = $this->Admin_model->deleteFAQ($questionId);
        print_r($result);
    }
    public function addNewFAQ()
    {
        $data['question'] = $_POST['question'];
        $data['answer'] = $_POST['answer'];
        $data['createDate'] = date('Y-m-d h:i:s');
        // $data['status'] = 1;
        $result = $this->Admin_model->addNewFAQ($data);
        print_r($data);
    }
    public function editFAQ()
    {
        $questionId = $_POST['questionId'];
        $data['question'] = $_POST['question'];
        $data['answer'] = $_POST['answer'];

        $result = $this->Admin_model->editFAQ($questionId, $data);

        print_r($result);
    }

    public function faqDetailView($questionId = null)
    {
        if ($this->session->userdata('adminId') != null) {
            $questionId = base64_decode(urldecode($questionId));
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrAdminData['faqData'] = $this->Admin_model->getQuestionValues($questionId);
            $arrAdminData['title'] = 'FAQ Detail';

            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/faq_detail');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }

    public function manageIssues()
    {
        if ($this->session->userdata('adminId') != null) {
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrAdminData['title'] = 'Manage issues';

            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/manage_issues');
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }
    public function loadIssues($rowNo = 0)
    {
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        if($rowNo == 0){
            $rowNo += 1;
        }
        $config['cur_tag_open'] = '<li class="page-item active"><a id="activePage" data-ci-pagination-page="'.$rowNo.'" class="disabled">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        // Row per page
        $rowPerPage = 20;

        // Row position
        if ($rowNo != 0) {
            $rowNo = ($rowNo - 1) * $rowPerPage;
        }

        // All records count
        $allCount = $this->Admin_model->getIssueRecordCount();

        // Get records
        $usersRecord = $this->Admin_model->getIssuesList($rowNo, $rowPerPage);
        // print_r($usersRecord);exit;

        // Pagination Configuration
        $config['base_url'] = base_url() . 'index.php/Admin/Admin/loadIssues';
        $config['use_page_numbers'] = true;
        $config['total_rows'] = $allCount;
        $config['per_page'] = $rowPerPage;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $usersRecord;
        $data['row'] = $rowNo;

        echo json_encode($data);
    }
    public function deleteIssue()
    {
        $issueId = $_POST['issueId'];
        $result = $this->Admin_model->deleteIssue($issueId);
        print_r($result);
    }
    public function addNewIssue()
    {
        $data['issue'] = $_POST['issue'];
        $data['createDate'] = date('Y-m-d h:i:s');
        $result = $this->Admin_model->addNewIssue($data);
        print_r($data);
    }
    public function getIssueValues()
    {
        $issueId = $_POST['issueId'];
        $result = $this->Admin_model->getIssueValues($issueId);
        print_r(json_encode($result));
    }
    public function editIssue()
    {
        $issueId = $_POST['issueId'];
        $data['issue'] = $_POST['issue'];

        $result = $this->Admin_model->editIssue($issueId, $data);

        print_r($result);
    }
    
    public function getChartDataFromUser()
    {
        $arrUserData = $this->Admin_model->getAllUserData();
        echo json_encode($arrUserData);
    }
    public function getDataFromUserForThisWeek()
    {
        $arrUserData = $this->Admin_model->getDataFromUserForThisWeek();
        echo json_encode($arrUserData);
    }
    public function getDataFromUserForThisMonth()
    {
        $arrUserData = $this->Admin_model->getDataFromUserForThisMonth();
        echo json_encode($arrUserData);
    }
    public function getDataFromUserForThisYear()
    {
        $arrUserData = $this->Admin_model->getDataFromUserForThisYear();
        echo json_encode($arrUserData);
    }

    public function getChartDataFromPublicAddresses()
    {
        $arrBusinessData = $this->Admin_model->getAllBusinessData();
        echo json_encode($arrBusinessData);
    }

    // business line chart filters for week, month and year
    public function getDataFromBusinessForThisWeek()
    {
        $arrBusinessData = $this->Admin_model->getDataFromBusinessForThisWeek();
        echo json_encode($arrBusinessData);
    }
    public function getDataFromBusinessForThisMonth()
    {
        $arrBusinessData = $this->Admin_model->getDataFromBusinessForThisMonth();
        echo json_encode($arrBusinessData);
    }
    public function getDataFromBusinessForThisYear()
    {
        $arrBusinessData = $this->Admin_model->getDataFromBusinessForThisYear();
        echo json_encode($arrBusinessData);
    }

    // users pie chart data to show users count for their status
    public function getStatusDataFromUser()
    {
        // $arrUserStatusData = $this->Admin_model->getAllUsersStatusData();
        // echo json_encode($arrUserStatusData);
        $data = $this->Admin_model->getAllUsersStatusData();
        $arrUserStatusData = [];
        for ($i = 0; $i < count($data); $i++) {
            $arrUserStatusData[$i]['userCount'] = $data[$i]['userCount'];
            if ($data[$i]['status'] == -5) {
                $arrUserStatusData[$i]['statusName'] = 'Blocked Users';
            } else if ($data[$i]['status'] == -1) {
                $arrUserStatusData[$i]['statusName'] = 'Deleted Users';
            } else if ($data[$i]['status'] == 0) {
                $arrUserStatusData[$i]['statusName'] = 'Deactivated Users';
            } else {
                $arrUserStatusData[$i]['statusName'] = 'Active Users';
            }
        }
        echo json_encode($arrUserStatusData);
    }

    public function updateAdminData()
    {
        $adminId = $_POST['adminId'];
        $data['userName'] = $_POST['userName'];
        $data['emailId'] = $_POST['emailId'];
        $data['name'] = $_POST['name'];
        $image = $_FILES['file'];
        if(!$image != 'undefined'){
            $imageNameTemp = $image['name'];
            $validExtensions = ['png', 'jpg', 'jpeg', 'tiff', 'raw', 'bmp'];
            $extension = end(explode('.',$imageNameTemp));
            $imageName = $adminId .'.'.$extension;
            $uploadDir = './uploads/admin/';
            if(in_array($extension, $validExtensions)){
                if(move_uploaded_file($image['tmp_name'], $uploadDir.$imageName)){
                    $data['profilePicURL'] = 'admin/'.$imageName;
                    $result = $this->Admin_model->updateAdminData($adminId, $data);
                } else{
                    $result = -1;
                }
            } else {
                $result = -2;
            }
        } else {
            $result = $this->Admin_model->updateAdminData($adminId, $data);
        }
        echo $result;
    }
    public function removeProfilePic()
    {
        $adminId = $_POST['adminId'];
        $result = $this->Admin_model->removeProfilePic($adminId);
        echo $result;
    }
    public function getStates()
    {
        $countryId = $_POST['countryId'];
        $states = $this->Admin_model->getStates($countryId);
        echo(json_encode($states));
    }

    public function sendNotification()
    {
        $data['countryId'] = $_POST['countryId'];
        $data['stateId'] = $_POST['stateId'];
        $data['categoryId'] = $_POST['categoryId'];
        $data['information'] = $_POST['information'];
        $data['createDate'] =  date('Y-m-d H:i:s a');
        $audience = $_POST['audience'];
        if($audience == 1){
            $data['notificationAudience'] = 'users';
            $arrAudience = $this->Admin_model->getUsersAudience($data);
        } else{
            $data['notificationAudience'] = 'businesses';
            $arrAudience = $this->Admin_model->getBusinessesAudience($data);
        }
        // print_r($arrAudience);
        if(!empty($arrAudience)){
            $notificationId = $this->Admin_model->saveNotificationAndGetNotificationId($data);
            // print_r($notificationId);
            for($i = 0; $i < count($arrAudience); $i++){
                $dataNotifyUsers['notificationId'] = $notificationId;
                $dataNotifyUsers['userId'] = $arrAudience[$i]['userId'];
                if($this->Admin_model->saveNotificationUser($dataNotifyUsers)){
                    $recipientPushTokens = $this->Admin_model->getPushToken($dataNotifyUsers['userId']);
                    // print_r($recipientPushTokens);
                    if(!empty($recipientPushTokens)){
                        for($j = 0; $j < count($recipientPushTokens); $j++){
                            $fields = array( 
                                'to' => $recipientPushTokens[$j]['pushToken'], 
                                'notification'  => array(
                                    'title'     => 'You have a new notification',
                                    'body'      => $data['information']
                                ),
                                'data'          => array(
                                    'type'      => 'admin'
                                )
                            );
                            // print_r($fields);
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
                }
            }
        }
        echo 'success';
    }
    public function repeatNotification()
    {
        $notificationId = $_POST['notificationId'];
        $data = $this->Admin_model->getNotificationDetail($notificationId);
        $data['createDate'] =  date('Y-m-d H:i:s a');
        $audience = $data['notificationAudience'];
        if($audience == 'users'){
            $arrAudience = $this->Admin_model->getUsersAudience($data);
        } else{
            $arrAudience = $this->Admin_model->getBusinessesAudience($data);
        }
        if(!empty($arrAudience)){
            $notificationId = $this->Admin_model->saveNotificationAndGetNotificationId($data);
            for($i = 0; $i < count($arrAudience); $i++){
                $dataNotifyUsers['notificationId'] = $notificationId;
                $dataNotifyUsers['userId'] = $arrAudience[$i]['userId'];
                if($this->Admin_model->saveNotificationUser($dataNotifyUsers)){
                    $recipientPushTokens = $this->Admin_model->getPushToken($dataNotifyUsers['userId']);
                    // print_r($id);
                    if(!empty($recipientPushTokens)){
                        for($j = 0; $j < count($recipientPushTokens); $j++){
                            $fields = array( 
                                'to' => $recipientPushTokens[$j]['pushToken'], 
                                'notification'  => array(
                                    'title'     => 'FCM Notification',
                                    'body'      => $data['information']
                                ),
                                'data'          => array(
                                    'type'      => 'admin'
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
                }
            }
        }
        echo 'success';
    }
    public function managePages()
    {
        if ($this->session->userdata('adminId') != null) {
            $adminId = $this->session->userdata('adminId');
            $arrAdminData['data'] = $this->Admin_model->getAdminData($adminId);
            $arrAdminData['title'] = 'Manage Pages';
            $data['about'] = $this->Admin_model->getAboutContent();
            $data['privacyPolicy'] = $this->Admin_model->getPrivacyPolicyContent();
            $data['termsConditions'] = $this->Admin_model->getTermsConditionsContent();

            // print_r($data);exit;
            $this->load->view('admin/includes/header', $arrAdminData);
            $this->load->view('admin/about', $data);
            $this->load->view('admin/includes/footer');
        } else {
            redirect('index.php/login');
        }
    }
    public function updateAboutUs()
    {
        $content = $_POST['content'];
        $result = $this->Admin_model->updateAboutUs($content);

        echo $result;
    }
    public function updatePrivacyPolicy()
    {
        $content = $_POST['content'];
        $result = $this->Admin_model->updatePrivacyPolicy($content);

        echo $result;
    }
    public function updateTermsConditions()
    {
        $content = $_POST['content'];
        $result = $this->Admin_model->updateTermsConditions($content);

        echo $result;
    }
    public function blockBusiness()
    {
        $reportId = $_POST['reportId'];
        $addressId = $_POST['addressId'];
        $data['blockDuration'] = $_POST['duration'];
        $data['blockDate'] = date('Y-m-d H:i:s a');
        if($data['blockDuration'] > 0){
            $data['isUnblockable'] = 1;
        } else {
            $data['isUnblockable'] = 0;
        }
        $data['status'] = -5;
        $result = $this->Admin_model->blockBusiness($reportId, $addressId, $data);
        if(!empty($result)){
            // $message = "your address has been deleted";
            $this->sendAddressMail($addressId, 'address', $data, 'block');
        }
        echo $result;
    }
    public function blockUser()
    {
        $reportId = $_POST['reportId'];
        $addressId = $_POST['addressId'];
        $blockDate = date('Y-m-d H:i:s a');
        $result = $this->Admin_model->blockUser($reportId, $addressId, $blockDate);
        if(!empty($result)){
            $this->sendAddressMail($addressId, 'user', 0, 'block');
        }
        echo $result;
    }
    public function sendAddressMail($addressId, $messageType, $data)
    {
        $arrData = $this->Admin_model->getUserMailDetail($addressId);
        if($messageType == 'address'){
            if($data['isUnblockable'] == 1){
                $message = "<div style='padding:10px 30px;'><p style='text-align: center;'>The following public address registered by your user account has been deactivated by Simplr Post</p>";
                $message .= '<h4 style="text-align: center;">'.$arrData['shortName'].', '.$arrData['address'].'</h4>';
                $message .= "<p style='text-align: center;'>If you have not requested this and a warning of this action was not previously communicated to you<br> then please contact <a href='mailto:abizerjafferjee@simplrpost.com?Subject=Account%20Deactivated' target='_blank' style='text-decoration:none;color:#1bac71'>abizerjafferjee@simplrpost.com</a> with your concern.</p></div>";
            } else {
                $message = "<div style='padding:10px 30px;'><p style='text-align: center;'>The following public address registered by your user account has been deleted by Simplr Post</p>";
                $message .= '<h4 style="text-align: center;">'.$arrData['shortName'].', '.$arrData['address'].'</h4>';
                $message .= "<p style='text-align: center;'>If you have not requested this and a warning of this action was not previously communicated to you<br> then please contact <a href='mailto:abizerjafferjee@simplrpost.com?Subject=Account%20Deleted' target='_blank' style='text-decoration:none;color:#1bac71'>abizerjafferjee@simplrpost.com</a> with your concern.</p></div>";
            }
        } else {
            $message = "<div style='padding:10px 30px;'><p style='text-align: center;'>Your account has been deleted by Simplr Post. If you have not requested this and a warning of this action was not previously communicated to you then please contact <a href='mailto:abizerjafferjee@simplrpost.com?Subject=Account%20Deleted' target='_blank' style='text-decoration:none;color:#1bac71'>abizerjafferjee@simplrpost.com</a> with your concern.</p></div>";
        }
        $email_data['email_title'] = 'Simplr Post';
        $email_data['heading'] = "Hey ".ucfirst($arrData['name']).',';
        $email_data['email_id'] = $arrData['emailId'];
        $email_data['message'] = $message;
        $email_data['footer'] = "";
        $email_data['view_url'] = 'email/otpEmailTemplate';
        
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
		$mail->SetLanguage( 'en', 'phpmailer/language/');

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
    public function sendUserEmail($userId)
    {
        $arrData = $this->Admin_model->getUserDetailForEmail($userId);

        $email_data['email_title'] = 'Simplr Post';
        $email_data['email_id'] = $arrData['emailId'];
        $email_data['heading'] = "Hey ". ucfirst($arrData['name']).',';
        $email_data['message'] = "<div style='padding:10px 30px;'><p style='text-align: center;'>Your account has been deleted by Simplr Post. If you have not requested this and a warning of this action was not previously communicated to you then please contact <a href='mailto:abizerjafferjee@simplrpost.com?Subject=Account%20Deleted' target='_blank' style='text-decoration:none;color:#1bac71'>abizerjafferjee@simplrpost.com</a></p></div>";
        $email_data['footer'] = '';
        $email_data['view_url'] = 'email/emailTemplate';
        
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
}
