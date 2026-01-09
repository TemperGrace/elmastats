<?php include("top.php"); ?>
<?php
  echo("<b>Average totaltimes</b><br/><br/>");


  $utimes = array();
  $unicks = array();
  $c = 0;
  foreach ($users as $cItem) {
    $utimes[] = stateTimes($cItem["nick"], $cItem["elmaname"]);
    $unicks[] = $cItem["nick"];
    $c++;
  }

  $tts = array();
  for ($y = 0;$y < $c;$y++) {
    $ctt = 0;
    $cItem["nick"] = $unicks[$y];
    $finished = 0;
    for ($x = 0;$x < 54;$x++) {
      $cTime = 0;
      for ($z = 0;$z < 10;$z++) {
        $xTime = $utimes[$y][$x+1][$z+1];
        if (!$xTime) {
          $cTime += 60000;
        } else {
          $cTime += $xTime;
          $finished++;
        }
      }
      $ctt += $cTime/10;
    }
    if ($ctt < 60000*54) {
      $cItem["time"] = round($ctt);
      $cItem["fnsh"] = $finished;
      $tts[] = $cItem;
    }
  }
  usort($tts, "cmp");

  echo("<table class=\"times\">");
  echo("<tr><th class=\"times\" width=\"20px\"></th><th class=\"times\" width=\"180px\">Kuski</th>");
  echo("<th class=\"times\" width=\"81px\"><center>TT</center></th>");
  echo("<th class=\"times\" width=\"81px\"><center>Times</center></th>");
  echo("<th class=\"times\" width=\"265px\"><center>Updated</center></th></tr>\n");
  for ($y = 0;$y < count($tts);$y++) {
    $str = "";
    if ($y > 0) $str = " title=\"+" . formatElmaTime($tts[$y]["time"]-$tts[0]["time"]) . " to " . $tts[0]["nick"] . "'s TT\"";
    echo("<tr><td class=\"times\" width=\"20px\">" . ($y+1) . ". </td>");
    echo("<td class=\"times\" width=\"180px\">" . man($tts[$y]["nick"]) . "</td>");
    echo("<td class=\"times\" width=\"81px\"" . $str . "><center>" . coloredtttime($tts[$y]["time"]) . "</center></td>");
    echo("<td class=\"times\" width=\"81px\"><center>" . $tts[$y]["fnsh"] . "/540</center></td>");
    echo("<td class=\"times\" width=\"265px\">&nbsp;" . date("d/m/y - H:i:s", ttime(filemtime("statefilesz/" . $tts[$y]["nick"] . ".dat"))) .
         " (" . timeSince(filemtime("statefilesz/" . $tts[$y]["nick"] . ".dat")) . " ago)</td></tr>\n");
  }
  echo("</table>");
?>
<?php include("tpo.php"); ?>