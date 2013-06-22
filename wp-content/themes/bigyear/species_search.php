<?php get_header(); 
	include("katherine_connect.php");
	/*
	Template Name: Species Search
	Copyright (c) 2013 Katherine Erickson
	*/
?>

<h1 id="species-title">Species Search</h1>
<div class="species-section">
	Select filters below to narrow search.
	Click a species name to see its page.
</div>

<div class="species-section">
	<h2 class="species-subtitle">Filters</h2>
	<div class="filter-subsection">
		<a class="filter-name" href="./">All "possible to see" birds</a>
	</div>
		
	<div class="filter-subsection">
		<a href="./?in_conservation_list=1">In conservation list</a>
	</div>
	
	<div class="filter-subsection">
		<span class="filter-name">ESA status:</span>
		<a href="./?esa_status_id=1">Endangered</a>
		<a href="./?esa_status_id=2">Threatened</a>
		<a href="./?esa_status_id=3">Candidate</a>
	</div>

	<div class="filter-subsection">
		<span class="filter-name">ABC status:</span>
		<a href="./?abc_status_id=1">Red (Highest Continental Concern)</a>
		<a href="./?abc_status_id=2">Yellow (Declining or Rare Continental Species)</a>
	</div>
			
	<div class="filter-subsection">
		<a href="./?is_lifer=1">Lifer for Laura</a>
	</div>
</div>

<?php
	// see if there is a filter term in the url
	$in_conservation_list = mysql_real_escape_string($_GET["in_conservation_list"]);
	$esa_status_id = mysql_real_escape_string($_GET["esa_status_id"]);
	$abc_status_id = mysql_real_escape_string($_GET["abc_status_id"]);
	$is_lifer = mysql_real_escape_string($_GET["is_lifer"]);
	
	// start building query
	$query = "
		SELECT common_name, seen_this_year,
		is_lifer, url_common_name,
		is_probably_extinct,
		date, state,
		flickr_code	
		FROM species_list
		LEFT JOIN sightings
		ON species_list.id = sightings.species_id
	";

	// if there is a filter term, add condition
	if ($in_conservation_list)
		$query = $query . " WHERE in_conservation_list = $in_conservation_list";
	if ($esa_status_id)
		$query = $query . " WHERE esa_status_id = $esa_status_id";
	else if ($abc_status_id)				
		$query = $query . " WHERE abc_status_id = $abc_status_id";	
	else if ($is_lifer)
		$query = $query . " WHERE is_lifer = $is_lifer";

	// order by aou_list id
	$query = $query . " ORDER BY species_list.id";

	$result = mysql_query($query) or die(mysql_error());
?>

<div class="species-section">
	<h2 class='species-subtitle'>
		<?php echo mysql_numrows($result); ?>
		species
	</h2>
	
	<div class='species-list'>	
		<?php		
			while ($row = mysql_fetch_assoc($result)) {
				$common_name = $row["common_name"];
				$seen_this_year = $row["seen_this_year"];
				$is_lifer = $row["is_lifer"];
				$url_common_name = $row["url_common_name"];
				$date = $row["date"];
				$state = $row["state"];
				$flickr_code = $row["flickr_code"];
				$is_probably_extinct = $row["is_probably_extinct"];
				
				echo "<span><a href = '/species/?common_name=$url_common_name'>$common_name</a>";

				if ($seen_this_year)
					echo " &#x2713;";
				if ($date)
					echo " $date";
				if ($state)
					echo " in $state";
				if ($is_lifer)
					echo " <b>LIFER!</b> ";
				if ($is_probably_extinct)
					echo " (Note: is probably extinct)";
				echo "</span>";
				
				// if logged in, show links to edit
				if ($_SESSION["logged_in"]) {
					echo "<a href = '/edit/$url_common_name'>Edit</a>";
				}
			}
		?>
	</div>	
</div>

<?php get_footer(); ?>
