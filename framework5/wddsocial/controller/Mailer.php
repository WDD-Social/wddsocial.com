<?php

namespace WDDSocial;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

/*
# email
import('wddsocial.controller.WDDSocial\Mailer');
$mailer = new Mailer();
$mailer->add_recipient('Bob Dole', 'bobdole@usa.com');
$mailer->add_recipient('Ralph Nader', 'stillnotpresident@hotmail.com');
$mailer->subject = "Subject Line";
$mailer->message = "Message Content";
$mailer->send();
*/

class Mailer extends \Framework5\Controller {
	
	
	private $_recipients = array();
	private $_data = array(
		'subject' => null,
		'message' => null
	);
	
	
	public function __get($key) {
		if (!array_key_exists($key, $this->_data))
			throw new Exception("Could not set mailer property '$key'");
		return $this->_data[$key];
	}
	
	public function __set($key, $value) {
		if (!array_key_exists($key, $this->_data))
			throw new Exception("Could not get invalid mailer property '$key'");
		$this->_data[$key] = $value;
		return true;
	}
	
	
	
	/**
	* Add a recipient
	*/
	
	public function add_recipient($name = null, $email = null) {
		# validate input
		if (!isset($name))
			throw new Exception("Recipient name not provided");
		if (!isset($email))
			throw new Exception("Email address not provided");
		
		# add to array
		$this->_recipients[$name] = $email;
	}
	
	
	
	/**
	* Send the current message
	*/
	
	public function send() {
		
		$total_recipients = count($this->_recipients);
		
		# validate properties
		if ($total_recipients == 0)
			throw new Exception("Mailer requires at least one recipient");
		if ($this->subject == null)
			throw new Exception("Mailer requires a subject");
		if ($this->message == null)
			throw new Exception("Mailer requires a message");
		
		# message template
		$message = render('wddsocial.view.email.WDDSocial\MailTemplate', $this->message);
		
		# Content-type header
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		# recipient(s)
		$delimiter = ', ';
		$i = 1;
		$headers .= "To: ";
		foreach ($this->_recipients as $name => $email) {
			if ($i == $total_recipients) $delimiter = '';
			$headers .= "$name &lt;$email&gt;$delimiter";
			$to .= $email . $delimiter;
			$i++;
		}
		$headers .= "\r\n";
		
		# From header
		$headers .= 'From: WDD Social &lt;notify@wddsocial.com&gt;' . "\r\n";
		
		# send
		mail($to, $subject, $message, $headers);
	}
	
	
}