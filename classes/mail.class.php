<?php

require "Mail.php";

class SendMail
{
	private $host;
	private $port;
	private $user;
	private $password;
	public $error = false;

	public function __construct($host, $port, $user, $password)
	{
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->password = $password;
	}

	public function confirmation($user)
	{
		$subject = "Account confirmation";
		$ckey = md5(time() . $user->username);
		$body = "http://127.0.0.1:8080/user_account/confirm.php?ckey=$ckey";
		$headers = array ('From' => $this->user, 'To' => $user->email, 'Subject' => $subject);
		$this->send($user->email, $body, $headers);

	}

	public function pwdreset($user, $selector, $token)
	{
		$subject = "Password reset";
		$body = "http://127.0.0.1:8080/user_account/pswd_reset.php?selector=" . $selector . "&validator=" . bin2hex($token);
		$headers = array ('From' => $this->user, 'To' => $user['email'], 'Subject' => $subject);
		$this->send($user['email'], $body, $headers);
	}

	protected function send($user_email, $body, $headers)
	{
		$smtp = Mail::factory('smtp', array(
			'host' => $this->host,
			'port' => $this->port,
			'auth' => true,
			'username' => $this->user,
			'password' => $this->password));
		$mail = $smtp->send($user_email, $headers, $body);
		if (PEAR::isError($mail))
		{
			$this->error = true;
			echo($mail->getMessage());
		} 
	}
}
