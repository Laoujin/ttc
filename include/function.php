<?php
	function VBCode($bbcode)
	{
		return str_replace("\n", "<br>", str_replace("\r\n", "<br>", str_replace('"', '\\"', $bbcode)));

	  //$bbcode = eregi_replace("\\[b\\]", quotemeta("<b>"), $bbcode);
	  //$bbcode = eregi_replace("\\[u\\]", quotemeta("<u>"), $bbcode);
	  //$bbcode = eregi_replace("\\[i\\]", quotemeta("<i>"), $bbcode);
	  //$bbcode = eregi_replace("\\[/b\\]", quotemeta("</b>"), $bbcode);
	  //$bbcode = eregi_replace("\\[/u\\]", quotemeta("</u>"), $bbcode);
      //$bbcode = eregi_replace("\\[/i\\]", quotemeta("</i>"), $bbcode);

	  // do [url]xxx[/url]
	  //$bbcode = eregi_replace(quotemeta("[url=")."([^\\[]*)".quotemeta("]")."([^\\[]*)".quotemeta("[/url]"), quotemeta("<a target=\"_blank\" class=\"inpost_link\" href=\"")."\\1".quotemeta("\">")."\\2".quotemeta("</a>"), $bbcode);

	  // do [email]xxx[/email]
	  //$bbcode = eregi_replace("\\[email\\]([^\\[]*)\\[/email\\]", "<a class=\"inpost_link\" href=\"mailto:\\1\">\\1</a>", $bbcode);

	  // do quotes
	  //$bbcode = eregi_replace(quotemeta("[quote]"), quotemeta("<blockquote><smallfont>quote:</smallfont><hr>"), $bbcode);
	  //$bbcode = eregi_replace(quotemeta("[/quote]"), quotemeta("<hr></blockquote>"), $bbcode);

	  // do pre
	  //$bbcode = eregi_replace(quotemeta("[pre]"), quotemeta("<pre>"), $bbcode);
	  //$bbcode = eregi_replace(quotemeta("[/pre]"), quotemeta("</pre>"), $bbcode);

	  // do [img]xxx[/img]
	  //$bbcode = eregi_replace("\\[img\\]([^\"\\[]*)\\[/img\\]", "<img src=\"\\1\" border=0>", $bbcode);

    // do item [*]
	  //$bbcode = eregi_replace(quotemeta("[*]"), quotemeta("<li>"), $bbcode);

	  //return str_replace("\n", "<br>", str_replace("\r\n", "<br>", $bbcode));
	  //return $bbcode;
	}

	function DisplayDay($DayOfWeek)
	{
		switch ($DayOfWeek - 1)
		{
		case 1:
			return "Maandag";
		case 2:
			return "Dinsdag";
		case 3:
			return "Woensdag";
		case 4:
			return "Donderdag";
		case 5:
			return "Vrijdag";
		case 6:
			return "Zaterdag";
		case 7:
			return "Zondag";
		}
	}

	function DisplayReeks(& $record, $class = '', $icon = false)
	{
		$var = $record['Competitie'].' '.$record['Reeks'].' '.$record['ReeksType'].' '.$record['ReeksCode'];
		if (isset($record['Jaar']) && $record['Jaar'] != date("Y")) $var = "Seizoen ".$record['Jaar']."-".($record['Jaar']*1 + 1).": $var";
		if ($icon) {
			$var = "<img src=img/kalender.bmp class=icon tag='Kalender' title='Kalender'></a> <a href=reeks.php?id=".$record['ReeksID']."&ploeg=".$record['ThuisPloeg'].($class != '' ? ' class='.$class : '').">".$var;
		}
		if (isset($record['ReeksID'])) {
			$var = "<a href=reeks.php?id=".$record['ReeksID']."&ploeg=".$record['ThuisPloeg'].($class != '' ? ' class='.$class : '').">".$var."</a>";
		}

		return $var;
	}

	function DisplayPloeg(& $record, $locatie, $class = '')
	{
		if ($record[$locatie.'ClubPloegID'] == "")
			return "<a href=ploeg.php?clubid=".$record[$locatie.'ClubID'].'>'.$record[$locatie.'Naam'].' '.$record[$locatie.'Ploeg'].'</a>';

		if ($locatie == "Thuis")
			return "<a href=reeks.php?ploeg=".$record['ReeksID']."&comp=".$record['Competitie'].">".$record[$locatie.'Naam'].' '.$record[$locatie.'Ploeg'].'</a>';
		else
			return "<a href=ploeg.php?id=".$record[$locatie.'ClubPloegID']."&clubid=".$record[$locatie.'ClubID'].">".$record[$locatie.'Naam'].' '.$record[$locatie.'Ploeg'].'</a>';
	}

	function nbsp($var)
	{
		return !strlen($var) ? "&nbsp;" : $var;
	}

	function nvl($in, $rep = '')
	{
		return !strlen($in) ? $rep : $in;
	}

	function GetImage($id, $tag)
	{
		$imgPath = "img/speler/".$id.".jpg";
		if (!file_exists($imgPath)) $imgPath = "img/speler/no-image.gif border=0";
		else $imgPath .= " border=1";
		return "<img src=$imgPath alt='$tag' title='$tag'>";
	}

	function CreateIconLink($a, $text, $icon, $class = '', $blank = false)
	{
		return "<a".($blank ? ' target=_blank' : '')." href=".$a."><img src=img/".$icon." class=icon></a>" . "&nbsp;<a".($blank ? ' target=_blank' : '')." href=".$a.">".$text."</a>";
	}

	function PrintKalender($db, $frenoyApi, $where, $stdUur, $weekSpacer, $context = 'main')
	{
		global $security;
		?>
		<table width="100%" class="maintable">
		<tr>
			<th width="5%">Week</td>
			<th width="10%">Dag</td>
			<th width="10%">Datum</td>
			<th width="5%">Uur</td>
			<?php
				if ($context != 'reeks') echo '<th width="15%">Competitie</td>';
			?>
			<th width="20%">Thuis</td>
			<th width="20%">Uit</td>
			<th width="15%">Uitslag</td>
		</tr>
		<?php
		$result = $db->Query(
		 "SELECT Week, TIME_FORMAT(Uur, '%k:%i') AS Uur, DATE_FORMAT(Datum, '%d/%m/%Y') AS FDatum, kalender.Beschrijving, UitPloeg, DAYOFWEEK(Datum) AS Dag
			, ThuisClubPloegID, clubthuis.Naam AS ThuisNaam, ThuisPloeg, Competitie, Reeks, ReeksType, ReeksCode, reeks.ID AS ReeksID
			, UitClubPloegID, clubuit.Naam AS UitNaam, TO_DAYS(Datum)-TO_DAYS(NOW()) AS Vandaag, Thuis, WEEK(Datum) AS JaarWeek, GeleideTraining
			, v.ID AS VerslagID, v.UitslagThuis, v.UitslagUit, v.WO, v.Details AS HeeftVerslag, kalender.ID AS KalenderID, clubthuis.ID AS ThuisClubID, clubuit.ID AS UitClubID
			, FrenoyMatchId
			FROM kalender
			LEFT JOIN clubploeg thuis ON ThuisClubPloegID=thuis.ID
			LEFT JOIN reeks ON thuis.ReeksID=reeks.ID
			LEFT JOIN club clubthuis ON ThuisClubID=clubthuis.ID
			LEFT JOIN club clubuit ON UitClubID=clubuit.ID
			LEFT JOIN verslag v ON kalender.ID=v.KalenderID
			$where
			ORDER BY Datum, kalender.ID");

		$week = 0;
		while ($record = mysql_fetch_array($result))
		{
			$linkClass = "rowa";
			if ($record['Vandaag'] < 0)
			{
				$class = " class='rowpassed'";
				$linkClass = "rowpassed";
			}
			else if ($record['Vandaag'] == 0) $class = " class='rowselected'";
			else $class = " class='rowa'";
			if ($record['Thuis'])
			{
				$thuis = "Thuis";
				$uit = "Uit";
			}
			else
			{
				$thuis = "Uit";
				$uit = "Thuis";
			}

			if ($weekSpacer && $week != $record['JaarWeek'])
			{
				if ($week != 0) echo "<tr height='3'><td colspan='8' class='rowheader'></td></tr>";
				$week = $record['JaarWeek'];
			}

			?>
			<tr<?php echo $class?>>
				<td><?php echo $record['Week']?></td>
				<td><?php echo DisplayDay($record['Dag'])?></td>
				<td><?php echo $record['FDatum']?></td>
				<td><?php echo ($record['Uur'] == $stdUur || $record['Vandaag'] < 0 ? $record['Uur'] : "<b>".$record['Uur']."</b>" ) ?></td>
				<?php if ($record['ThuisClubPloegID']) { ?>
					<?php
						if ($context != 'reeks') echo '<td>'.DisplayReeks($record, $linkClass).'</td>';
					?>
					<td><?php echo DisplayPloeg($record, $thuis, $linkClass)?></td>
					<td><?php echo DisplayPloeg($record, $uit, $linkClass)?></td>
					<td align='center'>
						<?php
						//if (false) echo "ID=".$record['KalenderID']."//";
						if ($record['Vandaag'] <= 0)
						{
							echo "<label id=uitslag".$record['KalenderID'].">";
							if ($record['VerslagID'] != '')
							{
								if ($record['WO'] == "1") echo "WO";
								elseif ($record['Thuis']) echo "<b>".$record['UitslagThuis']."</b> - ".$record['UitslagUit'];
								else echo $record['UitslagThuis']." - <b>".$record['UitslagUit']."</b>";
							}
							else
							{
								// fetch score from Frenoy and update in db
								$frenoyApi->SetCompetition($record['Competitie']);
								$matches = $frenoyApi->GetMatches($record['Week']);

								if (is_array($matches->TeamMatchesEntries)) {
									$this_match = array_filter($matches->TeamMatchesEntries, function ($match) use($record) {
										return $match->MatchId == $record['FrenoyMatchId'] && isset($match->Score);
									});
									$this_match = array_shift($this_match);
								} else {
									$this_match = $matches->TeamMatchesEntries;
								}

								if ($this_match) {
									if (count($this_match) > 0 && strpos($this_match->Score, '-') !== false) {
										$score = $this_match->Score;
										$home = substr($score, 0, strpos($score, '-'));
										$out = substr($score, strpos($score, '-') + 1);

										if (!is_numeric($home) || !is_numeric($out)) {
											$frenoy_report = $record['KalenderID'] . ", 1, NULL, NULL, 1, 0";
										} else {
											$frenoy_report = $record['KalenderID'] . ", 1, $home, $out, 0, 0";
										}
										$db->Query("INSERT INTO verslag (KalenderID, SpelerID, UitslagThuis, UitslagUit, WO, Details) VALUES ($frenoy_report)");

										echo $home . '&nbsp;-&nbsp;' . $out;
									}
								} else {
									echo "&nbsp;";
								}
							}
							echo "</label>";
							if ($security->Verslag() || $record['HeeftVerslag'] || ($record['Vandaag'] <= 0 && $record['VerslagID'] == ''))
							{
								echo "&nbsp;<a href='#' class='verslagLink' kalender='".$record['KalenderID']."' reeks='".$record['Competitie']."' thuis='".$record['Thuis']."' tag='Wedstrijdverslag' title='Wedstrijdverslag'><img src=img/verslag".($record['HeeftVerslag'] == 1 ? "" : "add").".png class=icon></a>";
							}
						}
						else
						{
							echo "&nbsp;";
						}
						?>
					</td>
				<?php
					} else if ($record['GeleideTraining'] && $security->GeleideTraining()) { ?>
					<td colspan="3" style="background-color: #ffffff"><?=$record['Beschrijving']?></td>
					<td align="center">
						<a href="#" class="geleidetraining" data-training-id="<?=$record['KalenderID'] ?>">
							<img src="img/verslag.png" class="icon" title="Schrijf je in!">
						</a>
					</td>

				<?php } else { ?>
					<td colspan=<?php echo ($context != 'reeks' ? 4 : 3)?>><?php echo $record['Beschrijving']?></td>
				<?php } ?>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	}
?>