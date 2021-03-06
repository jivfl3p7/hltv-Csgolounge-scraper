<html>
<head>
<?php
include_once "navadmin.php"; // Navbar
include_once "../connection.php"; // Connection and $url
?>

        <link href="<?php echo "//" . $url . "/css/style.css"; ?>" rel="stylesheet">
        <link href="<?php echo "//" . $url . "/bootstrap/css/bootstrap.css"; ?>" rel="stylesheet">
<title>Admin index</title>
</head>
<body>
<div class="wrap">
<h1>Matches</h1>
<div class="table table-hover" >
<?php
$sql = "SELECT * FROM Competitions
INNER JOIN
(SELECT  (SELECT n.tcountry
        FROM   Teams n 
        WHERE  m.tid_1 = n.tid) logo1,
        (SELECT n.tcountry
        FROM   Teams n 
        WHERE  m.tid_2 = n.tid) logo2,
     (SELECT n.name 
        FROM   Teams n 
        WHERE  m.tid_1 = n.tid) team_1,
        (SELECT n.name 
        FROM   Teams n 
        WHERE  m.tid_2 = n.tid) team_2,
    (SELECT n.hltvname
        FROM   Teams n 
        WHERE  m.tid_1 = n.tid) hltv1,
        (SELECT n.hltvname
        FROM   Teams n 
        WHERE  m.tid_2 = n.tid) hltv2,
        (SELECT n.cid
        FROM   Comp_belongs_to n 
        WHERE  m.subcid = n.subcid) cid
        , mid, hltv, csgl, csglodds1, csglodds2, match_time, match_date, tid_1, tid_2
FROM Matches m WHERE complete = 0) AS T
ON Competitions.cid = T.cid ORDER BY match_date asc, match_time asc, mid asc
";
$result = $conn->query($sql);




if ($result->num_rows > 0) {
    echo "<table class='table table-hover' ><thead><tr><th><b>Date</b></th>
                    <th><b>Time</b></th>
                    <th><b>BO</b></th>
                    <th class='text-right'><b>Team 1</b></th>
                    <th class='text-center'>%</th>
                    <th class='text-center'>#</th>
                    <th class='text-center'><b>-</b></th>
                    <th class='text-center'>#</th>
                    <th class='text-center'>%</th>
                    <th class='text-left'><b>Team 2</b></th>
                    <th><b>Matchpage</b></th>
                    </tr></thead>";
    while($row = $result->fetch_assoc()) {
    	$newDate   = date("Y-m.d", strtotime($row['match_date']));
      // Fetch maps, scores
      $mapsql = "SELECT name, pmapid, TT.mapid, score_1, score_2 FROM Maps
          INNER JOIN
          (SELECT T.pmapid, mapid, score_1, score_2 FROM Playedmaps 
          INNER JOIN 
          (SELECT mid, pmapid FROM Map_belongs_to WHERE mid =".$row['mid'].") AS T 
          ON Playedmaps.pmapid = T.pmapid) AS TT
          ON Maps.mapid = TT.mapid";
    $mapresult = $conn->query($mapsql);
    $bocheck = mysqli_num_rows($mapresult);

        // hltv & csgl formatting
        $hltv = "";
        if($row['hltv']){$hltv = "<a href='".$row['hltv']."' title='Hltv'><img src='//brintos.dk/csgo/imgs/hltv.png'></a>";}
        $csgl = "";
        if($row['csgl']){$csgl = "<a href='".$row['csgl']."' title='Csgolounge'><img src='//brintos.dk/csgo/imgs/csgl.png'></a>";}


        // flag and date
        $teamflag  = str_replace(' ', '', (strtolower($row["logo1"])));
        $teamflag2 = str_replace(' ', '', (strtolower($row["logo2"])));
        $newDate   = date("d.m.Y", strtotime($row['match_date']));

       // name and comp formatting
        $name1 = $row['team_1'];
        $name2 = $row['team_2'];
       if(strlen($name1) > 15){$name1 = substr($name1, 0, 12) . ".." ;}
       if(strlen($name2) > 15){$name2 = substr($name2, 0, 12) . ".." ;}
       $comp = $row['name'];
       if(strlen($comp) > 13){$comp = substr($comp, 0, 9) . ".." ;}


       // TBD handling
       if($row['tid_1'] == 108 && $row['tid_2'] == 108){$row['csglodds1'] = 0; $row['csglodds2'] = 0;}

       // Odds handling
       if($row['csglodds1'] == 0){$row['csglodds1'] = "";} else {$row['csglodds1'] = $row['csglodds1']."%";}
       if($row['csglodds2'] == 0){$row['csglodds2'] = "";} else {$row['csglodds2'] = $row['csglodds2']."%";}

       // Display handling for BO1
        $t1win = 0;
        $t2win = 0;
    while($map = $mapresult->fetch_assoc()){
      if($bocheck == 1){
        $bomap   = $map['name'];
        $t1win   = $map['score_1'];
        $t2win   = $map['score_2'];
      }

      else{
        if($map['score_1'] > $map['score_2'] && ($map['score_1'] > 15 || $map['score_1'] == 1)){$t1win++;}
        if($map['score_1'] < $map['score_2'] && ($map['score_2'] > 15 || $map['score_2'] == 1)){$t2win++;}
      }

    }   
        if($bocheck > 1){$bomap = "BO" . $bocheck;}
        else if($map["name"] != NULL OR !strcmp($map["name"], "TBA")){$bomap = "TBA";}




       // CSS formatting
       if($t1win > $t2win){$winloss = "win"; $winloss2 = "loss";}
       else if($t1win < $t2win){$winloss = "loss"; $winloss2 = "win";}
       else if($t1win != 0 && $t2win != 0){ $winloss ="draw"; $winloss2 = "draw";}
       else{$t1win = "&nbsp"; $t2win = "&nbsp"; $winloss ="draw"; $winloss2 = "draw";}
        echo "<tr><td>".$newDate."</td>
                  <td>".$row["match_time"]."</td>
                  <td>".$bomap."</td>
                  <td class='text-right'><a Title = \"". $row["team_1"] . "\" href='team/editteam.php?tid=".$row["tid_1"]."'>".$name1."</a> <img src='../flags/".$teamflag.".png' alt='".ucfirst($row["logo1"])."' title='".ucfirst($row["logo1"])."'></td>
                  <td class='text-center'>".$row['csglodds1']."</td>
                  <td class='".$winloss."'>".$t1win."</td>
                  <td class='text-center'>-</td>
                  <td class='".$winloss2."'>".$t2win."</td>
                  <td class='text-center'>".$row['csglodds2']."</td>
                  <td class='text-left'><img src='../flags/".$teamflag2.".png' alt='".ucfirst($row["logo2"])."' title='".ucfirst($row["logo2"])."'> <a Title = \"". $row["team_2"] . "\" href='team/editteam.php?tid=".$row["tid_2"]."'>".$name2."</a></td>
                  <td>".$hltv. " ". $csgl ." <a href='matches/editmatch.php?mid=".$row["mid"]."' Title = \"". $row["name"] . "\" >".$comp."</a></td></tr>";

    }
    echo "</table>";
} else {
    echo "No matches found";
}

$conn->close();
?>
</div>
</div>
</body>
</html>