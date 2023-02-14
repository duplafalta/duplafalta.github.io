<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Dupla Falta - Associação de Tenistas em São Gabriel da Palha - ES</title>
<style>
#musicas {
	width: 100%;
	height: 40px;
	text-align: left;
	vertical-align: middle;
	display: table-cell;
	color: #FFFF00;
	font: 14px Verdana, Sans-serif;
	}
</style>

</head>

<body>

<div align="center">
	<table border="0" width="100%" cellpadding="0" style="border-collapse: collapse" height="50" background="../radio/images/fundo-player.png">
		<tr>
			<td style="border-left: medium none #FFFFFF; border-right: 2px solid #FFFFFF; border-top: medium none #FFFFFF; border-bottom: 2px solid #FFFFFF; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" align="center" width="30%">

<!-- Inicio. radio hosting USAstreams.com html5 player -->
<!-- Licencia: GRATIS-XDF4543ERF -->
<iframe name="contenedorPlayer" class="cuadroBordeado" allow="autoplay" width="250" height="40" marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=no  src="http://cp.usastreams.com/html5PlayerGratis.aspx?stream=http://177.54.97.232:2013/;&fondo=03&formato=mp3&color=3&titulo=2&autoStart=20&vol=5&botonPlay=1"><a href="" Alt = "" title=""></a></iframe>
<!-- En players responsive puede modificar el weight a sus necesidades, Por favor no modifique el resto del codigo para poder seguir ofreciendo este servicio gratis  -->
<!-- Fin. USAstreams.com html5 player -->

			</td>

			<td style="border-left: medium none #FFFFFF; border-right: medium none #FFFFFF; border-top: medium none #FFFFFF; border-bottom: 2px solid #FFFFFF; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" align="center" width="15%">
			<p align="right">
			<font face="Verdana" color="#FFFFFF" style="font-size: 8pt">Tocando agora:</font></td>
			<td style="border-left: medium none #FFFFFF; border-right: medium none #FFFFFF; border-top: medium none #FFFFFF; border-bottom: 2px solid #FFFFFF; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" align="center" width="55%" valign="middle">
			<p align="left">

<style>
#musicas {
	width: 100%;
	height: 40px;
	text-align: left;
	vertical-align: middle;
	display: table-cell;
	color: #FFFF00;
	font: 14px Verdana, Sans-serif;
	}
</style>

<div id="musicas" style="width: 715px; height: 40px">
<?php	
	#Pega o conteudo do arquivo da lista
	$nome_musica = file_get_contents('CurrentSong.txt');
	
	#Converte o arquico para utf-8
	$nome_musica = utf8_encode($nome_musica);
	
	#escreve o conteudo na tela
	echo $nome_musica;
?>
</div>
			</td>
		</tr>
	</table>
</div>

</body>

</html>