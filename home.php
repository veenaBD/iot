<?php  
// send the command to NodeMCU - /change.php?id=NM2&pin=16&toggle=0
/*session_start();
require_once 'class.user.php';
$user_home = new USER();

if(!$user_home->is_logged_in())
{
 $user_home->redirect('index.php');
}

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$tempID=$row['userEmail'];
$userId=$row['userEmail'];
$username=$row['userName']
$filename=hash('sha256',strrev(substr($tempID,0,strpos($tempID,'@'))).$row['userPass']).".json";
$json_file = fopen($filename, "w");
*/
$username="veena";
$userId=15;
$filename='8bb96db453a58019d25531f97e9d34a5aa2f04d8b9b77ef9ef8116c363f85a31.json';
//$filename='sample.json';
date_default_timezone_set("Asia/Kolkata");
$renew=0;   
if(isset($_POST['id']) && isset($_POST['pin']) && isset($_POST['toggle'])){
  $deviceId = $_POST['id'];
  $gpioPin = $_POST['pin'];
  $toggle = $_POST['toggle'];
 // echo "Device Id = ".$deviceId." & pin = ".$gpioPin." is = ".$toggle;
$json_file = file_get_contents($filename);
$stats = json_decode($json_file);
$hits= $stats->count;
if($hits<2000){
$hits++;
$stats->count=$hits;
foreach ($stats->status as $i => $stat) {
	if(substr($stat->id,0,3) == $deviceId){
		$n='pin'.$gpioPin;
		//echo $stat->$n.strlen($stat->$n);
		$stat->$n=$toggle.substr($stat->$n,1,strlen($stat->$n)-1);
	}
}

$json = json_encode($stats, JSON_PRETTY_PRINT);
file_put_contents($filename, $json); 
}
else{
	$renew=1;
	echo "<script> alert('You Have Reahed Max Free Call/Month Limit');</script>";
}

}

if(isset($_POST['roomicon'])){
	$chng=$_POST['roomicon'];
	$chng.=",";
$chngcomma=substr_count($chng,",");
$json_file = file_get_contents($filename);//1|-20X-40,2|-12X10
$stats = json_decode($json_file);
foreach ($stats->status as $i => $stat) {
	//for($k=0,$currentpos=0;$k<$chngcomma;$k++){
	$pos = substr($chng,0,strpos($chng, ","));
	$posno=substr($pos,0,strpos($pos, "|"));
	if($i==$posno){	//$stat->id=substr($stat->id,0,strpos($stat->id,'|',0)+1).$roomnm;
	//substr($pos,strpos($pos, "-")+1,1)
	//$stat->$p16=substr($stat->$p16,0,2).substr($pos,strpos($pos, "-")+2,1);
	$stat->id=substr($stat->id,0,4).str_replace('X',',',substr($pos,strpos($pos, "|")+1)).substr($stat->id,strpos($stat->id,'|',0));
	//echo substr($stat->id,0,4).str_replace('X',',',substr($pos,strpos($pos, "|")+1)).substr($stat->id,strpos($stat->id,'|',0));
	$currentpos=strpos($chng, ",")+1;
	$chng=substr($chng,$currentpos,strlen($chng)-$currentpos);
	//}
  }
}
$json = json_encode($stats, JSON_PRETTY_PRINT);
file_put_contents($filename, $json); 
}

