<?php
define("RELATIVE_PATH", "");
include_once 'include/header.php';

if (!$security->GeleideTraining())
	die('oh noes');

$params = $db->GetParams(PARAM_TRAINING_PERSONEN);

switch ($_POST['action'])
{
case 'gtInschrijven':
	if (is_numeric($_POST['kalenderId']) && is_numeric($_POST['uur']) && is_numeric($_POST['spelerId']))
	{
		$where = 'WHERE KalenderId='.$_POST['kalenderId'].' AND SpelerId='.$_POST['spelerId'].' AND Uur='.$_POST['uur'];
		if ($db->ExecuteScalar("SELECT COUNT(0) FROM training $where") == 1)
		{
			$db->ExecuteScalar("DELETE FROM training $where");
		}
		else
		{
			$db->Query('INSERT INTO training (KalenderId, SpelerId, Uur) VALUES ('
			.  $_POST['kalenderId'] . ', ' . $_POST['spelerId'] . ', ' . $_POST['uur']
			. ')');
		}

		$descFormat = explode(',', $db->ExecuteScalar('SELECT GeleideTraining FROM kalender WHERE ID='.$_POST['kalenderId']));
		$desc = $descFormat[2];

		$result = $db->Query("SELECT Uur, COUNT(0) AS Cnt FROM training WHERE KalenderId=".$_POST['kalenderId']." GROUP BY Uur");
		while ($record = mysql_fetch_array($result))
		{
			if ($record['Uur'] == $descFormat[0]) $desc = str_replace('{vrij1}', $params[PARAM_TRAINING_PERSONEN] - $record['Cnt'], $desc);
			else $desc = str_replace('{vrij2}', $params[PARAM_TRAINING_PERSONEN] - $record['Cnt'], $desc);
		}
		$desc = str_replace('{vrij1}', $params[PARAM_TRAINING_PERSONEN], $desc);
		$desc = str_replace('{vrij2}', $params[PARAM_TRAINING_PERSONEN], $desc);

		$db->ExecuteScalar("
			UPDATE kalender SET Beschrijving='".$db->Escape($desc)."'
			WHERE GeleideTraining IS NOT NULL AND ID=".$_POST['kalenderId']);
	}

	break;
}

$db->Close();
?>