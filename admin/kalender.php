<?php
	include_once 'include.php';
	define("KALENDER", "kalender");
	include_once '../include/header.php';

	if (!$security->Kalender())
		header('Location: index.php');

	$params = $db->GetParams(array(PARAM_STANDAARDUUR, PARAM_KAL_WEEKS_OLD, PARAM_KAL_WEEKS_NEW, PARAM_JAAR, PARAM_TRAINING_KALENDER_DESC));

	$matchJaar = date("Y");
	$matchMaand = date("m");
	$reeksWizard = 0;
	$reeksId = 0;
	$reeksPloegId = 0;
	$reeksPloegCode = "";

	if (isset($_POST['Gebeurtenis']))
	{
		if (!strlen($_POST['Beschrijving'])) $msg = "Geef een beschrijving!";
		else
		{
			$fields = "datum, uur, beschrijving, geleidetraining";
			$values = "'".$db->ParseDate($_POST['Datum'])."', '".$_POST['Uur']."', '".$db->Escape($_POST['Beschrijving'])."', '".$db->Escape($_POST['GeleideTraining'])."'";
			if (strlen($_POST['Week']) > 0)
			{
				$keys .= ", Week";
				$fields .= ", ".$_POST['Week'];
			}
			$db->Query("INSERT INTO kalender ($fields) VALUES ($values)", KALENDER);

			$msg = "Geslaagd!";
		}
		$msg = "Toevoegen gebeurtenis: <br>" . $msg;
	}
	else if (isset($_POST['reeksButton']))
	{
		if (!is_numeric($_POST['reeksJaar'])) $msg = "Geef het jaar!";
		else if (strlen($_POST['reeks']) == 0) $msg = "Geef de reeks op";
		else
		{
			// reeks toevoegen
			$values = "'".$_POST['reeksCompetitie']."', '".$_POST['reeks']."', '".$_POST['reeksType']."'";
			$values .= ", '".strtoupper($_POST['reeksCode'])."', ".$_POST['reeksJaar'].", '".$_POST['reeksLink']."'";
			$db->Query("INSERT INTO reeks (Competitie, Reeks, ReeksType, ReeksCode, Jaar, LinkID) VALUES ($values)", KALENDER);
			$reeksId = mysql_insert_id();

			// ploegen in de reeks toevoegen
			//$ploegen = array();
			$aantalPloegen = $_POST['reeksAantalPloegen'] * 1;
			for ($i = 1; $i <= $aantalPloegen; $i++)
			{
				if ($_POST['reeksPloeg'.$i] != '' || $_POST['reeksPloegCode'.$i.'_0'] != '')
				{
					$clubId = $_POST['reeksPloegID'.$i];
					$fields = "ReeksID, ClubID";
					$values = "$reeksId, $clubId";
					if ($_POST['reeksPloegCode'.$i.'_0'] == '')
					{
						$db->Query("INSERT INTO clubploeg ($fields) VALUES ($values)");
						$insertId =  mysql_insert_id();
						//$ploegen[] = array("clubId" => $clubId, "id" => $insertId, "code" => "");
						if ($clubId == CLUB_ID)
						{
							$reeksPloegId = $insertId;
							$reeksPloegCode = $code;
						}
					}
					else
					{
						$fields .= ", Code";
						for ($ploegCode = 0; $ploegCode < 3; $ploegCode++)
						{
							$code = strtoupper($_POST['reeksPloegCode'.$i.'_'.$ploegCode]);
							if ($code != '')
							{
								$db->Query("INSERT INTO clubploeg ($fields) VALUES ($values, '$code')");
								$insertId =  mysql_insert_id();
								//$ploegen[] = array("clubId" => $clubId, "id" => $insertId, "code" => $code);
								if ($clubId == CLUB_ID)
								{
									$reeksPloegId = $insertId;
									$reeksPloegCode = $code;
								}
							}
						}
					}
				}
			}

			// spelers toevoegen aan eigen ploeg
			if ($reeksPloegId != 0)
			{
				$kapitein = 0;
				$keys = "ClubPloegID, SpelerID, Kapitein";
				if (isset($_POST['reeksKapitein']))
				{
					$kapitein = $_POST['reeksKapitein'];
					$db->Query("INSERT INTO clubploegspeler ($keys) VALUES ($reeksPloegId, ".$_POST['reeksKapitein'].", 1)");
				}

				$aantalSpelers = $_POST['reeksAantalSpelers'] * 1;
				for ($i = 1; $i <= $aantalSpelers; $i++)
				{
					if ($_POST['reeksSpeler'.$i] != '' && $kapitein != $_POST['reeksSpelerId'.$i])
					{
						$db->Query("INSERT INTO clubploegspeler ($keys) VALUES ($reeksPloegId, ".$_POST['reeksSpelerId'.$i].", 0)");
					}
				}
			}

			$reeksWizard = 1;
			$msg = "Reeks met ploegen toegevoegd. Ga nu verder met het ingeven van de matchen.";
		}

		$msg = "Reeks toevoegen: <br>" . $msg;
	}
	else if (isset($_POST['reeksMatchButton']))
	{
		$keys = "Datum, Uur, Week, Thuis, ThuisClubID, ThuisPloeg, ThuisClubPloegID, UitClubID, UitPloeg, UitClubPloegID";
		$aantalMatchen = $_POST['reeksAantalMatchen'] * 1;
		for ($i = 1; $i <= $aantalMatchen; $i++)
		{
			if (strlen($_POST['reeksJaar'.$i]) == 4 && is_numeric($_POST['reeksJaar'.$i]) && is_numeric($_POST['reeksMaand'.$i]) && is_numeric($_POST['reeksDag'.$i])
					&& is_numeric($_POST['reeksWeek'.$i]))
			{
				$values = "'".$db->ParseDateCombine($_POST['reeksJaar'.$i], $_POST['reeksMaand'.$i], $_POST['reeksDag'.$i])."', '".$_POST['reeksUur'.$i]."'";
				$values .= ", ".$_POST['reeksWeek'.$i];
				if ($_POST['reeksThuis'.$i] != '')
				{
					$values .= ", 1";
				}
				else
				{
					$values .= ", 0";
				}
				$values .= ", ".CLUB_ID.", '".$_POST['reeksPloegCode']."', ".$_POST['reeksPloegId'];
				$values .= ", ".$_POST['reeksTegenstanderClub'.$i].", '".$_POST['reeksTegenstanderCode'.$i];
				$values .= "', ".$_POST['reeksTegenstander'.$i];

				$db->Query("INSERT INTO kalender ($keys) VALUES ($values)");
			}
		}

		$msg = "Matchen toegevoegd";
	}
	else if (isset($_POST['matchButton']))
	{
		$fields = "Datum, Uur, Thuis";
		$values = "'".$db->ParseDateCombine($_POST['matchJaar'], $_POST['matchMaand'], $_POST['matchDag'])."', '".$_POST['matchUur']."', ".(isset($_POST['matchThuis']) && $_POST['matchThuis'] == 'on' ? '1' : '0');
		if (strlen($_POST['matchWeek']))
		{
			$fields .= ", Week";
			$values .= ", ".$_POST['matchWeek'];
		}

		$fields .= ", ThuisClubID, ThuisPloeg, ThuisClubPloegID";
		$result = $db->Query("SELECT ClubID, Code FROM clubploeg WHERE ID=".$_POST['matchPloegThuis']);
		$record = mysql_fetch_array($result);
		$values .= ", ".$record['ClubID'].", '".ucfirst($record['Code'])."', ".$_POST['matchPloegThuis'];

		$fields .= ", UitClubID";
		$values .= ", ".$_POST['matchPloegTegen'];
		if (strlen($_POST['matchPloegTegenCode']) > 0)
		{
			$fields .= ", UitPloeg";
			$values .= ", '".$_POST['matchPloegTegenCode']."'";
		}

		$db->Query("INSERT INTO kalender ($fields) VALUES ($values)", KALENDER);
		$msg = "Match toegevoegd!";
	}

	include_once 'admin_start.php';
