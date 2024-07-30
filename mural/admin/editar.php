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
$mural->define (array('pagina' => 'tpl_editar.htm'));
$mural->assign('{Titulo}', "Administração - Edição de Post - ID " . $_GET['id'] . "");
conecta();
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();
// Editar Post
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == "editar") {
    $sql = mysql_query("SELECT * FROM mural WHERE id='$_GET[id]'");
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('edicao', 'pagina');
    $mural->assign('{IdiomaNome}', $idioma_nome);
    $mural->assign('{IdiomaPara}', $idioma_para);
    $mural->assign('{IdiomaEmail}', $idioma_email);
    $mural->assign('{IdiomaMensagem}', $idioma_mensagem);
    $mural->assign('{IdiomaEnviar}', $idioma_enviar);
    $mural->assign('{Codigo}', $_GET['id']);
    while ($resultado = mysql_fetch_array($sql)) {
        $mural->assign('{Nome}', $resultado['nome']);
        $mural->assign('{Para}', $resultado['para']);
        $mural->assign('{Email}', $resultado['email']);
		$mural->assign('{Cidade}', $resultado['cidade']);
        $mural->assign('{Msg}', decodifica_msg($resultado['mensagem']));
    } 
    $mural->parse('EDITAR', '.edicao');
} elseif ($_GET['acao'] == "editar_post" && $_GET['id'] == $_GET['id'] && $_GET['editar'] == "sim") {
    $msg = codifica_msg($_POST['comentario']);
    mysql_query("UPDATE mural SET nome='$_POST[nome]', para='$_POST[para]', cidade='$_POST[cidade]', email='$_POST[email]', mensagem='$msg' WHERE id='$_GET[id]'")
    or die ("Erro ao editar post");
    $mural->define_dynamic('erro', 'pagina');
    $mural->assign('{Erro}', "$idioma_edicao_sucess <br> <a href=\"visualizar.php\">$idioma_edicao_outras</a>");
    $mural->parse('ERRO', '.erro');
    $mural->define_dynamic('edicao', 'pagina');
    $mural->clear_dynamic('edicao');
} else {
    $mural->define_dynamic('edicao', 'pagina');
    $mural->clear_dynamic('edicao');
    $mural->define_dynamic('erro', 'pagina');
    $mural->assign('{Erro}', 'Opção inválida ou ID incorreto!');
} 
// Fim do Arquivo
/* ---------------------------------------------------------------- */
mysql_close($conexao);
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>