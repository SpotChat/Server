<?php
class sms{
	 private $authkey,$to_mobile,$message,$sender,$route,$country;
	 public function __Construct($authkey){
	 	$this->authkey=$authkey;
	 }
	 public function add_rcpt($phone){
	 	$this->to_mobile.=(strlen($this->to_mobile)==0)?$phone:",".$phone;
	 }
	 public function set_message($text){
	 	$this->message=$text;
	 }
	 public function set_sender($text){
	 	$this->sender=$text;
	 }
	 public function set_route($route){
	 	$this->route=($route=="transactional")?4:1;
	 }
	 public function set_country($country_code){
	 	$this->country=$country_code;
	 }
	 public function send(){
	 	$ch = curl_init();
		$get="authkey={$this->authkey}&mobiles={$this->to_mobile}&message={$this->message}&sender={$this->sender}&route={$this->route}&country={$this->country}";
		$ch = curl_init("https://control.msg91.com/api/sendhttp.php?$get");
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		 $ret=curl_exec ($ch);
		 curl_close ($ch);
		 return $ret;
	 }
}

