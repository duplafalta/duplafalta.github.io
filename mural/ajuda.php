<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// Cabeçalho
/* ---------------------------------------------------------------- */
require("biblioteca/class.fasttemplate.php");
require("config.php");
require("idiomas/$idioma");
$mural = new FastTemplate('templates/' . $template . '/');
$mural->define (array('pagina' => 'ajuda.htm'));
$mural->assign('{IdiomaAjudaTitulo}', $idioma_ajuda_titulo);
$mural->assign('{IdiomaAjudaTit}', $idioma_ajuda_tit);
$mural->assign('{IdiomaAjudaEnd}', $idioma_ajuda_end);
$mural->assign('{IdiomaAjudaOu}', $idioma_ajuda_ou);
$mural->assign('{IdiomaEmail}', $idioma_email);
$mural->assign('{IdiomaSublinhado}', $idioma_sublinhado);
$mural->assign('{IdiomaItalico}', $idioma_italico);
$mural->assign('{IdiomaNegrito}', $idioma_negrito);
$mural->assign('{IdiomaFechar}', $idioma_fechar);
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>