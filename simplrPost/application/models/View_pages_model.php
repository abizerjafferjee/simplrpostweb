<?php
defined('BASEPATH') or exit('No direct script access allowed');

class View_pages_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getAboutUsContent()
    {
        $this->db->where('id', 1);
        $this->db->select('content');
        return $this->db->get('aboutUs')->row_array();  
    }
    public function getPrivacyPolicyContent()
    {
        $this->db->where('id', 1);
        $this->db->select('content');
        return $this->db->get('privacyPolicy')->row_array();  
    }
    public function getTermsConditionsContent()
    {
        $this->db->where('id', 1);
        $this->db->select('content');
        return $this->db->get('termsConditions')->row_array();  
    }
}