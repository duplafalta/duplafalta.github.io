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
$uploaddir = "imagens/smilies/";
$mural = new FastTemplate('templates/' . $template . '/');
$mural->define (array('pagina' => 'smilies.htm'));

// Listar Smilies
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == "listar") {
    $mural->define_dynamic('smilies', 'pagina');
    $mural->clear_dynamic('smilies');
    $mural->assign('{Titulo}', 'Administração - Listar Smilies');
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
		$mural->define_dynamic('view_smilies', 'pagina');
		$mural->clear_dynamic('view_smilies');
        $mural->define_dynamic('erro', 'pagina');
		$mural->assign('{Erro}', 'Nenhum smilie encontrado');
		$mural->parse('ERRO', '.erro');				
	}
	else{		
		$mural->define_dynamic('erro', 'pagina');
        $mural->clear_dynamic('erro');
	}
}

// Fim do Arquivo
/* ---------------------------------------------------------------- */
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>