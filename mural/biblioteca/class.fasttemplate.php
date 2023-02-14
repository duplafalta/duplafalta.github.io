<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
class FastTemplate {
	var $FILELIST	=	array();
	var $DYNAMIC	=	array();
	var $PARSEVARS	=	array();
	var	$LOADED		=	array();
	var	$HANDLE		=	array();
	var	$ROOT		=	"";
	var $WIN32		=	false;
	var $ERROR		=	"";
	var $LAST		=	"";
	var $STRICT		=	true;

	function FastTemplate ($pathToTemplates = ""){
		global $php_errormsg;
		if(!empty($pathToTemplates)){
			$this->set_root($pathToTemplates);
		}
	}

	function set_root ($root){
		$trailer = substr($root,-1);
		if(!$this->WIN32){
			if( (ord($trailer)) != 47 ){
				$root = "$root". chr(47);
			}
			if(is_dir($root)){
				$this->ROOT = $root;
			}
			else{
				$this->ROOT = "";
				$this->error("Specified ROOT dir [$root] is not a directory");
			}
		}
		else{
			if( (ord($trailer)) != 92 )	{
				$root = "$root" . chr(92);
			}
			$this->ROOT = $root;
		}

	}

	function utime (){
		$time = explode( " ", microtime());
		$usec = (double)$time[0];
		$sec = (double)$time[1];
		return $sec + $usec;
    }

	function strict (){
		$this->STRICT = true;
	}

	function no_strict (){
		$this->STRICT = false;
	}

	function is_safe ($filename){
		if(!file_exists($filename))	{
			$this->error("[$filename] does not exist",0);
			return false;
		}
		return true;
	}

	function get_template ($template){
		if(empty($this->ROOT)){
			$this->error("Impossível abrir template.",1);
			return false;
		}
		$filename	=	"$this->ROOT"."$template";
		$contents = implode("",(@file($filename)));
		if( (!$contents) or (empty($contents)) ){
			$this->error("get_template() erro: [$filename] $php_errormsg",1);
		}
		return $contents;
	}

	function show_unknowns ($Line){
		$unknown = array();
		if(ereg("({[A-Z0-9_]+})",$Line,$unknown)){
			$UnkVar = $unknown[1];
			if(!(empty($UnkVar))){
				@error_log("[FastTemplate] Aviso: nenhum valor encontrado para a variável: $UnkVar ",0);
			}
		}
	}

