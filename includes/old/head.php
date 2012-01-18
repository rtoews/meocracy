<?php 

echo "


<!DOCTYPE html> 
<html> 
	<head>
	<meta charset='utf-8'>

	<META NAME='ROBOTS' CONTENT='NOINDEX, NOFOLLOW'>

	<!-- iOS Viewport -->
	<!-- meta name = 'viewport' content = 'user-scalable=no, width=device-width' -->
	<meta name='viewport' content='width=device-width, initial-scale=1'> 

	<title>".$page_title."</title>  

	<!-- iOS Hides the status bar -->
	<meta name='apple-mobile-web-app-capable' content='yes' /> 
	<meta name='apple-mobile-web-app-status-bar-style' content='black' />

	<!-- iOS Icons -->
	<link rel='apple-touch-startup-image' href='/photos/logo/startup.jpg'>
	<link rel='apple-touch-icon' href='/photos/logo/touch-icon-iphone.png' /> 
	<link rel='apple-touch-icon' sizes='72x72' href='/photos/logo/touch-icon-ipad.png' /> 
	<link rel='apple-touch-icon' sizes='114x114' href='/photos/logo/touch-icon-iphone4.png' /> 

	<link rel='stylesheet' type='text/css' href='http://code.jquery.com/mobile/1.0rc3/jquery.mobile-1.0rc3.min.css' />
	<link rel='stylesheet' type='text/css' href='/css/meocracy.css' />

	<script type='text/javascript' src='http://code.jquery.com/jquery-1.6.4.min.js'></script>
	<script type='text/javascript' src='http://code.jquery.com/mobile/1.0rc3/jquery.mobile-1.0rc3.min.js'></script>
	<script type='text/javascript' src='/js/jqm-docs.js'></script>

	<!-- jtSage Datebox -->
	<link rel='stylesheet' type='text/css' href='/css/jquery.mobile.datebox-1.0rc1.min.css' />
	<script type='text/javascript' src='/js/jquery.mobile.datebox-1.0rc1.min.js'></script>

</head> 
<body> 


<div data-role='page' data-title='".$page_title."'>

<div data-role='header' data-theme='b'>
	<div data-role='navbar'>
		<ul>
		<li><a href='index_mobile.php' data-icon='home' data-iconpos='top' data-theme='".$nav_theme."' style='height:56px!important'>Home</a></li>
		<li><a href='prefs.php' data-icon='gear' data-iconpos='top' data-theme='".$nav_theme."' style='height:56px!important'>Prefs</a></li>
		<li><a href='about.php' class='logo' data-iconpos='notext' data-theme='".$nav_theme."'><img src='/images/logo_sm.png' alt='About Meocracy'></a></li>
		</ul>
	</div>
</div><!-- /header -->

<div data-role='content'>";

?>


