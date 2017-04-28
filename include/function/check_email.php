<?php 
function check_email(){
	global $db,$out;
	if(isset($_REQUEST['email'])){
	$eml=new email($_REQUEST['email']);
	//chekcing if email is new
	$data=$db->select("email","*","where user='{$eml->get_user()}' and domain='{$eml->get_domain()}'");
	if($data['rows']==0){
		$e=$eml->get_email();
		$e['added_on']=$_SERVER['REQUEST_TIME'];
		$e['id']=$_REQUEST['id'];
		$db->insert("email",$e);
		$data=$db->select("email","*","where user='{$eml->get_user()}' and domain='{$eml->get_domain()}'");
		$otp=Array("email"=>$data[0][0],"otp"=>$eml->get_otp(),"generated_on"=>$_SERVER['REQUEST_TIME'],"valid_for"=>300);
		trace($otp);
		$db->insert("email_otp",$otp);
		$out['action']="eotp_form";
	}
	else{
	//checking if otp verified
		$d=$db->select("email_otp","*","where email={$data[0][0]}");
		if($d['rows']!=0){
			$eml->set_otp($d[0][2]);
			if(isset($_REQUEST['eotp'])){
				if($eml->verify($_REQUEST['eotp'])){
					$now=$_SERVER['REQUEST_TIME'];
					$db->query("delete from email_otp where id={$d[0][0]}");
					$db->query("update email set verified_on =$now where id={$data[0][0]}");
					$out['status']="email OTP verified";
					$out['action']="profile_form";
					$out['session']=(isset($_REQUEST['session']))?$_REQUEST['session']:"";
				}
				else{
					$out['status']="OTP is not valid";
					$out['action']="eopt_form";
				}
			}
			else{
				$out['action']="eotp_form";
			}
		}else{
			$out['action']="profile_form";
			$out['status']="Email OTP Verified";
		}
	}
}
}
