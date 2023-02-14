<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Language" content="pt-br">
<title>Dupla Falta - Associação de Tenistas em São Gabriel da Palha - ES</title>
<link href='../images/duplafalta.ico' rel='shortcut icon'/>
<link href='../images/duplafalta.png' rel='shortcut icon'/>
<link href='../images/duplafalta.png' rel='apple-touch-icon'/>
<link href='../images/duplafalta.png' rel='shortcut icon' type='image/x-icon'/>

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>

<script type="text/javascript" src="../menu/principal/principal.js"></script>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>

</head>

    <body>
        
    <div align="center">
		<div align="center">
			<table border="0" width="100%" cellpadding="0" bgcolor="#1F2223" style="border-collapse: collapse" height="35">
				<tr>
					<td height="35">
					<p style="margin-top: 0; margin-bottom: 0" align="center">
					<iframe name="redesociais" src="../rede-social.html" marginwidth="0" marginheight="0" height="35" width="1024" scrolling="no" border="0" frameborder="0" align="center">
					</iframe></td>
				</tr>
				<tr>
					<td width="100%" height="100" bgcolor="#00B2ED">
					<p align="center" style="margin-top: 0; margin-bottom: 0">
						<iframe name="topo" src="../topo.html" marginwidth="0" marginheight="0" height="130" width="1024" scrolling="no" border="0" frameborder="0">
						</iframe></td>
				</tr>
			</table>
		</div>
		</div>
		<div align="center">
			<table border="0" width="1024" cellspacing="0" cellpadding="0" height="40">
				<tr>
					<td align="center">
					<p style="margin-top: 0; margin-bottom: 0" align="center"><div id="principal">
						<p style="margin-top: 0; margin-bottom: 0"></div></td>
				</tr>
			</table>
			</div>
			<div align="center">
				<table border="0" width="1024" cellpadding="0" bgcolor="#00B2ED" style="border-collapse: collapse">
					<tr>
						<td align="center"><?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// CabeÃ§alho
/* ---------------------------------------------------------------- */
require_once("biblioteca/class.fasttemplate.php");
require_once("biblioteca/class.paginator.php");
require_once("biblioteca/class.paginator_html.php");
require_once("config.php");
require_once("idiomas/$idioma");
require_once("funcoes.php");

$mural = new FastTemplate('templates/' . $template . '/');
$mural->define(array('pagina' => 'mural.htm'));
conecta();
$linhas = mysql_num_rows(mysql_query("SELECT id FROM mural WHERE bloqueado!='1'"));
$total_reg2 = ceil($linhas / $total_reg);
if ($_GET['pagina'] > $total_reg2) {
    $_GET['pagina'] = 1;
}
$nav = &new Paginator($_GET['pagina'], $linhas);
$nav = &new Paginator_html($_GET['pagina'], $linhas);
$mural->assign('{Titulo}', $idioma_titulo);
$mural->assign('{Copyright}', $copyright);
$mural->assign('{IdiomaTotal}', $idioma_total);
$mural->assign('{IdiomaMostrando}', $idioma_mostrando);
$mural->assign('{IdiomaDe}', $idioma_de);
$mural->assign('{IdiomaPagina}', $idioma_pagina);