if(isset($_POST['roomname'])){
	$chng=$_POST['roomname'];
	$chng.=",";
$chngcomma=substr_count($chng,",");
$json_file = file_get_contents($filename);//1|Bed Room,2|Kitchen
$stats = json_decode($json_file);
foreach ($stats->status as $i => $stat) {
	//for($k=0,$currentpos=0;$k<$chngcomma;$k++){
	$pos = substr($chng,0,strpos($chng, ","));
	$posno=substr($pos,0,strpos($pos, "|"));
	if($i==$posno){	//$stat->id=substr($stat->id,0,strpos($stat->id,'|',0)+1).$roomnm;
	//substr($pos,strpos($pos, "-")+1,1)
	//$stat->$p16=substr($stat->$p16,0,2).substr($pos,strpos($pos, "-")+2,1);
	$stat->id=substr($stat->id,0,strpos($stat->id,'|')+1).substr($pos,strpos($pos, "|")+1);
	//echo substr($stat->id,0,strpos($stat->id,'|')+1).substr($pos,strpos($pos, "|")+1);
	//substr($stat->id,0,4).str_replace('X',',',substr($pos,strpos($pos, "|")+1)).substr($stat->id,strpos($stat->id,'|',0));
	$currentpos=strpos($chng, ",")+1;
	$chng=substr($chng,$currentpos,strlen($chng)-$currentpos);
}
}
$json = json_encode($stats, JSON_PRETTY_PRINT);
file_put_contents($filename, $json); 
}
if(isset($_POST['settings'])){
$chng=$_POST['settings'];//1-1A,1-4C,2-3S
$chng.=",";
$chngcomma=substr_count($chng,",");//echo $chng." => ".$chngcomma ."<br>";
$json_file = file_get_contents($filename);//
$stats = json_decode($json_file);

foreach ($stats->status as $i => $stat) {	//$rnm='roomname'.$i;
		
for($k=0,$currentpos=0;$k<$chngcomma;$k++){//echo "<br>".$currentpos." next= ".$chng;
	$pos = substr($chng,0,strpos($chng, ","));//echo "<br>val= ".$pos;
/*if(strpos($pos, "|")>0)
	$posno=substr($pos,0,strpos($pos, "|"));

if(strpos($pos, "|")>0)*/
$posno=substr($pos,0,strpos($pos, "-"));
//echo " i= ".$i." position " .$posno;
if($i==$posno){
	//echo " >>".$pos." sss= ".substr($pos,strpos($pos, "-")+1,1);
	switch (substr($pos,strpos($pos, "-")+1,1)){
		case 1:{
			 $p16= "pin16";
			// echo " ".$p16." : ".substr($stat->$p16,0,2).substr($pos,strpos($pos, "-")+2,1);
			 $stat->$p16=substr($stat->$p16,0,2).substr($pos,strpos($pos, "-")+2,1);
		break;
		}
		case 2:{
			 $p5= "pin05";
			// echo " ".$p5." : ".substr($stat->$p5,0,2).substr($pos,strpos($pos, "-")+2,1);
			$stat->$p5=substr($stat->$p5,0,2).substr($pos,strpos($pos, "-")+2,1);
		break;
		}
		case 3:{
			 $p4= "pin04";
			// echo " ".$p4." : ".substr($stat->$p4,0,2).substr($pos,strpos($pos, "-")+2,1);
			$stat->$p4=substr($stat->$p4,0,2).substr($pos,strpos($pos, "-")+2,1);
		break;
		}
		case 4:{
			 $p0= "pin00";
			// echo " ".$p0." : ".substr($stat->$p0,0,2).substr($pos,strpos($pos, "-")+2,1);
			$stat->$p0=substr($stat->$p0,0,2).substr($pos,strpos($pos, "-")+2,1);
		break;
		}
		default:{
			// echo " null ";
		break;
		}
	}
$currentpos=strpos($chng, ",")+1;
$chng=substr($chng,$currentpos,strlen($chng)-$currentpos);
}
}
//$chng=substr(lastIndexOf(2),3);
//lastIndexOf("/")+1,nm.lastIndexOf("-on.pn"
//alert($chng);
}
$json = json_encode($stats, JSON_PRETTY_PRINT);
file_put_contents($filename, $json); 
//echo $json;
}
  
$json_file = file_get_contents($filename);//
$stats = json_decode($json_file);

