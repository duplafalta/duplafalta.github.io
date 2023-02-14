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
$mural->define (array('pagina' => 'tpl_filtro.htm'));
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();
// Adicionar Filtro
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == "adicionar") {
    $mural->assign('{Titulo}', 'Administração - Adicionar Filtros');
    $mural->define_dynamic('filtro', 'pagina');
    $mural->assign('{IdiomaEnviarFiltro}', $idioma_env_filtro);
    $mural->assign('{IdiomaCensurada}', $idioma_censurada);
    $mural->assign('{IdiomaGerenFiltro}', $idioma_geren_filtro);
    $mural->assign('{IdiomaSubstituir}', $idioma_substituir);
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('excluir_filtro', 'pagina');
    $mural->clear_dynamic('excluir_filtro');
    $mural->parse('FILTRO', '.filtro');
} elseif ($_GET['modo'] == "add" && $_GET['executar'] == "ok") {
    $mural->assign('{Titulo}', 'Administração - Adicionar Filtros');
    if (empty($_POST['msg_censurada'])) {
        $mural->define_dynamic('excluir_filtro', 'pagina');
        $mural->clear_dynamic('excluir_filtro');
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', "Digite uma palavra a ser censurada! <br><a href=\"javascript:window.history.go(-1);\">Voltar</a>");
        $mural->parse('ERRO', '.erro');
    } elseif (empty($_POST['msg_subst'])) {
        $mural->define_dynamic('excluir_filtro', 'pagina');
        $mural->clear_dynamic('excluir_filtro');
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', "Digite uma palavra para correção! <br><a href=\"javascript:window.history.go(-1);\">Voltar</a>");
        $mural->parse('ERRO', '.erro');
    } else {
        $msg_errada = $_POST['msg_censurada'];
        $msg_correta = "[b]$_POST[msg_subst][/b]";
        mysql_query("INSERT INTO filtro (msg_errada, msg_correta) VALUES ('$msg_errada','$msg_correta')");
        mysql_query("OPTIMIZE TABLE filtro");
        $mural->define_dynamic('excluir_filtro', 'pagina');
        $mural->clear_dynamic('excluir_filtro');
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', $idioma_filtro_sucess);
        $mural->parse('ERRO', '.erro');
    } 
    $mural->define_dynamic('filtro', 'pagina');
    $mural->clear_dynamic('filtro');
} 
// Excluir Filtro
/* ---------------------------------------------------------------- */
elseif ($_GET['acao'] == "excluir") {
    $mural->assign('{Titulo}', 'Administração - Excluir Filtros');
    $sql = mysql_query("SELECT id, msg_errada, msg_correta FROM filtro ORDER BY id DESC");
    $linha = mysql_num_rows($sql);
    $mural->define_dynamic('filtro', 'pagina');
    $mural->clear_dynamic('filtro');
    if ($linha != '0') {
        $mural->define_dynamic('erro', 'pagina');
        $mural->clear_dynamic('erro');
        $mural->define_dynamic('excluir_filtro', 'pagina');
        while ($resultado = mysql_fetch_array($sql)) {
            $mural->define_dynamic('visualizar_filtro', 'pagina');
            $mural->assign('{Excluir}', "?acao=deletar&confirma=sim&id=$resultado[id]");
            $mural->assign('{Id}', "$resultado[id]");
            $mural->assign('{Visualizar}', "<a href=\"?acao=deletar&confirma=sim&id=" . $resultado[id] . "\"><b>" . $resultado[msg_errada] . "</b></a>");
            $mural->assign('{Visualizar2}', "<a href=\"?acao=deletar&confirma=sim&id=" . $resultado[id] . "\">" . codifica_url($resultado[msg_correta]) . "</a>");
            $mural->parse('VISUALIZAR_FILTRO', '.visualizar_filtro');
        } 
        $mural->parse('EXCLUIR_FILTRO', '.excluir_filtro');
    } else {
        $mural->define_dynamic('excluir_filtro', 'pagina');
        $mural->clear_dynamic('excluir_filtro');
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', $idioma_sem_filtro);
        $mural->parse('ERRO', '.erro');
    } 
} elseif ($_GET['acao'] == "deletar" && $_GET['confirma'] == "sim") {
    if ($_GET['id'] != '') {
        $sql_del = mysql_query ("DELETE FROM filtro WHERE id='$_GET[id]'");
        mysql_query ("OPTIMIZE TABLE filtro");
        echo "<script language=javascript>location.replace('filtro.php?acao=excluir');</script>\n";
    } 
} else {
    $mural->assign('{Titulo}', 'Administração - Erro');
    $mural->define_dynamic('filtro', 'pagina');
    $mural->clear_dynamic('filtro');
    $mural->define_dynamic('excluir_filtro', 'pagina');
    $mural->clear_dynamic('excluir_filtro');
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