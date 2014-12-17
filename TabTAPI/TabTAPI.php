<?php
class TabTAPI
{
	private $_club;

	function TabTAPI($account, $password, $wsdlUrl)
	{
		$this->_credentials = new Credentials($account, $password);
		$this->_wsdlUrl = $wsdlUrl;
	}

	function Test()
	{
		$params = array(
		  "Credentials" => $this->_credentials
		);
		return $this->soapCall("Test", $params);
	}

	function SetCurrentSeason($year)
	{
		$params = array(
		  "Credentials" => $this->_credentials
		);
		$allSeasons = $this->soapCall("GetSeasons", $params);

		$this->_season = $allSeasons->CurrentSeason;
		return $allSeasons->CurrentSeason;
	}

	function SetClub($club)
	{
		$this->_club = $club;
	}

	function GetClubs($club = null)
	{
		$params = array(
		  "Credentials" => $this->_credentials,
		  "Season" => $this->_season,
		  "ClubCategory" => null,
		  "Club" => $club == null ? $this->_club : $club
		);

		$clubs = $this->soapCall("GetClubs", $params);
		return $clubs;
	}

	function GetMatches($weekName = null)
	{
		$params = array(
		  "Credentials" => $this->_credentials,
		  "DivsionId" => null,
		  "Club" => $this->_club,
		  "Team" => null,
		  "DivisionCategory" => null,
		  "Season" => $this->_season,
		  "WeekName" => $weekName,
		  "Level" => null,
		  "ShowDivisionName" => "short" /* no, yes, short */
		);
		return $this->soapCall("GetMatches", $params);
	}

	private function soapCall($functionName, $params)
	{
		$client = new SoapClient($this->_wsdlUrl);
		return $client->__soapCall($functionName, array($params));
	}
}

class Credentials
{
    function Credentials($account, $password)
    {
        $this->Account = $account;
        $this->Password = $password;
    }
}

class GetMatchesRequest
{
	function GetMatchesRequest($credentials, $divisionId, $club, $team, $divisionCategory, $season, $weekName, $level, $showDivisionName)
	{
		$this->Credentials = $credentials;
		$this->DivisionId = $divisionId;
		$this->Club = $club;
		$this->Team = $team;
		$this->DivisionCategory = $divisionCategory;
		$this->Season = $season;
		$this->WeekName = $weekName;
		$this->Level = $level;
		$this->ShowDivisionName = $showDivisionName;
	}
}
?>