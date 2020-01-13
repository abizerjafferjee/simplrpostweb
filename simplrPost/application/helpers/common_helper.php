<?php 
if (!function_exists('sendEmailTemplate'))
{
	function sendEmailTemplate($to,$subject,$body)
	{
		
		$config['protocol'] = 'smtp';
        $config['smtp_host'] = "mail.samosys.com";
        $config['smtp_port'] = 587;
        $config['smtp_user'] = "test@samosys.com";
        $config['smtp_pass'] = "test@#321";         
        $config['wordwrap'] = FALSE;
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";

        $CI = get_instance();
        $CI->load->library('email');

        $CI->email->initialize($config);

        $CI->email->set_newline("\r\n");
        $CI->email->from('test@samosys.com','Simplrpost');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($body);

        if ($CI->email->send()) {
           return true;
        } else {
           return false;
        }

	}
}


?>