foreach ($stats->status as $i => $stat) {
//$id1="device-id";
//echo substr($stat->$id1,4,strlen($stat->$id1)-4);
$ids[$i]=substr($stat->id,0,3);
$room[$i]=substr($stat->id,strpos($stat->id,'|',0)+1);//6,strlen($stat->id)-4);

//echo "<br> ".$room[$i]." is ".$ids[$i];
// write here
$prev=$stat->lastseen;//"00:05:00"; //5 minutes
/* echo strtotime($prev);
echo "<br>".$prev;
echo "<br>".date('D d-m-Y h:i:s A');
echo "<br>".chr(date_diff( date_create($prev), date_create(date('D d-m-Y h:i:s A'))));
$tooltip="";
update($prev,$i,$room[$i]); */

$p16="pin16";
$pin16s[$i]=substr($stat->$p16,0,1);

$p5="pin05";
$pin5s[$i]=substr($stat->$p5,0,1);

$p4="pin04";
$pin4s[$i]=substr($stat->$p4,0,1);

$p0="pin00";
$pin0s[$i]=substr($stat->$p0,0,1);

//echo " pin16 = ".$pin16s[$i]." pin5 = ".$pin5s[$i]." pin4 = ".$pin4s[$i]." pin0 = ".$pin0s[$i];

//dynamic web page starts here
	
$dialer[$i]="
<div  id='dialer".$i."' class='selector open ".(isset($_POST['id'])?(($ids[$i] !=  $deviceId)?"togglehide":""): "togglehide")."' style='top:55%; left:65%;'>
  <ul>
    <li>
      <input id='c1' type='checkbox'>
      <label for='c1'>
	  <form action='home.php' method='post'>
<p><input type='text' name='id' style='display:none;' value='".$ids[$i]."'/></p>
<p><input type='text' name='pin' style='display:none;' value='16'/></p>
<p><input type='text' name='toggle' style='display:none;' value='".($pin16s[$i]?0:1)."'/></p>
 <button type='submit' style='display: block;margin-top: -10px;color: transparent;border: none;'>
 <img class='dialimage' id='myImage1' src='img/".($pin16s[$i]?"on/":"off/").substr($stat->$p16,2,1).".png'>16</button>
</form>
</label>
    </li>
    
    <li>
      <input id='c3' type='checkbox'>
      <label for='c3'><form action='home.php' method='post'>
<p><input type='text' name='id' style='display:none;' value='".$ids[$i]."'/></p>
<p><input type='text' name='pin' style='display:none;' value='05'/></p>
<p><input type='text' name='toggle' style='display:none;' value='".($pin5s[$i]?0:1)."'/></p>
 <button type='submit' style='display: block;margin-top: -10px;color: transparent;border: none;'>
 <img class='dialimage' id='myImage1' src='img/".($pin5s[$i]?"on/":"off/").substr($stat->$p5,2,1).".png'>5</button>
</form></label>
    </li>
    
    <li>
      <input id='c5' type='checkbox'>
      <label for='c5'><form action='home.php' method='post'>
<p><input type='text' name='id' style='display:none;' value='".$ids[$i]."'/></p>
<p><input type='text' name='pin' style='display:none;' value='04'/></p>
<p><input type='text' name='toggle' style='display:none;' value='".($pin4s[$i]?0:1)."'/></p>
 <button type='submit' style='display: block;margin-top: -10px;color: transparent;border: none;'>
 <img class='dialimage' id='myImage1' src='img/".($pin4s[$i]?"on/":"off/").substr($stat->$p4,2,1).".png'>4</button>
</form></label>
    </li>
    
    <li>
      <input id='c7' type='checkbox'>
      <label for='c7'><form action='home.php' method='post'>
<p><input type='text' name='id' style='display:none;' value='".$ids[$i]."'/></p>
<p><input type='text' name='pin' style='display:none;' value='00'/></p>
<p><input type='text' name='toggle' style='display:none;' value='".($pin0s[$i]?0:1)."'/></p>
 <button type='submit' style='display: block;margin-top: -10px;color: transparent;border: none;'>
 <img class='dialimage' id='myImage1' src='img/".($pin0s[$i]?"on/":"off/").substr($stat->$p0,2,1).".png'>0</button>
</form></label>
    </li>
    
  </ul>
  <button><img src='img/mob3.png'  style='border-radius:35%;'></button>
</div>
";
//btns will show the total no of devices at the top of the page
$btns[$i]="
<div  id='show".$i."' class='desc' data-toggle='tooltip' data-placement='bottom' data-original-title='". showtooltip($prev)."' style='top:10%; left:".(18*($i+1))."%;'>
           <div class='item rotator'>
                <img src='img/image".($i+2).".png'>
                <div class='item_content'>
                    <h2>".$room[$i]."</h2><!-- $room[$i] will give us the romm name wher the device is installed -->
                </div>
            </div>
</div>
"; 

$display[$i]="
   <div class='togglehide popup' id='room".$i."'>
   <div id='msghead'>".$room[$i]." is Offline</div>		   
   <div id='msgbody'>last seen active on <div id='prev".$i."'>".$prev."</div></div>
   </div>
   ";
 // pop up menu ^^^^  
	 
//Dynamic Scripts for show and hide the dialers
    $scrpt[$i]="
    $('#show".$i."').click(function(){//alert('from php code');
		updateall(function(result){
			$('#prev".$i."').text(' -> '+ result[".$i."].substring(0,28)+':)'); 
			var prev=Math.round((new Date()- new Date(result[".$i."].substring(0,28)))/1000);";
	$size=count($stats->status);
	for($j=0;$j<$size;$j++){
		if($i!=$j){
			$scrpt[$i]=$scrpt[$i]."
			$('#dialer" .$j."').addClass('togglehide');
			$('#dialer" .$j."').removeClass('open');";
		}else{
			$scrpt[$i]=$scrpt[$i]."
			checkshow(".$i.",prev);";
		}
	}
	$scrpt[$i]=$scrpt[$i]."
		});
	});	";
	for($j=1;$j<=4;$j++){
		$scrpt[$i]=$scrpt[$i]."
		$('#icon-btn".$j.$i."').click(function(){
			var flag=0;
			if($('#iconlist".$j.$i."').hasClass('togglehide'))flag=1;
			$('.dropdown-content').addClass('togglehide');			
			if(flag)$('#iconlist".$j.$i."').toggleClass('togglehide');			
		});
		$('.dropdown-content img').click(function(){
		var icn=$(this).attr('src');
		var iconbtn='#icon-btn'+($(this).parent().attr('id').substring(8)) + ' img';
		$(iconbtn).attr('src',icn);
		$('.dropdown-content').addClass('togglehide');
	});";
		
	}

$forms[$i]="
<!--	<h3 style='padding: 0px 10px;left:13px;position: relative;display: inline-block;'>Room Name:</h3>-->
	
<input type='text' id='changeroom".$i."' name='roomname".$i."' placeholder='".$room[$i]."' style='border: 2px solid #ccc;padding: 6px;margin:10px 0px 10px 18px;width:210px;' >
<div class='imagess' style='padding-top:3%;'>
	<div id='roomimg".$i."' class='icon_butn' alt='0,0'></div>
</div>

	<div class='icon-btn'>
<div class='dropbtn' id='icon-btn1".$i."' style='right:147px;'><img src='img/on/".substr($stat->$p16,2).".png' alt='".substr($stat->$p16,2)."'></div>
<div id='iconlist1".$i."' class='dropdown-content togglehide' style='right:135px;' >
    <img src='img/on/A.png'>
	<img src='img/on/B.png'>
	<img src='img/on/C.png'>
	<img src='img/on/D.png'>
	<img src='img/on/E.png'>
	</div>
	<div class='icon-btn'>
<div class='dropbtn' id='icon-btn2".$i."' style='right:79px;'><img src='img/on/".substr($stat->$p5,2).".png' alt='".substr($stat->$p5,2)."'></div>
<div id='iconlist2".$i."' class='dropdown-content togglehide' style='right:50px;' >
    <img src='img/on/A.png'>
	<img src='img/on/B.png'>
	<img src='img/on/C.png'>
	<img src='img/on/D.png'>
	<img src='img/on/E.png'>
	</div>
	<div class='icon-btn'>
<div class='dropbtn' id='icon-btn3".$i."' style='right:9px;'><img src='img/on/".substr($stat->$p4,2).".png' alt='".substr($stat->$p4,2)."'></div>
<div id='iconlist3".$i."' class='dropdown-content togglehide' style='right:0px;' >
   <img src='img/on/A.png'>
	<img src='img/on/B.png'>
	<img src='img/on/C.png'>
	<img src='img/on/D.png'>
	<img src='img/on/E.png'>
	</div>
	<div class='icon-btn'>
<div class='dropbtn' id='icon-btn4".$i."' style='left:3px;'><img src='img/on/".substr($stat->$p0,2).".png' alt='".substr($stat->$p0,2)."'></div>
<div id='iconlist4".$i."' class='dropdown-content togglehide' style='right:-50px;' >
    <img src='img/on/A.png'>
	<img src='img/on/B.png'>
	<img src='img/on/C.png'>
	<img src='img/on/D.png'>
	<img src='img/on/E.png'>
	</div></div></div></div></div>
";
for($j=1;$j<=4;$j++){
	$scrpt[$i]=$scrpt[$i]."
	$('#roomimg".$i."').click(function(){
		$('#gallery').attr('alt','#roomimg".$i."')
		$('#gallery').show();
	});
	";

}
//end of dynamic web page
}


