<?php get_header();
	include("katherine_connect.php");
	/*
	Template Name: Species Search
	Copyright (c) 2013 Katherine Erickson
	*/
?>

<div class="site-content full-width">
	<h1 id="species-title">Big Year List</h1>

	Select an option below to list species.
	Click a species name to see its page.

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

		<a href="./?all=1"
			<?php if ($_GET['all']==1) echo "class='active-filter'"; ?>
		><div class="filter-shaded-box">
			All "possible to see"
		</div></a>

		<a href="./?is_lifer=1"
			<?php if ($_GET['is_lifer']==1) echo "class='active-filter'"; ?>
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
			LEFT JOIN sighting
			ON species_list.id = sighting.species_id
		";

		// if there is a filter term, add condition
		if ($in_conservation_list)
			$query = $query . " WHERE in_conservation_list = $in_conservation_list";
		else if ($esa_status_id)
			$query = $query . " WHERE esa_status_id = $esa_status_id";
		else if ($abc_status_id)
			$query = $query . " WHERE abc_status_id = $abc_status_id";
		else if ($is_lifer && $seen)
			$query = $query . " WHERE is_lifer = $is_lifer AND seen_this_year = 1";
		else if ($is_lifer)
			$query = $query . " WHERE is_lifer = $is_lifer";
		else if ($all)
			$query = $query;
		else
			$query = $query . " WHERE seen_this_year = 1";


		// order by aou_list id
		$query = $query . " GROUP BY common_name ORDER BY species_list.id";

		$result = mysql_query($query) or die(mysql_error());
	?>

	<div id="species-list">
		<h2 class='species-subtitle'>
			<?php echo mysql_numrows($result); ?>
			species
		</h2>

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

				echo "<span><a href = '/species/?common_name=$url_common_name'>$common_name";

				if ($is_probably_extinct)
					echo " (probably extinct)";

				echo "</a>";

				if ($seen_this_year)
					echo " &#x2713;";
				if ($date)
					echo " $date";
				if ($state)
					echo " in $state";
				if ($is_lifer and $seen_this_year)
					echo " <b>LIFER!</b> ";
				echo "</span>";
			}
		?>
	</div>
</div>
<?php get_footer(); ?>
