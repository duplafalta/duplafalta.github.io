<?php
/*

Change song title PHP script for SHOUTcast

This script is (C) MixStream.net 2008

Feel free to modify this free script 
in any other way to suit your needs.

It is strongly recommended that you
put this script in to a password protected
area of your website.

Version: v1.0

*/


/* ----------- Server configuration ---------- */

$ip = "131.255.89.244"; // Server Address 
$port = "2013"; // Server Port
$pass = "Df04072013"; // Admin Password

/* ----- No need to edit below this line ----- */
/* ------------------------------------------- */
$song = $_POST['song'];
if (empty($song)) {
?>
<p>Change the song title here.</p>
<form name="changesong" method="post" action="">
  <input name="song" type="text" id="song">
  <input type="submit" name="Submit" value="Change Title">
</form>

<?php
} else {
$song = urlencode($song); 
$song = str_replace("+", "%20", $song);
		$fp = @fsockopen($ip,$port,$errno,$errstr,4);
		if (!$fp) {
			print "Error: cant get server, please check that server is online";
		} else {
		    fputs($fp, "GET /admin.cgi?pass=" . $pass . "&mode=updinfo&song=" . $song . " HTTP/1.0\r\n");
		    fputs($fp, "User-Agent: Mozilla\r\n\r\n");
			fclose($fp);
$song = str_replace("%20", "+", $song);
$song = urldecode($song);
print "<strong>Title Updated</strong><p>$song</p><p style=\"font-size: 70%\">Powered by <a href=\"http://www.mixstream.net\" target=\"_blank\">MixStream.net - professional streaming</a></p>";
}
}
?>