function showtooltip($prev) {

$interval=date_diff(date_create(date('D d-m-Y h:i:s A')),date_create($prev));
//echo "<br> diifrence is : ". $interval->y * 8760 + $interval->m * 730 + $interval->d *24;
$flag=1;
$tooltip= "last seen ";
//echo getTimestamp(date_diff($prev->getTimestamp(), )));
if($interval->y) 
{
 if($interval->y==1){
		 $tooltip=$tooltip. " last year";
		$flag=0;
	}
	else
		 $tooltip=$tooltip. $interval->y." years ";
}
elseif($interval->m) 
{
 if($interval->m==1){
		  $tooltip=$tooltip. " last month";
		$flag=0;
	}
	else
		 $tooltip=$tooltip. $interval->m." months ";
}
elseif($interval->d) 
{
	if($interval->d==1){
		  $tooltip=$tooltip. " yesterday ";
		$flag=0;
	}
	else
		 $tooltip=$tooltip. $interval->d." days ";
}
elseif($interval->h) 
{
  $tooltip=$tooltip. $interval->h." hours ";
}
elseif($interval->i) 
{
  $tooltip=$tooltip. $interval->i." minutes ";
}
elseif($interval->s) 
{
  $tooltip=$tooltip. $interval->s." seconds ";
}
//else{echo "<br>Hello";}

If($flag)
 $tooltip=$tooltip. "ago";

//echo  "<br>".$tooltip;
//echo "device is offline";// ahve a back up of tis code//ok
return $tooltip;
}
// ________________

/* function checktime($prev){   
$t1=date_timestamp_get(date_create(date('D M d, Y h:i:s A')))-date_timestamp_get(date_create($prev));
//$t3=$t1-$t2 ;
    //echo "<br>".  $t1;
   
 return $t1;
}
 */
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>Welcome Home <?php echo $username;?></title>
  <!--   <META HTTP-EQUIV="refresh" CONTENT="120">
 <php
      if(reload==0){
      header("re=11");
   }
   ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https:////maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> -->
<link href="style1.css" rel="stylesheet">
<!--<link href="style2.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css">-->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="http://quablu.in/QuasarEnterprises.png" rel="icon" type="image/x-icon" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
<style>
.dialimage{
	width:65px;
}
.icon_butn{
	background:url("img/images.jpg") center top no-repeat scroll;
	width: 84px;
height: 84px;
display: inline-block;
}

#gallery{
	width:384px;
	height:192px;
}
.btn-group-lg > .btn, .btn-lg {
font-size:10px;
}
.togglehide{
	display:none;
}
.alertbox{
	height:100%;
	width:100%;
	background-color:rgba(0, 0, 0, 0.4);
	position:absolute;
	-moz-transition: opacity 1s ease-in-out;
}
.popup{
	left: 25%;
	top:25%;
position: absolute;
border-radius: 18px;
border: 12px solid #000000;
box-shadow: 50px 49px 28px -14px rgba(0,0,0,0.66);
}
#msghead{
	background-color: red;
	color:white;
	padding:5px;
	text-align:center;
}
#msgbody{
	background-color: white;
	padding:20px;
	text-align:center;
}

