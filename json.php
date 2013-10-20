<?php
	define("RELATIVE_PATH", "");
	include_once 'include/header.php';

	//$params = $db->GetParams(array(PARAM_STANDAARDUUR, PARAM_KAL_WEEKS_OLD, PARAM_KAL_WEEKS_NEW));
	
	switch ($_GET['type'])
	{
	case 'verslagSave':
		$kalenderId = $_POST['popupID'];
		$verslagId = $_POST['popupVerslagID'];
		//echo "Security:" . $security->Verslag($verslagId) . "/Kal:" . $kalenderId . '/Verslag:' . $verslagId;
		if (is_numeric($kalenderId) && $security->Verslag($verslagId))
		{
			if (is_numeric($verslagId))
			{
				//echo "DELETE FROM verslag WHERE ID=$verslagId";
				$db->Query("DELETE FROM verslagspeler WHERE VerslagID=$verslagId");
				$db->Query("DELETE FROM verslag WHERE ID=$verslagId");
			}
			
			$verslag = $db->Escape($_POST['editVerslag']);
			$values = "$kalenderId, ".$_SESSION['userid'].", '".$verslag."', ".(!is_numeric($_POST['editThuisPunten']) ? "NULL" : $_POST['editThuisPunten']).", ".(!is_numeric($_POST['editUitPunten']) ? "NULL" : $_POST['editUitPunten']);
			$values .= ", ".(isset($_POST['editWO']) && $_POST['editWO'] == 'WO' ? "1" : "0").", ".($verslag != '' ? "1" : "0");
			$db->Query("INSERT INTO verslag (KalenderID, SpelerID, Beschrijving, UitslagThuis, UitslagUit, WO, Details) VALUES ($values)");
			$verslagId = mysql_insert_id();
			
			$uit = ""; $thuis = 1;
			for ($i = 1; $i <= 4; $i++)
			{
				if ($_POST['edit'.$uit.'Speler'.$i] != "0" && is_numeric($_POST['edit'.$uit.'Speler'.$i]))
				{
					$values = "$verslagId, ".nvl($_POST['edit'.$uit.'Speler'.$i], "NULL").", ".nvl($_POST['edit'.$uit.'Winst'.$i], "NULL");
					$result = $db->Query("SELECT NaamKort, Klassement".$db->Escape($_POST['popupReeks'])." AS Klassement FROM speler WHERE ID=".$_POST['edit'.$uit.'Speler'.$i]);
					$record = mysql_fetch_array($result);
					$values .= ", '".$record['NaamKort']."', $thuis, '".$record['Klassement']."'";
					$db->Query("INSERT INTO verslagspeler (VerslagID, SpelerID, Winst, SpelerNaam, Thuis, Klassement) VALUES ($values)");
				}
			}
			$uit = "Uit"; $thuis = 0;
			for ($i = 1; $i <= 4; $i++)
			{
				if ($_POST['edit'.$uit.'Speler'.$i] != "")
				{
					$values = "$verslagId, NULL, ".nvl($_POST['edit'.$uit.'Winst'.$i], "NULL");
					$values .= ", '".$db->Escape(ucfirst($_POST['edit'.$uit.'Speler'.$i]))."', $thuis, '".$db->Escape($_POST['edit'.$uit.'Klas'.$i])."'";
					$db->Query("INSERT INTO verslagspeler (VerslagID, SpelerID, Winst, SpelerNaam, Thuis, Klassement) VALUES ($values)");
				}
			}
			
			$db->SetLastUpdate("kalender");
			//$msg = "Opgeslaan!";
		}
		if (isset($msg)) echo $msg;
		break;
	
	case 'verslag':
		if (is_numeric($_GET['id']))
		{
			/*$result = $db->Query('SELECT v.ID, NaamKort, Beschrijving, UitslagThuis, UitslagUit, WO, Details
														, UitPloeg, ThuisClubPloegID, clubthuis.Naam AS ThuisNaam, ThuisPloeg
														, UitClubPloegID, clubuit.Naam AS UitNaam, Thuis
														FROM verslag v 
														LEFT JOIN speler s ON v.SpelerID=s.ID 
														LEFT JOIN clubploeg thuis ON ThuisClubPloegID=thuis.ID
														LEFT JOIN club clubthuis ON ThuisClubID=clubthuis.ID
														LEFT JOIN club clubuit ON UitClubID=clubuit.ID
														WHERE KalenderID='.$_GET['id']);*/
			$result = $db->Query('SELECT v.ID, NaamKort, Beschrijving, UitslagThuis, UitslagUit, WO, Details
														FROM verslag v 
														LEFT JOIN speler s ON v.SpelerID=s.ID 
														WHERE KalenderID='.$_GET['id']);
			if ($record = mysql_fetch_array($result))
			{
				echo '{';
				echo '"VerslagID": ' . $record['ID'];
				echo ', "Naam": "' . $db->Html($record['NaamKort']) . '"';
				echo ', "Beschrijving": "' . VBCode($record['Beschrijving']) . '"';
				echo ', "UitslagThuis": ' . nvl($record['UitslagThuis'], 0);
				echo ', "UitslagUit": ' . nvl($record['UitslagUit'], 0);
				echo ', "WO": ' . $record['WO'];
				echo ', "Details": ' . $record['Details'];
				//echo ', "Thuis": ' . ($record['Thuis'] == 1 ? "true" : "false");
				
				$thuis = ''; $thuisO = '';
				$uit = ''; $uitO = '';
				$result = $db->Query('SELECT SpelerID, SpelerNaam, Winst, Thuis, Klassement FROM verslagspeler vs WHERE VerslagID='.$record['ID']);
				while ($record = mysql_fetch_array($result))
				{
					$addTo = & $uit;
					$addToO = & $uitO;
					if ($record['Thuis']) { $addTo = & $thuis; $addToO = & $thuisO; }
					$addTo .= $db->Html($record['SpelerNaam']) . ($record['Klassement'] ? " (".$record['Klassement'].")" : "") . ': ' . $record['Winst'] . '<br>';
					$addToO .= ', { "Naam": "' . $db->Html($record['SpelerNaam']) . '"';
					$addToO .= ', "Winst": ' . nvl($record['Winst'], "null");
					if ($record['SpelerID']) $addToO .= ', "ID": ' . $record['SpelerID'];
					$addToO .= ', "Klassement": "' . $record['Klassement'] . '"';
					$addToO .= '}';
				}
				
				echo ', "Thuis": "' . $thuis . '"';
				echo ', "Uit": "' . $uit . '"';
				if ($thuisO != '') echo ', "ThuisObject": [' . substr($thuisO, 2) . ']';
				if ($uitO != '') echo ', "UitObject": [' . substr($uitO, 2) . ']';
				
				echo '}';
			}
		}
		break;
		
	default:
		?>
<script language="javascript" type="text/javascript">
function showRequest()
{
	//$("#verslagForm").attr("action", "json.php?type=verslag&id=" + $("#verslag").val());
}

$(document).ready(function() {
	//$('#verslagForm').ajaxForm({ beforeSubmit: showRequest });
});
</script>
<form id='verslagForm' method=get action='json.php'>
	<input type=hidden id='verslag' size=4 name=type value='verslag'>
	Kalender ID: <input type=text id='verslag' size=4 name=id>
	&nbsp; <input type=submit value='Verslag ophalen'>
</form>
<br><br>
		<?php
		break;	
	}

	include_once 'include/footer.php';
?>