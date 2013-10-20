<?php
	if (!isset($_GET['clubid']) || !is_numeric($_GET['clubid']))
		header("Location: kalender.php");
	
	include_once 'include/menu_start_dev.php';
	$result = $db->Query("SELECT Naam, Douche, Website FROM club WHERE ID=".$_GET['clubid']);
	if ($record = mysql_fetch_array($result))
	{
		define("PAGE_TITLE", "Contact TTC ".$record['Naam']);
		define("PAGE_DESCRIPTION", "De locatie- en contactgegevens van Tafeltennis club TTC ".$record['Naam'].".");
		include_once 'include/menu_start_html.php';
		?>
		<h1>Clubinfo</h1>
		<table width='100%' class=maintable>
		<tr>
			<th colspan=2><?php echo $record['Naam']?></th>
		</tr>
		<?php if ($record['Website'] != "") { ?>
		<tr>
			<td class=subheader>Website:</td>
			<td><a target=_blank href="<?php echo $record['Website']?>"><?php echo $record['Website']?></a></td>
		</tr>
		<?php } ?>
		<?php
		//$first = true;
		$result = $db->Query("SELECT Lokaal, Adres, Gemeente, Postcode, Telefoon FROM clublokaal WHERE ClubID=".$_GET['clubid']." ORDER BY Hoofd DESC");
		while ($lokaalRecord = mysql_fetch_array($result))
		{
			echo "<tr><td class=subheader width='10%' valign=top>Lokaal:</td><td width='90%'>";
			//if (!$first) echo "<br><br>";
			echo $lokaalRecord['Lokaal']."<br>".$lokaalRecord['Adres']."<br>".$lokaalRecord['Postcode']." ".$lokaalRecord['Gemeente'];
			if ($lokaalRecord['Telefoon'] != '') echo "<br>".$lokaalRecord['Telefoon'];
			if ($record['Douche'] == "1") echo "<br><table class=emptytable><tr><td><img src=img/douche.gif></td><td valign=top>Douches&nbsp;aanwezig</td></tr></table>";
			echo "</td></tr>";
			//$first = false;
		}
		echo "</table>";
	}
	include_once 'include/menu_end.php';
?>