/*.settings_btn img {
position:absolute;
right:3%;
top:3%;
}
.logout_btn img {
	position:absolute;
	right:11%;
	top:2%;
}*/
.settings_panel {
    position:absolute;
	left: 40%;
	top: 25%;
	background-color: #f9f9f9;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	width:25%;
}
.icongallery{
	display: none;
	position:absolute;
	left: 31%;
	top: 23%;
	background-color: #f9f9f9;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}
.icongallery img{
	color: black;
    text-decoration: none;
    display: block;
}
/*.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}*/

.dropdown a:hover {background-color: #f1f1f1}

.show {display:block;}

.button {
    background-color:skyblue;
    border: none;
    color: white;
    padding: 5px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 0px 2px;
    cursor: pointer;
	position:absolute;
	left:93%;
	top:5%;
}
.image	1{
	width:30%;
	height:5%;
	margin-top:4%;
}
#arrow{
	display: inline-block;
position: absolute;
padding: 16px 0px;
}

.dropbtn {
	color: white;
    padding: 2px;
    font-size: 16px;
    border: none;
    cursor: pointer;
	background: -webkit-linear-gradient(top, #4c4e5a 0%,#2c2d33 100%);
    background: -moz-linear-gradient(top, #4c4e5a 0%,#2c2d33 100%);
    background: -o-linear-gradient(top, #4c4e5a 0%,#2c2d33 100%);
    background: -ms-linear-gradient(top, #4c4e5a 0%,#2c2d33 100%);
    background: linear-gradient(top, #4c4e5a 0%,#2c2d33 100%);
 
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
	width: 40px;
	height: 40px;
	display: inline-block;
	margin: 0px -5px;
	top:50px;
}
.dropbtn img {
	width: 35px;
	height:35px;
}
.dropbtn:hover, .dropbtn:focus {
    background-color: #3e8e41;
}

.icon-btn {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    position: absolute;
	top:40px;
	width:40px;
    background-color: #f9f9f9;
    overflow: auto;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	z-index: 99;
	left:18px;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
	
}
.dropdown-content img{
	width:40px;
	height:40px;
}

/* code written by VEda*/
.navbar-inverse{
 	width:100%;		
	z-index:99;
 }
 	.container-fluid{
 		background-color: #00004D;
 		color:white;
 	}

.menubar {
    /*display: inline-block;*/
    cursor: pointer;
    position: absolute;
    left:95%;
    top:1.5%;

}

.bar1, .bar2, .bar3 {
    width: 35px;
    height: 5px;
    background-color:white;
    margin: 6px 0;
    transition: 0.4s;
}

.change .bar1 {
    -webkit-transform: rotate(-20deg) translate(-5px, 8px) ;
    transform: rotate(-36deg) translate(-9px, 7px);
}

.change .bar2 {opacity: 0;}

.change .bar3 {
    -webkit-transform: rotate(25deg) translate(-18px, -8px) ;
    transform: rotate(36deg) translate(-9px, -8px);
}

.change + #menu {
  opacity: 1;
  visibility: visible;
}

/* menu appearance*/
#menu {
  position: fixed;
  color: #999;
  width: 100px;
  top:10%;
  left:92%;
  padding: 10px;
  margin: auto;
  font-family: "Segoe UI", Candara, "Bitstream Vera Sans", "DejaVu Sans", "Bitstream Vera Sans", "Trebuchet MS", Verdana, "Verdana Ref", sans-serif;
  text-align: center;
  border-radius: 4px;
  background: white;
  box-shadow: 0 1px 8px rgba(0,0,0,0.05);
  /* just for this demo */
  opacity: 0;
  visibility: hidden;
  transition: opacity .4s;
}
#menu:after {
  position: absolute;
  top: -20px;
  left: 35px;
  content: "";
  display: block;
  border-left: 15px solid transparent;
  border-right: 15px solid transparent;
  border-bottom: 20px solid white;
}
ul, li, li a {
  list-style: none;
  display: block;
  margin: 0;
  padding: 0;
}
li a {
  padding: 5px;
  color: #888;
  text-decoration: none;
  transition: all .2s;
}
#settings_btn:hover,
#settings_btn:focus {
  background: #1ABC9C;
  color: #fff;
}

#logout_btn:hover,
#logout_btn:focus {
  background: #1ABC9C;
  color: #fff;
}
p, p a { font-size: 12px;text-align: center; color: #888; }

@media screen and (min-width: 360px) and (orientation: portrait) and (min-height: 640px){
	/*header start*/
.menubar {
    /*display: inline-block;*/
    cursor: pointer;
    position: absolute;
    left:86%;
    top:25%;

}

.bar1, .bar2, .bar3 {
    width: 40px;
    height: 5px;
    background-color: white;
    margin: 6px 0;
    transition: 0.4s;
}

.change .bar1 {
    -webkit-transform: rotate(-37deg) translate(-5px, 8px) ;
    transform: rotate(-37deg) translate(-30px, 6px) ;
}

.change .bar2 {opacity: 0;}

.change .bar3 {
    -webkit-transform: rotate(37deg) translate(-8px, -8px) ;
    transform: rotate(37deg) translate(-38px, -8px) ;
}

.change + #menu {
  opacity: 1;
  visibility: visible;
}


	.navbar navbar-inverse{
 	width:100%;
 }
.container-fluid{
	width:101.6%;
	height: 150px;
	/*position: absolute;*/
    white-space: nowrap;
    overflow: hidden;
}
.navbar-brand{
	color:white;
	font-size: 30px;
	margin-top:40px;
}
#menu{
		position: absolute;
		width:250px;
  		right:-2%;
  		top: 50%;
		height:250px;
		margin-bottom: 10.5em;
  		color: #999;
  		padding: 10px;
  		margin: auto;
  		font-family: "Segoe UI", Candara, "Bitstream Vera Sans", "DejaVu Sans", "Bitstream Vera Sans", "Trebuchet MS", Verdana, "Verdana Ref", sans-serif;
  		text-align: center;
  		border-radius: 4px;
  		background: white;
 		box-shadow: 0 1px 8px rgba(0,0,0,0.05);
  /* just for this demo */
  opacity: 0;
  visibility: hidden;
  transition: opacity .4s;

		
	}
	#menu img{
		width:47px;
		margin-top:10px;
	}
	#menu h3{
		font-size: 45px;
		margin-top:10px;
	}
	#menu:after {
  position: absolute;
  top: -20px;
  left: 107px;
  content: "";
  display: block;
  border-left: 15px solid transparent;
  border-right: 15px solid transparent;
  border-bottom: 20px solid white;
}

