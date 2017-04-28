<?php 
die;
$out=Array();
include "conf.php";
if(isset($_REQUEST['device']))$out['request']=json_decode($_REQUEST['device'],1);
if(isset($_COOKIES['user'])){
	$user=$_COOKIES['user'];
}
else
{
	if(isset($_REQUEST['user'])){
		$out['email']=$_REQUEST['user'][1];
		$out['phone']=$_REQUEST['user'][0];
		$out['action']='form2';
	}else{
		$out['action']='form5';
		$out['flds']=explode("|","Height|Weight|complexion|marital status|smoking|drinking");
		$out['flds_inp']=explode("|","<input type=number class=form-control>|<input type=number class=form-control>|<select class=form-control>
<option>White</option><option>Fair</option><option>Wheatish</option><option>Dark</option></select>|<select class=form-control><option value=0>Yes</option><option value=1>No</option></select>|select class=form-control><option value=0>Yes</option><option value=1>No</option></select>");
	}
}
$output=Array("message"=>json_encode($out));
$db->insert("response",$output);
echo json_encode($out);
