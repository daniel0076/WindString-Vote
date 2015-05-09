<?php
require_once('admin/auth/db.php');
session_start();
global $db;
$query="SELECT * FROM `candidates` ORDER BY `votes` DESC";
try{
    $dbe=$db->prepare($query);
    $dbe->execute();
    $result=$dbe->fetchAll();
}
catch(PDOException $e){
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function make_grid($result){
    $grid="";
    foreach ($result as $row) {
        $grid.='<div class="column"> <div class="ui segment">';
        if($row['type']=="演奏組"){ $grid.="<a class='ui top left attached orange large label'>$row[type]</a>";}
        else if($row['type']=="團體組"){ $grid.="<a class='ui top left attached yellow large label'>$row[type]</a>";}
        else {$grid.="<a class='ui top left attached purple large label'>$row[type]</a>";}
        $grid.="<div class='ui video' data-source='youtube' data-id=$row[youtube_id] ></div>";
        $grid.="
<div class='ui blue icon message'>
    <div class='content'>
      <div class='header'>
       $row[performer] - $row[song]
      </div>
        <p>表演者：$row[performer]</p>
        <p>說明：$row[description]</p>
    </div>
</div>";
        $grid.="
    <div class='ui green animated fade button' onclick=vote($row[cid])>
      <div class='visible content'>目前票數：$row[votes]</div>
      <div class='hidden content'>投我一票 </div>
    </div>
";
        $grid.="</div></div>";
    }
    return $grid;
};
function make_add() {
    $grid='<div class="column"> <div class="ui segment" style="text-align:center">';
    $grid.="
    <div class='ui red button'>
      增加選項
    </div>
";
    $grid.="</div></div>";
    return $grid;
}
?>


<!DOCTYPE html>
<html>
<head>
 <meta charset="UTF-8">
<?php
require_once('template/pre_css_js.php');
?>
<link rel="stylesheet" href="css/index.css"/>
<title>人氣獎投票 | 2015交大風弦盃</title>
<script >
$(document).ready(function(){
    $('.ui.video').video();
}
);
function vote(cid) {
    alert("投票結束了");
}
</script>
</head>
<body>
<div class='content'>
  <div class="top">
        <img src="static/top.png" alt="Top Img">
  </div>
    <div class="ui blue icon message" style="margin:auto;text-align:center;">
      <i class="facebook square icon" ></i>
      <div class="content">
        <div class="header">
          風弦盃預賽有很多很棒的表演，我們要選出最棒的人氣獎
        </div>
        <p>把票投給您最喜歡的表演。按點投票按紐，進行Facebook驗證，就完成囉。而且，這不會在您的塗鴨牆上發表貼文，是不是很貼心</p>
      </div>
    </div>

<div class="candidates">
  <div class="ui three column grid">
<?php
echo make_grid($result);
?>
  </div>
</div>
<div id="footer">
    <div class="ui divider"></div>
    <div class="sponsors">
        <h2 class="ui header">贊助●Sponsors</h2>
        <img class="ui small image vendor" src="images/vendor/1.jpg" alt="vendor pic">
        <img class="ui small image vendor" src="images/vendor/2.jpg" alt="vendor pic">
        <img class="ui medium image vendor" src="images/vendor/3.jpg" alt="vendor pic">
        <img class="ui small image vendor" src="images/vendor/4.jpg" alt="vendor pic">
        <img class="ui medium image vendor" src="images/vendor/5.jpg" alt="vendor pic">
        <img class="ui image vendor" src="images/vendor/6.jpg" alt="vendor pic">
        <img class="ui image vendor" src="images/vendor/7.jpg" alt="vendor pic">
    </div>
</div>
</div>
</body>
</html>
