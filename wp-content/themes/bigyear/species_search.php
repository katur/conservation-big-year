<?php
include("katherine_functions.php");
include("katherine_connect.php");
/*
Template Name: Species Search
Copyright (c) 2013 Katherine Erickson
*/

get_header(); ?>

    	<h2 id="bird">Filters</h2>
    	<div class="subsection">
    	    <div class="subsubsection">
    	        <h4><a href="./">Clear Filter</a></h4>
    	    </div>
    	    
    	    <div class="subsubsection">
    	        <h4>ESA status:</h4>
        		<a href="./?esa_status=1">Endangered</a>&nbsp;&nbsp;
        		<a href="./?esa_status=2">Threatened</a>&nbsp;&nbsp;
        		<a href="./?esa_status=3">Candidate</a>&nbsp;&nbsp;
        		<a href="./?esa_status=0">Not Endangered</a>&nbsp;&nbsp;
    	    </div>

    		<div class="subsubsection">
    		    <h4>ABC status:</h4>
        		<a href="./?abc_status=1">Red (Highest Continental Concern)</a>&nbsp;&nbsp;
        		<a href="./?abc_status=2">Yellow (Declining or Rare Continental Species)</a>&nbsp;&nbsp;
        		<a href="./?abc_status=0">No ABC status</a>&nbsp;&nbsp;
        	</div>
            
            <div class="subsubsection">
    		    <h4>Lifer for Laura:</h4>
    		    <a href="./?lifer=1">Lifer</a>&nbsp;&nbsp;
        		<a href="./?lifer=0">Not a lifer</a>&nbsp;&nbsp;
    	    </div>
        </div>

        <div class="subsection">
        <?php
        	// query for common names in big year list
        	$query = "SELECT aou_list.common_name, aou_list.url_common_name 
        		FROM aou_list 
        	";
	
        	$esa_status = mysql_real_escape_string($_GET["esa_status"]);
        	$abc_status = mysql_real_escape_string($_GET["abc_status"]);
        	$lifer = mysql_real_escape_string($_GET["lifer"]);
	
        	// if there is a filter term
        	if ($esa_status || $abc_status || $lifer ) {
        		$query = $query . "
        			LEFT JOIN big_year_list
        				ON aou_list.id = big_year_list.species_id
        		";
        		if ($esa_status)
        			$query = $query . "
        				WHERE big_year_list.esa_status = $esa_status
        			";
        		else if ($abc_status)				
        			$query = $query . "
        				WHERE big_year_list.abc_status = $abc_status
        			";	
        		else if ($lifer)
        			$query = $query . "
        				WHERE big_year_list.lifer = $lifer
        			";
		
        	// if there is no filter term
        	} else
        		$query = $query . "
        			WHERE aou_list.in_big_year_list = 1
        		";
	
        	// Regardless, order by aou_list id
        	$query = $query . "
        		ORDER BY aou_list.id
        	";

        	// run query
        	$result = mysql_query($query) or die(mysql_error());
	
        	echo "<h2 id='bird'>" . mysql_numrows($result) . " Results</h2>";
	
        	// fetch results
        	while ($row = mysql_fetch_assoc($result)) {
        		$common_name = $row["common_name"];
        		$url_common_name = $row["url_common_name"];
        		echo "<a href = '/species/?common_name=$url_common_name'>$common_name</a>";

        		// if logged in, show links to edit
        		if ($_SESSION["logged_in"]) {
        			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href = '/edit/$url_common_name'>Edit</a>";
        		}
        		echo "<br>";
        	}
        ?>
        </div>

<?php get_footer(); ?>