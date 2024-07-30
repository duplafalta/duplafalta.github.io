<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/*                    Classe - Iván Ariel Melgrati                  */
/* -----------------------------------------------------------------*/

class iam_backup
{

    var $host="localhost";
    var $dbname="mysql";
    var $dbuser="root";
    var $dbpass="";
    var $newline;
    var $struct_only = false;
    var $output = true;
    var $compress = true;
    var $filename = "";

    function iam_backup($host = 'localhost', $dbname = 'mysql', $dbuser = 'root', $dbpass = '', $struct_only = false, $output = true, $compress = true, $filename="")
    {
        $this->output       = $output;
        $this->struct_only  = $struct_only;
        $this->compress     = $compress;

        if(is_object($host) && isset($host->database)){
            $this->host     = $host->host;
            $this->dbname     = $host->database;
            $this->dbuser     = $host->user;
            $this->dbpass     = $host->password;
        }else{
            $this->host     = $host;
            $this->dbname     = $dbname;
            $this->dbuser     = $dbuser;
            $this->dbpass     = $dbpass;
        }
        $this->filename    = $filename;
        $this->newline     = $this->_define_newline();
    }

    function _backup()
    {
        $now = date('d/m/y H:i:s');

        $newfile.= "#------------------------------------------".$this->newline;
        $newfile.= "# Backup - Mural Inter4u".$this->newline;
        $newfile.= "# Banco de dados: $this->dbname".$this->newline;
        $newfile.= "# Data: $now".$this->newline;
        $newfile.= "#------------------------------------------".$this->newline.$this->newline;

        $result = mysql_pconnect("$this->host","$this->dbuser","$this->dbpass");
        if(!$result)     // If no connection can be obtained, return empty string
        {
        return "Erro. Não foi possível conectar ao banco de dados: $this->dbname";
        }

        if(!mysql_select_db("$this->dbname"))  // If db can't be set, return empty string
        {
        return "Erro. Banco de dados $this->dbname não foi selecionado.";
        }

        $result = @mysql_query("show tables from $this->dbname");
        while (list($table) = @mysql_fetch_row($result))
        {
        $newfile .= $this->_get_def($table);
        $newfile .= "$this->newline";
        if(!$struct_only) // If table data also has to be written, get table contents
            $newfile .= $this->_get_content($table);
        $newfile .= "$this->newline";
        $i++;
        }

        $this->_out($newfile);
    }

    function _out($dump)
    {
        if($this->filename)
        {
            $fptr= fopen($this->filename, "wb");
            if ($fptr)
            {
                if($this->compress)
                {
                   $gzbackupData = "\x1f\x8b\x08\x00\x00\x00\x00\x00".substr(gzcompress($dump,9),0,-4).pack('V',crc32($dump)).pack('V',strlen($dump));
                   fwrite($fptr, $gzbackupData);
                }
                else
                   fwrite($fptr, $dump);
                fclose($fptr);
            }
        }
        else
        {
            if(($this->compress) and ($this->output))
            {
               $gzbackupData = "\x1f\x8b\x08\x00\x00\x00\x00\x00".substr(gzcompress($dump,9),0,-4).pack('V',crc32($dump)).pack('V',strlen($dump));
               echo $gzbackupData;
            }
            else
               echo $dump;
        }
    }

    function _get_def($tablename)
    {
        $def = "";
        $def .="#------------------------------------------".$this->newline;
        $def .="# Estrutura da tabela $tablename".$this->newline;
        $def .="#------------------------------------------".$this->newline;
        $def .= "DROP TABLE IF EXISTS $tablename;".$this->newline.$this->newline;
        $def .= "CREATE TABLE $tablename (".$this->newline;
        $result = @mysql_query("SHOW FIELDS FROM $tablename") or die("Table $tablename not existing in database");
        while($row = @mysql_fetch_array($result))
        {
          $def .= "    `$row[Field]` $row[Type]";
          if ($row["Default"] != "") $def .= " DEFAULT '$row[Default]'";
          if ($row["Null"] != "YES") $def .= " NOT NULL";
          if ($row[Extra] != "") $def .= " $row[Extra]";
          $def .= ",$this->newline";
        }
        $def = ereg_replace(",$this->newline$","", $def);

        $result = @mysql_query("SHOW KEYS FROM $tablename");
        while($row = @mysql_fetch_array($result))
        {
          $kname=$row[Key_name];
          if(($kname != "PRIMARY") && ($row[Non_unique] == 0)) $kname="UNIQUE|$kname";
          if(!isset($index[$kname])) $index[$kname] = array();
          $index[$kname][] = $row[Column_name];
        }

        while(list($x, $columns) = @each($index))
        {
          $def .= ",$this->newline";
          if($x == "PRIMARY") $def .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
          else if (substr($x,0,6) == "UNIQUE") $def .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
          else $def .= "   KEY $x (" . implode($columns, ", ") . ")";
        }
        $def .= "$this->newline);";

        return (stripslashes($def));
    }

