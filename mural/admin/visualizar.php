<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// Segurança
/* ---------------------------------------------------------------- */
function verifica_sessao2()
{
    session_start();
    if ((!isset($_SESSION[mUsuario])) AND (!isset($_SESSION[mSenha]))) {
        header("Location: ../login.php");
    } 
} 
verifica_sessao2();
// Cabeçalho
/* ---------------------------------------------------------------- */
require("../biblioteca/class.fasttemplate.php");
require("../biblioteca/class.paginator.php");
require("../biblioteca/class.paginator_html.php");
require("../config.php");
require("../idiomas/$idioma");
require("../funcoes.php");
conecta();

$mural = new FastTemplate('tpl/');
$linhas = mysql_num_rows(mysql_query("SELECT id FROM mural"));
$nav = &new Paginator($_GET['pagina'], $linhas);
$nav = &new Paginator_html($_GET['pagina'], $linhas);
$mural->define (array('pagina' => 'tpl_visualizar.htm'));
$mural->assign('{Titulo}', 'Administração - Visualizando Posts');
// Sistema de Navegação
/* ---------------------------------------------------------------- */
$nav->set_Limit(20);
$nav->set_Links(3);
$limite1 = $nav->getRange1();
$limite2 = $nav->getRange2();
$sql = mysql_query("SELECT * FROM mural ORDER BY id DESC LIMIT $limite1, $limite2");
$mural->assign('{Nav}', $nav->previousNext());
$mural->assign('{Tr}', $linhas);
if (!$_GET['pagina'])
    $pc = "1";
else
    $pc = $_GET['pagina'];
$mural->assign('{Nav4}', $pc);
$mural->assign('{Nav5}', ceil($linhas / 20));
// Visualizar Posts
/* ---------------------------------------------------------------- */
if ($linhas == 0) {
    $mural->define_dynamic('navegacao', 'pagina');
    $mural->clear_dynamic('navegacao');
    $mural->define_dynamic('tabela', 'pagina');
    $mural->clear_dynamic('tabela');
    $mural->define_dynamic('erro', 'pagina');
    $mural->assign('{Erro}', $idioma_nao_encontrada);
    $mural->parse('ERRO', '.erro');
} else {
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('navegacao', 'pagina');
    $mural->define_dynamic('tabela', 'pagina');
    $mural->assign('{IdiomaTotal}', $idioma_total);
    $mural->assign('{IdiomaMostrando}', $idioma_mostrando);
    $mural->assign('{IdiomaDe}', $idioma_de);
    $mural->assign('{IdiomaPagina}', $idioma_pagina);
    $mural->assign('{IdiomaOpDesAdmin}', $idioma_op_des_admin);
    $mural->assign('{IdiomaEditarAdmin}', $idioma_editar_admin);
    $mural->assign('{IdiomaOpDesAdmin}', $idioma_op_des_admin);
    $mural->assign('{IdiomaOpDesAdmin}', $idioma_op_des_admin);
    $mural->assign('{IdiomaBrowserAdmin}', $idioma_browser_admin);
    $mural->assign('{IdiomaIpAdmin}', $idioma_ip_admin);
    $mural->assign('{IdiomaExcluirSelecionadasAdmin}', $idioma_del_sel_admin);
	$mural->define_dynamic('sel', 'pagina');
    $mural->define_dynamic('visualizar', 'pagina');

    while ($resultado = mysql_fetch_array($sql)) {
        if ($resultado['bloqueado'] == 0) {
            $mural->assign('{Bloqueio}', "bloquear");
            $mural->assign('{Bloqueio2}', "Bloquear");
            $mural->assign('{Cor01}', "#A3CEFA");
            $mural->assign('{Cor02}', "#CFEBFC");
        } else {
            $mural->assign('{Bloqueio}', "desbloquear");
            $mural->assign('{Bloqueio2}', "Desbloquear");
            $mural->assign('{Cor01}', "#FF8282");
            $mural->assign('{Cor02}', "#FFE6E6");
        } 
        $mural->assign('{Codigo}', $resultado['id']);
        $mural->assign('{Ip}', $resultado['ip']);
        $mural->assign('{Browser}', $resultado['browser']);
        $mural->assign('{Nome}', stripslashes(strip_tags ($resultado['nome'], '<i>')));
		$mural->assign('{Para}', stripslashes(strip_tags($resultado['para'], '<i>')));
        $mural->assign('{Email}', stripslashes(strip_tags (strtolower ($resultado['email']))));
        $mural->assign('{Data}', $resultado['data']);
        $msg = str_replace('imagens/smilies', '../imagens/smilies', codifica_url(quebra_linha(stripslashes($resultado['mensagem']))));
        $mural->assign('{Comentario}', $msg);
// Comentários
		$sql2 = mysql_query ("SELECT * FROM comentarios WHERE id_post='$resultado[id]' ORDER BY id_comentario DESC");
		$mural->assign('{qtd_comment}', mysql_num_rows($sql2));
		$mural->assign('{id_comment}', $resultado['id']);
		$mural->parse('VISUALIZAR', '.visualizar');
// Comentários
    }
    $mural->parse('SEL', '.sel');
    $mural->parse('NAVEGACAO', '.navegacao');
    $mural->parse('TABELA', '.tabela');
} 
// Apagar
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == 'apagar') {
    if ($_POST['id'] != '') {
        foreach($_POST['id'] as $v) {
            mysql_query("DELETE FROM mural WHERE id=$v") or die ("Erro ao apagar registro");
        } 
        echo "<script language=javascript>location.replace('visualizar.php?pagina=$_GET[pagina]');</script>\n";
    } 
    echo "<script language=javascript>location.replace('visualizar.php?pagina=$_GET[pagina]');</script>\n";
} 
// Apagar
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == 'apagar_comment') {
    if ($_GET['id_comment'] != '') {
    	mysql_query("DELETE FROM comentarios WHERE id_comentario='$_GET[id_comment]'") or die ("Erro ao apagar registro - ". mysql_error());
    } 
        echo "<script language=javascript>location.replace('visualizar.php?pagina=$_GET[pagina]');</script>\n";
    echo "<script language=javascript>location.replace('visualizar.php?pagina=$_GET[pagina]');</script>\n";
} 
// Bloqueio
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == 'bloquear') {
    if ($_GET['id'] != '') {
        mysql_query("UPDATE mural SET bloqueado='1' WHERE id='$_GET[id]'") or die ("Erro ao bloquear registro");
        echo "<script language=javascript>location.replace('visualizar.php?pagina=$_GET[pagina]');</script>\n";
    } 
    echo "<script language=javascript>location.replace('visualizar.php?pagina=$_GET[pagina]');</script>\n";
} 
if ($_GET['acao'] == 'desbloquear') {
    if ($_GET['id'] != '') {
        mysql_query("UPDATE mural SET bloqueado='0' WHERE id='$_GET[id]'") or die ("Erro ao desbloquear registro");
        echo "<script language=javascript>location.replace('visualizar.php?pagina=$_GET[pagina]');</script>\n";
    } 
    echo "<script language=javascript>location.replace('visualizar.php?pagina=$_GET[pagina]');</script>\n";
} 
// Fim do Arquivo
/* ---------------------------------------------------------------- */
mysql_close($conexao);
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>