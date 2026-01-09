<?php include("top.php"); ?>
<b>Some stuff about elmastats</b><br/><br/>
elmastats is a site by jon<br/>
its a statistics site for <a href="http://www.elastomania.com">elastomania</a> by balazs rozsa :D<br/>
start by kreating an acc <a href="register.php">here</a>, then upload your state.dat file <a href="up.php">here</a>!<br/>
there are a couple of advantages of using teh state.dat instead of stats.txt, such as:<br/>
&nbsp;- its encrypted, which makes it much harder to hax<br/>
&nbsp;- you dont have to quit elma for it to update (you can just alt-tab and upload when youve done a new pr/wr)<br/>
&nbsp;- i guess thats it<br/>
anyway heres some lists<br/><br/><br/>
<?php
  $targets = loadTargets();

  function parseLine2($cline) {
    $data = array();
    $data["nick"] = substr($cline, 0, strpos($cline, "|"));
    $cline = substr($cline, strpos($cline, "|")+1);
    $data["int"] = substr($cline, 0, strpos($cline, "|"));
    $cline = substr($cline, strpos($cline, "|")+1);
    $data["time1"] = substr($cline, 0, strpos($cline, "|"));   
    $cline = substr($cline, strpos($cline, "|")+1);
    $data["time2"] = substr($cline, 0, strpos($cline, "|"));
    $data["time"] = substr($cline, strpos($cline, "|")+1);
    return $data;
  }
  
  echo("<div class=\"lefty\">");
  echo("<div class=\"box2\">");
  echo("<b>Last improvements</b><br/><br/>");
  $lines = file("newtajms2");
  echo("<table>");
  echo("<tr><th width=\"160px\">Kuski</th><th width=\"32px\">Int</th><th width=\"170px\">Improvement</th><th>When</th></tr>");
  $xs = count($lines);
  if ($xs > 30) $xs = 30;
  $y=0;
  for ($x = 0;$y < $xs;$x++) {
    $data = parseLine2(ld($lines[count($lines)-1-$x]));
    if(man($data["nick"])!=$data["nick"]){
      echo("<tr>");
      echo("<td>" . man($data["nick"]) . "</td>");
      echo("<td>" . internal($data["int"], true, false, false, true) . "</td>");
      echo("<td>" . sttime($data["nick"], $data["int"], $data["time1"], false, false, target($data["time1"], $data["int"])) . 
           " -> " . sttime($data["nick"], $data["int"], $data["time2"], false, false, target($data["time2"], $data["int"])) . "</td>");
      echo("<td>" . date("d/m/y", ttime($data["time"])) . "</td>");
      echo("</tr>");
      $y++;
    }
  }
  echo("</table>");
  echo("</div></div>");
    
  function parseLine($cline) {
    $data = array();
    $data["nick"] = substr($cline, 0, strpos($cline, "|"));
    $cline = substr($cline, strpos($cline, "|")+1);
    $data["tt"] = substr($cline, 0, strpos($cline, "|"));
    $data["time"] = substr($cline, strpos($cline, "|")+1);
    return $data;
  }
  
  echo("<div class=\"lefty\">");
  echo("<div class=\"box2\">");
  echo("<b>Last TT minute breaks</b><br/><br/>");
  $lines = file("newttz");
  echo("<table>");
  echo("<tr><th width=\"160px\">Kuski</th><th width=\"32px\">TT</th><th>When</th></tr>");
  if ($lines) {
    $xs = count($lines);
    if ($xs > 30) $xs = 30;
    $y=0;
    for ($x = 0;$y < $xs;$x++) {
      $data = parseLine(ld($lines[count($lines)-1-$x]));
      if(man($data["nick"])!=$data["nick"]){
        echo("<tr>");
        echo("<td>" . man($data["nick"]) . "</td>");
        echo("<td>" . $data["tt"] . "</td>");
        echo("<td>" . date("d/m/y", ttime($data["time"])) . "</td>");
        echo("</tr>");
        $y++;
      }
    }
  }
  echo("</table>");
  echo("</div></div>");
  
  /*
  $lines = file("newtajms");
  echo("<table>");
  echo("<tr><th class=\"left\" width=\"180px\">Kuski</th><th class=\"left\" width=\"170px\">Internal</th><th class=\"left\" width=\"170px\">Improvement</th><th class=\"left\" width=\"150px\">Date</th></tr>");
  $xs = count($lines)-1-30;
  if ($xs < 0) $xz = 0;
  for ($x = count($lines)-1;$x > $xs;$x--) {
    echo(substr($lines[$x], 0, strlen($lines[$x])-1));
  }
  echo("</table>");
  */
?>
<?php include("tpo.php"); ?>