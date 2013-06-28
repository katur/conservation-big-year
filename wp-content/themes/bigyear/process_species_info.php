<?php	
	include("katherine_connect.php");
	/*
	Template Name: Process Species Info
	Copyright (c) 2013 Katherine Erickson
	*/
	
	// get the common name from the GET variable (visible in the url). 
	$url_common_name = mysql_real_escape_string($_GET['common_name']);

	// grab variables from POST variable
	$flickr_code = $_POST['flickr_code'];
	$cornell_map = $_POST['cornell_map'];
	$ebird_map = $_POST['ebird_map'];
	$essay = $_POST['essay'];
	
	$query = "UPDATE species_list
		SET flickr_code = '$flickr_code', essay = '$essay',
		ebird_map = '$ebird_map', cornell_map = '$cornell_map'
		WHERE url_common_name = '$url_common_name'
	";

	$result = mysql_query($query) or die(mysql_error());
	
	// set the header to redirect to VIEW page
	wp_redirect("/species/?common_name=$url_common_name");
?>