    function _get_content($tablename)
    {
        $content = "";

        $result = @mysql_query("SELECT * FROM $tablename");

        if(@mysql_num_rows($result)>0)
        {
            $content .="\n";
			$content .="#------------------------------------------".$this->newline;
            $content .="# Dados da tabela $tablename".$this->newline;
            $content .="#------------------------------------------".$this->newline;
        }

        while($row = @mysql_fetch_row($result))
        {
          $insert = "INSERT INTO $tablename VALUES (";

          for($j=0; $j<@mysql_num_fields($result);$j++)
          {
            $row[$j] = str_replace(';','',$row[$j]);
			if(!isset($row[$j])) $insert .= "NULL,";
            else if($row[$j] != "") $insert .= "'".addslashes($row[$j])."',";
            else $insert .= "'',";
          }

          $insert = ereg_replace(",$", "", $insert);
          $insert .= ");$this->newline";
          $content .= $insert;
        }

        return $content.$this->newline;
    }

    function _define_newline()
    {
         $unewline = "\r\n";

         if (strstr(strtolower($_SERVER["HTTP_USER_AGENT"]), 'win'))
         {
            $unewline = "\r\n";
         }
         else if (strstr(strtolower($_SERVER["HTTP_USER_AGENT"]), 'mac'))
         {
            $unewline = "\r";
         }
         else
         {
            $unewline = "\n";
         }

         return $unewline;
    }

    function _get_browser_type()
    {
        $USER_BROWSER_AGENT="";

        if (ereg('OPERA(/| )([0-9].[0-9]{1,2})', strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version))
        {
            $USER_BROWSER_AGENT='OPERA';
        }
        else if (ereg('MSIE ([0-9].[0-9]{1,2})',strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version))
        {
            $USER_BROWSER_AGENT='IE';
        }
        else if (ereg('OMNIWEB/([0-9].[0-9]{1,2})', strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version))
        {
            $USER_BROWSER_AGENT='OMNIWEB';
        }
        else if (ereg('MOZILLA/([0-9].[0-9]{1,2})', strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version))
        {
            $USER_BROWSER_AGENT='MOZILLA';
        }
        else if (ereg('KONQUEROR/([0-9].[0-9]{1,2})', strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version))
        {
            $USER_BROWSER_AGENT='KONQUEROR';
        }
        else
        {
            $USER_BROWSER_AGENT='OTHER';
        }

        return $USER_BROWSER_AGENT;
    }

    function _get_mime_type()
    {
        $USER_BROWSER_AGENT= $this->_get_browser_type();

        $mime_type = ($USER_BROWSER_AGENT == 'IE' || $USER_BROWSER_AGENT == 'OPERA')
                       ? 'application/octetstream'
                       : 'application/octet-stream';
        return $mime_type;
    }

    function perform_backup()
    {

        $now = date('d/m/y H:i:s');
        if ($this->compress)
        {
            $filename = $this->dbname.".sql";
            $ext = "gz";
        }
        else
        {
            $filename = $this->dbname;
            $ext = "sql";
        }

        $USER_BROWSER_AGENT= $this->_get_browser_type();

        if($this->filename)
        {
            $this->_backup();
        }
        else
            if ($this->output == true)
            {
                 header('Content-Type: ' . $this->_get_mime_type());
                 header('Expires: ' . $now);
                 if ($USER_BROWSER_AGENT == 'IE')
                 {
                      header('Content-Disposition: inline; filename="' . $filename . '.' . $ext . '"');
                      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                      header('Pragma: public');
                 }
                 else
                 {
                      header('Content-Disposition: attachment; filename="' . $filename . '.' . $ext . '"');
                      header('Pragma: no-cache');
                 }

                 $this->_backup();
            }
            else
            {
                 echo "<html><body><pre>";
                 echo htmlspecialchars($this->_backup());
                 echo "</PRE></BODY></HTML>";
            }
    }
}

?>