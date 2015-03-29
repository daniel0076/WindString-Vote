<?php
require_once('admin/auth/db.php');
session_start();
global $db;
$query="SELECT * FROM `candidates`";
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
    $grid.="<a class='ui top left attached teal large label'>$row[type]</a>";
    $grid.="<div class='ui video' data-source='youtube' data-id=$row[youtube_id] ></div>";
    $grid.="
<div class='ui icon message'>
    <div class='content'>
      <div class='header'>
       $row[performer] - $row[song]
      </div>
        <p>表演者：$row[performer]</p>
        <p>說明：$row[description]</p>
    </div>
</div>";
    $grid.="<div class='ui blue animated fade button'>
  <div class='visible content'>目前票數：$row[votes]</div>
  <div class='hidden content'>投我一票 </div>
</div>";
    $grid.="</div></div>";
  }
  return $grid;
}
?>


<!DOCTYPE html>
<html>
<head>
<?php
require_once('template/pre_css_js.php');
?>
<title>人氣獎投票 | 2015交大風弦盃</title>
<script >
$(document).ready(function(){
  $('.ui.video').video();
}
);
</script>
</head>
<body>
  <div id="top">
    <div class="ui icon positive message">
      <i class="inbox icon"></i>
      <div class="content">
        <div class="header">
          2015交大風弦盃 人氣獎投票
        </div>
        <p>為您喜愛的隊伍投下一票</p>
      </div>
    </div>
  </div>
<div class="ui three column grid">
<?php
echo make_grid($result);
?>
</div>
</body>
</html>
