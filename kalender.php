<?php
	define("PAGE_TITLE", "Kalender");
	define("PAGE_DESCRIPTION", "Kalender met de nog te spelen Sporta en VTTL competitie matchen van alle ploegen.");
	include_once 'include/menu_start.php';
?>
<h1>Kalender</h1>
<!--<table width="500" class="maintable">
	<tr>
		<th>Eetfestijn TTC Erembodegem</th>
	</tr>
	<tr>
		<td>
			Ons jaarlijks eefestijn gaat door op <b>zaterdag 24 september 2011</b> 
			<br>Van <b>18u tot 22u30</b>
			<br>In de parochiezaal, Termurenlaan 4, 9320 Erembodegem!<br>
			
			<br>
			
			<li>Tongrolletjes in mosterdsaus (€15)
			<li>Varkenshaasje met sla en tomaten met saus naar keuze (€15)
			<li>Kindermenu: Kip met appelmoes (€7.5)
			
			<br><br><div align=center><b>Kaarten verkrijgbaar bij de leden!</b></div>
		</td>
	</tr>
	<tr>
		<td align=center>
		Ter sponsering van onze gloednieuwe zaal (met peperdure vloer)
		zijn er eveneens steunkaarten van €3.
		</td>
	</tr>
</table>
-->
	<?php
	$params = $db->GetParams(array(PARAM_STANDAARDUUR, PARAM_KAL_WEEKS_OLD, PARAM_KAL_WEEKS_NEW));
	
	PrintKalender($db, "WHERE Datum BETWEEN DATE_SUB(NOW(), INTERVAL ".($params[PARAM_KAL_WEEKS_OLD]*7)." DAY) AND DATE_ADD(NOW(), INTERVAL ".($params[PARAM_KAL_WEEKS_NEW]*7)." DAY)", $params[PARAM_STANDAARDUUR], true);
	?>
<?php
	include_once "include/menu_end.php";
?>