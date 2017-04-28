<?php
include "conf.php";
function process_user(){
	global $db;
	global $out;
	// Checking details of the user
	$user=$db->select("userinfo","*","where phone='{$_COOKIE['phone']}' and email='{$_COOKIE['email']}'");
	if($user['rows']==0){
		//checking if form3 is submitted
		if(var_defined(Array("name",'dob','status','sex','nearby'),$_REQUEST)){
			$data=Array("username"=>"md5('{$_COOKIE['phone']}{$_COOKIE['email']}')",
			"phone"=>$_COOKIE['phone'],
			"email"=>$_COOKIE['email'],
			"device_info"=>get_cookie('device_info'),
			"name"=>$_REQUEST['name'],
			"dob"=>$_REQUEST['dob'],
			"status"=>$_REQUEST['status'],
			"nearby"=>$_REQUEST['nearby']);
			$out['action']='form4';
		}
		else
		{
			$out['action']='form3';
		}
	}else{
		$out['user']=$user[0];
	}
}

//Verify if user is logged in
if(isset($_COOKIE['user'])){
//The user has been authenticated on the system
//Activities required after login
}else{
	$out['action']="form1";
	if(isset($_REQUEST['device']))$_COOKIE['device_info']=json_encode($_REQUEST['device']);
	//Checking if login is requested (from form1)
	if(isset($_REQUEST['phone'])&& isset($_REQUEST['email'])){
		$phn=new phone($_REQUEST['phone']);
		$sms=new sms('81455ADZILxwLcEZ5527c2f4');
		$sms->add_rcpt($phn->get_phone()['phn']);
		$sms->set_message('Your one time password for SPOTCHAT is '.$phn->get_otp());
		$sms->set_sender('etutor');
		$sms->set_route('transactional');
		$sms->set_country(91);
		//Storing otp into cookies
		setcookie("phone",$phn->get_num(), time() + (86400), "/");//set cookies for 1 day
		setcookie("otp",$phn->get_otp(), time() + (300), "/");//set cookies for 5 minutes
		$sms->send();
		$email=new email(get_request('email'));
		setcookie("email",$email->get_email(), time() + (86400), "/");//set cookies for 1 day
		setcookie("evc",$email->get_evc(), time() + (86400), "/");//set cookies for 1 day
		$email->send();
		$out['action']="form2";
	}
	//Checking if evc and otp verification requested
	if(isset($_REQUEST['evc'])){
		$eml=new email(get_cookie("email"));
		$eml->set_evc(get_cookie("evc"));
		if($eml->verify(get_request("evc"))){
			$out['action']="evc_confirmed";
			setcookie("evc_status",'verified', time() + (86400), "/");//set cookies for 1 day
			if(isset($_COOKIE['evc_status'])){
				process_user();
			}
		}else{
			$out['message']='Please try again';
			$out['action']='evc_failed';
		}
	}
	if(isset($_REQUEST['otp'])){
		$phn=new phone(get_cookie("phone"));
		$phn->set_otp(get_cookie("otp"));
		if($phn->verify(get_request("otp"))){
			$out['action']="otp_confirmed";
			setcookie("otp_status",'verified', time() + (86400), "/");//set cookies for 1 day
			if(isset($_COOKIE['evc_status'])){
				process_user();
			}
		}else{
			$out['message']='Please try again';
			$out['action']='otp_failed';
		}
	}
	//checking if otp and evc both verified
	if(isset($_COOKIE['evc_status'])&&isset($_COOKIE['otp_status'])){
		process_user();
	}
}
$output=Array("message"=>json_encode($out));
$db->insert("response",$output);
echo json_encode($out);
?>
