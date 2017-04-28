<?php
function update_profile(){
	global $db;
	$user['uuid']=$_REQUEST['uuid'];
	$user['user']=$_REQUEST['id'];
	$user['name']=$_REQUEST['name'];
	$user['sex']=$_REQUEST['sex'];
	$user['dob']=$_REQUEST['dob'];
	$user['status']=$_REQUEST['status'];
	$user['privacy']=$_REQUEST['privacy'];
	$user['created_on']=$_SERVER['REQUEST_TIME'];
	$data=$db->select("login","*","where uuid='{$user['uuid']}'");
	if($data['rows']==0){
		$d=$db->select("login","*","where user='{$user['user']}'");
		if($d['rows']==0){
			$db->insert("login",$user);
		}
	}
}

