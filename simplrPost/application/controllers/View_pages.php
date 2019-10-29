<?php defined('BASEPATH') OR exit('No direct script access allowed');

class View_pages extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('View_pages_model');
    }
    public function aboutUs()
    {
        $data['content'] = $this->View_pages_model->getAboutUsContent();
        $this->load->view('viewPages/page', $data);
    }
    public function privacyPolicy()
    {
        $data['content'] = $this->View_pages_model->getPrivacyPolicyContent();
        $this->load->view('viewPages/page', $data);
    }
    public function termsConditions()
    {
        $data['content'] = $this->View_pages_model->getTermsConditionsContent();
        $this->load->view('viewPages/page', $data);
    }
}