<?php
class SecurityManager
{
	var $level;

	function SecurityManager($accessLevel)
	{
		if (is_numeric($accessLevel))
			$this->level = $accessLevel * 1;
		else
			$this->level = 0;
	}

	function Kalender()
	{
	 	return !(($this->level & TOEGANG_ADMIN) == 0);
	}

	function Params()
	{
		return !(($this->level & TOEGANG_ADMIN) == 0);
	}

	function Spelers()
	{
		return !(($this->level & TOEGANG_ADMIN) == 0);
	}

	function Ploegen()
	{
		return !(($this->level & TOEGANG_ADMIN) == 0);
	}

	function Verslag($verslagId = '')
	{
		return !(($this->level & TOEGANG_SPELER) == 0);

		//if ($verslagId == '') return !(($this->level & TOEGANG_SPELER) == 0);
		//return (($this->level & TOEGANG_KAPITEIN) != 0 || ($this->level & TOEGANG_ADMIN) != 0);
	}

	function GeleideTraining()
	{
		return $this->Spelers();
	}

	function Any()
	{
		return !(($this->level & TOEGANG_SPELER) == 0);
	}

	function Admin()
	{
		return !(($this->level & TOEGANG_ADMIN) == 0);
	}
}
?>