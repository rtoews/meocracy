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

	<title>".$entity_name."</title>  

	<!-- iOS Hides the status bar -->
	<meta name='apple-mobile-web-app-capable' content='yes' /> 
	<meta name='apple-mobile-web-app-status-bar-style' content='black' />

	<!-- iOS Icons -->
	<link rel='apple-touch-startup-image' href='/photos/logo/startup.jpg'>
	<link rel='apple-touch-icon' href='/photos/logo/touch-icon-iphone.png' /> 
	<link rel='apple-touch-icon' sizes='72x72' href='/photos/logo/touch-icon-ipad.png' /> 
	<link rel='apple-touch-icon' sizes='114x114' href='/photos/logo/touch-icon-iphone4.png' /> 

	<link rel='stylesheet'  href='http://code.jquery.com/mobile/1.0rc2/jquery.mobile-1.0rc2.min.css' />
	<link rel='stylesheet' href='/css/jquery.mobile.datebox.min.css' />
	<link rel='stylesheet' href='/css/jqm-docs.css' />
	<link rel='stylesheet' href='/css/overrides.css' />

	<script src='http://code.jquery.com/jquery-1.6.4.min.js'></script>
	<script src='http://code.jquery.com/mobile/1.0rc2/jquery.mobile-1.0rc2.min.js'></script>
	<script src='/js/jquery.mobile.datebox.min.js'></script>
	<script src='/js/jquery.mobile.themeswitcher.js'></script>
	<script src='/js/jqm-docs.js'></script>
</head> 
<body>

<div data-role='page' class='type-interior'>

	<div data-role='content'>

	<div data-role='header' data-theme='b'>
		<a href='index' data-icon='home' class='ui-btn-left'>Home</a>
			<h1>".$entity_name."</h1>
		<a href='setup' data-icon='gear' class='ui-btn-right'>Setup</a>
	</div><!-- /header -->



	<div data-role='content-primary'>
		".$content."
	</div>



	<div class='content-secondary'>


	<div data-role='collapsible' data-collapsed='true'>

	<ul data-role='listview' data-inset='true'>\n";

	if ($section=='feedback') {
		echo "\t\t\t\t<li data-theme='b'><a href='feedback'>Feedback</a></li>\n"; }
		else { echo "\t\t\t\t<li><a href='feedback'>Feedback</a></li>\n"; }
	if ($section=='message') {
		echo "\t\t\t\t<li data-theme='b'><a href='message'>Messages</a></li>\n"; }
		else { echo "\t\t\t\t<li><a href='message'>Messages</a></li>\n"; }
	if ($section=='announcement') {
		echo "\t\t\t\t<li data-theme='b'><a href='announcement'>Announcements</a></li>\n"; }
		else { echo "\t\t\t\t<li><a href='announcement'>Announcements</a></li>\n"; }
	if ($section=='legislation') {
		echo "\t\t\t\t<li data-theme='b'><a href='legislation'>Legislation</a></li>\n"; }
		else { echo "\t\t\t\t<li><a href='legislation'>Legislation</a></li>\n"; }
	if ($section=='user') {
		echo "\t\t\t\t<li data-theme='b'><a href='user'>Users</a></li>\n"; }
		else { echo "\t\t\t\t<li><a href='user'>Users</a></li>\n"; }

	echo "</ul></div>

	</div>
	</div>

	<div data-role='footer' data-position='fixed' data-theme='c'>
		<img src='/images/logo_sm.png' height='20' width='92'>
	</div>

<!-- /page -->
</div>

</body>
</html>"; 

?>