// Inicio
/* ---------------------------------------------------------------- */
// Sistema de NavegaÃ§Ã£o
/* ---------------------------------------------------------------- */
$nav->set_Limit($total_reg);
$nav->set_Links(3);
$limite1 = $nav->getRange1();
$limite2 = $nav->getRange2();
$sql = mysql_query("SELECT * FROM mural WHERE bloqueado !='1' ORDER BY id DESC LIMIT $limite1, $limite2");
$sql2 = mysql_query("SELECT ip FROM ip WHERE ip='$_SERVER[REMOTE_ADDR]'") or die("<font color=#FF0000><b>Erro: " . mysql_error() . "</b></font>");
$qtd_ip = mysql_num_rows($sql2);
mysql_free_result($sql2);
$mural->assign('{Nav}', $nav->previousNext());
$mural->assign('{Tr}', $linhas);
if (!$_GET['pagina']) {
    $pc = "1";
} else {
    $pc = $_GET['pagina'];
} 
$mural->assign('{Nav4}', $pc);
$mural->assign('{Nav5}', $total_reg2);
// Menu
/* ---------------------------------------------------------------- */
if ($qtd_ip != 0) {
    $mural->assign('{Menu1}', "<a href=\"mural.php\" onClick=\"javascript:ip_bloq()\" onmouseover=\"window.status='$idioma_post_mensagem'; return true;\">$idioma_post_mensagem</a>\n");
} elseif (isset($_COOKIE['ip_mural'])) {
    $mural->assign('{Menu1}', "<a href=\"mural.php\" onClick=\"javascript:flood()\" onmouseover=\"window.status='$idioma_post_mensagem'; return true;\">$idioma_post_mensagem</a>\n");
} else {
    $mural->assign('{Menu1}', "<a href=\"javascript:popup('form.php',516,450,'no','Form',10, 100);\" onmouseover=\"window.status='$idioma_post_mensagem'; return true;\">$idioma_post_mensagem</a>");
} 
$mural->assign('{Menu2}', "<a href=\"javascript:popup('ajuda.php',260,335,'no','Ajuda',50, 100);\" onmouseover=\"window.status='$idioma_ajuda'; return true;\">$idioma_ajuda</a>");
$mural->assign('{Menu3}', "<a href=\"admin.php\" onmouseover=\"window.status='$idioma_admin'; return true;\">$idioma_admin</a></b>");
// Resultados na tela
/* ---------------------------------------------------------------- */
if ($linhas == 0) {
    $mural->define_dynamic('erro', 'pagina');
    $mural->assign('{Erro}', $idioma_nao_encontrada);
    $mural->parse('ERRO', '.erro');
    $mural->define_dynamic('tabela', 'pagina');
    $mural->clear_dynamic('tabela');
} else {
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('tabela', 'pagina');
    while ($resultado = mysql_fetch_array($sql)) {
        $sql2 = mysql_query("SELECT * FROM comentarios WHERE id_post='$resultado[id]'");
		$mural->assign('{NComentarios}', mysql_num_rows($sql2));
		$mural->assign('{Cor}', ($coralternada++ % 2 ? $corFundoTabela01 : $corFundoTabela02));
        $mural->assign('{Nome}', stripslashes(strip_tags($resultado['nome'], '<i>')));
        $mural->assign('{Para}', stripslashes(strip_tags($resultado['para'], '<i>')));
        $mural->assign('{Cidade}', stripslashes(strip_tags($resultado['cidade'], '<i>')));
        $mural->assign('{Email}', stripslashes(strtolower(strip_tags($resultado['email']))));
        $mural->assign('{Data}', $resultado['data']);
        $mural->assign('{Mensagem}', codifica_url(filtro(stripslashes(quebra_linha($resultado['mensagem'])))));
        $mural->assign('{IDPost}', $resultado['id']);
		$mural->parse('TABELA', '.tabela');
    } 
} 
// RodapÃ©
/* ---------------------------------------------------------------- */
mysql_close($conexao);
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>

<td valign="middle">
<p align="center" style="margin-top: 0; margin-bottom: 0">
</tr>
</table>
<div align="center">
<div align="center">
<div align="center">
<table border="0" width="1024" cellpadding="0" style="border-collapse: collapse">
<tr>
<td valign="middle">
<p align="center" style="margin-top: 0; margin-bottom: 0">
<iframe name="I40" marginwidth="0" marginheight="0" height="4" scrolling="no" border="0" frameborder="0">
</iframe></td>
</tr>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
    <div align="center">
		<table border="0" width="100%" cellpadding="0" style="border-collapse: collapse" bgcolor="#00B5F1" height="40">
			<tr>
				<td>
				<p style="margin-top: 0; margin-bottom: 0" align="center">
				<iframe name="rodape" src="../rodape.html" marginwidth="0" marginheight="0" height="40" width="1024" scrolling="no" border="0" frameborder="0">
				</iframe></td>
			</tr>
		</table>
		</div>