/*menu end*/

.popup{
	left: 20%;
	top:30%;
	width:60%;
	height: 50%;
position: absolute;
/*border-radius: 10px;
border: 10px solid #000000;
box-shadow: 50px 49px 28px -14px rgba(0,0,0,0.66);*/

}

}
/* done by Veda*/

/* anjana added these*/

body {
background-color:skyblue;
/*margin-top:30px;	*/
}


#savebtn {
display: block;
    color: white;
    border: 2px;
    font-size: 15px;
    position: absolute;
    left: 15px;
 
}
#cancelbtn {
display: block;
color: white;
border: 2px;
position:absolute;
right:10px;
font-size:15px;
}
.block {
	margin:20px 0px 60px 0px;
}

.roombtn {
	
	position: absolute;
    width: 100%;
    	
}
.icon-btn {
    position: relative;
    display: inline-block;
    padding-left: 23px;
	margin-bottom:3px;
}
.imagess {
float: right;
margin-right: 54px;
width:32px;
}

@media only screen and (max-width: 360px) {
	.navbar-brand{
		color:white;
		font-size:25px;
		margin-top:2px;
	}
	.menubar {
	   /* display: inline-block; */
	    cursor: pointer;
	    position: absolute;
	    left: 86%;
	    top: 10%;
	}
	.container-fluid {
		height:50px;
	}
	.dropdown-content {
	left: 17px;
	top:45px;
}
	.item {
		width: 50px;
    height: 50px;
    top: 85px;
	left: 15px;
	}
	.item img {
		width:40px;
	}

#savebtn { 
font-size:18px;
/*left: -40%;*/
}

#cancelbtn {
font-size:18px;	
}
.block {
	/*margin-top: 136px;
    margin-bottom: 10px;*/
}

.settings_panel {
    position: absolute;
    width: 90%;
    height:65%;
	left:5%;
    background-color: #f9f9f9;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	
}

.h2, h2 {
font-size: 20px;	
margin-top:10px;
}

.dropbtn {
    color: white;
    padding: 2px;
    font-size: 16px;
    width: 42px;
    height: 45px;
    display: inline-block;
    /* margin: 0px 5px; */
    /* top: 69px; */
    margin-left: 16px;
    margin-top: 0px;
	margin-bottom:12px;
}

.dropbtn img {
    width: 40px;
    height: 40px;
}

.icon-btn {
    padding-left: 0px;
}
#menu {
		top: 8%;
		left: 72%;		
	}
.navbar-inverse{
	position:fixed;
}
.imagess {
margin: 5px 60px 5px 0px;
}
}

@media screen and (max-width: 640px) and (orientation : landscape) {
	
	.settings_panel {
		left: 28%;
		top: 55%;
		width: 60%;
		height:110%;
		
	}
	.h2, h2 {
font-size: 25px;	
	}
.dropbtn  {	
margin-left: 13px;
margin-bottom: 10px;
}	

.item {
		width: 50px;
    height: 50px;
    top: 55px;
	left: 70px;
	}
	.item img {
		width:45px;
	}
	.icon-btn {
    padding-left: 5px;
}
.imagess {
    margin-top: -40%;
    float: right;
    margin-right: 50px;
}
.icon_butn {
    margin-top: 125px;
}
}

/*^^ done by anjana*/


</style>
<script>
function updateall(callback) {
	$.post( 'update.php', { id : <?php echo $userId; ?> }, function(data) { 	//alert(data);
		callback(JSON.parse(data));
	});
}
function checkshow(i,prev){//alert(prev);
	if(prev<120){
		$('#dialer'+i).removeClass('togglehide').delay(300);
		setTimeout(function(){toggleOptions($('#dialer'+i));}, 100);
		$('.popup').hide();
		$('#blur').hide();
	}else{
		$('#blur').show();$('#room'+i).slideDown(500).fadeIn('fast');
		setTimeout(function(){
			$('#room'+i).fadeOut(500);
			$('#blur').fadeOut(100);
		}, 5000);
	}	
}

