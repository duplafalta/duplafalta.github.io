<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// Cabeçalho
/* ---------------------------------------------------------------- */
require("../config.php");
require("../idiomas/$idioma");
require("../funcoes.php");
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Administra&ccedil;&atilde;o do Mural</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!--
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/*                       inter4u@inter4u.com.br                     */
/* -----------------------------------------------------------------*/
-->
</head>

<frameset rows="*" cols="184,*" framespacing="0" frameborder="NO" border="0">
  <frame src="menu.php" name="menu" scrolling="AUTO">
  <frame src="visualizar.php" name="conteudo">
</frameset>
<noframes><body>

</body></noframes>
</html>