<?php
class TabTAPI
{
	private $_club;
	private $_season;

	private $_credentials;
	private $_urlVTTL;
	private $_urlSporta;
	private $_wsdlUrl;

	function TabTAPI($account, $password, $year, $clubVTTL, $clubSporta, $urlVTTL, $urlSporta)
	{
		$this->_credentials = new Credentials($account, $password);
		$this->_urlVTTL = $urlVTTL;
		$this->_urlSporta = $urlSporta;
		$this->_clubCodeVTTL = $clubVTTL;
		$this->_clubCodeSporta = $clubSporta;
		$this->_defaultYear = $year;
		
		$this->SetCompetition("VTTL");
	}

	function Test()
	{
		$params = array(
		  "Credentials" => $this->_credentials
		);
		return $this->soapCall("Test", $params);
	}

	function SetCompetition($comp)
	{
		switch ($comp)
		{
			case "VTTL":
				$this->_wsdlUrl = $this->_urlVTTL;
				$this->_club = $this->_clubCodeVTTL;
				break;

			case "Sporta":
				$this->_wsdlUrl = $this->_urlSporta;
				$this->_club = $this->_clubCodeSporta;
				break;
		}
	}

	function GetCurrentClub()
	{
		return $this->_club;
	}

	function SetCurrentSeason($year)
	{
		$this->_defaultYear = null;

		$params = array(
		  "Credentials" => $this->_credentials
		);
		$allSeasons = $this->soapCall("GetSeasons", $params);

		$this->_season = $allSeasons->CurrentSeason;
		return $allSeasons->CurrentSeason;
	}

	function GetDivisionRanking($divisionId)
	{
		$params = array(
		  "Credentials" => $this->_credentials,
		  "DivisionId" => $divisionId
		);

		$result = $this->soapCall("GetDivisionRanking", $params);
		return $result->RankingEntries;
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
		  "DivisionId" => null,
		  "Club" => $this->_club,
		  "Team" => null,
		  "DivisionCategory" => null,
		  "Season" => $this->_season,
		  "WeekName" => $weekName,
		  "Level" => null,
		  "ShowDivisionName" => "short" /* no, yes, short */
		);
		
		echo "<pre>";
		print_r($params);
		echo "</pre>";
		
		return $this->soapCall("GetMatches", $params);
	}

	private $_lastCallSuccess;

	private function soapCall($functionName, $params)
	{
		if ($this->_defaultYear != null) {
			$this->SetCurrentSeason($this->_defaultYear); // CurrentSeason is the same for both VTTL and Sporta
		}

		try {
			$this->_lastCallSuccess = true;

			$this->_client = new SoapClient($this->_wsdlUrl/*, array('exceptions' => 0)*/);
			//$this->_client = new SoapClient("http://thissurelydoesntexist.com/", array('exceptions' => 0));
			$result = $this->_client->__soapCall($functionName, array($params));
			return $result;

		} catch (SoapFault $e) {
			$this->_lastCallSuccess = false;
			return null;
		}
	}

	function IsSuccess()
	{
		//return !is_soap_fault($this->_client);
		return $this->_lastCallSuccess;
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