<meta http-equiv="refresh" content="5;URL=http://duplafalta.com.br/radio/now-playing.php"> 
<head>
<title>Dupla Falta - Associação de Tenistas em São Gabriel da Palha - ES</title>

<style>
#musicas {
	align="center"
	margin:0 auto;
	margin-top:0%;
	width:460px;
	height:50px;
	color: #FF6600;
	text-align: center;
	font: bold 18px Arial, Sans-serif;
	}
</style>
</head>

<div id="musicas">
<?php	
	#Pega o conteudo do arquivo da lista
	$nome_musica = file_get_contents('CurrentSong.txt');
	
	#Converte o arquico para utf-8
	$nome_musica = utf8_encode($nome_musica);
	
	#escreve o conteudo na tela
	echo $nome_musica;
?>
</div>
