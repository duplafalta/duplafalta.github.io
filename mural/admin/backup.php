<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// Cabeçalho
/* ---------------------------------------------------------------- */
require_once("../biblioteca/class.fasttemplate.php");
require_once("../config.php");
require_once("../idiomas/$idioma");
require_once("../funcoes.php");
$mural = new FastTemplate('tpl/');
$mural->define (array('pagina' => 'tpl_backup.htm'));
$mural->assign('{Titulo}', 'Administração - Backup do Banco de Dados');
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();
// Backup
/* ---------------------------------------------------------------- */
if ($_GET['opcao'] == 'backup') {
    if ($_GET['opcao'] == 'backup' AND $_GET['acao'] == 'backup') {
        require_once("../biblioteca/class.backup.php");
        if ($_POST['compactar'] == true) {
            $back = true;
        } else {
            $back = false;
        } 
        $backup = new iam_backup($host, $banco, $usuario, $senha, false, true, $back);
        $backup->perform_backup();
        exit; // importante
    } else {
        $mural->define_dynamic('restore', 'pagina');
        $mural->clear_dynamic('restore');
        $mural->define_dynamic('erro', 'pagina');
        $mural->clear_dynamic('erro');
        $mural->define_dynamic('backup', 'pagina');
        $mural->parse('CONFIG', '.backup');
    } 
} 
// Restore
/* ---------------------------------------------------------------- */
if ($_GET['opcao'] == 'restore') {
    if ($_GET['opcao'] == 'restore' AND $_GET['acao'] == 'restore') {
        require_once("../biblioteca/class.restore.php");
        $arquivo = $_FILES['arquivo']['tmp_name'];
        $restore = new iam_restore($arquivo, $host, $banco, $usuario, $senha);
        $restore->perform_restore();
        $mural->define_dynamic('restore', 'pagina');
        $mural->clear_dynamic('restore');
        $mural->define_dynamic('erro', 'pagina');
        $mural->assign('{Erro}', 'Restauração efetuada com sucesso!');
        $mural->parse('ERRO', '.erro');
    } 
    $mural->define_dynamic('backup', 'pagina');
    $mural->clear_dynamic('backup');
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('restore', 'pagina');
    $mural->parse('CONFIG', '.restore');
} 
// Fim do Arquivo
/* ---------------------------------------------------------------- */
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>