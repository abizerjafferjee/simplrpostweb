<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH. 'libraries/vendor/qr-code-master/src/QrCode.php';
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
require APPPATH . 'libraries/vendor/autoload.php';

class Qrimages extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		  $this->load->helper('url');
    }
	
	public function index()
	{
		$data['img_url']="";
		if($this->input->post('action') && $this->input->post('action') == "generate_qrcode")
		{
			$this->load->library('ciqrcode');
			$qr_image=rand().'.png';
			$params['data'] = $this->input->post('qr_text');
			$params['level'] = 'H';
			$params['size'] = 6;
			$params['savename'] =FCPATH."uploads/qr_image/".$qr_image;
			if($this->ciqrcode->generate($params))
			{
				$data['img_url']=$qr_image;	
			}
		}
		$this->load->view('qrcode',$data);
	}
	public function test(){
		// Create a basic QR code
		$qrCode = new QrCode();
		$qrCode->setText('http://ourcodeworld.com')
			->setSize(300)
			->setPadding(10)
			->setErrorCorrection('high')
			->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
			->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
			// Path to your logo with transparency
			->setLogo("logo.png")
			// Set the size of your logo, default is 48
			->setLogoSize(98)
			->setImageType(QrCode::IMAGE_TYPE_PNG)
		;

		// Send output of the QRCode directly
		header('Content-Type: '.$qrCode->getContentType());
		$qrCode->render();
	}
	

}
