<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// Cabeçalho
/* ---------------------------------------------------------------- */
require("../biblioteca/class.fasttemplate.php");
require("../config.php");
require("../idiomas/$idioma");
require("../funcoes.php");
$mural = new FastTemplate('tpl/');
$mural->define (array('pagina' => 'tpl_ip.htm'));
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();
// Adicionar IP
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == "adicionar") {
    $mural->assign('{Titulo}', 'Administração - Adicionar Ip');
    $mural->define_dynamic('ip', 'pagina');
    $mural->assign('{IdiomaEnviarIP}', $idioma_env_ip);
    $mural->assign('{IdiomaGerenIP}', $idioma_geren_ip);
    $mural->assign('{IdiomaAddIP}', $idioma_env_ip);
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('excluir_ip', 'pagina');
    $mural->clear_dynamic('excluir_ip');
    $mural->parse('IP', '.ip');
} elseif ($_GET['modo'] == "add" && $_GET['executar'] == "ok") {
    $mural->assign('{Titulo}', 'Administração - Adicionar Ip');
    if (empty($_POST['ip_bloq'])) {
        $mural->define_dynamic('excluir_ip', 'pagina');
        $mural->clear_dynamic('excluir_ip');
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', "Digite um IP! <br><a href=\"javascript:window.history.go(-1);\">Voltar</a>");
        $mural->parse('ERRO', '.erro');
    } else {
        $msg_errada = $_POST['ip_bloq'];
        $msg_correta = "[b]$_POST[msg_subst][/b]";
        mysql_query("INSERT INTO ip (ip) VALUES ('$_POST[ip_bloq]')");
        mysql_query("OPTIMIZE TABLE ip");
        $mural->define_dynamic('excluir_ip', 'pagina');
        $mural->clear_dynamic('excluir_ip');
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', $idioma_ip_sucess);
        $mural->parse('ERRO', '.erro');
    } 
    $mural->define_dynamic('ip', 'pagina');
    $mural->clear_dynamic('ip');
} 
// Excluir IP
/* ---------------------------------------------------------------- */
elseif ($_GET['acao'] == "excluir") {
    $mural->assign('{Titulo}', 'Administração - Excluir Ip');
    $sql = mysql_query("SELECT id, ip FROM ip ORDER BY id DESC");
    $linha = mysql_num_rows($sql);
    $mural->define_dynamic('ip', 'pagina');
    $mural->clear_dynamic('ip');
    if ($linha != '0') {
        $mural->define_dynamic('erro', 'pagina');
        $mural->clear_dynamic('erro');
        $mural->define_dynamic('excluir_ip', 'pagina');
        while ($resultado = mysql_fetch_array($sql)) {
            $mural->define_dynamic('visualizar_ip', 'pagina');
            $mural->assign('{Excluir}', "?acao=deletar&confirma=sim&id=$resultado[id]");
            $mural->assign('{Id}', "$resultado[id]");
            $mural->assign('{Visualizar}', "<a href=\"?acao=deletar&confirma=sim&id=$resultado[id]\">$resultado[ip]</a><br>");
            $mural->parse('VISUALIZAR_IP', '.visualizar_ip');
        } 
        $mural->parse('EXCLUIR_IP', '.excluir_ip');
    } else {
        $mural->define_dynamic('excluir_ip', 'pagina');
        $mural->clear_dynamic('excluir_ip');
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', $idioma_sem_ip);
        $mural->parse('ERRO', '.erro');
    } 
} elseif ($_GET['acao'] == "deletar" && $_GET['confirma'] == "sim") {
    if ($_GET['id'] != '') {
        $sql_del = mysql_query ("DELETE FROM ip WHERE id='$_GET[id]'");
        mysql_query ("OPTIMIZE TABLE ip");
        echo "<script language=javascript>location.replace('ip.php?acao=excluir');</script>\n";
    } 
} else {
    $mural->assign('{Titulo}', 'Administração - Erro');
    $mural->define_dynamic('ip', 'pagina');
    $mural->clear_dynamic('ip');
    $mural->define_dynamic('excluir_ip', 'pagina');
    $mural->clear_dynamic('excluir_ip');
    $mural->define_dynamic('erro', 'pagina');
    $mural->assign('{Erro}', 'Opção inválida!');
    $mural->parse('ERRO', '.erro');
} 
// Fim do Arquivo
/* ---------------------------------------------------------------- */
mysql_close($conexao);
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>