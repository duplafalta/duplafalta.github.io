<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// Cabeçalho
/* ---------------------------------------------------------------- */
require("biblioteca/class.fasttemplate.php");
require("config.php");
require("idiomas/$idioma");
require("funcoes.php");
$mural = new FastTemplate('templates/' . $template . '/');
$mural->define (array('pagina' => 'login.htm'));
$mural->assign('{Titulo}', $idioma_titulo);
$mural->assign('{Copyright}', $copyright);
// Inicio
/* ---------------------------------------------------------------- */
$mural->assign('{Erro}', '');
$mural->assign('{IdiomaTituloAdmin}', $idioma_tit_login);
$mural->assign('{IdiomaUsuario}', $idioma_usuario_login);
$mural->assign('{IdiomaSenha}', $idioma_senha_login);
$mural->assign('{IdiomaEntrar}', $idioma_entrar_login);
if ($_GET['acao'] == "logar") {
    conecta();
    $pass = md5($_POST['mSenha']);
    $sql = mysql_query("SELECT usuario,senha FROM usuario WHERE usuario='$_POST[mUsuario]' AND senha='$pass'");
    $linha = mysql_num_rows($sql);
    if ($linha == 0) {
        $mural->assign('{Erro}', $idioma_erro_login);
    } else {
        $user = mysql_result($sql, 0, 'usuario');
        $pass = mysql_result($sql, 0, 'senha');
        session_start();
        $_SESSION[mUsuario] = $user;
        $_SESSION[mSenha] = $pass;
        header("Location: admin/");
    } 
} 
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>