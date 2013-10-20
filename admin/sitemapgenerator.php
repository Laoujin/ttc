<?php
	include_once 'include.php';
	include_once '../include/header.php';
	
	if (!$security->Kalender())
		header('Location: index.php');
	
	$params = $db->GetParams(array(PARAM_JAAR));	

	$host = "http://www.ttc-erembodegem.be/";

	header("Content-type: text/xml; charset=utf-8");
	echo '<'.'?xml version="1.0" encoding="UTF-8"?'.'>';
?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
			
	<url>
	  <loc><?php echo $host."clubinfo.php"; ?></loc>
	  <priority>1.00</priority>
	</url>
   <url> 
      <loc><?php echo $host."kalender.php"; ?></loc>
	  <changefreq>daily</changefreq> 
	  <priority>0.90</priority> 
   </url>
   <url> 
      <loc><?php echo $host."spelers.php"; ?></loc>
	  <priority>0.90</priority> 
   </url>
   <url> 
      <loc><?php echo $host."reeks.php?competitie=1"; ?></loc>
	  <priority>0.90</priority> 
   </url>
   <url> 
      <loc><?php echo $host."reeks.php?competitie=2"; ?></loc>
	  <priority>0.90</priority> 
   </url>
   <url> 
      <loc><?php echo $host."fotos.php"; ?></loc>
	  <priority>0.90</priority> 
   </url>
   <url> 
      <loc><?php echo $host."nieuwezaal/index.php"; ?></loc>
	  <priority>0.90</priority> 
   </url>
   <url> 
      <loc><?php echo $host."weetjes.php"; ?></loc>
	  <priority>0.90</priority> 
   </url>
   <url> 
      <loc><?php echo $host."links.php"; ?></loc>
	  <priority>0.90</priority> 
   </url>
<?php
// alle ploegen
$result = $db->Query("SELECT r.ID AS ReeksID
					  FROM reeks r JOIN clubploeg cp ON r.ID=cp.ReeksID AND cp.ClubID=".CLUB_ID."
					  WHERE Jaar=".$params[PARAM_JAAR]);
while ($record = mysql_fetch_array($result))
{
	echo "<url><loc>" . $host . "reeks.php?id=" . $record['ReeksID'] . "</loc><priority>0.80</priority></url>\n";
}

// alle spelers
$result = $db->Query("SELECT ID
						FROM speler WHERE (ClubIdVTTL=".CLUB_ID." OR ClubIdSporta=".CLUB_ID.") AND Gestopt IS NULL");
while ($record = mysql_fetch_array($result))
{
	echo "<url><loc>" . $host . "speler.php?id=" . $record['ID'] . "</loc><priority>0.70</priority></url>\n";
}

$result = $db->Query("SELECT r.ID AS ReeksID
					  FROM reeks r JOIN clubploeg cp ON r.ID=cp.ReeksID AND cp.ClubID=".CLUB_ID."
					  WHERE Jaar<>".$params[PARAM_JAAR]);
while ($record = mysql_fetch_array($result))
{
	echo "<url><loc>" . $host . "reeks.php?id=" . $record['ReeksID'] . "</loc><priority>0.30</priority></url>\n";
}
?>
</urlset>