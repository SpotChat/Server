<?php
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

if($_SERVER['PHP_SELF']=="/conf.php"){die("Access to this page is not allowed");}else{
	if(!function_exists("load_class")){
		function load_class($class,$classpath=__DIR__){
			$file=$classpath."/include/class/".$class.".php";
			if(!file_exists($file)){
				echo "The class {$class} is not installed in your application";
			}else{
				include $file;
			}
		}
	}
	if(!function_exists("trace")){
		function trace($var){
			echo "<pre>".print_r($var,true)."</pre>";
		}
	}
	if(!function_exists("get_request")){
		function get_request($varname){
			return isset($_REQUEST[$varname])?$_REQUEST[$varname]:"";
		}
	}
	if(!function_exists("var_defined")){
		function var_defined($varname,$array){
			
			foreach ($varname as $value){
				if(!isset($array[$value]))return false;
			}
			return true;
		}
	}
	if(!function_exists("get_cookie")){
		function get_cookie($var){
			return (isset($_COOKIE[$var]))?$_COOKIE[$var]:"";
		}
	}
	spl_autoload_register ("load_class");
	$db =new database(Array("host"=>'localhost','user'=>'spotchatweb','dbname'=>'spotchatweb','pass'=>'palw'));
	$req=Array("message"=>json_encode($_REQUEST));
if(!strstr($_SERVER['REQUEST_URI'],'log'))$db->insert("logs",$req);
}


