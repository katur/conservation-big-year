<?php
	include("katherine_connect.php");
	/*
	Template Name: Species
	Copyright (c) 2013 Katherine Erickson
	*/
	get_header(); 

	// get the common name from the url. 
	// (php-format url available via htaccess file)
	$url_common_name = mysql_real_escape_string($_GET['common_name']);

	// define MySQL query for info on current species
	$query = "SELECT common_name, scientific_name, 
		species_list.order, family, subfamily, 
		seen_this_year, is_lifer, is_probably_extinct, in_conservation_list, 
		population_estimate, flickr_code,
		abc_status, esa_status, cornell_map, ebird_map, 
		conservation_concerns, cool_information 
		FROM species_list 
		WHERE url_common_name = '$url_common_name'
	;";

	// run query
	$result = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($result) != 1) {
		echo "The bird in the URL is not on the list, 
			is spelled differently,
			or had more than one match.";

	} else {
		// define variables
		while ($row = mysql_fetch_assoc($result)) {
			$common_name = $row["common_name"];
			$scientific_name = $row["scientific_name"];
			$order = $row["order"];
			$family = $row["family"];
			$subfamily = $row["subfamily"];
			$seen_this_year = $row["seen_this_year"];
			$is_lifer = $row["is_lifer"];
			$is_probably_extinct = $row["is_probably_extinct"];
			$in_conservation_list = $row["in_conservation_list"];
			$population_estimate = $row["population_estimate"];
			$flickr_code = $row["flickr_code"];
			$abc_status = $row["abc_status"];
			$esa_status = $row["esa_status"];
			$cornell_map = $row["cornell_map"];
			$ebird_map = $row["ebird_map"];
			$conservation_concerns = $row["conservation_concerns"];
			$cool_information = $row["cool_information"];
		}
	}

	$abc_query = "SELECT status FROM abc_status WHERE id = $abc_status;";
	$abc_result = mysql_query($abc_query) or die(mysql_error());
	if (mysql_num_rows($abc_result) == 0) {
		echo "This bird's abc status not found in abc_status table.";
	} else {
		while ($abc_row = mysql_fetch_assoc($abc_result)) {
			$abc_long = $abc_row["status"];
		}
	}
	
	$esa_query = "SELECT status FROM esa_status WHERE id = $esa_status;";
	$esa_result = mysql_query($esa_query) or die(mysql_error());
	if (mysql_num_rows($esa_result) == 0) {
		echo "This bird's esa status not found in esa_status table.";
	} else {
		while ($esa_row = mysql_fetch_assoc($esa_result)) {
			$esa_long = $esa_row["status"];
		}
	}
?>
	
<h1 id="species-title"><?php echo $common_name; ?></h1>

<!-- general -->
<div class="species-section">
	<!-- image -->	
	<div id="primary-image">
		<?php echo $flickr_code ?>
	</div>
	
	<!-- scientific classification -->
	<div id="scientific-classification">
		<?php
			if ($probably_extinct) echo "<span>Probably Extinct</span>";
		?>
		<span>Order: <?php echo $order; ?></span>
		<span>Family: <?php echo $family; ?></span>
		<?php if ($subfamily) echo "<span>Subfamily: $subfamily</span>"; ?>
		<span>Scientific Name: <i><?php echo $scientific_name; ?></i></span>
		<?php
			if ($is_lifer) echo "
				<span>Lifer for Laura</span>
			";
			if ($seen_this_year) echo "
				<span>SEEN THIS YEAR!</span>
			";
		?>
	</div>
</div>
	
<!-- conservation information -->
<div id="conservation-classification" class="species-section">
	<h2 class="species-subtitle">Conservation Classification</h2>
	<span>
		<?php
			if ($in_conservation_list) 
				echo "In Laura's Conservation List";
			else
				echo "Not in Laura's Conservation List";
		?>
	</span>
	
	<span>
		<a href="http://www.abcbirds.org/abcprograms/science/watchlist/index.html" target="_blank">
			ABC WatchList
		</a>:
		<?php echo $abc_long; ?>
	</span>
	
	<span>
		<a href="http://www.fws.gov/endangered/species/index.html" target="_blank">
			ESA Status
		</a>:
		<?php echo $esa_long; ?>
	</span>
	<?php
		if ($population_estimate) echo "<span>Population Estimate: $population_estimate</span>";
	?>
</div>
	

<!-- links -->
<div id='species-links' class='species-section'>
	<h2 class='species-subtitle'>Links</h2>
	<?php
		if ($cornell_map)
			echo "<a href='$cornell_map' target='_blank'>Cornell's Range Map</a>";

		if ($ebird_map) 
			echo "<a href='$ebird_map' target='_blank'>eBird Dynamic Map</a>";
	?>
</div>

<!-- writing -->
<?php 
	if ($conservation_concerns)
	  echo "<div class='species-section'>
			<h2 class='species-subtitle'>Conservation Concerns</h2>
			$conservation_concerns
			</div>
		";
    		
  if ($cool_information)
		echo "<div class='species-section'>
			<h2 class='species-subtitle'>Cool Information</h2>
			$cool_information
			</div>
		";
?>
</div>
