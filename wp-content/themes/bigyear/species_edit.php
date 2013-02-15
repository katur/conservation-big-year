<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<?php include("includes/head.php"); ?>
	
	<div id="content">
		<a href='/'>Home</a><br>
	
		<?php
			// get the common name from the GET variable (visible in the url). 
			// the php-format url is available via htaccess file.
			$url_common_name = mysql_real_escape_string($_GET['url_common_name']);
	
			// If there is data from a POST
			if ($_POST) {		
				// grab the variables from the POST variable
				$lifer = mysql_real_escape_string($_POST['lifer']);
				$abc_status = mysql_real_escape_string($_POST['abc_status']);
				$esa_status = mysql_real_escape_string($_POST['esa_status']);
				$probably_extinct = mysql_real_escape_string($_POST['probably_extinct']);
				$cornell_map = mysql_real_escape_string($_POST['cornell_map']);
				$ebird_map = mysql_real_escape_string($_POST['ebird_map']);
				$why_concern = mysql_real_escape_string($_POST['why_concern']);
				$what_issues = mysql_real_escape_string($_POST['what_issues']);
				$what_help = mysql_real_escape_string($_POST['what_help']);
				$laura_plans = mysql_real_escape_string($_POST['laura_plans']);
				$what_you = mysql_real_escape_string($_POST['what_you']);
				$flickr_main = mysql_real_escape_string($_POST['flickr_main']);
				$flickr_why_concern = mysql_real_escape_string($_POST['flickr_why_concern']);
				$flickr_what_issues = mysql_real_escape_string($_POST['flickr_what_issues']);
				$flickr_what_help = mysql_real_escape_string($_POST['flickr_what_help']);
				$flickr_laura_plans = mysql_real_escape_string($_POST['flickr_laura_plans']);
				$flickr_what_you = mysql_real_escape_string($_POST['flickr_what_you']);
				$flickr_small_main = mysql_real_escape_string($_POST['flickr_small_main']);
				$flickr_small_why_concern = mysql_real_escape_string($_POST['flickr_small_why_concern']);
				$flickr_small_what_issues = mysql_real_escape_string($_POST['flickr_small_what_issues']);
				$flickr_small_what_help = mysql_real_escape_string($_POST['flickr_small_what_help']);
				$flickr_small_laura_plans = mysql_real_escape_string($_POST['flickr_small_laura_plans']);
				$flickr_small_what_you = mysql_real_escape_string($_POST['flickr_small_what_you']);
		
				$query = "UPDATE big_year_list
					LEFT JOIN aou_list
						ON big_year_list.species_id = aou_list.id
					SET big_year_list.lifer = '$lifer',
						big_year_list.abc_status = '$abc_status',
						big_year_list.esa_status = '$esa_status',
						big_year_list.probably_extinct = '$probably_extinct',
						big_year_list.cornell_map = '$cornell_map',
						big_year_list.ebird_map = '$ebird_map',
						big_year_list.why_concern = '$why_concern',
						big_year_list.what_issues = '$what_issues',
						big_year_list.what_help = '$what_help',
						big_year_list.laura_plans = '$laura_plans',
						big_year_list.what_you = '$what_you',
						big_year_list.flickr_main = '$flickr_main', big_year_list.flickr_small_main = '$flickr_small_main',
						big_year_list.flickr_why_concern = '$flickr_why_concern', big_year_list.flickr_small_why_concern = '$flickr_small_why_concern',
						big_year_list.flickr_what_issues = '$flickr_what_issues', big_year_list.flickr_small_what_issues = '$flickr_small_what_issues',
						big_year_list.flickr_what_help = '$flickr_what_help', big_year_list.flickr_small_what_help = '$flickr_small_what_help',
						big_year_list.flickr_laura_plans = '$flickr_laura_plans', big_year_list.flickr_small_laura_plans = '$flickr_small_laura_plans',
						big_year_list.flickr_what_you = '$flickr_what_you', big_year_list.flickr_small_what_you = '$flickr_small_what_you'
					WHERE aou_list.url_common_name = '$url_common_name'
				";
		
				$result = mysql_query($query) or die(mysql_error());
		
				// php function to set the header to redirect to VIEW page
				header("Location: /species/$url_common_name");

			// if there is not data from a post	
			} else {
				// define MySQL query to access fields for current species
				$query = "SELECT aou_list.common_name, big_year_list.lifer, 
					big_year_list.abc_status, abc_status.status as abc_status_long, 
					big_year_list.esa_status, esa_status.status as esa_status_long,
					big_year_list.probably_extinct, big_year_list.cornell_map, big_year_list.ebird_map,
					big_year_list.why_concern, big_year_list.what_issues, 
					big_year_list.what_help, big_year_list.laura_plans, big_year_list.what_you,
					big_year_list.flickr_main, big_year_list.flickr_small_main, 
					big_year_list.flickr_why_concern, big_year_list.flickr_small_why_concern, 
					big_year_list.flickr_what_issues, big_year_list.flickr_small_what_issues, 
					big_year_list.flickr_what_help, big_year_list.flickr_small_what_help, 
					big_year_list.flickr_laura_plans, big_year_list.flickr_small_laura_plans, 
					big_year_list.flickr_what_you, big_year_list.flickr_small_what_you
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
	
				// if no result
				if (mysql_num_rows($result) == 0) {
					echo "That is not a bird.";
	
				// If result
				} else {
					// Define variables
					while ($row = mysql_fetch_assoc($result)) {
						$common_name = $row["common_name"];
						$lifer = $row["lifer"];
						$abc_status = $row["abc_status"];
						$esa_status = $row["esa_status"];
						$abc_status_long = $row["abc_status_long"];
						$esa_status_long = $row["esa_status_long"];
						$probably_extinct = $row["probably_extinct"];
						$cornell_map = $row["cornell_map"];
						$ebird_map = $row["ebird_map"];
						$why_concern = $row["why_concern"];
						$what_issues = $row["what_issues"];
						$what_help = $row["what_help"];
						$laura_plans = $row["laura_plans"];
						$what_you = $row["what_you"];
						$flickr_main = $row['flickr_main'];
						$flickr_why_concern = $row['flickr_why_concern'];
						$flickr_what_issues = $row['flickr_what_issues'];
						$flickr_what_help = $row['flickr_what_help'];
						$flickr_laura_plans = $row['flickr_laura_plans'];
						$flickr_what_you = $row['flickr_what_you'];
						$flickr_small_main = $row['flickr_small_main'];
						$flickr_small_why_concern = $row['flickr_small_why_concern'];
						$flickr_small_what_issues = $row['flickr_small_what_issues'];
						$flickr_small_what_help = $row['flickr_small_what_help'];
						$flickr_small_laura_plans = $row['flickr_small_laura_plans'];
						$flickr_small_what_you = $row['flickr_small_what_you'];
					}
			
					?>
			
					<h1><?php echo $common_name; ?></h1>
					<form method='post' action='./<?php echo $url_common_name; ?>'>
						<h3>Main Photo</h3>
						<?php flickr_edit("flickr_main", $flickr_main, "flickr_small_main", $flickr_small_main); ?><br>
								
						<h3>Is a lifer?</h3>
						<select name='lifer'>
							<option value = '1'
								<?php if ($lifer == 1) {echo "selected='selected'";} ?>
							>Yes</option>
					
							<option value = '0' 
								<?php if ($lifer == 0) {echo "selected='selected'";} ?>
							>No</option>
						</select>
					
						<h3>Probably Extinct?</h3>
						<select name='probably_extinct'>
							<option value = '1'
								<?php if ($probably_extinct == 1) {echo "selected='selected'";} ?>
							>Yes</option>
					
							<option value = '0'
								<?php if ($probably_extinct == 0) {echo "selected='selected'";} ?>
							>No</option>
						</select>
					
						<h3>American Bird Conservancy status?</h3>
						<select name='abc_status'>
							<option value = '1'
								<?php if ($abc_status == 1) {echo "selected='selected'";} ?>
							>Highest Continental Concern</option>
					
							<option value = '2'
								<?php if ($abc_status == 2) {echo "selected='selected'";} ?>
							>Rare or Declining</option>
					
							<option value = '0'
								<?php if ($abc_status == 0) {echo "selected='selected'";} ?>
							>None</option>
						</select>
				
						<h3>Endangered Species Act status?</h3>
						<select name='esa_status'>
							<option value='1' 
								<?php if ($esa_status == 1) {echo "selected='selected'";} ?>
							>Endangered</option>
					
							<option value='2'
								<?php if ($esa_status == 2) {echo "selected='selected'";} ?>
							>Threatened</option>
					
							<option value='3'
								<?php if ($esa_status == 3) {echo "selected='selected'";} ?>
							>Candidate</option>
					
							<option value='0'
								<?php if ($esa_status == 0) {echo "selected='selected'";} ?> 
							>Not Endangered</option>
					
							<?php if ($esa_status > 3) { ?>
								<option value='<?php echo $esa_status; ?>' selected='selected'>
									<?php echo $esa_status_long; ?>
								</option>
							<?php } ?>
						</select>
				
						<h3>Cornell Range Map</h3>
						<input name='cornell_map' type='text' size='150' value='<?php echo $cornell_map; ?>'>
				
						<h3>Ebird Dynamic Map</h3>
						<input name='ebird_map' type='text' size='150' value='<?php echo $ebird_map; ?>'>
			
						<h3>Why are we concerned about the <?php echo $common_name; ?>?</h3>
						<textarea name='why_concern' rows='10' cols='100'><?php echo $why_concern; ?></textarea><br>
						<?php flickr_edit("flickr_why_concern", $flickr_why_concern, "flickr_small_why_concern", $flickr_small_why_concern); ?><br>
				
						<h3>What specific issues are facing the <?php echo $common_name; ?>?</h3>
						<textarea name='what_issues' rows='10' cols='100'><?php echo $what_issues; ?></textarea><br>
						<?php flickr_edit("flickr_what_issues", $flickr_what_issues, "flickr_small_what_issues", $flickr_small_what_issues); ?><br>
				
						<h3>What is being done to help the <?php echo $common_name; ?>?</h3>
						<textarea name='what_help' rows='10' cols='100'><?php echo $what_help; ?></textarea><br>
						<?php flickr_edit("flickr_what_help", $flickr_what_help, "flickr_small_what_help", $flickr_small_what_help); ?><br>
					
						<h3>What are Laura's plans to help the <?php echo $common_name; ?>?</h3>
						<textarea name='laura_plans' rows='10' cols='100'><?php echo $laura_plans; ?></textarea><br>
						<?php flickr_edit("flickr_laura_plans", $flickr_laura_plans, "flickr_small_laura_plans", $flickr_small_laura_plans); ?><br>
				
						<h3>What can you do to help the <?php echo $common_name; ?>?</h3>
						<textarea name='what_you' rows='10' cols='100'><?php echo $what_you; ?></textarea><br>
						<?php flickr_edit("flickr_what_you", $flickr_what_you, "flickr_small_what_you", $flickr_small_what_you); ?><br>
				
						<br><br><input type='submit' value='Submit to Database'></input>
					</form>
				<?php }
			}
		?>
	</div>
</html>