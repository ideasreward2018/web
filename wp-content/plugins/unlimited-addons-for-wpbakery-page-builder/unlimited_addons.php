<?php
/*
Plugin Name: Unlimited Addons for WPBakery Page Builder
Plugin URI: http://unlimited-addons.com
Description: Unlimited Addons - addons pack for WPBakery Page Builder (formally visual composer)
Author: Unite CMS
Version: 1.0.38
Author URI: http://unitecms.net
*/

//ini_set("display_errors", "on");
//ini_set("error_reporting", E_ALL);

if(!defined("UNLIMITED_ADDONS_INC"))
	define("UNLIMITED_ADDONS_INC", true);

$mainFilepath = __FILE__;
$currentFolder = dirname($mainFilepath);
$pathProvider = $currentFolder."/provider/";


//phpinfo();
try{
	$pathAltLoader = $pathProvider."provider_alt_loader.php";
	if(file_exists($pathAltLoader)){
		require $pathAltLoader;
	}else{
	require_once $currentFolder.'/includes.php';
	
	require_once  GlobalsUC::$pathProvider."core/provider_main_file.php";
	}
	
}catch(Exception $e){
	$message = $e->getMessage();
	$trace = $e->getTraceAsString();
	echo "<br>";
	echo $message;
	echo "<pre>";
	print_r($trace);
}


