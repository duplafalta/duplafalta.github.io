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
$mural->define (array('pagina' => 'tpl_senha.htm'));
$mural->assign('{Titulo}', 'Administração - Alterar Senha');
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();
// Mudar Senha Sim
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == "senha" && $_GET['mudar'] == "sim") {
    $mural->define_dynamic('mudar_senha', 'pagina');
    $mural->clear_dynamic('mudar_senha');
    if (empty($_POST['usuario1'])) {
        $mural->assign('{Erro}', 'Digite o Nome de Usuário');
    } elseif (empty($_POST['senha1'])) {
        $mural->assign('{Erro}', 'Digite a Senha');
    } else {
        $pass = md5($_POST['senha1']);
        mysql_query ("UPDATE usuario SET usuario='$_POST[usuario1]', senha='$pass'");
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', $idioma_alter_sucess);
        $mural->parse('ERRO', '.erro');
        mysql_close($conexao);
    } 
} else {
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('mudar_senha', 'pagina');
    $mural->assign('{IdiomaAlterAdmin}', $idioma_alter_admin);
    $mural->assign('{IdiomaUsuarioLogin}', $idioma_usuario_login);
    $mural->assign('{IdiomaSenhaLogin}', $idioma_senha_login);
    $mural->assign('{IdiomaEnviarAdmin}', $idioma_enviar_admin);
    $mural->parse('MUDAR_SENHA', '.mudar_senha');
} 
// Fim do Arquivo
/* ---------------------------------------------------------------- */
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>