<?php

//*********** PUT YOUR INFO HERE ***********//
//Configuration
$scdef = "Streamsolutions Demo Stats";
// ABOVE: Default station name to display when server or stream is down
$scip = "131.255.89.244"; // ip or url of shoutcast server (DO NOT ADD HTTP:// don't include the port)
$scport = "2013"; // port of shoutcast server
$scpass = "Df04072013"; // password to shoutcast server

$refreshrate = "200"; // Script/Page refresh time
$bgcolor = "#ffffff"; // page background colour, hex value, default = white, #ffffff

//End configuration
//*********** PUT YOUR INFO HERE ***********//

$scfp = fsockopen("$scip", $scport, &$errno, &$errstr, 30);
if(!$scfp) {
$scsuccs=1;
echo''.$scdef.' is Offline';
}
if($scsuccs!=1){
fputs($scfp,"GET /admin.cgi?pass=$scpass&mode=viewxml HTTP/1.0\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
while(!feof($scfp)) {
$page .= fgets($scfp, 1000);
}

//define xml elements
$loop = array("STREAMSTATUS", "BITRATE", "SERVERTITLE", "CURRENTLISTENERS");
$y=0;
while($loop[$y]!=''){
$pageed = ereg_replace(".*<$loop[$y]>", "", $page);
$scphp = strtolower($loop[$y]);
$$scphp = ereg_replace("</$loop[$y]>.*", "", $pageed);
if($loop[$y]==SERVERGENRE || $loop[$y]==SERVERTITLE || $loop[$y]==SONGTITLE || $loop[$y]==SERVERTITLE)
$$scphp = urldecode($$scphp);

// uncomment the next line to see all variables
//echo'$'.$scphp.' = '.$$scphp.'<br>';
$y++;
}
//end intro xml elements

//get song info and history
$pageed = ereg_replace(".*<SONGHISTORY>", "", $page);
$pageed = ereg_replace("<SONGHISTORY>.*", "", $pageed);
$songatime = explode("<SONG>", $pageed);
$r=1;
while($songatime[$r]!=""){
$t=$r-1;
$playedat[$t] = ereg_replace(".*<PLAYEDAT>", "", $songatime[$r]);
$playedat[$t] = ereg_replace("</PLAYEDAT>.*", "", $playedat[$t]);
$song[$t] = ereg_replace(".*<TITLE>", "", $songatime[$r]);
$song[$t] = ereg_replace("</TITLE>.*", "", $song[$t]);
$song[$t] = urldecode($song[$t]);
$dj[$t] = ereg_replace(".*<SERVERTITLE>", "", $page);
$dj[$t] = ereg_replace("</SERVERTITLE>.*", "", $pageed);
$r++;
}
//end song info

fclose($scfp);
}

//display stats
if($streamstatus == "1"){
//you may edit the html below, make sure to keep variable intact
echo'

<html>
<head>
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<META HTTP-EQUIV="REFRESH" CONTENT="'.$refreshrate.';URL=radio_stats.php">
<link href="main.css" type="text/css" rel="stylesheet" />
<title>'.$scdef.'</title>
</head>
<body text="" style="background-color:transparent">
<span class="boldtype2">.:: Currently Playing</span><br />
<br />
<center>
<span class="playdisplay">'.$song[0].'</span><br />
<br />
<a href="http://209.51.162.173:9108/listen.pls" class="image"><img src="http://www.streamsolutions.co.uk/images/script%20images/bolt.jpg" alt="click here to listen" /></a>
</center>
<span class="boldtype2">.:: Previous Tracks</span><br />
<li><span class="boldtype3">'.$song[1].'</span></li>
<li><span class="boldtype3">'.$song[2].'</span></li>
<li><span class="boldtype3">'.$song[3].'</span></li>
<li><span class="boldtype3">'.$song[4].'</span></li>
</body>
</html>';

}
if($streamstatus == "0")
{
//you may edit the html below, make sure to keep variable intact
echo'
<html>

<head>
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.document">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<META HTTP-EQUIV="REFRESH" CONTENT="'.$refreshrate.';URL=radio_stats.php">
<link href="main.css" type="text/css" rel="stylesheet" />
<title>Radio Server Is Offline</title>
</head>

<body text="" style="background-color:transparent">
<span class="playdisplay">Server Offline! </span>
</body>

</html>';
} 