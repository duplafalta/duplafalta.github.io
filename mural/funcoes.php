<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
// Conexão com o banco de dados
function conecta()
{
    global $host, $usuario, $senha;
    global $banco, $db, $conexao;
    global $idioma_conectar, $idioma_selecionar;
    $conexao = @mysql_connect($host, $usuario, $senha) or die("<b>$idioma_conectar - \"" . mysql_error() . "\"</b>");
    $db = @mysql_select_db($banco, $conexao) or die("<b>$idioma_selecionar - \"" . mysql_error() . "\"</b>");
} 
// Por favor, não retire o copyright
$copyright = "<p align=center class=tabela>Powered by <a href=mailto:inter4u@inter4u.com.br>Inter4u</a><br>$idioma_versao $v_mural - Php " . phpversion() . "</p>";
// Codifica as mensagens
function codifica_msg($msg)
{
    if ($msg) {
        $msg = str_replace("\r", "", $msg);
        $msg = str_replace("\n", "<br>", $msg);
        $resultado = mysql_query("SELECT * FROM smilies");
        while ($mostra = mysql_fetch_array($resultado)) {
            $msg = str_replace($mostra['cod'], "[img]imagens/smilies/$mostra[link][/img]", $msg);
        } 
    } 
    return $msg;
} 
// Decodifica as mensagens
function decodifica_msg($msg)
{
    if ($msg) {
        $msg = str_replace("<br>", "\n", $msg);
        $resultado = mysql_query("SELECT * FROM smilies");
        while ($mostra = mysql_fetch_array($resultado)) {
            $msg = str_replace("[img]imagens/smilies/$mostra[link][/img]", $mostra['cod'], $msg);
        } 
    } 
    return $msg;
} 
// Codifica o url das mensagens
function codifica_url($msg)
{
    if (get_magic_quotes_gpc() != 1) {
        $msg = addslashes($msg);
    } 
    $msg = nl2br($msg);
    $msg = ereg_replace("javascript", "", $msg);
    $msg = eregi_replace(quotemeta("[b]"), quotemeta("<b>"), $msg);
    $msg = eregi_replace(quotemeta("[/b]"), quotemeta("</b>"), $msg);
    $msg = eregi_replace(quotemeta("[i]"), quotemeta("<i>"), $msg);
    $msg = eregi_replace(quotemeta("[/i]"), quotemeta("</i>"), $msg);
    $msg = eregi_replace(quotemeta("[u]"), quotemeta("<u>"), $msg);
    $msg = eregi_replace(quotemeta("[/u]"), quotemeta("</u>"), $msg);
    $msg = eregi_replace("\\[url\\]www.([^\\[]*)\\[/url\\]", "<a href=\"http://www.\\1\" target=_blank>\\1</a>", $msg);
    $msg = eregi_replace("\\[url\\]([^\\[]*)\\[/url\\]", "<a href=\"\\1\" target=_blank>\\1</a>", $msg);
    $msg = eregi_replace("\\[url=([^\\[]*)\\]([^\\[]*)\\[/url\\]", "<a href=\"\\1\" target=_blank>\\2</a>", $msg);
    $msg = eregi_replace("\\[email\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\1</a>", $msg);
    $msg = eregi_replace("\\[img\\]([^\\[]*)\\[/img\\]", "<img src=\"\\1\" border=0>", $msg);
    return $msg;
} 
// Filtro de Palavrões
function filtro($msg)
{
    if ($msg) {
        $resultado = mysql_query("SELECT * FROM filtro");
        while ($mostra = mysql_fetch_array($resultado)) {
            $msg = str_replace($mostra['msg_errada'], $mostra['msg_correta'], $msg);
        } 
    } 
    return $msg;
} 
// Faz a quebra de linhas
function quebra_linha($msg, $maximo = 55)
{
    $letras = explode(' ' , eregi_replace('<br>', ' ', $msg));
    for($i = 0 ; $i < count($letras) ; $i++) {
        if (strlen($letras[$i]) > $maximo) {
            $msg = eregi_replace($letras[$i], chunk_split($letras[$i], $maximo), $msg);
        } 
    } 
    return $msg;
} 
// Determina um tamanho para as frases
function cortar($msg, $quantidade = 66)
{
    $tamanho = strlen($msg);
    if ($tamanho > $quantidade)
        $msg = substr_replace($msg, "...", $quantidade, $tamanho - $quantidade);
    return $msg;
} 
// Codifica os smilies
function cod_smilie($var, $qte=5)
{
	$var = strtolower($var);
	$var = ereg_replace(' ','_', $var);
	$var = ereg_replace('\.','_', $var);
	$var = substr($var, 0, $qte);
	$var = substr_replace($var, ':', 0, 0);
	$var = substr_replace($var, ':', $qte + 1, -1);
	return $var;
}	
// Verifica sessão
function verifica_sessao()
{
    session_start();
    if ((!isset($_SESSION[mUsuario])) AND (!isset($_SESSION[mSenha]))) {
        header("Location: ../login.php");
    } 
} 
// Sai da administração
function fechar_sessao()
{
    session_start();
    unset($_SESSION[mUsuario]);
    unset($_SESSION[mSenha]);
    header('Location: ../mural.php');
} 

/* ---------------------------------------------------------------- */
?>