$(document).ready(function(){
//var dial; 
 $('[data-toggle="tooltip"]').tooltip();   
 $('.alertbox').click(function(){$('.alertbox').hide();$('.popup').hide();});
  
$('.menubar').click(function() {    $('.menubar').toggleClass('change');});
 
 $('#settings_btn').click(function(){//		alert("12e423423");
 $('.menubar').toggleClass('change');
	if($('.settings_panel').hasClass('togglehide')){//		alert("sdjcgjhdc");
	//update the settings field
	$.post( 'update.php', { settings : <?php echo $userId; ?> }, function(data) {//	alert("its  "+data);		//callback(JSON.parse(data));
		var result=JSON.parse(data);
		for(var i=0;i<result.length;i++){
			for(var j=0;j<5;j++){//	alert("its  "+result[i][j]);
				if(!j){
					var rname='#changeroom'+i;//alert(rname);
					$(rname).val("");
					$(rname).attr('placeholder',result[i][j].substr(result[i][j].indexOf("|")+1));
					rname="#roomimg"+i;
					$(rname).attr("alt",result[i][j].substr(0,result[i][j].indexOf("|")));
					var posx=result[i][j].substr(0,result[i][j].indexOf(","))+'px';
					var posy=result[i][j].substr(result[i][j].indexOf(",")+1,result[i][j].indexOf("|")-result[i][j].indexOf(",")-1)+'px'; 
					//alert(posx+"\n"+posy);
					//alert(result[i][j].substr(result[i][j].indexOf(",")+1,result[i][j].indexOf("|")-result[i][j].indexOf(",")-1));
					$(rname).css({'background-position-x': posx, 'background-position-y': posy});
					//alert('Room '+i+' is a '+result[i][j]);
				}else{
					var rname='#icon-btn'+j;
					rname+=i + ' img';
					$(rname).attr('src',"img/on/"+result[i][j]+".png");
					//alert(rname+'Button '+j +' is a '+result[i][j]);
				}
			}
			//alert(pin[i]);
		}
	});
	
	}	
$('.settings_panel').toggleClass('togglehide');
});

<?php
  try{
  if(count($scrpt)>0){
	foreach($scrpt as $i =>$scripts){
		echo $scripts;
	}
  }
  } catch (Exception $e) {
	 // echo '//Caught exception: ',  $e->getMessage(), "\n";904629735
  }
 ?>
 
  $('#setform').one('submit',function(e){//function processForm() {//$('#savebtn').live("click", function () {// $('#savebtn').click(function(e){//
	var arr=[];
	 var roomico=[];
	 var roomnm=[];
	for(var i=0,k="";i<<?php echo $size; ?>;i++){
		 var temp= [];
		 k="#changeroom"+i;		// $(":input[value=''][value!='.']").attr('disabled', true);		 //alert($(k).val());
		 if($(k).val()!=""){			 //alert($(k).attr("placeholder"));
			roomnm.push(i+"|"+$(k).val());
		}
		$(k).attr('disabled', true);	 //temp.push("r"+i+"|"+$(k).val());
		
		k="#roomimg"+i;
		var posx=$(k).css('background-position-x');
		var posy=$(k).css('background-position-y');
		
		posx.substr(0,posx.indexOf("px"))+","+posy.substr(0,posy.indexOf("px"));//alert(posx);
		if($(k).attr("alt")!=(posx.substr(0,posx.indexOf("px"))+","+posy.substr(0,posy.indexOf("px")))){
			//send the postion though a varablr
		roomico.push(i+"|"+posx.substr(0,posx.indexOf("px"))+"X"+posy.substr(0,posy.indexOf("px")));
		}
		 for(var j=1,nm="",al="";j<=4;j++){
			k='#icon-btn'+j;
			k+=i+' img';
			nm=$(k).attr('src');
			nm = nm.substring(nm.lastIndexOf("/")+1,nm.lastIndexOf(".png"));
			al=$(k).attr('alt');
			if(!(al==nm)){	  //$('<input>').attr('type', 'hidden').attr('name', i+','+j).attr('value', nm).appendTo('#setform');
				temp.push(i+"-"+j+nm);//alert(k+"\n"+res);
			}
		}
		if(temp.length !== 0)
			arr.push(temp);
	}	//	alert(roomico.toString());
	 if(arr.length !== 0)$('<input>').attr('type', 'hidden').attr('name', 'settings').attr('value', arr.toString()).appendTo('#setform');
	 if(roomico.length !== 0)$('<input>').attr('type', 'hidden').attr('name', 'roomicon').attr('value',roomico.toString()) .appendTo('#setform');
	 if(roomnm.length !== 0)$('<input>').attr('type', 'hidden').attr('name', 'roomname').attr('value',roomnm.toString()) .appendTo('#setform');
/* 	 $(':input[value="k"]').attr('disabled', true);
	 alert(k);
		console.log( arr.serializeArray() );
	var push=JSON.stringify(arr);
    alert(push);
var array={"settings":arr};
	alert(JSON.stringify(array));
	e.preventDefault(); */
/* 	$.post(window.location,{
                  data: array,
                  success: function (data) {
                     alert("Data Loaded: yesss\n" + data);
					//  console.log("Response Data" +data);
			
                  }
              }); */
			/*  $.post('home.php',arr).fail(function() {
    alert( "error" );
  }); */
	//$(this)[0].action=push;//
	/*   $.ajax({
        type: 'POST',
        url:  'home.php',
        data: array,
        dataType: 'json'
      }).done(function(data) {
        //The code below is executed asynchronously, 
        //meaning that it does not execute until the
        //Ajax request has finished, and the response has been loaded.
        //This code may, and probably will, load *after* any code that
        //that is defined outside of it.
        alert("Response Data" +data); //Log the server response to console
      });//alert("Does this alert appear first or second?");  */
 });

 $('#cancelbtn').click(function(){ 
/*  var k="#roomimg1";
		var posx=$(k).css('background-position-x');
		var posy=$(k).css('background-position-y');
				alert(posx.substr(0,posx.indexOf("px"))+","+posy.substr(0,posy.indexOf("px")));//posx);
		if($(k).attr("alt")!==(posx.substr(0,posx.indexOf("px"))+","+posy.substr(0,posy.indexOf("px")))){
			alert("yes they are same");
		}else{alert("no they are not same");} */
		$('.settings_panel').toggleClass('togglehide');});
 
$('.icongallery').click(function(e){
	var offset = $(this).offset();
  var relativeX = (e.pageX - offset.left);
  var relativeY = (e.pageY - offset.top);
  var res=48;
  var posx='-'+Math.round(relativeX/res)*48+'px';
  var posy='-'+Math.round(relativeY/res)*48+'px';//  alert(posx+'\n'+posy);
  // get the alt value so that we can know from where we have clicked it
   
  var myClass = $(this).attr("alt");//   alert(myClass); icongallery from_btn40
$('.icongallery').hide();  
  $(myClass).css({'background-position-x': posx, 'background-position-y': posy});
});
});
setInterval(function() {
	updateall(function(result){
		for(var i=0;i<result.length;i++){
			$('#prev1').text(' -> '+ result[i].substring(0,28)+':)'); 
			var prev=Math.round((new Date()- new Date(result[i].substring(0,28)))/1000);
			if(prev>120){//				alert(prev + "#dialer" +i);
				if(!($("#dialer" +i).hasClass('togglehide'))){//					alert("Dialer");
					$("#dialer" +i).addClass('togglehide');
					$("#dialer" +i).removeClass('open');
					$('#blur').show();$('#room'+i).slideDown(500).fadeIn('fast');
					setTimeout(function(){
						$('#room'+i).fadeOut(500);
						$('#blur').fadeOut(100);
					}, 5000);
				}//else alert("Dialer "+i +" already hidden");
			}			
		}
	});
}, 120000);// try to understand
</script>
</head>

