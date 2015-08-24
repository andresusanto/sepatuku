<?php
include 'required.php';

$uri = $_SERVER['REQUEST_URI'];

$uri = split("/", $uri);

if (!isset($uri[1])||$uri[1]=="") {
    include 'pagelist.php';	
} else {
	switch ($uri[1]) {
	  case "archive":
	    $template = $uri[2];   
	    include 'archive.php';
	  break;
	  case "update":
		  $tdkDir = $rootDir."/".$uri[2];
		  $tdkConfig = parse_ini_file($tdkDir."/.sirclo-tdk");
		  $dir = $tdkConfig['importer'];
		  $templateSrc = $tdkConfig['src'];
			$old_path = getcwd();
			chdir($dir);
			$output = shell_exec('./templatesdk-import.sh '.$tdkDir.' '.$templateSrc.' 2>&1');
			chdir($old_path);
			header("Location: /");
			die();
	  break;
	  default:
			    echo '404';
			
	}
}
