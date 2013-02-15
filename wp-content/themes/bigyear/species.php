<?php
include("katherine_functions.php");
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
$query = "SELECT aou_list.common_name, aou_list.scientific_name, 
	aou_list.order, aou_list.family, aou_list.subfamily, 
	big_year_list.lifer, 
	abc_status.status as abc_status, esa_status.status as esa_status,
	big_year_list.probably_extinct, 
	big_year_list.cornell_map, big_year_list.ebird_map,
	big_year_list.conservation_concerns, big_year_list.cool_information		
	FROM aou_list 
	LEFT JOIN big_year_list 
		ON aou_list.id = big_year_list.species_id
	LEFT JOIN abc_status
		ON abc_status.id = big_year_list.abc_status
	LEFT JOIN esa_status
		ON esa_status.id = big_year_list.esa_status
	WHERE aou_list.url_common_name = '$url_common_name'
	LIMIT 1
";

// run query
$result = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($result) == 0) {
	echo "That is not a bird.";

} else {
	// define variables
	while ($row = mysql_fetch_assoc($result)) {
		$common_name = $row["common_name"];
		$scientific_name = $row["scientific_name"];
		$order = $row["order"];
		$family = $row["family"];
		$subfamily = $row["subfamily"];
		$lifer = $row["lifer"];
		$abc_status = $row["abc_status"];
		$esa_status = $row["esa_status"];
		$probably_extinct = $row["probably_extinct"];
		$cornell_map = $row["cornell_map"];
		$ebird_map = $row["ebird_map"];
		$conservation_concerns = $row["conservation_concerns"];
		$cool_information = $row["cool_information"];
	}
}
?>
	


<h2 id="bird"><?php echo $common_name; ?></h2>

<!-- gallery -->


<!-- general -->
<div class="subsection">
    
    <!-- scientific classification -->
	<div class="subsubsection">
		<?php
			if ($probably_extinct)
				echo "<b>Probably Extinct</b><br><br>";
		?>
		<h4>Order:</h4> <?php echo $order; ?><br>
		<h4>Family:</h4> <?php echo $family; ?><br>
		<?php if ($subfamily) echo "<h4>Subfamily:</h4> $subfamily<br>"; ?>
		<h4>Scientific Name:</h4> <i><?php echo $scientific_name; ?></i><br>
	</div>
	
    <!-- endangered statuses -->
	<div class="subsubsection">
			<h4><a href="http://www.abcbirds.org/abcprograms/science/watchlist/index.html" target="_blank">ABC WatchList</a>:</h4>
			<?php echo $abc_status; ?>
			<br>
			<h4><a href="http://www.fws.gov/endangered/species/index.html" target="_blank">ESA Status</a>:</h4>
			<?php echo $esa_status; ?>
	</div>
	
	<!-- lifer? -->
	<div class="subsubsection">
		<h4>Lifer for Laura?</h4>
		<?php echo ($lifer) ? "Yes" : "No"; ?>
	</div>
</div>


<!-- maps -->
<div class="subsection">		
	<?php
		if ($cornell_map || $ebird_map) {
			echo "<div class='subsubsection'>";
			if ($cornell_map)
				echo "<h4><a href='$cornell_map' target='_blank'>Cornell's Range Map</a></h4>";

			if ($ebird_map) 
				echo "<h4><a href='$ebird_map' target='_blank'>eBird Dynamic Map</a></h4>";

			echo "</div>";
		}
	?>
</div>


<!-- sightings -->


<!-- writing -->
<div class="subsection">
	<?php 
		if ($conservation_concerns)
		    echo "<div class='subsubsection'>
				<h4>Conservation Concerns</h4>
				$conservation_concerns
    		</div>";
    		
    	if ($cool_information)
    	    echo "<div class='subsubsection'>
				<h4>Cool Information</h4>
				$cool_information
    		</div>";
    ?>
</div>


<?php get_footer(); ?>