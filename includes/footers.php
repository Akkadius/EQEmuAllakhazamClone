<?php
// Close the right-side (content) column

//print "<br/>\n";
print "</td>\n";
print "</tr>\n";

// Separation Line
print "      <tr class='myline' height='6'><td colspan='2'></td></tr>\n";


// End of Main Content Area
print "</table></center>\n";
print "</td>\n";
print "</tr>\n";

// Display site name, version and contact email
print "<tr>\n";
print "<td height='100%' align='center' valign='top' colspan='2'>AllaClone - Version: $cfgversion\n";
print "<a href='mailto:".$SiteEmail."'>$SiteEmail</a>\n";
print "</td>\n";
print "</tr>\n";

// Display Theme Selector
print "<tr>\n";
print "<td align='center'>\n";
print "<table border='0' width='0%' height='100%' cellpadding='0' cellspacing='7'>\n";
print "<tr align='center'>\n";
print "<td>\n";
print 'Select Theme:';
print "</td>\n";
print "</tr>\n";
print "<tr align='center'>\n";
print "<td>\n";
print '<form method=post name="SetTheme" >
		<select name="SetTheme" onChange="this.form.submit()">
		<option selected>Selected: '. $_COOKIE['Theme'] . '</option>
		<option value="Dark Blue">Dark Blue</option>
		<option value="Allakhazam">Allakhazam</option>
		</select>
		</form>';
print "<br><br>\n";
print "</td>\n";
print "</tr>\n";

print "</table>\n";
print "</td>\n";
print "</tr>\n";

print "</table>\n";
print "</body>\n";
print "</html>\n";

?>
