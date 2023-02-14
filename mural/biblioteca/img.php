<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/*                      Classe - Alfred Reinold Baudisch            */
/* -----------------------------------------------------------------*/

session_start();

$done = isset($_GET["done"]) ? (bool)$_GET["done"] : FALSE;

require "class.img_validator.php";

if(isset($_GET["words"]))
{
    $words = array(
        "Computer", "PHP5", "Internet", "OOP", "Linux", "Slackware", "WindowsXP", "phpClasses"
    );

    srand((double) microtime() * 1000000);

    $t = rand(0, count($words)-1);
    $word = $words[$t];
}
else
{
    $word = false;
}

$img = new img_validator();
$img->generates_image($word, $done);
?>