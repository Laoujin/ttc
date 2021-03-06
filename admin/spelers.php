<?php
	include_once 'include.php';
	define("SPELERS", "spelers");
	include_once '../include/header.php';
	
	if (!$security->Spelers())
		header('Location: index.php');
		
	if (isset($_POST['overviewButton']))
	{
		for ($i = 1; $i <= $_POST['aantalSpelers']; $i++)
		{
			$values = "Naam='".$db->Escape($_POST['naam'.$i])."', NaamKort='".$db->Escape($_POST['naamKort'.$i])."'";
			if (is_numeric($_POST['gestopt'.$i])) $values .= ", Gestopt=".$_POST['gestopt'.$i];
			elseif (strlen($_POST['gestopt'.$i]) == 0) $values .= ", Gestopt=NULL";
			$values .= ", ClubIdVTTL=".($_POST['clubIdVTTL'.$i] == "0" ? "NULL" : CLUB_ID);
			$values .= ", ClubIdSporta=".($_POST['clubIdSporta'.$i] == "0" ? "NULL" : CLUB_ID);
			$values .= ", Toegang=".$_POST['toegang'.$i];
			$values .= ", VolgnummerVTTL=".(is_numeric($_POST['volgnummerVTTL'.$i]) ? $_POST['volgnummerVTTL'.$i] : "NULL").", IndexVTTL=".(is_numeric($_POST['indexVTTL'.$i]) ? $_POST['indexVTTL'.$i] : "NULL");
			$values .= ", VolgnummerSporta=".(is_numeric($_POST['volgnummerSporta'.$i]) ? $_POST['volgnummerSporta'.$i] : "NULL").", IndexSporta=".(is_numeric($_POST['indexSporta'.$i]) ? $_POST['indexSporta'.$i] : "NULL");
			$values .= ", KlassementVTTL='".$_POST['klassementVTTL'.$i]."', KlassementSporta='".$_POST['klassementSporta'.$i]."'";
			$db->Query("UPDATE speler SET $values WHERE ID=".$_POST['id'.$i]);
		}

		$db->SetLastUpdate(SPELERS);
		$msg = "Speler gegevens geupdate!";
	}
	
	include_once 'admin_start.php';
?>
<script type="text/javascript">
$(document).ready(function() {
	<?php if (isset($_POST['spelerLijst']) && ($_POST['spelerLijst'] == "VTTL" || $_POST['spelerLijst'] == "Sporta")) { ?>
	$("#guessIndexen").click(function() {
		var aantalSpelers = parseInt($("#aantalSpelers").val(), 10);
		var last = null, currentIndex = aantalSpelers;
		for (var i = aantalSpelers; i > 0; i--)
		{
			$("input[name='volgnummer<?php echo $_POST['spelerLijst']?>"+i+"']").val(i);
			var current = $("#klassement<?php echo $_POST['spelerLijst']?>"+i).val();
			if (last != current)
			{
				currentIndex = i;
				last = current;
			}
			$("input[name='index<?php echo $_POST['spelerLijst']?>"+i+"']").val(currentIndex);
		}
	});
	<?php } ?>

	// Remove club = remove klassement, index etc
	$("select[name*='clubId']").click(function() {
		var self = $(this);
		if (self.val() === "0") {
			self.parent().find("input,select:last").each(function() {
				$(this).val("");
			});
		}
	});
});
</script>

<h1>Admin - spelers</h1>
<a href="spelernew.php">Nieuwe speler toevoegen</a>
<form method=post>
<table width="100%" align=center class="maintable">
	<tr>
		<th colspan=8>Spelers beheren</th>
	</tr>
	<tr>
		<td colspan=3>
			<select name=spelerLijst>
				<option <?php echo (!isset($_POST['spelerLijst']) || $_POST['spelerLijst'] == "Actieve" ? "selected" : "")?>>Actieve</option>
				<option <?php echo (isset($_POST['spelerLijst']) && $_POST['spelerLijst'] == "Alle" ? "selected" : "")?>>Alle</option>
				<option <?php echo (isset($_POST['spelerLijst']) && $_POST['spelerLijst'] == "VTTL" ? "selected" : "")?>>VTTL</option>
				<option <?php echo (isset($_POST['spelerLijst']) && $_POST['spelerLijst'] == "Sporta" ? "selected" : "")?>>Sporta</option>
			</select>
			<input type=submit value="Lijst laden">
		</td>
		<td colspan=5>
			<?php if (isset($_POST['spelerLijst']) && ($_POST['spelerLijst'] == "VTTL" || $_POST['spelerLijst'] == "Sporta")) { echo'<input type=button id=guessIndexen value="Volgnummer en index overschrijven">'; } ?>
		</td>
	</tr>
	<tr>
		<th width="10%">Naam</th>
		<th width="10%">Naam kort</th>
		<th width="25%">VTTL<br><font size=-1>Club - Volgnummer - Index - Klassement</font></th>
		<th width="25%">Sporta<br><font size=-1>Club - Volgnummer - Index - Klassement</font></th>
		<th width="10%">Gestopt</th>
		<th width="10%">Toegang</th>
		<th width="10%">Details</th>
	</tr>