<body style="background-color:skyblue;">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      
      <a class="navbar-brand" href="#">Home Automation</a>
      <div class="menubar">
  <div class="bar1"></div>
  <div class="bar2"></div>
  <div class="bar3"></div>
</div>

<div id="menu" style="margin: 25px 25px 0px 0px;z-index:99;">
  <ul>
    <li id="settings_btn"><img src="img/settings.ico" alt="Settings"><h3>Settings</h3></a></li>
    <li id="logout_btn"
     		onclick="return confirm('Are you sure,you want to Logout?');">
     		<img  src="img/image8.png" alt="Logout"><h3>Logout</h3></a>
    </li>
    
  </ul>
</div>
    </div>
  </div>
</nav>
<?php
echo "<div class='roombtn' style='position:absolute; width: 100%;'>";
if(count($btns)>0){
	foreach($btns as $i =>$buttons){
		echo $buttons;
	}
}
echo "</div>";

if(count($dialer)>0){
for($i=0;$i<count($dialer);$i++){
 echo $dialer[$i];
 
}
}
echo "<div class='togglehide alertbox' id='blur'></div>";
if(count($display)>0){
		foreach($display as $i =>$dspl){
		echo $dspl;
	}
}

/* echo "<div class='agileinfo_main'><form id='survey-form' action='#' method='post' target='submit-survey'>";
if(count($form)>0){
		foreach($form as $i =>$formfields){
		echo $formfields;
	}
}
echo "</form></div>"; */
?>

 <div id="settings" class="settings_panel togglehide">
	<div style="text-align:center;"><h2>Settings</h2></div>
   
   <form id="setform" method="post" action="home.php"><div style="padding: 10px 0px;overflow-y:auto;height:18em;">
<?php 
if(count($forms)>0){
		foreach($forms as $i =>$frm){
		echo $frm;
	}
}
?>
	</div>
	<div class="block">
	<button class="btn btn-info" id="savebtn" type="submit">Save</button>
	<button type="button" class="btn btn-info" id="cancelbtn">Cancel</button></div> </form>
  </div>
    
 <div id="gallery" class="icongallery">
 <img src="img/images.jpg">
 </div>
 
 <script type="text/javascript" src="switcher.js"></script>

</body>
</html>
