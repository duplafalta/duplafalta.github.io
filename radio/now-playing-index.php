<meta http-equiv="refresh" content="5;URL=http://duplafalta.com.br/radio/now-playing-index.php"> 
<head>
<title>Dupla Falta - Associação de Tenistas em São Gabriel da Palha - ES</title>

<style>
#musicas {
	width: 80%;
	height: 30px;
	text-align: left;
	vertical-align: middle;
	display: table-cell;
	color: #FFFF00;
	font: 12px Arial, Sans-serif;
	}
</style>
</head>

<div id="musicas" style="width: 715px; height: 30px">
<?php	
	#Pega o conteudo do arquivo da lista
	$nome_musica = file_get_contents('CurrentSong.txt');
	
	#Converte o arquico para utf-8
	$nome_musica = utf8_encode($nome_musica);
	
	#escreve o conteudo na tela
	echo $nome_musica;
?>
</div>
