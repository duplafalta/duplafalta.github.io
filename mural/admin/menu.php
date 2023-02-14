<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Administra&ccedil;&atilde;o do Mural</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
body {
	scrollbar-face-color:#6CB1F7;
	scrollbar-highlight-color:#DFF2FD;
	scrollbar-3dlight-color:#99D5F9;
	scrollbar-darkshadow-color:#000000;
	scrollbar-shadow-color:#0E7AE7;
	scrollbar-arrow-color:#FFFFFF;
	scrollbar-track-color:#CFEBFC;
	margin-left: 10px;
	margin-top: 10px;
	margin-bottom: 0px;
	background-image: url(../imagens/bg_menu.jpg);
}	
.tabela {
	font-family: Verdana, Arial;
	font-size: 10px;
	color: #000000;
}
.tabela3 {
	font-family: Verdana, Arial;
	font-size: 10px;
	letter-spacing: normal;
	word-spacing: normal;
	border-top: 1px solid #666666;
	border-right: 1px solid #666666;
	border-bottom: 1px solid #666666;
	border-left: 1px solid #666666;
}
.tabela4 {
	font-family: Verdana, Arial;
	font-size: 10px;
	letter-spacing: normal;
	word-spacing: normal;
	border-top: 1px solid #666666;
	border-right: 1px solid #666666;
	border-bottom: 1px solid #666666;
	border-left: 1px solid #666666;
}
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	color: #000000;
	text-decoration: none;
}
a:hover {
	color: #000000;
	text-decoration: underline;
}
.campos {
	font-family: Verdana, Arial;
	font-size: 10px;
	color: #000000;
	border: 1px solid #000000;
}
.botao {
	font-family: Verdana, Arial;
	font-size: 10px;
	color: #000000;
	background-color: #FFFFFF;
	border: 1px solid #000000;
}
.botao2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	border: 1px solid #000000;
}
.campos2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	border: 1px solid #000000;
}
.campoerro {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	border: 1px solid #FF0000;
	background-color: #FFE1E1;
}
.regua {
    border: 1px ridge;
}
</style>

</head>

<body>
<table width="100%" bgcolor="#FFFFFF" border="0" align="center" cellpadding="4" cellspacing="1" class="tabela3">
  <tr>
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA"> 
      <div align="center"><strong>Principal</strong></div></td>
  </tr>
  <tr>
    <td bgcolor="#CFEBFC"><a href="../mural.php" target="_blank">Visualizar Mural</a><br>
      <a href="javascript:parent.location.href='sair.php';">Sair</a></td>
  </tr>
</table>
<br>

<table width="100%" bgcolor="#FFFFFF" border="0" cellpadding="4" align="center" cellspacing="1" class="tabela3">
  <tr> 
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA">
<div align="center"><strong>Mensagens</strong></div></td>
  </tr>
  <tr> 
    <td bgcolor="#CFEBFC"><a href="visualizar.php" target="conteudo">Editar</a></td>
  </tr>
</table>
<br>
<table width="100%" bgcolor="#FFFFFF" border="0" cellpadding="4" align="center" cellspacing="1" class="tabela3">
  <tr> 
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA">
<div align="center"><strong>Ajustes</strong></div></td>
  </tr>
  <tr> 
    <td bgcolor="#CFEBFC"><a href="ajustes.php" target="conteudo">Configurar</a></td>
  </tr>
</table>
<br>
<table width="100%" bgcolor="#FFFFFF" border="0" cellpadding="4" align="center" cellspacing="1" class="tabela3">
  <tr> 
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA"> <div align="center"><strong>Senha</strong></div></td>
  </tr>
  <tr> 
    <td bgcolor="#CFEBFC"><a href="senha.php" target="conteudo">Alterar Senha</a></td>
  </tr>
</table>
<br>
<table width="100%" bgcolor="#FFFFFF" border="0" cellpadding="4" align="center" cellspacing="1" class="tabela3">
  <tr> 
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA">
<div align="center"><strong>Filtro</strong></div></td>
  </tr>
  <tr> 
    <td height="33" bgcolor="#CFEBFC"><a href="filtro.php?acao=adicionar" target="conteudo">Adicionar</a><br>
      <a href="filtro.php?acao=excluir" target="conteudo">Listar</a> </td>
  </tr>
</table>
<br>
<table width="100%" bgcolor="#FFFFFF" border="0" cellpadding="4" align="center" cellspacing="1" class="tabela3">
  <tr>
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA"><div align="center"><strong>Smilies</strong></div></td>
  </tr>
  <tr>
    <td height="33" bgcolor="#CFEBFC"><a href="smilies.php?acao=adicionar" target="conteudo">Adicionar</a><br>
        <a href="smilies.php?acao=listar" target="conteudo">Listar</a> </td>
  </tr>
</table>
<br>
<table width="100%" bgcolor="#FFFFFF" border="0" cellpadding="4" align="center" cellspacing="1" class="tabela3">
  <tr> 
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA">
<div align="center"><strong>Bloqueio de IP</strong></div></td>
  </tr>
  <tr> 
    <td bgcolor="#CFEBFC"><a href="ip.php?acao=adicionar" target="conteudo">Adicionar</a><br>
      <a href="ip.php?acao=excluir" target="conteudo">Listar</a> </td>
  </tr>
</table>
<br>
<table width="100%" bgcolor="#FFFFFF" border="0" cellpadding="4" align="center" cellspacing="1" class="tabela3">
  <tr>
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA"><div align="center"><strong>Backup</strong></div></td>
  </tr>
  <tr>
    <td bgcolor="#CFEBFC"><a href="backup.php?opcao=backup" target="conteudo">Criar Backup </a><br>
        <a href="backup.php?opcao=restore" target="conteudo">Restaurar</a></td>
  </tr>
</table>
<br>
<table width="100%" bgcolor="#FFFFFF" border="0" cellpadding="4" align="center" cellspacing="1" class="tabela3">
  <tr> 
    <td height="22" background="tpl/img/bg_menu.gif" bgcolor="#A3CEFA"> <div align="center"><strong>Estat&iacute;sticas</strong></div></td>
  </tr>
  <tr> 
    <td bgcolor="#CFEBFC"><a href="estatisticas.php?tipo=mural" target="conteudo">Estat&iacute;sticas do Mural</a><br> 
      <a href="estatisticas.php?tipo=mysql" target="conteudo">Estat&iacute;sticas 
    do Mysql</a> </td>
  </tr>
</table>
</body>
</html>
