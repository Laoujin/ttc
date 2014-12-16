<?php
	include_once 'include.php';
	include_once '../include/header.php';	
	
	$params = $db->GetParams(array(PARAM_EMAIL));
	
	function createRandomPassword()
	{ 
    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 7)
    { 
      $num = rand() % 33; 
      $tmp = substr($chars, $num, 1); 
      $pass = $pass . $tmp; 
      $i++; 
    } 
    return $pass; 
	}
	
	if (isset($_POST['buttonEmailPass']))
	{
		$result = $db->Query("SELECT Email, Naam FROM speler WHERE ID=".$_POST['emailLogin']." AND Email='".$db->Escape(trim($_POST['emailEmail']))."' AND Toegang<>0");
		if ($record = mysql_fetch_array($result))
		{
			$rndPwd = createRandomPassword();
			$mail = "Je nieuw paswoord is: $rndPwd \nJe kan dit in het admin gedeelte van de site opnieuw aanpassen.";
			mail($record['Email'], "Nieuw paswoord TTC Erembodegem", $mail, "From:".$params[PARAM_EMAIL]);
			$db->Query("UPDATE speler SET Paswoord=MD5('".$rndPwd."') WHERE ID=".$_POST['emailLogin']);
			$msg = "Nieuw paswoord is verstuurd!";
		}
		else $msg = "Incorrect email adres opgegeven!";
	}
	
	include_once 'admin_start.php';
	
	if (!isset($_SESSION['user']))
	{
		?>
		<form method="post" action="index.php">
		<table width="350" align="left" class="maintable">
			<tr>
				<th colspan="2">Inloggen</th>
			</tr>
			<tr>
				<td>Login:</td>
				<td><?php echo $db->BuildSpelerCombo("login", CLUB_ID, isset($_COOKIE['login']) ? $_COOKIE['login'] : "", true)?></td>
			</tr>
			<tr>
				<td>Paswoord:</td>
				<td><input type=password name="paswoord"></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type=submit name=inloggen value="Inloggen">
				</td>
			</tr>
		</table>
		
		<br><br>
		<br><br>
		<br><br>
		<br><br>
		
		<table width="350" class=maintable>
			<tr>
				<th colspan=2>Wachtwoord vergeten?</th>
			</tr>
			<tr>
				<td>Login:</td>
				<td>
					<?php echo $db->BuildSpelerCombo("emailLogin", CLUB_ID, isset($_COOKIE['emailLogin']) ? $_POST['emailLogin'] : "", true)?>
				</td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><input type=text name=emailEmail></td>
			</tr>
			<tr>
				<td align=center colspan=2><input type=submit name='buttonEmailPass' value='Nieuw paswoord emailen'></td>
			</tr>
		</table>
		</form>
		<?php
	}
	else
	{
		echo "<h2>Welkom ".$_SESSION['user']."</h2>";
		echo "Gebruik het menu om de site te beheren.";
		echo "<br><br>";
		echo "PHP Versie: " . phpversion();
		//echo phpinfo();
	}
	include_once 'admin_end.php';
?>