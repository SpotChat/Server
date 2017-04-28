<?php
class phone{
	private $num,$otp,$valid_upto;
	public function __Construct($num){
		$this->num=$num;
		$this->generate_otp();
	}
	private function generate_otp($len=4,$chars="0123456789"){
		$otp="";
		for($i=0;$i<$len;$i++){
			$otp.=$chars[rand(0,strlen($chars)-1)];
		}
		$this->otp=$otp;
	}
	public function set_otp($otp){$this->otp=$otp;}
	public function verify($otp){
		return ($otp==$this->otp);
	}
	public function get_otp(){return $this->otp;}
	public function get_phone(){
		$output=Array(
			'phn'=>$this->num);
		return $output;
	}
	public function get_num(){return $this->num;}
}

