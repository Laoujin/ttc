<?php
	include_once 'include.php';
	define("KALENDER", "kalender");
	include_once '../include/header.php';
	
	if (!$security->Any())
		header('Location: index.php');
	
	if (isset($_POST['newpass']))
	{
		$db->Query("UPDATE speler SET Paswoord=MD5('".$db->Escape($_POST['newpass'])."') WHERE ID=".$_SESSION['userid']);
		$msg = "Paswoord gewijzigd!";
	}

	include_once 'admin_start.php';
?>
<h1>Admin - paswoord</h1>
<form method=post>
<table width="300" align=left class="maintable">
	<tr>
		<th colspan=2>Paswoord wijzigen</th>
	</tr>
	<tr>
		<td width='30%' nowrap>Nieuw wachtwoord:</td>
		<td width='70%'><input type=password name=newpass size=20></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit value='Wijzigen'></td>
	</tr>
</table>
</form>
<?php
	include_once 'admin_end.php';
?>