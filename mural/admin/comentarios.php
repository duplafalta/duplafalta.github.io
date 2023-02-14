<?php
require("../config.php");
require("../biblioteca/class.fasttemplate.php");
require("../idiomas/$idioma");
require("../funcoes.php");
conecta();
$mural = new FastTemplate('tpl/');
$mural->define(array('pagina' => 'tpl_comentarios.htm'));
$mural->assign('{Titulo}', $idioma_titulo);
$mural->assign('{Copyright}', $copyright);
$sql = mysql_query ("SELECT * FROM comentarios WHERE id_post='$_GET[id_post]' ORDER BY id_comentario DESC") or die ("Erro - " . mysql_error());
$linhas = mysql_num_rows($sql);
// Resultados na tela
/* ---------------------------------------------------------------- */
if ($linhas == 0) {
    $mural->define_dynamic('erro', 'pagina');
    $mural->assign('{Erro}', 'Nenhum comentário encontrado');
    $mural->parse('ERRO', '.erro');
    $mural->define_dynamic('tabela', 'pagina');
    $mural->clear_dynamic('tabela');
} else {
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('tabela', 'pagina');
    while ($resultado = mysql_fetch_array($sql)) {
        $mural->assign('{Nome}', stripslashes(strip_tags($resultado['nome'], '<i>')));
        $mural->assign('{Email}', stripslashes(strtolower(strip_tags($resultado['email']))));
        $mural->assign('{Data}', $resultado['data']);
        $mural->assign('{Mensagem}', codifica_url(filtro(stripslashes(quebra_linha($resultado['mensagem'], 40)))));
        $mural->assign('{id_comment}', $resultado['id_comentario']);
		$mural->parse('TABELA', '.tabela');
		$mural->assign("{id_post}", $_GET['id_post']);
    } 
} 
if ($_GET['acao'] == "excluir") {
    if ($_GET['id_comment'] != '') {
    	mysql_query("DELETE FROM comentarios WHERE id_comentario='$_GET[id_comment]'") or die ("Erro ao apagar registro - ". mysql_error());
    } 
        echo "<script language=javascript>location.replace('comentarios.php?id_post=$_GET[id_post]');</script>\n";
    echo "<script language=javascript>location.replace('comentarios.php?id_post=$_GET[id_post]');</script>\n";
}
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');
?>