?>
<script type="text/javascript">

$(document).ready(function() {
	$("#nieuwereeksbutton").click(function() {
		$("#nieuwereekstoevoegen").hide();
		$("#manueelToevoegen").hide();
		$(".nieuwereeks").show();
	});

	$("#reeksCompetitie").change(function() {
		$("#reeksType").val($(this).val() == "VTTL" ? "Prov" : "Afd");
	}).change();

	<?php
	if (isset($_POST['reeksButton']) && !$reeksWizard)
	{
		echo '$("#nieuwereeksbutton").click();';
	}
	?>
});

function SwitchCheckbox(id, thisValue)
{
	$("#reeksThuis" + id).attr('checked', thisValue == 1 ? false : true);
}

function ShiftFocus(moveTo, maxLength, maxValue, curValue)
{
	if (curValue.length >= maxLength || curValue * 1 > maxValue)
		$("#" + moveTo).focus();
}
</script>

<h1>Admin - kalender</h1>
<form method=post>
<table width="100%" align=center class="maintable" id="manueelToevoegen">
	<tr>
		<th colspan=2>Match toevoegen</th>
	</tr>
	<tr>
		<td colspan=2>
			<span class=help>
			Tip: Gebruik dit enkel om losse matchen in te geven! Om een ploeg in te geven
			in VTTL of Sporta gebruik 'Reeks ingeven' hieronder.
			</span>
		</td>
	</tr>
	<tr>
		<td>Datum:</td>
		<td>
			<input type=text name=matchDag size=3>
			<input type=text name=matchMaand size=3 value="<?php echo $matchMaand?>">
			<input type=text name=matchJaar size=5 value="<?php echo $matchJaar?>">
			&nbsp;
			<input type=text name=matchUur value='<?php echo $params[PARAM_STANDAARDUUR]?>' size="5">
			&nbsp;
			Thuis? <input type=checkbox name=matchThuis value=on>
		</td>
	</tr>
	<tr>
		<td>Week:</td>
		<td><input type=text name=matchWeek size="3"></td>
	</tr>
	<tr>
		<td>Ploeg Erembodegem:</td>
		<td>
			<select name=matchPloegThuis>
				<?php
				$result = $db->Query("SELECT clubploeg.ID, Competitie, Reeks, ReeksType, ReeksCode, Code
															FROM clubploeg INNER JOIN reeks ON ReeksID=reeks.ID
															WHERE ClubID=".CLUB_ID." AND Jaar=".$params[PARAM_JAAR]." ORDER BY Competitie DESC, Code");
				while ($record = mysql_fetch_array($result))
				{
					echo '<option value='.$record['ID'].'>'.$record['Competitie'].' '.$record['Code'].'</option>';
				}
				?>
			</select>
		</td>
		<tr>
			<td>Tegenstander:</td>
			<td>
				<select name=matchPloegTegen>
				<?php
				$result = $db->Query("SELECT ID, Naam FROM club WHERE Actief=1 AND ID<>".CLUB_ID." ORDER BY Naam");
				while ($record = mysql_fetch_array($result))
				{
					echo "<option value=".$record['ID'].">".$record['Naam']."</option>";
				}
				?>
				</select>
				<input type=textbox name=matchPloegTegenCode size=3>
			</td>
		</tr>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit name=matchButton value='Toevoegen'></td>
	</tr>

<tr>
		<th colspan=2>Gebeurtenis toevoegen</th>
	</tr>
	<tr>
		<td>Datum:</td>
		<td>
			<input type=text name=Datum id="Datum" size="15">
			<a href="javascript:NewCal('Datum','ddmmyyyy',false,24)">
			<img src="../img/cal.gif" width="16" height="16" border="0" alt="Kies een datum"></a> (DD/MM/JJJJ)
			<input type=text name=Uur value='<?php echo $params[PARAM_STANDAARDUUR]?>' size="5">
		</td>
	</tr>
	<tr>
		<td>Week:</td>
		<td><input type=text name=Week size="3"></td>
	</tr>
	<tr>
		<td>Beschrijving:</td>
		<td><input type=text name=Beschrijving value="Training" size=75></td>
	</tr>
	<tr>
		<td>Geleide training?</td>
		<td>
			<?php

			?>


			<input type="button" value="Dit is een geleide training." id="gtStart" />
			<div id="gtHidden" style="display: none">
				<input type=text name=GeleideTraining value="" size="75">
				<br><font size="-1">Formaat: Uur1,Uur2,Omschrijving in kalender
				<br>Binnen de omschrijving wordt {vrij1} en {vrij2} vervangen door het aantal vrije sloten voor de training.</font>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit name=Gebeurtenis value="Toevoegen"></td>
	</tr>
</table>

<script>
$(function() {
	$("#gtStart").click(function() {
		$(this).hide();
		$("#gtHidden input:first").val("<?=$params[PARAM_TRAINING_KALENDER_DESC]?>");
		$("#gtHidden").show();
	});
});
</script>




<br>





<table width="100%" align=center class="maintable">
	<tr>
		<th colspan="2">Reeks toevoegen</th>
	</tr>
	<?php if (!$reeksWizard) { ?>
	<tr id='nieuwereekstoevoegen'>
		<td colspan="2"><input type="button" value="Toevoegen" id="nieuwereeksbutton"></td>
	</tr>
	<tr style="display: none;" class='nieuwereeks'>
		<td>Jaar:</td>
		<td><input type=text size=5 name="reeksJaar" value="<?php echo (isset($_POST['reeksJaar']) ? $_POST['reeksJaar'] : date("Y"))?>"></td>
	</tr>
	<tr style="display: none;" class='nieuwereeks'>
		<td>Competitie:</td>
		<td>
			<select name="reeksCompetitie" id="reeksCompetitie">
				<option>VTTL</option>
				<option<?php echo (isset($_POST['reeksCompetitie']) && $_POST['reeksCompetitie'] == "Sporta" ? " selected" : "")?>>Sporta</option>
			</select>
			&nbsp;
			<input type=text name="reeks" size=2 value='<?php echo (isset($_POST['reeks']) && $_POST['reeks'] ? $_POST['reeks'] : "")?>'>e&nbsp;
			<input type=text name="reeksType" id="reeksType" size=5 value='<?php echo (isset($_POST['reeksType']) && $_POST['reeksType'] ? $_POST['reeksType'] : "")?>'>
			<input type=text name="reeksCode" size=2 value='<?php echo (isset($_POST['reeksCode']) && $_POST['reeksCode'] ? $_POST['reeksCode'] : "")?>'>
			<span class="help">&nbsp; (vb: VTTL 4e Prov C)</span>
		</td>
	</tr>
	<tr style="display: none;" class='nieuwereeks'>
		<td>Link ID:</td>
		<td>
			<input type=text size=6 name="reeksLink"><br>
			<span class='help'>
				Dit is de link naar de officiële pagina's met de resultaten en rangschikking van onze ploegen.
				In de URL is dit de code na "div_id=".<br>
				vb Sporta: http://tafeltennis.sporcrea.be/competitie/index.php?menu=4&perteam=1&div_id=<b>333_A</b>
			</span>
		</td>
	</tr>
	<tr style="display: none;" class='nieuwereeks'>
		<td valign=top>Ploegen:</td>
		<td>
			<span class=help>
			Help: Vink de clubs aan die een ploeg hebben in de nieuwe reeks.
			Gebruik de textbox om aan te geven welke ploeg het precies is (A, B, ...).
			Gebruik de tweede (en derde) textbox indien er meerdere ploegen van dezelfde
			club in de reeks spelen.
			</span>
			<table width=100% border=0 class=maintable>
				<tr>
					<th width='50%'>Club</th>
					<th width='50%'>Ploeg</th>
				</tr>
				<?php
				$ploegIndex = 0;
				$result = $db->Query("SELECT ID, Naam FROM club WHERE Actief=1 ORDER BY Naam");
				while ($record = mysql_fetch_array($result))
				{
					$ploegIndex++;
					if (isset($_POST['reeksPloeg'.$ploegIndex]))
					{
						$reeksPloeg = $_POST['reeksPloeg'.$ploegIndex];
						$reeksPloeg0 = $_POST['reeksPloegCode'.$ploegIndex.'_0'];
						$reeksPloeg1 = $_POST['reeksPloegCode'.$ploegIndex.'_1'];
						$reeksPloeg2 = $_POST['reeksPloegCode'.$ploegIndex.'_2'];
					}
					else
					{
						$reeksPloeg = "";
						$reeksPloeg0 = "";
						$reeksPloeg1 = "";
						$reeksPloeg2 = "";
					}
					echo "<tr><td".($record['ID'] == CLUB_ID ? " class='rowselected'" : "").">";
					echo "<input type=hidden name=reeksPloegID".$ploegIndex." value=".$record['ID'].">";
					echo "<input type=checkbox name=reeksPloeg".$ploegIndex.(($record['ID'] == CLUB_ID || $reeksPloeg) ? " checked" : "").">";
					echo $record['Naam'];
					echo "</td><td>";
					echo "<input type=text size=2 name='reeksPloegCode".$ploegIndex."_0' value='".$reeksPloeg0."'>";
					echo "&nbsp;<input type=text size=2 name='reeksPloegCode".$ploegIndex."_1' value='".$reeksPloeg1."'>";
					echo "&nbsp;<input type=text size=2 name='reeksPloegCode".$ploegIndex."_2' value='".$reeksPloeg2."'>";
					echo "</tr>";
				}
				echo "<input type=hidden name=reeksAantalPloegen value=$ploegIndex>";
				?>
			</table>
		</td>
	</tr>
	<tr style="display: none;" class='nieuwereeks'>
		<td>Kapitein:</td>
		<td>
			<?php echo $db->BuildSpelerCombo("reeksKapitein", CLUB_ID, (isset($_POST['reeksKapitein']) ? $_POST['reeksKapitein'] : -1), true)?>
		</td>
	</tr>
	<tr style="display: none;" class='nieuwereeks'>
		<td valign="top">Spelers:</td>
		<td>
			<?php
			$spelerIndex = 0;
			$result = $db->Query("SELECT ID, NaamKort AS Naam FROM speler WHERE Gestopt IS NULL AND (ClubIdVTTL=".CLUB_ID." OR ClubIdSporta=".CLUB_ID.") ORDER BY Naam");
			while ($record = mysql_fetch_array($result))
			{
				$spelerIndex++;
				echo "<input type=checkbox name=reeksSpeler".$spelerIndex.(isset($_POST['reeksSpeler'.$spelerIndex]) && $_POST['reeksSpeler'.$spelerIndex] ? " checked" : "").">".$record['Naam']." &nbsp; ";
				echo "<input type=hidden name=reeksSpelerId$spelerIndex value='".$record['ID']."'>";
			}
			?>
			<input type=hidden name=reeksAantalSpelers value="<?php echo $spelerIndex?>">
		</td>
	</tr>
	<tr style="display: none;" class='nieuwereeks'>
		<td colspan=2 align=center><input type=submit name="reeksButton" value="Toevoegen"></td>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan=2><b>Ingave <?php echo $reeksPloegCode?> ploeg <?php echo $_POST['reeksCompetitie']?></b></td>
	</tr>
	<tr>
		<td>
			<table width='100%' class="maintable">
				<input type=hidden name="reeksPloegId" value="<?php echo $reeksPloegId?>">
				<input type=hidden name="reeksPloegCode" value="<?php echo $reeksPloegCode?>">
				<tr>
					<th>Week</th>
					<th>Datum</th>
					<th>Uur</th>
					<th>Thuis</th>
					<th>Tegenstander</th>
				</tr>
				<?php
				function BuildReeksRow(& $record, $index, $defaultUur, $jaar, $returnIndex)
				{
					$row = "<tr>";
					$row .= "<td><input type=text size=3 name=reeksWeek$index onkeyup=\"ShiftFocus('reeksDag$index', 2, 2, this.value);\"></td>";
					$row .= "<td><input type=text size=2 id=reeksDag$index name=reeksDag$index onkeyup=\"ShiftFocus('reeksMaand$index', 2, 3, this.value);\">";
					$row .= " <input type=text size=2 id=reeksMaand$index name=reeksMaand$index onkeyup=\"ShiftFocus('reeksUur$index', 2, 1, this.value);\">";
					$row .= " <input type=text size=4 name=reeksJaar$index value='$jaar'></td>";
					$row .= "<td><input type=text size=6 id=reeksUur$index name=reeksUur$index value=".$defaultUur."></td>";
					$row .= "<td><input type=checkbox id=reeksThuis$index name=reeksThuis$index".($returnIndex > 0 ? " onchange='SwitchCheckbox($returnIndex, this.checked);' checked" : "")."></td>";
					$row .= "<input type=hidden name='reeksTegenstander$index' value='".$record['ID']."'>";
					$row .= "<input type=hidden name='reeksTegenstanderCode$index' value='".$record['Code']."'>";
					$row .= "<input type=hidden name='reeksTegenstanderClub$index' value='".$record['ClubID']."'>";
					$row .= "<td>".$record['Naam'];
					if ($record['Code'] != "") $row .= " ".$record['Code'];
					$row .= "</td>";
					$row .= "</tr>";

					return $row;
				}

				$terugRonde = "";
				$matchIndex = 0;
				$result = $db->Query("SELECT cp.ID, c.Naam, cp.Code, cp.ClubID FROM clubploeg cp INNER JOIN club c ON cp.ClubID=c.ID WHERE ReeksId=$reeksId AND cp.ID<>".$reeksPloegId." ORDER BY c.Naam");
				while ($record = mysql_fetch_array($result))
				{
					$matchIndex++;
					echo BuildReeksRow($record, $matchIndex, $params[PARAM_STANDAARDUUR], $_POST['reeksJaar'], $matchIndex + 1);
					$matchIndex++;
					$terugRonde .= BuildReeksRow($record, $matchIndex, $params[PARAM_STANDAARDUUR], $_POST['reeksJaar'] * 1 + 1, 0);
				}
				?>
				<tr>
					<td colspan=5><hr></td>
				</tr>
				<?php echo $terugRonde?>
				<input type=hidden name=reeksAantalMatchen value="<?php echo $matchIndex?>">
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit name=reeksMatchButton value="Toevoegen"></td>
	</tr>
	<?php } ?>
</table>
</form>
<?php
	include_once 'admin_end.php';
?>