<?php
$i = 0;
$where = "";
$extraOrder = "";
if (!isset($_POST['spelerLijst'])) $_POST['spelerLijst'] = "Actieve";
switch ($_POST['spelerLijst'])
{
	case "Actieve":
		$where = "WHERE Gestopt IS NULL";
		break;
	case "VTTL":
		$where = "WHERE Gestopt IS NULL AND ClubIdVTTL IS NOT NULL";
		$extraOrder = ", kVTTL.WaardeVTTL DESC";
		break;
	case "Sporta":
		$where = "WHERE Gestopt IS NULL AND ClubIdSporta IS NOT NULL";
		$extraOrder = ", kSporta.WaardeSporta DESC";
		break;
	default:
}
$result = $db->Query("SELECT ID, Naam, NaamKort, ClubIdVTTL, ClubIdSporta, Gestopt, Toegang, KlassementVTTL, KlassementSporta, VolgnummerVTTL,
						     VolgnummerSporta, IndexVTTL, IndexSporta
					  FROM speler s
					  LEFT JOIN klassement kVTTL ON s.KlassementVTTL=kVTTL.Code
					  LEFT JOIN klassement kSporta ON s.KlassementSporta=kSporta.Code
					  $where
					  ORDER BY Gestopt$extraOrder, Naam");

while ($record = mysql_fetch_array($result))
{
	$i++;
	echo "<input type=hidden name=id$i value=".$record['ID'].">";
	echo "<tr>";
	echo "<td><input type=text name=naam$i value=\"".$db->Html($record['Naam'])."\"></td>";
	echo "<td><input type=text name=naamKort$i value=\"".$db->Html($record['NaamKort'])."\"></td>";
	echo "<td align=center>"
			.GetClub("clubIdVTTL$i", $record['ClubIdVTTL'])."&nbsp;"
			."<input type=text size=3 name=volgnummerVTTL$i value=".$record['VolgnummerVTTL'].">&nbsp;"
			."<input type=text size=3 name=indexVTTL".$i." value=".$record['IndexVTTL'].">&nbsp;"
			.$db->BuildKlassementCombo("klassementVTTL".$i, VTTL, $record['KlassementVTTL'])
			."</td>";
	echo "<td align=center>"
			.GetClub("clubIdSporta$i", $record['ClubIdSporta'])."&nbsp;"
			."<input type=text size=3 name=volgnummerSporta$i value=".$record['VolgnummerSporta'].">&nbsp;"
			."<input type=text size=3 name=indexSporta$i value=".$record['IndexSporta'].">&nbsp;"
			.$db->BuildKlassementCombo("klassementSporta".$i, SPORTA, $record['KlassementSporta'])
			."</td>";
	echo "<td align=center><input type=text name=gestopt$i value='".$record['Gestopt']."' size=5></td>";
	echo "<td align=center>".GetToegangCombo("toegang$i", $record['Toegang'])."</td>";
	echo "<td><a href='speleredit.php?id=".$record['ID']."'>Editeer</a></td>";
	echo "</tr>";
}
echo "<input type=hidden name=aantalSpelers id=aantalSpelers value=$i>";
?>
	<tr>
		<td colspan=7 align=center>
			<input type=submit name=overviewButton value='Opslaan'>
		</td>
	</tr>
</table>
</form>
<?php
	function GetToegangCombo($name, $selected = '')
	{
		$v = "<select name=$name>";
		$v .= "<option value=".TOEGANG_NONE."></option>";
		$v .= "<option value=".TOEGANG_SPELER.(($selected & TOEGANG_SPELER) != 0 ? " selected" : "").">Speler</option>";
		$v .= "<option value=".(TOEGANG_SPELER | TOEGANG_KAPITEIN).(($selected & TOEGANG_KAPITEIN) != 0 ? " selected" : "").">Kapitein</option>";
		$v .= "<option value=".(TOEGANG_SPELER | TOEGANG_KAPITEIN | TOEGANG_ADMIN).(($selected & TOEGANG_ADMIN) != 0 ? " selected" : "").">Admin</option>";
		$v .= "</select>";
		return $v;
	}
	
	function GetToegang($waarde)
	{
		if (($waarde & TOEGANG_ADMIN) != 0) return "Admin";
		if (($waarde & TOEGANG_KAPITEIN) != 0) return "Kapitein";
		if (($waarde & TOEGANG_SPELER) != 0) return "Speler";
		
		return "&nbsp;";
	}
	
	function GetClub($name, $current)
	{
		$v = "<select name=$name>";
		$v .= "<option value=0".($current != CLUB_ID ? " selected" : "")."></option>";
		$v .= "<option value=".CLUB_ID.($current == CLUB_ID ? " selected" : "").">Erembodegem</option>";
		$v .= "</select>";
		return $v;
	}

	include_once 'admin_end.php';
?>