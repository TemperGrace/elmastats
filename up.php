<?php include("top.php"); ?>
hi this is elmastats you can compare your times to other mans and stuff<br/><br/>
<?php
  if (isset($_SESSION["nick"])) {
?>
<b>Upload stats</b><br/>
<form enctype="multipart/form-data" action="up.php" method="post">
  <input type="hidden" name="MAX_FILE_SIZE" value="3000000"/>
  <table border="0" cellpadding="0" cellspacing="2">
    <tr>
      <td>State file (*.dat):&nbsp;</td>
      <td><input name="uploadedfile" type="file"/></td>
      <td><input type="submit" value="Upload"/></td>
    </tr>
  </table>
</form>
<?php
  $targets = loadTargets();

  if (sizeof($_POST) > 0) {
    $file = $_FILES["uploadedfile"]["name"];
    if (strlen($file) > 0) {
      echo("<br/>");
      if (endswith(basename($_FILES["uploadedfile"]["name"]), ".dat")) {
        if (filesize($_FILES["uploadedfile"]["tmp_name"]) == 67910) {
          $path = "statefilesz/" . $_SESSION["nick"] . ".dat";
          $showimp = false;
          if (file_exists($path)) {
            rename($path, "statefilesz/" . $_SESSION["nick"] . "_old.dat");
            $showimp = true;
          }
          if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $path)) {
            chmod($path, 0644);
            echo("<span style=\"color: #00CC00\">Stats uploaded! Click <a href=\"player.php?player=" . $_SESSION["nick"] . "\">here</a> to see your page.</span><br/>");
            echo("!!!ATTENTION!!! Remember to <a href=\"uprec.php\">upload your recs</a> too for extra coolness<br/>");
            if ($showimp) {
              $cTimes = stateTimes($_SESSION["nick"] . "_old", $users[$_SESSION["nick"]]["elmaname"]);
              $nTimes = stateTimes($_SESSION["nick"], $users[$_SESSION["nick"]]["elmaname"]);
              unlink("statefilesz/" . $_SESSION["nick"] . "_old.dat");
              $date = date("d/m/y - H:i:s");
              $oldtt = 0;
              $newtt = 0;
              $improvements = array();
              $deprovments = array();

              for ($x = 0;$x < 54;$x++) {
                $oldtt += (!$cTimes[$x+1][1] ? 60000 : $cTimes[$x+1][1]);
                $newtt += (!$nTimes[$x+1][1] ? 60000 : $nTimes[$x+1][1]);
                $oldTarget = target($cTimes[$x+1][1], $x+1);
                $newTarget = target($nTimes[$x+1][1], $x+1);

                $in = $x+1 . ".&nbsp; ";
                if ($x+1 > 9) $in = ($x+1) . ". ";
                $in .= $intnames[$x+1];
                for ($y = 0;$y < 20-strlen($intnames[$x+1]);$y++) $in .= "&nbsp;";
                if ($nTimes[$x+1][1] < $cTimes[$x+1][1] && $nTimes[$x+1][1] > 0 && $cTimes[$x+1][1] > 0) {

                  //Improvement
                  $improvements[] = $in . sttime("", 1, $cTimes[$x+1][1], false, false, $oldTarget) . " -> " . sttime("", 1, $nTimes[$x+1][1], false, false, $newTarget) . "<br/>";

                  //$lines = file("newtajms");
                  //$lines[] = "[" . $date . "] " .  . "\n";
                  /*if (count($lines) > 29) {
                    for ($y = 1;$y < count($lines);$y++) {
                      $lines[$y-1] = $lines[$y];
                    }
                    $lines = array_splice($lines, 30);
                  }*/
                  $in = $x+1 . ".&nbsp; ";
                  if ($x+1 > 9) $in = ($x+1) . ". ";
                  $in .= $intnames[$x+1];
                  //old
                  $fh = fopen("newtajms", "a");
                  fwrite($fh, "<tr><td>" . man($_SESSION["nick"]) . "</td><td>" . $in . "</td><td>" . formatElmaTime($cTimes[$x+1][1]) . " -> " . formatElmaTime($nTimes[$x+1][1]) . "</td><td>" . $date . "</td></tr>\n");
                  fclose($fh);
                  //new
                  $fh = fopen("newtajms2", "a");
                  fwrite($fh, $_SESSION["nick"] . "|" . ($x+1) . "|" . $cTimes[$x+1][1] . "|" . $nTimes[$x+1][1] . "|" . time() . "\n");
                  fclose($fh);

                } elseif ($nTimes[$x+1][1] > $cTimes[$x+1][1] && $nTimes[$x+1][1] > 0 && $cTimes[$x+1][1] > 0) {
                  //Deimprovement
                  $deprovments[] = $in . sttime("", 1, $cTimes[$x+1][1], false, false, $oldTarget) . " -> " . sttime("", 1, $nTimes[$x+1][1], false, false, $newTarget) . "<br/>";
                } elseif ($nTimes[$x+1][1] > 0 && $cTimes[$x+1][1] == 0) {
                  //Finished unfinished lev
                  $improvements[] = $in . sttime("", 1, $nTimes[$x+1][1], false, false, $newTarget) . "<br/>";
                } elseif ($nTimes[$x+1][1] == 0 && $cTimes[$x+1][1] > 0) {
                  //Definished finished lev
                  $deprovments[] = $in . sttime("", 1, $cTimes[$x+1][1], false, false, $oldTarget) . " -> N/A<br/>";
                }
              }

              if(count($improvements) > 0) {
                echo("<br/>");
                echo("<span style='color:green;font-size:16px;font-weight:bold;'>Improvements</span>");
                echo("<br/>");

                foreach($improvements as $imp) {
                  echo($imp);
                }
              }

              if(count($deprovments) > 0) {
                echo("<br/>");
                echo("<span style='color:red;font-size:16px;font-weight:bold;'>Deprovements</span>");
                echo("<br/>");

                foreach($deprovments as $dep) {
                  echo($dep);
                }
              }

              if ($newtt < $oldtt) {
                echo("<br/>Total time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . coloredtttime($oldtt) . " -> " . coloredtttime($newtt) . "<br/>");
                if (floor($newtt/6000) < floor($oldtt/6000)) {
                  $fh = fopen("newttz", "a");
                  fwrite($fh, $_SESSION["nick"] . "|" . floor($newtt/6000) . "|" . time() . "\n");
                  fclose($fh);
                  echo("GZ! " . floor($newtt/6000) . " club :D<br/>");
                }

                //add to history
                if (file_exists("statefileszh/" . $_SESSION["nick"])) {
                  $n = 0;
                  while (file_exists("historyz/" . $_SESSION["nick"] . "_" . $n)) $n++;
                  copy("statefileszh/" . $_SESSION["nick"], "historyz/" . $_SESSION["nick"] . "_" . $n);
                  $fh = fopen("historyz/" . $_SESSION["nick"] . "_" . $n, "a");
                  fwrite($fh, time() . "\n");
                  fclose($fh);
                }
              }
              if ($newtt > $oldtt) {
                echo("<br/>Total time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . coloredtttime($oldtt) . " -> " . coloredtttime($newtt) . "<br/>");
              }
            }
          } else {
            echo("<span style=\"color: #FF0000\">Error uploading the file!</span><br/>");
          }
        } else {
          echo("<span style=\"color: #FF0000\">File is empty! or u is upload vrong file..</span><br/>");
        }
      } else {
        echo("<span style=\"color: #FF0000\">Wrong filetype!</span><br/>");
      }
    }
  }

?>
<?php
  } else {
?>

<b>Log in</b> (or if you haven't got an acc, pls <a href="register.php">register</a>)<br/><br/>
<form enctype="multipart/form-data" action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
  <input type="hidden" name="MAX_FILE_SIZE" value="300000000000"/>
  <table border="0" cellpadding="0" cellspacing="2">
    <tr>
      <td>Nick:&nbsp;</td>
      <td><input type="text" name="nick" size="18"/></td>
    </tr>
    <tr>
      <td>Pwd:&nbsp;</td>
      <td><input type="password" name="pwd" size="18"/></td>
    </tr>
    <tr>
      <td>Remember me:&nbsp;</td>
      <td><input type="checkbox" name="remembar" value="ye"/></td>
    </tr>
    <tr><th colspan="2">&nbsp;</th></tr>
    <tr>
      <td colspan="2"><center><input type="submit" value="Log in"/></center></td>
    </tr>
  </table>
</form>
<br/>
<!--<a href="lostpwd.php">Forgot password??</a><br/>-->

<?php
    if (strlen($status) > 0) {
      echo("<br/><span style=\"color: #FF0000\">" . $status . "</span><br/>");
    }
  }
?>
<?php include("tpo.php"); ?>
