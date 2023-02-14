<?php
$ip = "131.255.89.244";
$port = "2013";
$fp = @fsockopen($ip,$port,$errno,$errstr,1);
if (!$fp) { 
    $title = "Connection timed out or the server is offline  ";
} else { 
    fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla\r\n\r\n");
    while (!feof($fp)) {
        $info = fgets($fp);
    }
    $info = str_replace('</body></html>', "", $info);
    $split = explode(',', $info);
    if (empty($split[6])) {
        $title = "The current song is not available  ";
    } else {
        $count = count($split);
        $i = "6";
        while($i<=$count) {
            if ($i > 6) {
                $title .= ", " . $split[$i];
            } else {
                $title .= $split[$i];
            }
            $i++;
        }
    }
}
$title = substr($title, 0, -2);
print $title;
?> <head><title>Dupla Falta - Associação de Tenistas em São Gabriel da Palha - ES</title></head>