	function parse_template ($template, $tpl_array){
		while( list ($key,$val) = each ($tpl_array) ){
			if(!(empty($key))){
				if(gettype($val) != "string")	{
					settype($val,"string");
				}
				$template = ereg_replace("{$key}","$val","$template");
			}
		}
		if(!$this->STRICT){
			$template = ereg_replace("{([A-Z0-9_]+)}","",$template);
		}
		else{
			if(ereg("({[A-Z0-9_]+})",$template)){
				$unknown = split("\n",$template);
				while(list ($Element,$Line) = each($unknown) )
				{
					$UnkVar = $Line;
					if(!(empty($UnkVar))){
						$this->show_unknowns($UnkVar);
					}
				}
			}
		}
		return $template;
	}
	function parse ( $ReturnVar, $FileTags ){
		$append = false;
		$this->LAST = $ReturnVar;
		$this->HANDLE[$ReturnVar] = 1;

		if(gettype($FileTags) == "array"){
			unset($this->$ReturnVar);	
			while( list ( $key , $val ) = each ( $FileTags ) ){
				if( (!isset($this->$val)) || (empty($this->$val)) )
				{
					$this->LOADED["$val"] = 1;
					if(isset($this->DYNAMIC["$val"])){
						$this->parse_dynamic($val,$ReturnVar);
					}
					else{
						$fileName = $this->FILELIST["$val"];
						$this->$val = $this->get_template($fileName);
					}
				}
				$this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);
				$this->assign( array( $ReturnVar => $this->$ReturnVar ) );
			}
		}	
		else{
			$val = $FileTags;
			if( (substr($val,0,1)) == '.' ){
				$append = true;
				$val = substr($val,1);
			}
			if( (!isset($this->$val)) || (empty($this->$val)) ){
					$this->LOADED["$val"] = 1;
					if(isset($this->DYNAMIC["$val"])){
						$this->parse_dynamic($val,$ReturnVar);
					}
					else{
						$fileName = $this->FILELIST["$val"];
						$this->$val = $this->get_template($fileName);
					}
			}
			if($append){
				$this->$ReturnVar .= $this->parse_template($this->$val,$this->PARSEVARS);
			}
			else{
				$this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);
			}
			$this->assign(array( $ReturnVar => $this->$ReturnVar) );
		}
		return;
	}	

	function FastPrint ( $template = "" ){
		if(empty($template)){
			$template = $this->LAST;
		}
		if( (!(isset($this->$template))) || (empty($this->$template)) ){
			$this->error("Nada analisado, nada impresso",0);
			return;
		}
		else{
			print (stripslashes($this->$template));
		}
		return;
	}

	function fetch ( $template = "" )
	{
		if(empty($template)){
			$template = $this->LAST;
		}
		if( (!(isset($this->$template))) || (empty($this->$template)) ){
			$this->error("Nada analisado, nada impresso",0);
			return "";
		}
		return($this->$template);
	}

	function define_dynamic ($Macro, $ParentName){
		$this->DYNAMIC["$Macro"] = $ParentName;
		return true;
	}

	function parse_dynamic ($Macro,$MacroName){
		$ParentTag = $this->DYNAMIC["$Macro"];
		if( (!$this->$ParentTag) or (empty($this->$ParentTag)) ){
			$fileName = $this->FILELIST[$ParentTag];
			$this->$ParentTag = $this->get_template($fileName);
			$this->LOADED[$ParentTag] = 1;
		}
		if($this->$ParentTag){
			$template = $this->$ParentTag;
			$DataArray = split("\n",$template);
			$newMacro = "";
			$newParent = "";
			$outside = true;
			$start = false;
			$end = false;
			while( list ($lineNum,$lineData) = each ($DataArray) ){
				$lineTest = trim($lineData);
				if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest" ){
					$start = true;
					$end = false;
					$outside = false;
				}
				if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest" ){
					$start = false;
					$end = true;
					$outside = true;
				}
				if( (!$outside) and (!$start) and (!$end) ){
					$newMacro .= "$lineData\n";
				}
				if( ($outside) and (!$start) and (!$end) ){
					$newParent .= "$lineData\n";
				}
				if($end){
					$newParent .= "{$MacroName}\n";
				}
				if($end) { $end = false; }
				if($start) { $start = false; }
			}
			$this->$Macro = $newMacro;
			$this->$ParentTag = $newParent;
			return true;
		}
		else{
			@error_log("ParentTag: [$ParentTag] não carregada!",0);
			$this->error("ParentTag: [$ParentTag] não carregada!",0);
		}
		return false;
	}

function clear_dynamic ($Macro=""){
	if(empty($Macro)) { return false; }
	$ParentTag = $this->DYNAMIC["$Macro"];
	if( (!$this->$ParentTag) or (empty($this->$ParentTag))){
		$fileName = $this->FILELIST[$ParentTag];
		$this->$ParentTag = $this->get_template($fileName);
		$this->LOADED[$ParentTag] = 1;
	}
	if($this->$ParentTag){
		$template = $this->$ParentTag;
		$DataArray = split("\n",$template);
		$newParent = "";
		$outside = true;
		$start = false;
		$end = false;
		while( list ($lineNum,$lineData) = each ($DataArray)){
			$lineTest = trim($lineData);
			if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest" ){
				$start = true;
				$end = false;
				$outside = false;
			}
			if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest" ){
				$start = false;
				$end = true;
				$outside = true;
			}
			if( ($outside) and (!$start) and (!$end) ){
				$newParent .= "$lineData\n"; 
			}
			if($end) { $end = false; }
			if($start) { $start = false; }
		}
		$this->$ParentTag = $newParent;
		return true;
	}	
	else{
		@error_log("ParentTag: [$ParentTag] não carregada!",0);
		$this->error("ParentTag: [$ParentTag] não carregada!",0);
	}
	return false;
}

