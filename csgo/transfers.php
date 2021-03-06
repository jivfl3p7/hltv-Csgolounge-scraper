<html>
<head>
<?php
error_reporting(0);
include_once "navbar.php";
include_once "connection.php";
?>
<title>Transfers</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<div class="wrap">
<div>
<h1 style='float:left'><a href="players.php">Players</a></h1>
<h1 style='text-align: right'>Transfers</h1>
</div>
<?php
$num_rec_per_page=200;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $num_rec_per_page; 

$sql = "SELECT Players.pid, ign, country, tid1, tid2, namex, name, tcountryx, tcountry, transdate FROM Players
INNER JOIN
(SELECT pid, tid1, tid2, namex, name, tcountryx, tcountry, transdate FROM Teams
INNER JOIN
(SELECT pid, tid AS tidx, tid1, tid2, name as namex, tcountry as tcountryx, transdate FROM Teams
INNER JOIN
(SELECT pid, tid1, tid2, transdate FROM Playertransfers) AS T
ON Teams.tid = T.tid1) AS TT
ON Teams.tid = TT.tid2) AS TTT
ON Players.pid = TTT.pid ORDER BY transdate desc, name asc, namex asc LIMIT $start_from, $num_rec_per_page";
$result = $conn->query($sql);


$sql2 = "SELECT * FROM Playertransfers"; 
$result2 = $conn->query($sql2); //run the query
$total_records = mysqli_num_rows($result2);  //count number of records
$total_pages = ceil($total_records / $num_rec_per_page); // count number of pages
$nextpage = $page+1;
$prevpage = $page-1;
echo "<div style='text-align: center; width: 100%;'>";

  // Goto 1st page  
    if($page == 1){
        echo "
        <div style='float: left'>
        <a id='unavbtn' href='#'>First page</a>
        </div>";
    }
    else {
        echo "
        <div style='float: left'>
        <a id='btn' class='btn' href='players.php?page=1'>First page</a>
        </div> ";  
    }
    echo "<div style='margin: 0 auto; display: inline-block;'>";

    // Previous page
    if($page == 1){
        echo "<a id='unavbtn' href='#'>Previous page</a>";
    }

    else {
      echo "<a id='btn' href='players.php?page=".$prevpage."'>Previous page</a>";
    }
    echo " ";

    // Next page
    if($page == $total_pages)
    {
      echo "<a id='unavbtn' href='#'>Next page</a>";
    }
    else{
        echo "<a id='btn' href='players.php?page=".$nextpage."'>Next page</a>";
    }
    echo "<br/>Page ".$page." of ".$total_pages."</div>";

  // Goto last page 
    if($page == $total_pages)
    {
        echo "
        <div style='float: right;'>
        <a id='unavbtn' href='#'>Last page</a>
        </div>";
    }
    else {
        echo "
        <div style='float: right;'>
        <a id='btn' class='btn' href='players.php?page=".$total_pages."'>Last page</a>
        </div>";
    }

echo "</div>";  
if ($result->num_rows > 0) {

    echo "
    <div class='table table-hover'>
    <table class='table table-hover'>
    <thead>
    <tr>
        <td ><b>Date</b></td>
        <td ><b>Playername</b></td>
        <td ><b>Team1</b></td>
        <td ><b></b></td>
        <td ><b>Team2</b></td>
    </tr></thead>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $flag = str_replace(' ', '', (strtolower($row["country"])));
        $teamflag = str_replace(' ', '', (strtolower($row["tcountry"])));
        $teamflag2 = str_replace(' ', '', (strtolower($row["tcountryx"])));
        $newDate = date("d.m.Y", strtotime($row["transdate"]));
        echo "<tr>
        <td><b>".$newDate."</b></td>
        <td><img src='flags/".$flag.".png''> <a href='playerid.php?pid=".$row["pid"]."'>".$row["ign"]."</td></a>
        <td><img src='flags/".$teamflag2.".png''> <a href='teamid.php?tid=".$row["tid1"]."'>".$row["namex"]."</a></td>
        <td>=></td>
        <td><img src='flags/".$teamflag.".png''> <a href='teamid.php?tid=".$row["tid2"]."'>".$row["name"]."</a></td>
        </tr>";}
    echo "</table>";
} else {
    echo "0 results";
}

echo "<div style='text-align: center; width: 100%;'>";

  // Goto 1st page  
    if($page == 1){
        echo "
        <div style='float: left'>
        <a id='unavbtn' href='#'>First page</a>
        </div>";
    }
    else {
        echo "
        <div style='float: left'>
        <a id='btn' class='btn' href='players.php?page=1'>First page</a>
        </div> ";  
    }
    echo "<div style='margin: 0 auto; display: inline-block;'>";

    // Previous page
    if($page == 1){
        echo "<a id='unavbtn' href='#'>Previous page</a>";
    }

    else {
      echo "<a id='btn' href='players.php?page=".$prevpage."'>Previous page</a>";
    }
    echo " ";

    // Next page
    if($page == $total_pages)
    {
      echo "<a id='unavbtn' href='#'>Next page</a>";
    }
    else{
        echo "<a id='btn' href='players.php?page=".$nextpage."'>Next page</a>";
    }
    echo "<br/>Page ".$page." of ".$total_pages."</div>";

  // Goto last page 
    if($page == $total_pages)
    {
        echo "
        <div style='float: right;'>
        <a id='unavbtn' href='#'>Last page</a>
        </div>";
    }
    else {
        echo "
        <div style='float: right;'>
        <a id='btn' class='btn' href='players.php?page=".$total_pages."'>Last page</a>
        </div>";
    }

echo "</div>"; 
$conn->close();
?>
</div>
</div>
<?php include_once("footer.php"); ?>
</body>
</html>