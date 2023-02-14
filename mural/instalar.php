<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/

if (is_writable('config.php')) {
    include_once("config.php");
    include_once("funcoes.php");
    include_once("idiomas/$idioma");
	$url = $_SERVER['HTTP_HOST'] . "/mural/";
    ?>
<html>
<head>
<title><?php echo $idioma_titulo;

    ?> - Instalação</title>
<style type="text/css">
body {
    scrollbar-face-color:#C0C0C0;
    scrollbar-highlight-color:#FFFFFF;
    scrollbar-3dlight-color:#C0C0C0;
    scrollbar-darkshadow-color:#000000;
    scrollbar-shadow-color:#808080;
    scrollbar-arrow-color:#8B8B8B;
    scrollbar-track-color:#E0E0E0;
}	
.tabela {
	font-family: Verdana, Arial;
	font-size: 10px;
	color: #000000;
}
.tabela3 {
	font-family: Verdana, Arial;
	font-size: 10px;
	letter-spacing: normal;
	word-spacing: normal;
	border-top: 1px solid #666666;
	border-right: 1px solid #666666;
	border-bottom: 1px solid #666666;
	border-left: 1px solid #666666;
}
.tabela4 {
	font-family: Verdana, Arial;
	font-size: 10px;
	letter-spacing: normal;
	word-spacing: normal;
	border-top: 1px solid #666666;
	border-right: 1px solid #666666;
	border-bottom: 1px solid #666666;
	border-left: 1px solid #666666;
}
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	color: #000000;
	text-decoration: none;
}
a:hover {
	color: #000000;
	text-decoration: underline;
}
.campos {
	font-family: Verdana, Arial;
	font-size: 10px;
	color: #000000;
	border: 1px solid #000000;
}
.botao {
	font-family: Verdana, Arial;
	font-size: 10px;
	color: #000000;
	background-color: #FFFFFF;
	border: 1px solid #000000;
}
.botao2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	border: 1px solid #000000;
}
.campos2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	border: 1px solid #000000;
}
.campoerro {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	border: 1px solid #FF0000;
	background-color: #FFE1E1;
}
.regua {
    border: 1px ridge;
}
.style1 {color: #FF0000}
.style3 {color: #FF0000; font-weight: bold; }
</style>
</head>
<body>
<table width="488" border="0" align="center" cellpadding="6" cellspacing="0" class="tabela">
  <tr> 
    <td width="118"><div align="center"><b><img src="imagens/instalar.gif" width="118" height="110"></b></div></td>
    <td width="361"><b><font color="#FF0000" size="4">INSTALA&Ccedil;&Atilde;O DO MURAL</font></b></td>
  </tr>
  <tr> 
    <td colspan="2"><div align="justify">
<?php
    if ($_GET['acao'] == "instalar" && $_GET['etapa'] == 1) {
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
        $fp = fopen("config.php", "w");
        fputs($fp, $config);
        fclose($fp); ?>
<p align="left"><font color="#FF0000">
  <strong>INSTRU&Ccedil;&Otilde;ES:</strong><br> Primeira parte conclu&iacute;da</font><br>
  Clique no bot&atilde;o prosseguir para continuar a instala&ccedil;&atilde;o<br>
  </p><form action="instalar.php?acao=instalar" method="post">
  <input type="hidden" name="opcao" value="<?php echo $_POST["opcao"] ; ?>">
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabela">
  <tr><td width="100%"><div align="center">
  <input type="hidden" name="url" value="<?php echo $_POST['url']; ?>">
  <input name=Submit type=submit class="botao" value="PROSSEGUIR">
  </div></td></tr></table></form>
<?php
    } elseif ($_GET['acao'] == "instalar" && $_POST['opcao'] == 2) {
        conecta();

        mysql_query("DROP TABLE IF EXISTS mural");
        mysql_query("CREATE TABLE `mural` (
  		`id` smallint(6) NOT NULL auto_increment,
  		`ip` varchar(20) default NULL,
  		`bloqueado` smallint(6) NOT NULL default '0',
  		`browser` varchar(100) default NULL,
  		`nome` varchar(100) NOT NULL default '',
  		`para` varchar(30) default NULL,
 		 `cidade` varchar(200) default NULL,
  		`email` varchar(200) default NULL,
  		`data` varchar(24) default NULL,
  		`mensagem` text NOT NULL,
  		PRIMARY KEY  (`id`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela mural (" . mysql_error() . ")");
        $erro = "Tabela mural adicionada<br>";

        mysql_query("DROP TABLE IF EXISTS smilies");
        mysql_query("CREATE TABLE `smilies` (
  		`cod` varchar(30) NOT NULL default '',
  		`link` varchar(20) NOT NULL default '',
  		PRIMARY KEY  (`cod`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela smilies (" . mysql_error() . ")");
        $erro .= "Tabela smilies adicionada<br>";

        mysql_query("DROP TABLE IF EXISTS usuario");
        mysql_query("CREATE TABLE `usuario` (
  		`usuario` varchar(50) NOT NULL default '',
  		`senha` varchar(50) NOT NULL default '',
  		PRIMARY KEY  (`usuario`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela usuario (" . mysql_error() . ")");
        $erro .= "Tabela usuario adicionada<br>";

        mysql_query("DROP TABLE IF EXISTS filtro");
        mysql_query("CREATE TABLE `filtro` (
  		`id` smallint(6) NOT NULL auto_increment,
  		`msg_errada` varchar(200) default NULL,
  		`msg_correta` varchar(200) default NULL,
 		 PRIMARY KEY  (`id`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela filtro (" . mysql_error() . ")");
        $erro .= "Tabela filtro adicionada<br>";

        mysql_query("DROP TABLE IF EXISTS ip");
        mysql_query("CREATE TABLE `ip` (
  		`id` int(11) NOT NULL auto_increment,
  		`ip` varchar(16) NOT NULL default '',
  		PRIMARY KEY  (`id`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela ip (" . mysql_error() . ")");
        $erro .= "Tabela ip adicionada<br>";
		
        mysql_query("DROP TABLE IF EXISTS comentarios");
        mysql_query("CREATE TABLE `comentarios` (
  		`id_comentario` int(11) NOT NULL auto_increment,
  		`ip` varchar(18) NOT NULL default '',
  		`id_post` int(11) NOT NULL default '0',
  		`nome` varchar(200) NOT NULL default '',
  		`email` varchar(200) NOT NULL default '',
  		`data` varchar(30) NOT NULL default '',
  		`mensagem` text NOT NULL,
  		PRIMARY KEY  (`id_comentario`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela comentarios (" . mysql_error() . ")");
        $erro .= "Tabela comentarios adicionada";

        mysql_close($conexao); ?>
<p align="left">  <span class="style3"><?php echo $erro; ?></span><br>
  Para concluir a instala&ccedil;&atilde;o, coloque seu nome de usuario e senha 
  para a administra&ccedil;&atilde;o<br>
  </p>
<form action="instalar.php?acao=instalar&etapa=3" method="post">
  <table width=100% border=0 cellpadding=3 cellspacing=0 class=tabela>
  <tr><td width=7%>Usu&aacute;rio:</td>
  <td width=93%><input name="user" type="text" class="campos2" size="30"></td>
  </tr><tr><td>Senha:</td>
  <td><input name="password" type="password" class="campos2" size="30"></td>
  </tr><tr><td colspan=2> <div align=center><br>
  <input type="hidden" name="url" value="<?php echo $_POST['url']; ?>">
  <input name="Submit" type="submit" class="botao" value="FINALIZAR">
  </div></td></tr></table></form>
<?php
    } elseif ($_GET['acao'] == "instalar" && $_POST['opcao'] == 3) {
        conecta();

        mysql_query("DROP TABLE IF EXISTS smilies");
        mysql_query("CREATE TABLE `smilies` (
  		`cod` varchar(30) NOT NULL default '',
  		`link` varchar(20) NOT NULL default '',
  		PRIMARY KEY  (`cod`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela smilies (" . mysql_error() . ")");
        $erro .= "Tabela smilies adicionada<br>";

	    mysql_query("DROP TABLE IF EXISTS ip");
        mysql_query("CREATE TABLE `ip` (
  		`id` int(11) NOT NULL auto_increment,
  		`ip` varchar(16) NOT NULL default '',
  		PRIMARY KEY  (`id`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela ip (" . mysql_error() . ")");
        $erro .= "Tabela ip adicionada<br>";
		
        mysql_query("DROP TABLE IF EXISTS comentarios");
        mysql_query("CREATE TABLE `comentarios` (
  		`id_comentario` int(11) NOT NULL auto_increment,
  		`ip` varchar(18) NOT NULL default '',
  		`id_post` int(11) NOT NULL default '0',
  		`nome` varchar(200) NOT NULL default '',
  		`email` varchar(200) NOT NULL default '',
  		`data` varchar(30) NOT NULL default '',
  		`mensagem` text NOT NULL,
  		PRIMARY KEY  (`id_comentario`)
		) TYPE=MyISAM") or die("Ocorreu um erro ao adicionar a tabela comentarios (" . mysql_error() . ")");
        $erro .= "Tabela comentarios adicionada<br>";

        mysql_query("ALTER TABLE `mural` ADD `bloqueado` SMALLINT NOT NULL AFTER `ip` ;");
        mysql_query("ALTER TABLE `mural` CHANGE `browser` `browser` VARCHAR( 100 ) ");
        mysql_query("ALTER TABLE `mural` ADD `cidade` VARCHAR( 200 ) AFTER `para` ;");
        mysql_query("ALTER TABLE `mural` CHANGE `email` `email` VARCHAR( 30 )");

        $erro .= "Tabela mural atualizada<br>";

        mysql_query("ALTER TABLE `usuario` CHANGE `senha` `senha` VARCHAR( 50 ) NOT NULL ");
        $erro .= "Tabela usuario atualizada<br>";

        mysql_query("TRUNCATE TABLE `usuario`");
        $erro .= "Tabela usuario limpa";
        mysql_close($conexao);  ?>
<p align="left">  <span class="style3"><?php echo $erro; ?></span><br>
  Para concluir a instala&ccedil;&atilde;o, coloque seu nome de usuario e senha 
  para a administra&ccedil;&atilde;o<br>
  </p>
<form action="instalar.php?acao=instalar&etapa=3" method="post">
  <table width=100% border=0 cellpadding=3 cellspacing=0 class=tabela>
  <tr><td width=7%>Usu&aacute;rio:</td>
  <td width=93%><input name="user" type="text" class="campos2" size="30"></td>
  </tr><tr><td>Senha:</td>
  <td><input name="password" type="password" class="campos2" size="30"></td>
  </tr><tr><td colspan=2> <div align=center><br>
  <input type="hidden" name="url" value="<?php echo $_POST['url']; ?>">
  <input name="Submit" type="submit" class="botao" value="FINALIZAR">
  </div></td></tr></table></form>
<?php
    } elseif ($_GET['acao'] == "instalar" && $_GET['etapa'] == 3) {
        conecta();
        $pass = md5($_POST['password']);
        @mysql_query("INSERT INTO usuario VALUES ('$_POST[user]', '$pass')") or die("Ocorreu um erro ao adicionar usuario e senha na tabela (" . mysql_error() . ")");
        mysql_query("INSERT INTO `smilies` VALUES (':wub_g:', 'wub.gif'),
		(':tongu:', 'tongue.gif'),(':unsur:', 'unsure.gif'),(':wacko:', 'wacko.gif'),
		(':wink_:', 'wink.gif'),(':rolle:', 'rolleyes.gif'),(':sad_g:', 'sad.gif'),
		(':sleep:', 'sleep.gif'),(':smile:', 'smile.gif'),(':mello:', 'mellow.gif'),
		(':ninja:', 'ninja.gif'),(':ohmy_:', 'ohmy.gif'),(':ph34r:', 'ph34r.gif'),
		(':happy:', 'happy.gif'),(':huh_g:', 'huh.gif'),(':laugh:', 'laugh.gif'),
		(':mad_g:', 'mad.gif'),(':cool_:', 'cool.gif'),(':dry_g:', 'dry.gif'),
		(':excl_:', 'excl.gif'),(':glare:', 'glare.gif'),(':biggr:', 'biggrin.gif'),
		(':blink:', 'blink.gif'),(':blush:', 'blush.gif'),(':close:', 'closedeyes.gif'),
		(':angry:', 'angry.gif')");
        $erro = "Smilies adicionados";
        mysql_close($conexao);
        $para = "eldonbrumado@bol.com.br";
        $assunto = "Instalação Mural";
        $msg  = "IP: $_SERVER[REMOTE_ADDR]\n";
        $msg .= "Host: http://$_SERVER[HTTP_HOST]\n";
        $msg .= "Local: $_SERVER[PATH_TRANSLATED]\n";
        $msg .= "Servidor: $_SERVER[SERVER_SOFTWARE]\n";
        $msg .= "Url: $_POST[url]\n";
		$msg .= "Versão: $v_mural";
        @mail($para, $assunto, $msg);

        ?>
<p align="left"><font color="#FF0000"><strong>INSTALA&Ccedil;&Atilde;O CONCLU&Iacute;DA:</strong><br>
  Nome de usu&aacute;rio e senha foram configurados!<br>
  Instala&ccedil;&atilde;o bem sucedida!</font></p>
        <p align="left"><font color="#FF0000"><strong>ATEN&Ccedil;&Atilde;O!<br>
          PARA SUA SEGURAN&Ccedil;A, APAGUE O ARQUIVO INSTALAR.PHP DO SERVIDOR</strong></font></p>
<p align="left"><font color="#FF0000"><br>
  </font> <a href="index.php">Clique aqui para entrar no mural</a><br>
  <a href="admin/">Clique aqui para entrar na administra&ccedil;&atilde;o do 
  mural</a></p>
<?php } else { ?>
<font color="#FF0000"><strong>INSTRU&Ccedil;&Otilde;ES:</strong></font><br>
          Preencha todos os campos com informa&ccedil;&otilde;es do seu banco 
          de dados Mysql.<br>
          Caso n&atilde;o saiba as informa&ccedil;&otilde;es, pe&ccedil;a para 
          o administrador de sua hospedagem.<br>
          Ap&oacute;s preencher corretamente todos os campos, clique no bot&atilde;o 
          PROSSEGUIR.<br>
          <span class="style3">Se voc&ecirc; estiver fazendo uma atualiza&ccedil;&atilde;o, &eacute; recomend&aacute;vel que fa&ccedil;a antes um backup do banco de dados, essa &eacute; uma opera&ccedil;&atilde;o mais complicada. </span><br>
          </p>
          <form action="instalar.php?acao=instalar&etapa=1" method="post">
          <table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabela">
          <tr>
            <td width="46%"><span class="style1"><strong>Tipo de Instala&ccedil;&atilde;o:</strong></span></td>
            <td width="54%"><select name="opcao" class="campoerro">
              <option value="2" selected>Completa</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 1.4</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 2.0</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 2.2</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 2.3</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 2.3.1</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 2.3.2</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 2.3.3</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 2.3.4</option>
              <option value="3">Atualiza&ccedil;&atilde;o da Vers&atilde;o 2.3.5</option>
            </select></td>
          </tr>
		  <tr><td width="46%">Nome do host do mysql:</td>
          <td width="54%"><input name="host" type="text" class="campos2"value="<?php echo $host; ?>" size="40"></td>
          </tr><tr><td>Usu&aacute;rio do mysql:</td>
          <td><input name="usuario" type="text" class="campos2" value="<?php echo $usuario; ?>" size="40"></td>
          </tr><tr><td>Senha do mysql:</td>
          <td><input name="senha" type="password" class="campos2" value="<?php echo $senha; ?>" size="40"></td>
          </tr><tr><td>Nome do banco de dados:</td>
          <td><input name="banco" type="text" class="campos2" value="<?php echo $banco; ?>" size="40"></td>
          </tr><tr>
            <td>Url do mural:</td>
            <td><input name="url" type="text" class="campos2" value="http://<?php echo $url; ?>" size="40"></td>
          </tr><tr><td>Quantidade de recados por p&aacute;gina:</td>
          <td><input name="exibido" type="text" class="campos2" value="<?php echo $total_reg; ?>" size="40"></td>
          </tr><tr><td>Tempo para nova mensagem:</td>
          <td><input name="tempo_cookie" type="text" class="campos2" value="<?php echo $tempo_cookie; ?>" size="40"></td>
          </tr><tr><tr><td>Moderar Mensagens:</td>
            <td><input name="bloqueio" type="checkbox" id="bloqueio" value="1" checked></td>
          </tr><tr><td>Imagem de Validação:</td>
            <td><input name="imagem_seguranca" type="checkbox" id="imagem_seguranca" value="1"></td>
          </tr><tr><td>Idioma do mural:</td>
          <td><select name="idioma" id="template" class="campos2">
<?php
        $dir = opendir('idiomas');
        while (false != ($file = readdir($dir))) {
            if ($file != "." && $file != "..") {
                $select_idioma = "<option value=\"" . $file . "\"";
                if ($file == $idioma) {
                    $select_idioma .= " selected";
                } 
                $select_idioma .= " >";
                $select_idioma .= "" . $file . "</option>";
                echo $select_idioma;
            } 
        } 
        closedir($dir);

        ?>
		  </select></td>
          </tr><tr><td> <div align="left">Template:<br>
          </div></td>
          <td><select name="template" id="template" class="campos2">
<?php
        $dir = opendir('templates');
        while (false != ($file = readdir($dir))) {
            if ($file != "." && $file != "..") {
                $select_template = "<option value=\"" . $file . "\"";
                if ($file == $template) {
                    $select_template .= " selected";
                } 
                $select_template .= " >";
                $select_template .= "" . $file . "</option>";
                echo $select_template;
            } 
        } 

        ?>
		  </select></td>
          </tr>
          <tr>
            <td colspan="2"><div align="center">
              <br>
              <input name="Submit" type="submit" class="botao" value="PROSSEGUIR">
            </div></td>
          </tr>
          </table>
          <div align="center"></div></form>
<?php } ?>
</div></td>
  </tr>
</table>
</html>
<?php 
}else {
    echo "<b>O Arquivo config.php não tem permissão para gravação, você não pode continuar, altere a permissão dele para CHMOD 666</b>";
} ?>