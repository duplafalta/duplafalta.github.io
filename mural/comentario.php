<?php
require("config.php");
require("biblioteca/class.fasttemplate.php");
require("idiomas/$idioma");
require("funcoes.php");
conecta();
$mural = new FastTemplate('templates/' . $template . '/');
$mural->define(array('pagina' => 'comentario.htm'));
$mural->assign('{Titulo}', $idioma_titulo);
$mural->assign('{Copyright}', $copyright);
$sql = mysql_query ("SELECT * FROM comentarios WHERE id_post='$_GET[id_post]' ORDER BY id_comentario DESC") or die ("Erro - " . mysql_error());
$linhas = mysql_num_rows($sql);
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
        $mural->assign('{Nome}', stripslashes(strip_tags($resultado['nome'], '<i>')));
        $mural->assign('{Email}', stripslashes(strtolower(strip_tags($resultado['email']))));
        $mural->assign('{Data}', $resultado['data']);
        $mural->assign('{Mensagem}', codifica_url(filtro(stripslashes(quebra_linha($resultado['mensagem'], 40)))));
        $mural->assign('{IDPost}', $resultado['id']);
		$mural->parse('TABELA', '.tabela');
    } 
} 
if ($_POST['acao'] == "cadastrar") {
    $ip      = $_SERVER['REMOTE_ADDR'];
	$id_post = $_POST['id_post'];
    $nome    = $_POST['nome'];
    $email   = $_POST['email'];
    $data    = date('d/m/y H:i');
    $msg     = codifica_msg(strip_tags($_POST['comentario']));
    if (empty($nome)) {
        $erro = $idioma_nome_vazio;
        $err = 1;
    } elseif (empty($msg)) {
        $erro = $idioma_msg_vazia;
        $err = 1;
    } else {
        @mysql_query("INSERT INTO comentarios VALUES ('','$ip','$id_post','$nome','$email','$data','$msg')")
        or die("<font color=#FF0000><b>$idioma_inserir_dados</b></font>");
		echo "<script language=javascript>location.replace('comentario.php?id_post=$id_post');</script>\n";
	}        
}
if ($err == 1) {
   	$mural->define_dynamic('Erro', 'pagina');
   	$mural->assign("{Erro}", $erro);
   	$mural->parse('ERRO', '.Erro');
} else {
   	$mural->define_dynamic('Erro', 'pagina');
   	$mural->clear_dynamic('Erro');
} 
// Variáveis
/* ---------------------------------------------------------------- */
$mural->assign("{IdiomaCaracteres}", $idioma_caracteres);
$mural->assign("{IdiomaCaracteres2}", $idioma_caracteres2);
$mural->assign("{IdiomaNome}", $idioma_nome);
$mural->assign("{IdiomaEmail}", $idioma_email);
$mural->assign("{IdiomaMensagem}", $idioma_mensagem);
$mural->assign("{IdiomaEnviar}", $idioma_enviar);
$mural->assign("{IDPost}", $_GET['id_post']);

$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');
?>