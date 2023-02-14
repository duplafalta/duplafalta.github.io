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
$mural->define (array('pagina' => 'tpl_estatisticas.htm'));
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();
// Estatísticas do Mysql
/* ---------------------------------------------------------------- */
if ($_GET['tipo'] == 'mysql') {
    /*
	$sql = mysql_query("SHOW TABLE STATUS FROM mural");
    while ($resultado = mysql_fetch_array($sql)) {
        $mostra['tbl_cnt']++;
        $mostra['data_sz'] += $resultado['Data_length'];
        $mostra['idx_sz'] += $resultado['Index_length'];
    } 
    $tamanho01 = round($mostra['data_sz'] / 1024, 1);
    $tamanho02 = round($mostra['idx_sz'] / 1024, 1);
    $tamanho = $tamanho01 + $tamanho02;
	*/ 
    $mural->define_dynamic('mural', 'pagina');
    $mural->clear_dynamic('mural');
    $mural->assign('{Titulo}', 'Administração - Estatísticas do Banco de Dados - MySQL');
    $mural->define_dynamic('mysql', 'pagina');
    $mural->assign('{VersaoMysql}', strtoupper(mysql_get_server_info()));
    $mural->assign('{VersaoCliente}', strtoupper(mysql_get_client_info()));
    $mural->assign('{Host}', strtoupper(mysql_get_host_info()));
    //$mural->assign('{TamanhoMural}', $tamanho);
    $mural->parse('MYSQL', '.mysql');
} elseif ($_GET['tipo'] == 'mural') {
    $sql = mysql_query("SELECT data FROM mural ORDER BY id DESC");
    while ($mostra = mysql_fetch_array($sql)) {
        $primeiro_post = $mostra['data'];
    } 
    $sql2 = mysql_query("SELECT data FROM mural ORDER BY id ASC");
    while ($mostra2 = mysql_fetch_array($sql2)) {
        $ultimo_post = $mostra2['data'];
    } 
    $n_recados = mysql_num_rows($sql);
    $idioma = str_replace(".php", "", $idioma);
    $mural->define_dynamic('mysql', 'pagina');
    $mural->clear_dynamic('mysql');
    $mural->assign('{Titulo}', 'Administração - Estatísticas do Mural');
    $mural->define_dynamic('mural', 'pagina');
    $mural->assign('{Recados}', $n_recados);
    $mural->assign('{PrimeiroPost}', $primeiro_post);
    $mural->assign('{UltimoPost}', $ultimo_post);
    $mural->assign('{Template}', strtoupper($template));
    $mural->assign('{Idioma}', strtoupper($idioma));
    $mural->assign('{Flood}', $tempo_cookie);
    if ($tempo_cookie != 1) {
        $mural->assign('{s}', 's');
    } else {
        $mural->assign('{s}', '');
    } 
    $mural->parse('MURAL', '.mural');
} 
// Fim do Arquivo
/* ---------------------------------------------------------------- */
mysql_close($conexao);
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?> 