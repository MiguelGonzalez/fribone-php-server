<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_PHPMailer {
    public function __construct() {
    	$this->ci =& get_instance();
    	$this->ci->load->helper('email');
    	$this->ci->config->load('email');

        require_once('PHPMailer/class.phpmailer.php');
    }

    public function send_email($email, $subject, $data, $name_template) {
    	if (!valid_email($email)) {
    		return FALSE;
    	}

    	$body_email = $this->ci->load->view('default/mailing/' . $name_template, $data, true);

    	$mail = new PHPMailer;

		$mail->isSMTP();
		$mail->Host = $this->ci->config->item('email-host');
		$mail->Port = $this->ci->config->item('email-port');
		$mail->SMTPAuth = $this->ci->config->item('email-SMTPAuth');
		$mail->Username = $this->ci->config->item('email-username');
		$mail->Password = $this->ci->config->item('email-password');
		$mail->SMTPSecure = $this->ci->config->item('email-SMTPSecure');
		$mail->isHTML($this->ci->config->item('email-userHTML'));
		$mail->CharSet = $this->ci->config->item('email-charSet');

		$mail->From = $this->ci->config->item('email-from');
		$mail->FromName = $this->ci->config->item('email-fromName');
		if(is_null($email_name)) {
			$mail->addAddress($email, $email_name);
		} else {
			$mail->addAddress($email);
		}

		$mail->Subject = 'Recuperación de contraseña';
		$mail->Body = $body_email;

		if($mail->send()) {
		    return TRUE;
		}
		return FALSE;
    }
}