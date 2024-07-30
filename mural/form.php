<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// Cabeçalho
/* ---------------------------------------------------------------- */
require "biblioteca/class.img_validator.php";
require("biblioteca/class.fasttemplate.php");
require("config.php");
require("idiomas/$idioma");
require("funcoes.php");
session_start();
conecta();
$mural = new FastTemplate('templates/' . $template . '/');
$mural->define(array('pagina' => 'form.htm'));
$mural->assign('{Titulo}', $idioma_titulo);
$mural->assign('{Copyright}', $copyright);
$sql3 = mysql_query("SELECT ip FROM ip WHERE ip='$_SERVER[REMOTE_ADDR]'")or die("<font color=#FF0000><b>Erro: " . mysql_error() . "</b></font>");
$qtd_ip = mysql_num_rows($sql3);
mysql_free_result($sql3);
if ($qtd_ip != 0){
    header("Location: mural.php"); 
}
if(empty($imagem_seguranca)){
	$erro3 = 1;
   	$mural->define_dynamic('Imagem', 'pagina');
	$mural->clear_dynamic('Imagem');
}
$sql_s = mysql_query("SELECT * FROM `smilies` ORDER BY `cod` ASC LIMIT 0 , 24");
$mural->define_dynamic('Smilies', 'pagina');
while($smilies = mysql_fetch_array($sql_s)){
	$mural->assign('{cod_smilie}', $smilies['cod']);
	$mural->assign('{url_smilie}', $smilies['link']);
	$mural->assign('{alt_smilie}', $smilies['nome']);
	$mural->parse('SMILIES', '.Smilies');	
}

// Faz a inserção dos dados no mural
/* ---------------------------------------------------------------- */
if ($_POST['acao'] == "cadastrar") {
    $ip      = $_SERVER['REMOTE_ADDR'];
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $nome    = $_POST['nome'];
    $para    = $_POST['para'];
    $cidade  = $_POST['cidade'];
    $email   = $_POST['email'];
    $data    = date('d/m/y H:i');
    $msg     = codifica_msg(strip_tags($_POST['comentario']));
    $img = new img_validator();
    $img->checks_word($_POST["code"]);
	if ($bloqueio == 1) {
        $bloqueado = 1;
    } else {
        $bloqueado = 0;
    }
	if($erro3 == 1){
		$erro2 = 0;
	}
    if (empty($nome)) {
        $erro = $idioma_nome_vazio;
        $err = 1;
    } elseif (empty($msg)) {
        $erro = $idioma_msg_vazia;
        $err = 1;
    } elseif($erro2 == 1){
        $erro = $idioma_imagem_errada;
        $err = 1;
    } else {
        @mysql_query("INSERT INTO mural VALUES ('','$ip','$bloqueado','$browser','$nome','$para','$cidade','$email','$data','$msg')")
        or die("<font color=#FF0000><b>$idioma_inserir_dados</b></font>");
        setcookie("ip_mural", $ip, time() + (60 * $tempo_cookie), "/");
        if ($bloqueio == 1) {
            echo "<script language=\"javascript\">\n function bloqueio() {\n alert ('$idioma_bloqueio');\n }\n
            bloqueio()</script>\n";
        } 
        echo "<script language=\"javascript\">\n function closeWindow(){\n window.close();\n }
		\n opener.location.href = opener.location;\n setTimeout('closeWindow()', 100);
		\n </script>\n";
    } 
} 
if ($err == 1) {
    $mural->define_dynamic('Erro', 'pagina');
    $mural->assign("{Erro}", $erro);
    $mural->parse('ERRO', '.Erro');
} else {
    $mural->define_dynamic('Erro', 'pagina');
    $mural->clear_dynamic('Erro');
	$mural->define_dynamic('Imagem', 'pagina');
	$mural->parse('IMAGEM', '.Imagem');
} 

// Variáveis
/* ---------------------------------------------------------------- */
$mural->assign("{Nome}", $nome);
$mural->assign("{Para}", $para);
$mural->assign("{Email}", $email);
$mural->assign("{Mensagem}", decodifica_msg($msg));
$mural->assign("{IdiomaCaracteres}", $idioma_caracteres);
$mural->assign("{IdiomaCaracteres2}", $idioma_caracteres2);
$mural->assign("{IdiomaNome}", $idioma_nome);
$mural->assign("{IdiomaPara}", $idioma_para);
$mural->assign("{IdiomaCidade}", $idioma_cidade);
$mural->assign("{IdiomaEmail}", $idioma_email);
$mural->assign("{IdiomaMensagem}", $idioma_mensagem);
$mural->assign("{IdiomaEnviar}", $idioma_enviar);
$mural->assign("{IdiomaCodigo}", $idioma_codigo);
$mural->assign("{IdiomaVoltar}", $idioma_back_mural);
$mural->assign("{IdiomaEscritoImagem}", $idioma_escrito_imagem);

$mural->parse('SAIDA', 'pagina');
$mural->FastPrint('SAIDA');
mysql_close($conexao);

?>