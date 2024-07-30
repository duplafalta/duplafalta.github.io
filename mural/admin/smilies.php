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
$uploaddir = "../imagens/smilies/";
$uploaddir2 = "../imagens/";
$mural = new FastTemplate('tpl/');
$mural->define (array('pagina' => 'tpl_smilies.htm'));
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();
// Adicionar Smilies
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == "adicionar") {
    if(is_writable($uploaddir) AND is_writable($uploaddir2)){
		$mural->define_dynamic('smilies', 'pagina');
    	$mural->assign('{Titulo}', 'Administração - Adicionar Smilies');
    	$mural->define_dynamic('erro', 'pagina');
    	$mural->clear_dynamic('erro');
    	$mural->define_dynamic('edit_smilies', 'pagina');
    	$mural->clear_dynamic('edit_smilies');
    	$mural->parse('SMILIES', '.smilies');
	}
	else{
		$mural->define_dynamic('erro', 'pagina');
    	$mural->assign('{Titulo}', 'Administração - Adicionar Smilies');
		$mural->assign('{Erro}', 'AS pastas IMAGENS e SMILIES precisam ter permissão de leitura');
		$mural->parse('{ERRO}', '.erro');
    	$mural->define_dynamic('smilies', 'pagina');
    	$mural->clear_dynamic('smilies');
    	$mural->define_dynamic('edit_smilies', 'pagina');
    	$mural->clear_dynamic('edit_smilies');
	}		
}
if ($_GET['modo'] == "add" && $_GET['executar'] == "ok") {
    $mural->assign('{Titulo}', 'Administração - Adicionar Smilies');
	$st01 = $_FILES['smilie01']['tmp_name'];
	$st02 = $_FILES['smilie02']['tmp_name'];
   	$st03 = $_FILES['smilie03']['tmp_name'];
	$st04 = $_FILES['smilie04']['tmp_name'];
    $sn01 = $_FILES['smilie01']['name'];
    $sn02 = $_FILES['smilie02']['name'];
    $sn03 = $_FILES['smilie03']['name'];
    $sn04 = $_FILES['smilie04']['name'];
	$sc01 = cod_smilie($sn01);
	$sc02 = cod_smilie($sn02);
	$sc03 = cod_smilie($sn03);
	$sc04 = cod_smilie($sn04);
	clearstatcache();
	if(!file_exists($uploaddir . $sn01)){
		mysql_query("INSERT INTO smilies VALUES ('$sc01', '$sn01')") or die(mysql_error());
		$sm01 = move_uploaded_file($st01, $uploaddir . $sn01);
	}
	if(!file_exists($uploaddir . $sn02)){
		mysql_query("INSERT INTO smilies VALUES ('$sc02', '$sn02')") or die(mysql_error());
		$sm02 = move_uploaded_file($st02, $uploaddir . $sn02);
	}
	if(!file_exists($uploaddir . $sn03)){
		mysql_query("INSERT INTO smilies VALUES ('$sc03', '$sn03')") or die(mysql_error());
   		$sm03 = move_uploaded_file($st03, $uploaddir . $sn03);
	}
	if(!file_exists($uploaddir . $sn04)){
		mysql_query("INSERT INTO smilies VALUES ('$sc04', '$sn04')") or die(mysql_error());
		$sm04 = move_uploaded_file($st04, $uploaddir . $sn04);
	}
	$mural->define_dynamic('edit_smilies', 'pagina');
    $mural->clear_dynamic('edit_smilies');
    $mural->define_dynamic('smilies', 'pagina');
    $mural->clear_dynamic('smilies');
    $mural->define_dynamic('erro', 'pagina');
    $mural->assign('{Erro}', 'Smilies adicionados com sucesso<br> <a href=\"javascript:history.back(-1);\">Voltar</a>');
    $mural->parse('ERRO', '.erro');
} 
// Listar Smilies
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == "listar") {
    $mural->define_dynamic('smilies', 'pagina');
    $mural->clear_dynamic('smilies');
    $mural->assign('{Titulo}', 'Administração - Listar Smilies');
	$mural->define_dynamic('edit_smilies', 'pagina');
	$mural->define_dynamic('view_smilies', 'pagina');
	if ($handle = opendir($uploaddir)) {
   		while (false !== ($file = readdir($handle))) {
       		if ($file != "." && $file != "..") {
				$imagens[$indice] = $files;
				$indice++;
					$mural->assign('{link_smilies}', $uploaddir . $file);
					$mural->assign('{nome_smilies}', $file);
					$mural->assign('{code_smilies}', cod_smilie($file, 5));
					$mural->parse('VIEW_SMILIES', '.view_smilies');
       		}
   		}
   	closedir($handle);
	}
    $linhas = ceil(count($imagens));
	if($linhas == 0){
		$mural->define_dynamic('edit_smilies', 'pagina');
		$mural->clear_dynamic('edit_smilies');
        $mural->define_dynamic('erro', 'pagina');
		$mural->assign('{Erro}', 'Nenhum smilie encontrado');
		$mural->parse('ERRO', '.erro');				
	}
	else{		
		$mural->define_dynamic('erro', 'pagina');
        $mural->clear_dynamic('erro');
		$mural->parse('EDIT_SMILIES', '.edit_smilies');
	}
}
// Excluir Smilies
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == "excluir") {
	if(file_exists($uploaddir . $_GET['smilie'])){
		unlink($uploaddir . $_GET['smilie']);
		mysql_query ("DELETE FROM smilies WHERE link='$_GET[smilie]'");
		mysql_query ("OPTIMIZE TABLE smilies");
		echo "<script language=javascript>location.replace('smilies.php?acao=listar');</script>\n";
	} 
}
// Fim do Arquivo
/* ---------------------------------------------------------------- */
mysql_close($conexao);
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>