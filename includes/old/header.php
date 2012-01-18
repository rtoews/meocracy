<!DOCTYPE html> 
<html> 
	<head> 
	<meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Meocracy Admin</title>  
	<link rel="stylesheet"  href="http://code.jquery.com/mobile/1.0rc1/jquery.mobile-1.0rc1.min.css" /> 
	<link rel="stylesheet" href="css/jqm-docs.css"/>
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script src="js/jquery.mobile.themeswitcher.js"></script>
	<script src="js/jqm-docs.js"></script>
	<script src="http://code.jquery.com/mobile/1.0rc1/jquery.mobile-1.0rc1.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script> 

	<!-- CSS override -->
	<link rel="stylesheet" href="css/styles.css">
</head> 
<body>

<div data-role="page" class="type-interior">

	<div data-role="header" data-theme="b">
		<h1><?php echo $entity; ?></h1>
		<a href="/index.php" data-icon="home" data-iconpos="notext" data-direction="reverse" class="ui-btn-right jqm-home">Home</a>
	</div><!-- /header -->


	<div data-role="content" data-theme="c">
		<div class="content-primary">