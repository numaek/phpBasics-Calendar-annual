<?php

	/* 
	 * phpBasics
	 * ---------
	 * 
	 * Script:        nKalender
	 * 
	 * Version:       1.0
	 * Release:       01.10.2019
	 * 
	 * Author:        numaek   
	 * Copyright (c): 2004-2019 by www.numaek.de
	 * 
	 * *************************************************************************************************************************************************************************************************
	 */


	// Konfiguration
	// =============

	// Die Woche mit Samstag beginnen lassen (1):
	// ------------------------------------------
	define('NKALENDER_SAMSTAG', 0);


	// ================================================================================================================================================================================================


	// Monatstexte
	$monat_text     = array();
	$monat_text[0]  = "";
	$monat_text[1]  = "Januar";
	$monat_text[2]  = "Februar";
	$monat_text[3]  = "Maerz";
	$monat_text[4]  = "April";
	$monat_text[5]  = "Mai";
	$monat_text[6]  = "Juni";
	$monat_text[7]  = "Juli";
	$monat_text[8]  = "August";
	$monat_text[9]  = "September";
	$monat_text[10] = "Oktober";
	$monat_text[11] = "November";
	$monat_text[12] = "Dezember";

	$zeit           = time();
	$datum          = getdate($zeit);
	$tag            = "$datum[mday]";
	$dieser_monat   = "$datum[mon]";
	$dieses_jahr    = "$datum[year]";

	if( isset($_GET['jahr']) )
	{
		$jahr = htmlspecialchars(urldecode(trim($_GET['jahr'])));
	} else
		if( isset($_POST['jahr']) )
		{
			$jahr = $_POST['jahr'];
		} else
		  {
			$jahr = "$datum[year]";
		  }


	// Tage je Monat und Schaltjahre
	// =============================
	if( gettype($jahr/4) == "integer" )
	{
		$monat_tage = array(0,31,29,31,30,31,30,31,31,30,31,30,31);
	} else
	  {
		$monat_tage = array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	  }

	$minus        = $jahr - 1;
	$plus         = $jahr + 1;

	$nKalLinkBack = $_SERVER['PHP_SELF']."?jahr=".$minus;
	$nKalLinkFor  = $_SERVER['PHP_SELF']."?jahr=".$plus;

	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"nKalender.css\">

	<script language=\"javascript\">

		// Lässt die Kalenderwochen aufleuchten
		// ************************************
		function changeLineColor(kwNR, mode)
		{
			cellIdName1 = 'kw_'+kwNR+'_1';
			cellIdName2 = 'kw_'+kwNR+'_2';

			if( document.getElementById(cellIdName1) )
			{
				document.getElementById(cellIdName1).className = ( mode == 1 ) ? 'nKalender_tagZelle nKalender_kwOn' : 'nKalender_tagZelle nKalender_kwOff';
			}

			if( document.getElementById(cellIdName2) )
			{
				document.getElementById(cellIdName2).className = ( mode == 1 ) ? 'nKalender_tagZelle nKalender_kwOn' : 'nKalender_tagZelle nKalender_kwOff';
			}
		}

	</script>

	<table class=\"nKalender_rahmen\">
		<tr>
			<th>
				Jahreskalender &nbsp;&nbsp;&nbsp;
				<a href=\"".$nKalLinkBack."\">&lt;&lt;</a>
				&nbsp;".$jahr."&nbsp;
				<a href=\"".$nKalLinkFor."\">&gt;&gt;</a>
			</th>
		</tr>
		<tr>
			<td>
				<table class=\"nKalender_tabelle\">\n";

					$kw = 1;
					for( $monat = 1; $monat < 13; $monat++ )
					{
						// Neue Monatsblock-Zeile nach 3 Monaten
						// =====================================
						if( $monat == 1 )
						{
							echo "<tr>\n";
						} else
					//	if( $monat == 4 || $monat == 7 || $monat == 10 )
						if( $monat == 5 || $monat == 9 )
						{
							echo "</tr><tr>\n";
						}

						echo "<td class=\"nKalender_monate\">\n";

						$start      = getdate(mktime(2,0,0,$monat,1,$jahr));
						$beginn     = "$start[wday]";
						$monatClass = ( $monat == $dieser_monat ) ? "nKalender_monatOn" : "nKalender_monatOff";

						if( NKALENDER_SAMSTAG == 1 )
						{
							$beginn = $beginn + 2;
							if( $beginn == 8 )
							{
								$beginn = 1;
							}

							echo "<table class=\"tableMain\" style=\"border-spacing: 1px;\">
							<tr>
								<td colspan=\"7\" class=\"".$monatClass."\">".$monat_text[$monat]."</td>
							</tr>
							<tr>
								<td class=\"nKalender_wochentag\">Sa</td>
								<td class=\"nKalender_wochentag\">So</td>
								<td class=\"nKalender_wochentag\">Mo</td>
								<td class=\"nKalender_wochentag\">Di</td>
								<td class=\"nKalender_wochentag\">Mi</td>
								<td class=\"nKalender_wochentag\">Do</td>
								<td class=\"nKalender_wochentag\">Fr</td>
							</tr>\n";
						} else
						  {
							if( $beginn == 0 )
							{
								$beginn = 7;
							}

							echo "<table class=\"tableMain\" style=\"border-spacing: 1px;\">
							<tr>
								<td colspan=\"8\" class=\"".$monatClass."\">".$monat_text[$monat]."</td>
							</tr>
							<tr>
								<td class=\"nKalender_kwText\">KW</td>
								<td class=\"nKalender_wochentag\">Mo</td>
								<td class=\"nKalender_wochentag\">Di</td>
								<td class=\"nKalender_wochentag\">Mi</td>
								<td class=\"nKalender_wochentag\">Do</td>
								<td class=\"nKalender_wochentag\">Fr</td>
								<td class=\"nKalender_wochentag\">Sa</td>
								<td class=\"nKalender_wochentag\">So</td>
							</tr>\n";
						  }

						$zeile     = 1;
						$spalte    = 1;
						$tagSpalte = 1;
						$tagnummer = 1;

						echo "<tr";
						if( NKALENDER_SAMSTAG != 1 )
						{
							echo " onMouseOver=\"changeLineColor(".$kw.", 1);\" onMouseOut=\"changeLineColor(".$kw.", 0);\"";
						}
						echo ">\n";

						if( NKALENDER_SAMSTAG != 1 )
						{
							echo "<td id=\"kw_".$kw."_1\" class=\"nKalender_tagZelle nKalender_kwOff\">".$kw."</td>\n";
						}

						for( $y = 1; $y < ( $monat_tage[$monat] + $beginn ); $y++ )
						{
							if( $y < $beginn )
							{ 
								// Anfängliche leere Tage auffüllen
								// ================================
								echo "<td class=\"nKalender_tagZelle nKalender_tagLeer\">&nbsp;</td>\n"; 
							} else
							  {
								// Wochentag ermitteln
								// ===================
								if( $tagnummer == $tag && $monat == $dieser_monat && $jahr == $dieses_jahr )
								{
									$cellBgClass = "nKalender_heute";
								} else
								if( $tagSpalte == 6 )
								{
									$cellBgClass = "nKalender_samstag";
								} else
								if( $tagSpalte == 7 )
								{
									$cellBgClass = "nKalender_sonntag";
								} else
								  {
									$cellBgClass = "nKalender_tag";
								  }

								echo "<td class=\"nKalender_tagZelle ".$cellBgClass."\">".$tagnummer."</td>\n";

								$tagnummer++;
							  }

							// Kalenderwoche immer Sonntags hochzählen
							// =======================================
							if( $tagSpalte == 7 )
							{
								$kw++;
							}

							// Neue Wochenzeile nach 7 Spalten und wenn noch Tage im Monat folgen
							// ==================================================================
							if( gettype($spalte/7) == "integer" && $y < ( $monat_tage[$monat] + $beginn - 1 ) )
							{
								$zeile++;
								$tagSpalte = 0;

								echo "</tr>\n<tr";
								if( NKALENDER_SAMSTAG != 1 )
								{
									echo " onMouseOver=\"changeLineColor(".$kw.", 1);\" onMouseOut=\"changeLineColor(".$kw.", 0);\"";
								}
								echo ">\n";

								if( NKALENDER_SAMSTAG != 1 )
								{
									echo "<td id=\"kw_".$kw."_2\" class=\"nKalender_tagZelle nKalender_kwOff\">".$kw."</td>\n";
								}
							}

							$tagSpalte++;
							$spalte++;
						}

						$ende = $zeile * 7;
						$rest = ($ende - $spalte) + 1;
						if( $rest >= 7 )
						{
							$rest = 0;
						}

						// Restliche leere Tage auffüllen
						// ==============================
						for( $r = 0; $r < $rest; $r++ )
						{
							echo "<td id=\"kw_".$kw."\" class=\"nKalender_tagZelle nKalender_tagLeer\">&nbsp;</td>\n";
						}

						echo "</tr>\n";

						// Ggf. mit Leerzeile den Monatsblock auffüllen
						// ============================================
						if( $zeile < 6 )
						{
							echo "<tr><td colspan=\"";
							echo ( NKALENDER_SAMSTAG != 1 ) ? 8 : 7;
							echo "\" class=\"nKalender_monatOff\">&nbsp;</td></tr>\n";
						}

						echo "</table></td>\n";
					}

					echo "</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>\n";

?>