<?php
function check_phone(){
	global $db;
	global $out;
	if(isset($_REQUEST['phone'])){
		$phn=new phone($_REQUEST['phone'],$_REQUEST['cc']);
		//chekcing if phone number is new
		$data=$db->select("phone","*","where country_code='{$phn->get_cc()}' and phn='{$phn->get_num()}'");
		if($data['rows']==0){
			$p=$phn->get_phone();
			$p['added_on']=$_SERVER['REQUEST_TIME'];
			$db->insert("phone",$p);
			$data=$db->select("phone","*","where country_code='{$phn->get_cc()}' and phn='{$phn->get_num()}'");
			$otp=Array("phone"=>$data[0][0],"otp"=>$phn->get_otp(),"generated_on"=>$_SERVER['REQUEST_TIME'],"valid_for"=>300);
			$db->insert("phone_otp",$otp);
			$out['action']="otp_form";
		}
		else{
		//checking if otp verified
			$d=$db->select("phone_otp","*","where phone={$data[0][0]}");
			if($d['rows']!=0){
				$phn->set_otp($d[0][2]);
				if(isset($_REQUEST['potp'])){
					if($phn->verify($_REQUEST['potp'])){
						$now=$_SERVER['REQUEST_TIME'];
						$db->query("delete from phone_otp where id={$d[0][0]}");
						$db->query("update phone set verified_on =$now where id={$data[0][0]}");
						$out['status']="OTP verified";
						$out['action']="email_form";
						$out['id']=$data[0][0];
					}
					else{
						$out['status']="OTP is not valid";
						$out['action']="opt_form";
					}
				}
				else{
					$out['action']="otp_form";
				}
			}else{
				$out['action']="email_form";
				$out['status']="Phone OTP Verified";
				$out['id']=$data[0][0];
			}
		}

	}else{
		$out['action']="phone_form";
	}
}
