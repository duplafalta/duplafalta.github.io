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
$mural->define (array('pagina' => 'tpl_ajustes.htm'));
$mural->assign('{Titulo}', 'Administração - Configurações Principais do Mural');
// Segurança
/* ---------------------------------------------------------------- */
conecta();
verifica_sessao();
// Inserção das modificações do config
/* ---------------------------------------------------------------- */
if ($_GET['acao'] == 'configurar') {
    $fp = fopen("../config.php", "w");
        $config = "<?php\n";
        $config .= "\$host             = '$_POST[host]';\n";
        $config .= "\$usuario          = '$_POST[usuario]';\n";
        $config .= "\$senha            = '$_POST[senha]';\n";
        $config .= "\$banco            = '$_POST[banco]';\n";
        $config .= "\$total_reg        = '$_POST[exibido]';\n";
        $config .= "\$idioma           = '$_POST[idioma]';\n";
        $config .= "\$v_mural          = '$v_mural';\n";
        $config .= "\$tempo_cookie     = '$_POST[tempo_cookie]';\n";
        $config .= "\$template         = '$_POST[template]';\n";
        $config .= "\$bloqueio         = '$_POST[bloqueio]';\n";
        $config .= "\$instalado        = 's';\n";
		$config .= "\$imagem_seguranca = '$_POST[imagem_seguranca]';\n";
        $config .= "?>\n";
    fputs($fp, $config);
    fclose($fp);
    $mural->define_dynamic('config', 'pagina');
    $mural->clear_dynamic('config');
    $mural->define_dynamic('erro', 'pagina');
    $mural->assign('{Erro}', 'Mural Configurado!');
    $mural->parse('ERRO', '.erro');
} 
// Mostra o formulário
/* ---------------------------------------------------------------- */
else {
    $mural->define_dynamic('erro', 'pagina');
    $mural->clear_dynamic('erro');
    $mural->define_dynamic('config', 'pagina');
    $mural->assign('{Host}', $host);
    $mural->assign('{Usuario}', $usuario);
    $mural->assign('{Senha}', $senha);
    $mural->assign('{Banco}', $banco);
    $mural->assign('{TotalReg}', $total_reg);
    $mural->assign('{TempoCookie}', $tempo_cookie);
	$mural->assign('{Idioma}', $idioma);
	$mural->assign('{Template}', $template);
    if ($bloqueio == 1) {
        $mural->assign('{Check}', 'checked');
        $mural->assign('{Mod}', 'Sim');
    } else {
        $mural->assign('{Check}', '');
        $mural->assign('{Mod}', 'Não');
    } 
    if ($imagem_seguranca == 1) {
        $mural->assign('{Check2}', 'checked');
        $mural->assign('{Imagem}', 'Sim');
    } else {
        $mural->assign('{Check2}', '');
        $mural->assign('{Imagem}', 'Não');
    } 
	$mural->define_dynamic('select_idioma', 'pagina');
    $dir = opendir('../idiomas');
    while (false != ($file = readdir($dir))) {
        if ($file != "." && $file != "..") {
            $select_idioma = "<option value=\"" . $file . "\"";
            if ($file == $idioma) {
                $select_idioma .= " selected";
            } 
            $select_idioma .= " >";
            $select_idioma .= "" . $file . "</option>";
            $mural->assign('{SelectIdioma}', $select_idioma);
            $mural->parse('SELECT_IDIOMA', '.select_idioma');
        } 
    } 
    closedir($dir);
    $mural->define_dynamic('select_template', 'pagina');
    $dir = opendir('../templates');
    while (false != ($file = readdir($dir))) {
        if ($file != "." && $file != "..") {
            $select_template = "<option value=\"" . $file . "\"";
            if ($file == $template) {
                $select_template .= " selected";
            } 
            $select_template .= " >";
            $select_template .= "" . $file . "</option>";
            $mural->assign('{SelectTemplate}', $select_template);
            $mural->parse('SELECT_TEMPLATE', '.select_template');
        } 
    } 
    closedir($dir);
    $mural->parse('CONFIG', '.config');
} 
// Fim do Arquivo
/* ---------------------------------------------------------------- */
$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');

?>