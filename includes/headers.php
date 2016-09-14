<?php 
	

	if(isset($_POST['SetTheme'])){
		setcookie("Theme", $_POST['SetTheme'], time()+31536000); ### Set Theme for the user and hold cookie for a year
		echo '<meta http-equiv="REFRESH" content="0;url='. $_SERVER['PHP_SELF'] . '">';
	}

	echo '<script type="text/javascript" src="jquery/easytooltip/js/jquery.js"></script>';
	echo '<script type="text/javascript" src="jquery/easytooltip/js/easyTooltip.js"></script>';
	
	
	$XhtmlCompliant = TRUE;
	if($XhtmlCompliant)
	{ 
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n"; 
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
	}
	else
	{
		echo "<html>\n";
	}

	echo "<head>\n";
	echo "<link rel=\"stylesheet\" href=\"". $includes_url . $CssStyle . ".css\" type=\"text/css\"/>\n";
	echo "<title>$SiteTitle ".( $Title != "" ? " :: $Title" : "")."</title>\n";
	
	echo "<script language='javascript'>
			function getItem(id)
			{
				var itm = false;
				if(document.getElementById)
					itm = document.getElementById(id);
				else if(document.all)
					itm = document.all[id];
				else if(document.layers)
					itm = document.layers[id];
	
				return itm;
			}
	
			function toggleItem(id)
			{
				itm = getItem(id);
	
				if(!itm)
					return false;
	
				if(itm.style.display == 'none')
					itm.style.display = '';
				else
					itm.style.display = 'none';
	
				return false;
			}
			</script>";
	echo "</head>\n";
	if(isset($QueryResult)) 
	{
		// Toggle/Hide search fields after results are shown (for items.php)
		echo "<body onload='toggleItem(\"myTbody\")'>\n";
	}
	else
	{
		echo "<body>\n";
	}

	// Main Table for the page that includes header and background
	echo "<table border='0' class='page_background' cellspacing='0' cellpadding='0' width='100%' height='100%'>\n";
	
	// Two-columns table : left side is the menu, right side is th main content
	echo "<tr><td valign='top'><center><table border='0' class='main_page' cellspacing='0' cellpadding='0' width='70%' height='100%' >\n";

	// Header at the top of the screen
	if($ShowHeader){
		echo "<tr class='myheader' height='100'>\n";
		echo "<td colspan='2'>\n";
		echo "<center>\n";
		echo "<table width='80%' border='0'>\n";
		echo "<tr valign='top' align='right'>\n";
		echo "<td>\n";
		echo '<center><font face="times new roman" size="12"><b>'.$SiteTitle.'</b></font></center>';
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</center>\n";
		echo "</td></tr>\n";
		
	}


	// Separation line
	echo "<tr class='myline' height='6'><td colspan='2'></td></tr>\n";
	
	// Left-side menu
	echo "<tr valign='top'>\n";
	echo "<td class='menu' width='0%'>\n";
	include("menu.php");
	echo "</td>\n";

	// Right side content
	//echo "<td class='page' width='90%'>\n";
	//echo "<table width='100%'><td class='menuh'><h1 class='page_small_title'><center>$Title</center></h1></td></table>\n";
	print "<td class='page' width='100%'>\n";
	print "<p class='page_title'>$Title</p>\n";	
?>
