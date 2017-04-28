<?php
class email{
	private $user,$domain,$evc;
	public function send(){
		 $to = $this->user."@".$this->domain;
         $subject = "Email authentication code from SpotChat";
         
         $message = "<b>Dear User,</b>";
         $message .= "<p>Your email verification code is ".$this->evc." .<br\><br\><br\>This code is valid only for 48 hours. <br\><br\><br\>Thanks for joining us.</p>";
         
         $header = "From:registration@spotchat.in \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to,$subject,$message,$header);

	}
	public function __Construct($email){
		$info=explode("@",$email);
		if(count($info)==2){
			$this->user=$info[0];
			$this->domain=$info[1];
			$this->generate_evc();
		}else{
			return false;
		}
	}
	private function generate_evc($len=6,$chars="ABCDEFGHIJKLMNOPQRSTUVWXYZ"){
		$evc="";
		for($i=0;$i<$len;$i++){
			$evc.=$chars[rand(0,strlen($chars)-1)];
		}
		$this->evc=$evc;
	}
	public function get_domain(){return $this->domain;}
	public function get_user(){return $this->user;}
	public function get_email(){
		$output=$this->user."@".$this->domain;
		return $output;
	}
	public function get_evc(){
		return $this->evc;
	}
	public function set_evc($evc){
		$this->evc=$evc;
	}
	public function verify($evc){
		return ($this->evc==$evc);
	}
}

