<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/*                    Classe - Iván Ariel Melgrati                  */
/* -----------------------------------------------------------------*/

class iam_restore {
    var $host = "localhost";
    var $dbname = "mysql";
    var $dbuser = "root";
    var $dbpass = "";
    var $filename;

    function iam_restore($filename, $host, $dbname, $dbuser, $dbpass)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
        $this->filename = $filename;
    } 

    function _Open()
    {
        $fp = gzopen($this->filename, "rb") or die("Erro. Não foi possível abrir o arquivo $this->filename");
        while (! gzeof($fp)) {
            $line = gzgets($fp, 1024);
            $SQL .= "$line";
        } 
        gzclose($fp);
        return $SQL;
    } 

    function perform_restore()
    {
        $SQL = explode(";", $this->_Open());
        $link = mysql_connect($this->host, $this->dbuser, $this->dbpass) or (die (mysql_error()));
        mysql_select_db($this->dbname, $link) or (die (mysql_error())); 
        // ---- Drop all tables from DB
        $result = mysql_list_tables($this->dbname);
        $not = mysql_num_rows($result);
        for ($i = 0; $i < $not-1; $i++) {
            $row = mysql_fetch_row($result);
            $tables .= $row[0] . ",";
        } 

        $row = mysql_fetch_row($result);
        $tables .= $row[0];
        if ($tables != "" || $tables != null)
            mysql_query("DROP TABLE " . $tables) or (die (mysql_error())); 
        // ---- And now execute the SQL statements from backup file.
        for ($i = 0;$i < count($SQL)-1;$i++) {
            mysql_unbuffered_query($SQL[$i]) or (die (mysql_error()));
        } 
        return 1;
    } 
} 

?>