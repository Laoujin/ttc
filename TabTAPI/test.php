<?php
define("RELATIVE_PATH", "../");
define("PAGE_TITLE", "Test TabTAPI");
define("PAGE_DESCRIPTION", "Test the TabTAPI");
include_once '../include/header.php';
include_once 'TabTAPI.php';

$params = $db->GetParams(array(PARAM_FRENOY_URL_SPORTA, PARAM_FRENOY_URL_VTTL, PARAM_FRENOY_LOGIN, PARAM_FRENOY_PASSWORD, PARAM_JAAR, PARAM_EMAIL));

$api = new TabTAPI($params[PARAM_FRENOY_LOGIN], $params[PARAM_FRENOY_PASSWORD], $params[PARAM_FRENOY_URL_SPORTA]);

//$response = $api->Test();

$api->SetCurrentSeason($params[PARAM_JAAR]);

//$response = $api->GetClubs(CLUB_CODE_VTTL);
//$api->SetClubCategory("Oost-Vlaanderen");
$api->SetClub(CLUB_CODE_SPORTA);

//$response = $api->GetClubs();

$response = $api->GetMatches(9);



//var_dump($response);
echo "<pre>";
print_r($response);
echo "</pre>";
?>
<h1>Login</h1>
Wsdl: <input type='text' id='wsdlUrl' /><br>
Account: <input type='text' id='account' /><br>
Password: <input type='password' id='password' />

<?php
	include_once "../include/menu_end.php";
?>