function define ($fileList){
	while( list ($FileTag,$FileName) = each ($fileList)){
		$this->FILELIST["$FileTag"] = $FileName;
	}
	return true;
}

function clear_parse( $ReturnVar = ""){
	$this->clear($ReturnVar);
}

function clear( $ReturnVar = "" ){
	if(!empty($ReturnVar)){
		if( (gettype($ReturnVar)) != "array"){
			unset($this->$ReturnVar);
			return;
		}
		else{
			while( list ($key,$val) = each ($ReturnVar)){
				unset($this->$val);
			}
			return;
		}
	}
	while( list ( $key,$val) = each ($this->HANDLE)){
		$KEY = $key;
		unset($this->$KEY);
	}
	return;
}	

function clear_all(){
	$this->clear();
	$this->clear_assign();
	$this->clear_define();
	$this->clear_tpl();
	return;
}	

function clear_tpl ($fileHandle = ""){
	if(empty($this->LOADED)){
		return true;
	}
	if(empty($fileHandle)){
		while( list ($key, $val) = each($this->LOADED)){
			unset($this->$key);
		}
		unset($this->LOADED);
		return true;
	}
	else{
		if((gettype($fileHandle)) != "array"){
			if((isset($this->$fileHandle)) || (!empty($this->$fileHandle))){
				unset($this->LOADED[$fileHandle]);
				unset($this->$fileHandle);
				return true;
			}
		}
		else{
			while( list ($Key, $Val) = each ($fileHandle)){
				unset($this->LOADED[$Key]);
				unset($this->$Key);
			}
			return true;
		}
	}
	return false;
}	

function clear_define( $FileTag = "" ){
	if(empty($FileTag)){
		unset($this->FILELIST);
		return;
	}
	if((gettype($Files)) != "array"){
		unset($this->FILELIST[$FileTag]);
		return;
	}
	else{
		while(list($Tag, $Val) = each ($FileTag)){
			unset($this->FILELIST[$Tag]);
		}
		return;
	}
}

function clear_assign (){
	if(!(empty($this->PARSEVARS))){
		while(list($Ref,$Val) = each($this->PARSEVARS)){
			unset($this->PARSEVARS["$Ref"]);
		}
	}
}

function clear_href($href){
	if(!empty($href)){
		if((gettype($href)) != "array"){
			unset($this->PARSEVARS[$href]);
			return;
		}
		else{
			while(list($Ref,$val) = each ($href)){
				unset($this->PARSEVARS[$Ref]);
			}
			return;
		}
	}
	else{
		$this->clear_assign();
	}
	return;
}

function assign($tpl_array, $trailer=""){
	if(gettype($tpl_array) == "array"){
		while( list($key,$val) = each($tpl_array)){
			if(!(empty($key))){
				$this->PARSEVARS["$key"] = $val;
			}
		}
	}
	else{
		if(!empty($tpl_array)){
			$this->PARSEVARS["$tpl_array"] = $trailer;
		}
	}
}

function get_assigned($tpl_name = ""){
	if(empty($tpl_name)) { return false; }
	if(isset($this->PARSEVARS["$tpl_name"])){
		return ($this->PARSEVARS["$tpl_name"]);
	}
	else{
		return false;
    }
}

function error($errorMsg, $die = 0){
	$this->ERROR = $errorMsg;
	echo "ERRO: $this->ERROR <BR> \n";
	if($die == 1){
		exit;
	}
	return;
}
} 
?>