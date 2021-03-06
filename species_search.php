<?php get_header();
	include("katherine_connect.php");
	/*
	Template Name: Species Search
	Copyright (c) 2013 Katherine Erickson
	*/
?>

<div class="site-content full-width">
	<h1 id="species-title">Big Year List</h1>
	<div id="filters">
		<a href="./"
			<?php if (empty($_GET)) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			Laura has seen this year
		</div></a>

		<a href="./?is_lifer=1&seen=1"
			<?php if ($_GET['is_lifer']==1 && $_GET['seen']==1) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			Lifers this year
		</div></a>

		<a href="./?seen_in_refuge=1"
			<?php if ($_GET['seen_in_refuge']==1) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			Seen in National Wildlife Refuge System
		</div></a>

		<a href="./?seen_only_in_refuge=1"
			<?php if ($_GET['seen_only_in_refuge']==1) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			Seen only in National Wildlife Refuge System
		</div></a>

		<a href="./?all=1"
			<?php if ($_GET['all']==1) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			All "possible to see"
		</div></a>

		<a href="./?is_lifer=1"
			<?php if ($_GET['is_lifer']==1 && $_GET['seen']==0) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			All would-be lifers
		</div></a>

		<a href="./?in_conservation_list=1"
			<?php if ($_GET['in_conservation_list']==1) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			Laura's conservation list
		</div></a>

		<a href="./?esa_status_id=1"
			<?php if ($_GET['esa_status_id']==1) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			ESA status: Endangered
		</div></a>

		<a href="./?esa_status_id=2"
			<?php if ($_GET['esa_status_id']==2) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			ESA status: Threatened
		</div></a>

		<a href="./?abc_status_id=1"
			<?php if ($_GET['abc_status_id']==1) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			ABC status: Red (Highest Continental Concern)
		</div></a>

		<a href="./?abc_status_id=2"
			<?php if ($_GET['abc_status_id']==2) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			ABC status: Yellow (Declining or Rare Continental Species)
		</div></a>
	</div>

	<?php
		// see if there is a filter term in the url
		$all = mysql_real_escape_string($_GET["all"]);
		$seen = mysql_real_escape_string($_GET["seen"]);
		$seen_in_refuge = mysql_real_escape_string($_GET["seen_in_refuge"]);
		$seen_only_in_refuge = mysql_real_escape_string($_GET["seen_only_in_refuge"]);
		$in_conservation_list = mysql_real_escape_string($_GET["in_conservation_list"]);
		$esa_status_id = mysql_real_escape_string($_GET["esa_status_id"]);
		$abc_status_id = mysql_real_escape_string($_GET["abc_status_id"]);
		$is_lifer = mysql_real_escape_string($_GET["is_lifer"]);

		// start building query
		$query = "
			SELECT common_name, aba_countable, seen_this_year,
			seen_in_refuge, seen_only_in_refuge,
			is_lifer, url_common_name,
			is_probably_extinct,
			date, state,
			flickr_code
			FROM species_list
			LEFT JOIN sighting
			ON species_list.id = sighting.species_id
		";

		// if there is a filter term, add condition
		if ($is_lifer && $seen)
			$query = $query . " WHERE is_lifer = $is_lifer AND seen_this_year = 1";
		else if ($seen_in_refuge)
			$query = $query . " WHERE seen_in_refuge = 1";
		else if ($seen_only_in_refuge)
			$query = $query . " WHERE seen_only_in_refuge = 1";
		else if ($all)
			$query = $query . " WHERE species_list.id IS NOT NULL";
		else if ($is_lifer)
			$query = $query . " WHERE is_lifer = $is_lifer";
		else if ($in_conservation_list)
			$query = $query . " WHERE in_conservation_list = $in_conservation_list";
		else if ($esa_status_id)
			$query = $query . " WHERE esa_status_id = $esa_status_id";
		else if ($abc_status_id)
			$query = $query . " WHERE abc_status_id = $abc_status_id";
		else
			$query = $query . " WHERE seen_this_year = 1";
		
		// first count just those countable
		$aba_query = $query . " AND aba_countable = 1 GROUP BY common_name";	
		$aba_result = mysql_query($aba_query) or die(mysql_error());
		
		// now get full list, ordering by aou_list id
		$query = $query . " GROUP BY common_name ORDER BY species_list.id";
		$result = mysql_query($query) or die(mysql_error());	
	?>

	<div id="species-list">
		<h2 class='species-subtitle'>
			<?php 
				echo mysql_numrows($result) . " species ";
				echo "(" . mysql_numrows($aba_result) . " ABA countable)";
			?>
		</h2>

		<?php
			while ($row = mysql_fetch_assoc($result)) {
				$common_name = $row["common_name"];
				$aba_countable = $row["aba_countable"];
				$seen_this_year = $row["seen_this_year"];
				$seen_in_refuge = $row["seen_in_refuge"];
				$seen_only_in_refuge = $row["seen_only_in_refuge"];
				$is_lifer = $row["is_lifer"];
				$url_common_name = $row["url_common_name"];
				$date = $row["date"];
				$state = $row["state"];
				$flickr_code = $row["flickr_code"];
				$is_probably_extinct = $row["is_probably_extinct"];

				echo "<span>";

				if ($seen_only_in_refuge)
					echo "** ";
				else if ($seen_in_refuge)
					echo "* ";

				if (!$aba_countable)
					echo "&#8224; ";

				echo "<a href='/species/?common_name=$url_common_name'>$common_name";

				if ($is_probably_extinct)
					echo " (probably extinct)";

				echo "</a>";

				if ($seen_this_year)
					echo " &#x2713;";
				if ($date) {
					$date = date("M j", strtotime($row["date"]));
					echo " $date";
				}
				if ($state)
					echo " in $state";
				if ($is_lifer and $seen_this_year)
					echo " <b>LIFER!</b> ";
				echo "</span>";
			}
		?>
	</div>

	<div id="refuge-asterisks">
		<span>* Seen within the <a href="http://www.fws.gov/refuges/" target="_blank">National Wildlife Refuge System</a></span>
		<span>** Seen only within the <a href="http://www.fws.gov/refuges/" target="_blank">National Wildlife Refuge System</a></span>
		<span>&#8224; Not countable by ABA rules</span>
	</div>
</div>
<?php get_footer(); ?>
