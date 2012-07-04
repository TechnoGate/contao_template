<?php
//-------------------------------------------------------------------
// backup.php	Backup Contao-Datenbank
//
// Copyright (c) 2007-2012 by Softleister
//-------------------------------------------------------------------
class BackupDbCommon extends Backend
{
  //-------------------------
  //  Variablen
  //-------------------------
  public $Version = '1.5.0';
  public $contao_ver;

  public $extcludeExt = Array (            // Module aus der Standard-Installation < 2.7
                             "backend", "calendar", "comments", "development", "dfGallery",
                             "frontend", "listing", "news", "newsletter", "pun_bridge",
                             "registration", "rss_reader", "tpl_editor", "BackupDB", "faq",
                             "rep_base", "rep_client", "memberlist"
                           );

  public $extcludeExt27 = Array (            // Module aus der Standard-Installation >= 2.7
                             "backend", "calendar", "comments", "dfGallery", "faq", "frontend", 
                             "glossary", "listing", "memberlist", "news", "newsletter", 
                             "registration", "rep_base", "rep_client", "rss_reader", "tpl_editor", 
                             "BackupDB"
                           );

  public $extcludeExt28 = Array (            // Module aus der Standard-Installation >= 2.8 (auch 2.9 und 2.10)
                             "backend", "calendar", "comments", "faq", "frontend", "listing", 
                             "news", "newsletter", "registration", "rep_base", "rep_client", 
                             "rss_reader", "tpl_editor", "BackupDB"
                           );

  public $extcludeExt211 = Array (            // Module aus der Standard-Installation >= 2.11
                             "backend", "calendar", "comments", "faq", "frontend", "listing", 
                             "news", "newsletter", "registration", "rep_base", "rep_client", 
                             "rss_reader", "tasks", "tpl_editor", "BackupDB"
                           );

  //---------------------------------------
  // Konstruktor
  //---------------------------------------
  public function __construct()
  {
    $this->import( 'BackendUser', 'User' );	// User importieren
    $this->import( 'Database' );

    $ver = explode( '.', VERSION );                 // $ver[0] = Main version, $ver[1] = Sub version
    $this->contao_ver = $ver[0] + ($ver[1] / 100);  // Subversion 2-stellig
  }

  //------------------------------------------------
  //  Dateifähiger Filename
  //------------------------------------------------
  public function getWsName()
  {
    return str_replace( array( " - ", " ", ".", ",", "\\", "/", ":", "*", "?", "<", ">", "|", "\"" ),
                        array( "_",   "_", "",  "",  "",   "",  "",  "",  "",  "(", ")", "",  "" ),
                        $GLOBALS['TL_CONFIG']['websiteTitle'] );
  }
        
  //---------------------------------------
  // Extension-Versions-Info
  //---------------------------------------
  public function getVersionInfo( $ver )
  {
    $version = floor( $ver / 10000000 ) . '.';            // Hauptversion
    $version .= floor(($ver % 10000000) / 10000) . '.';   // Subversion
    $version .= floor(($ver % 10000) / 10 );              // Sub-Subversion
    switch( $ver % 10 ) {
      case 0:   $version .= ' alpha1';    break;
      case 1:   $version .= ' alpha2';    break;
      case 2:   $version .= ' alpha3';    break;
      case 3:   $version .= ' beta1';     break;
      case 4:   $version .= ' beta2';     break;
      case 5:   $version .= ' beta3';     break;
      case 6:   $version .= ' rc1';       break;
      case 7:   $version .= ' rc2';       break;
      case 8:   $version .= ' rc3';       break;
      case 9:   $version .= ' stable';    break;
    }
    return $version;
  }

  //---------------------------------------
  // Extension-Versions-Info
  //---------------------------------------
  public function getHeaderInfo( $sql_mode, $savedby='Saved by Cron' )
  {
    $result = "#================================================================================\r\n"
             ."# Contao-Website   : ".$GLOBALS['TL_CONFIG']['websiteTitle']."\r\n"
             ."# Contao-Database  : ".$GLOBALS['TL_CONFIG']['dbDatabase']."\r\n"
             ."# " . $savedby . "\r\n"
             ."# Time stamp       : " . date( "Y-m-d" ) . " at " . date( "H:i:s" ) . "\r\n"
             ."#\r\n"
             ."# Contao Extension : BackupDB, Version ".$this->Version."\r\n"
             ."# Copyright        : Softleister (www.softleister.de)\r\n"
             ."# Author           : Hagen Klemp\r\n"
             ."# Licence          : LGPL\r\n"
             ."#\r\n"
             ."# Visit Contao project page http://www.contao.org for more information\r\n"
             ."#\r\n"

    //--- Installierte Module unter /system/modules auflisten ---
             ."#-----------------------------------------------------\r\n"
             ."# ".$GLOBALS['TL_LANG']['MSC']['tl_backupdb']['ws_template_module']."\r\n"
             ."#-----------------------------------------------------\r\n";

    if( $this->Database->tableExists('tl_repository_installs') ) {
      if( $this->contao_ver >= 2.08 )
        $objData = $this->Database->executeUncached("SELECT * FROM tl_repository_installs");
      else
        $objData = $this->Database->execute("SELECT * FROM tl_repository_installs");

      while( $objData->next() ) {
        if( $objData->extension === 'BackupDB' ) continue;   // BackupDB für Restore nicht notwendig
        $result .= '#   - ' . sprintf('%-16s', $objData->extension) . ': Version ' . $this->getVersionInfo( $objData->version ) . ', Build ' . $objData->build . "\r\n";
        $instExt[] = strtolower( $objData->extension );
      }
    }

    $modullist = "";
    $handle = opendir( ".." );
    while( $file = readdir( $handle ) ) {
      if( substr( $file, 0, 1 ) == "." ) continue;                                // . und .. ausblenden
      if( isset($instExt) && in_array( strtolower($file), $instExt ) ) continue;  // keine Files, die schon im Repository stehen

      if( $this->contao_ver < 2.07 ) {
        if( in_array( $file, $this->extcludeExt ) ) continue;       // Standard-Module überspringen
      }
      else if( $this->contao_ver < 2.08 ) {
        if( in_array( $file, $this->extcludeExt27 ) ) continue;
      }
      else if( $this->contao_ver < 2.11 ) {
        if( in_array( $file, $this->extcludeExt28 ) ) continue;     // gilt 2.8.x ... 2.10.x
      }
      else {
        if( in_array( $file, $this->extcludeExt211 ) ) continue;    // ab 2.11.x
      }
      $modullist .= "#   - $file\r\n";
    }
    closedir( $handle );
    if( $modullist != "" )      $result .= $modullist;
    else if( !count($instExt) ) $result .= "#   == keine ==\r\n";
    
    $result .= "#\r\n"
              ."#================================================================================\r\n"
              ."\r\n";
    if( $sql_mode ) {
      $result .= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\r\n"
                ."\r\n"
                ."/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n"
                ."/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n"
                ."/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n"
                ."/*!40101 SET NAMES utf8 */;\r\n";
    }
    return $result;
  }

  //------------------------------------------------
  //  Erzeugt die Strukturdefinitionen
  //------------------------------------------------
  public function getFromDB()
  {
    $tables = $this->Database->listTables();
    if( !count($tables) ) return array();
    
    $return = array();
    
    //--- Neue Struktur der listFields-Ausgabe ab Contao 2.10 ---
    if( $this->contao_ver >= 2.10 ) {
      foreach( $tables as $table ) {
        $keys = array();
        $fields = $this->Database->listFields( $table );

        foreach( $fields as $field ) {
          $name = $field['name'];
      	  $field['name'] = '`'.$field['name'].'`';
      
          if( $field['type'] != 'index' ) {
       	    unset($field['index']);

      	    // Field type
      	    if( strlen($field['length']) ) {
      	      $field['type'] .= '(' . $field['length'] . (strlen($field['precision']) ? ',' . $field['precision'] : '') . ')';
          
      	      unset( $field['length'] );
      	      unset( $field['precision'] );
      	    }
      	    
      	    // detecting FULLTEXT KEY
      	    if( in_array(strtolower($field['type']), array('text', 'tinytext', 'mediumtext', 'longtext')) && isset($fields[$name]) && ($fields[$name]['index'] == 'KEY') ) {
    	      $keys[$name] = 'FULLTEXT';
      	    }
          
      	    // Default values
      	    if( in_array(strtolower($field['type']), array('text', 'tinytext', 'mediumtext', 'longtext', 'blob', 'tinyblob', 'mediumblob', 'longblob')) || ((substr(strtolower($field['type']),0,4) == 'int(') && ($field['default'] == '')) || stristr($field['extra'], 'auto_increment') ) {
      	      unset($field['default']);
      	    }
      	    else if( is_null($field['default']) || strtolower($field['default']) == 'null' ) {
      	      $field['default'] = "default NULL";
      	    }
      	    else {
      	      $field['default'] = "default '" . $field['default'] . "'";
      	    }
          
      	    $return[$table]['TABLE_FIELDS'][$name] = trim( implode( ' ', $field ) );
      	  }
      
      	  // Indices
      	  if( strlen($field['index']) && $field['index_fields'] ) {
      	    $index_fields = implode( '`, `', $field['index_fields'] );

            if( isset($keys[$name]) ) $field['index'] = $keys[$name];
      	    switch( $field['index'] ) {
      	      case 'UNIQUE':    if( $name == 'PRIMARY' ) $return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'PRIMARY KEY  (`'.$index_fields.'`)';
      	                        else  		         $return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'UNIQUE KEY `'.$name.'` (`'.$index_fields.'`)';
       	  		        break;
          
      	      case 'FULLTEXT':  $return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'FULLTEXT KEY `'.$name.'` (`'.$index_fields.'`)';
      	  		        break;
          
      	      default:          $return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'KEY `'.$name.'` (`'.$index_fields.'`)';
      	  		        break;
      	    }
          
      	    unset( $field['index_fields'] );
      	    unset( $field['index'] );
      	  }
        }
      }
    }

    //--- Alte Struktur der listFields-Ausgabe bis Contao 2.9 ---
    else {
      foreach( $tables as $table ) {
        $fields = $this->Database->listFields( $table );	// Liste der Felder lesen

        foreach( $fields as $field ) {
	  $name = $field['name'];
	  $field['name'] = '`'.$field['name'].'`';

	  // Field type
	  if( strlen($field['length']) ) {
	    $field['type'] .= '(' . $field['length'] . (strlen($field['precision']) ? ',' . $field['precision'] : '') . ')';

	    unset( $field['length'] );
	    unset( $field['precision'] );
	  }

	  // Default values
	  if( in_array(strtolower($field['type']), array('text', 'tinytext', 'mediumtext', 'longtext', 'blob', 'tinyblob', 'mediumblob', 'longblob', 'time', 'date', 'datetime')) || stristr($field['extra'], 'auto_increment') ) {
	    unset( $field['default'] );
	  }
	  else if( is_null($field['default']) || strtolower($field['default']) == 'null' ) {
	    $field['default'] = "default NULL";
	  }
	  else {
	    $field['default'] = "default '" . $field['default'] . "'";
	  }

	  // Indices
	  if( strlen($field['index']) ) {
	    switch( $field['index'] ) {
	      case 'PRIMARY':	$return[$table]['TABLE_CREATE_DEFINITIONS']['PRIMARY'] = 'PRIMARY KEY  (`'.$name.'`)';
				break;

	      case 'UNIQUE':    $return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'UNIQUE KEY `'.$name.'` (`'.$name.'`)';
				break;

	      case 'FULLTEXT':  $return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'FULLTEXT KEY `'.$name.'` (`'.$name.'`)';
				break;

	      default:          if( (strpos(' '.$field['type'], 'text') || strpos(' '.$field['type'], 'char')) && ($field['null'] == 'NULL') )	// Fulltext-Search bei text-Fields
	    			  $return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'FULLTEXT KEY `'.$name.'` (`'.$name.'`)';
	    			else
	                	  $return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'KEY `'.$name.'` (`'.$name.'`)';
				break;
	    }
            unset( $field['index'] );
	  }
	  $return[$table]['TABLE_FIELDS'][$name] = trim( implode(' ', $field) );
        }
      }
    }
    
    // Table status
    $objStatus = $this->Database->prepare( "SHOW TABLE STATUS" )->execute( );
    while( $zeile = $objStatus->fetchAssoc() ) {
      $return[$zeile['Name']]['TABLE_OPTIONS'] = " ENGINE=".$zeile['Engine']." DEFAULT CHARSET=".substr($zeile['Collation'], 0, strpos($zeile['Collation'],"_"))."";
      if( $zeile['Auto_increment'] != "" ) $return[$zeile['Name']]['TABLE_OPTIONS'] .= " AUTO_INCREMENT=".$zeile['Auto_increment']." ";
    }

    return $return;
  }

  //---------------------------------------
  // Erzeut Struktur der Datentabelle
  //---------------------------------------
  public function get_table_structure( $table, $tablespec )
  {
    $result = "\r\n"
             ."#---------------------------------------------------------\r\n"
             ."# Table structure for table '$table'\r\n"
             ."#---------------------------------------------------------\r\n";

    $result .= "CREATE TABLE `" . $table . "` (\n  " . implode(",\n  ", $tablespec['TABLE_FIELDS']) . (count($tablespec['TABLE_CREATE_DEFINITIONS']) ? ',' : '') . "\n";
    
    if( is_array( $tablespec['TABLE_CREATE_DEFINITIONS'] ) )                     // Bugfix 29.3.2009 Softleister
      $result .= "  " . implode(",\n  ", $tablespec['TABLE_CREATE_DEFINITIONS']) . "\n";
    $result .= ")" . $tablespec['TABLE_OPTIONS'] . ";\r\n\r\n";
    
    return $result;
  }

  //------------------------------------------------
  //  Erzeut INSERT-Zeilen mit den Datenbankdaten
  //------------------------------------------------
  public function get_table_content( $table, $datei=NULL, $sitetemplate=false )
  {
    if( $this->BackupDbCommon->contao_ver >= 2.08 )
      $objData = $this->Database->executeUncached( "SELECT * FROM $table" );
    else
      $objData = $this->Database->execute( "SELECT * FROM $table" );

    $fields = $this->Database->listFields( $table );		// Liste der Felder lesen
    if( $sitetemplate ) {
      $fieldlist = ' (';
      foreach( $fields as $field ) {
        if( $field['type'] != 'index' ) $fieldlist .= '`' . $field['name'] . '`, ';
      }
      $fieldlist = substr( $fieldlist, 0, -2 ) . ')';             // letztes ", " abschneiden

      echo $table . '<span style="color:#a0a0a0;">, ' . $objData->numRows . " Entries</span><br />\r\n";
    }

    $noentries = $objData->numRows ? '' : ' - no entries';
    if( $datei == NULL ) {
      print "\r\n"
           ."#\r\n"
	   ."# Dumping data for table '$table'" . $noentries . "\r\n"
	   ."#\r\n\r\n";
    }
    else {
      $datei->write( "\r\n"
                    ."#\r\n"
	            ."# Dumping data for table '$table'" . $noentries . "\r\n"
	            ."#\r\n\r\n" );
    }

    while( $row = $objData->fetchRow() ) {
      $insert_data = 'INSERT INTO `' . $table . '`' . $fieldlist . ' VALUES (';
      $i = 0;							// Fields[0]['type']
      foreach( $row as $field_data ) {
	if( !isset( $field_data ) )
	  $insert_data .= " NULL,";
	else if( $field_data != "" ) {
	  switch( strtolower($fields[$i]['type']) ) {
	    case 'blob':
	    case 'tinyblob':
	    case 'mediumblob':
	    case 'longblob':	$insert_data .= " 0x";		// Auftackt für HEX-Darstellung
	                        $insert_data .= bin2hex($field_data);
	    			$insert_data .= ",";		// Abschuß
	    			break;
	    
	    case 'smallint':
	    case 'int':		$insert_data .= " $field_data,";
	    			break;
	    
	    case 'text':
	    case 'mediumtext':  if( strpos( $field_data, "'" ) != false ) {  // ist im Text ein Hochkomma vorhanden, wird der Text in HEX-Darstellung gesichert
	        		  $insert_data .= " 0x" . bin2hex($field_data) . ",";
	    			  break;
	    			}
	    			// else: weiter mit default  
	    
	    default:	        $insert_data .= " '".str_replace( array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "\\r", "\\n"), $field_data )."',";
				break;
	  }
	}
	else
	  $insert_data .= " '',";
	$i++;						// Next Field
      }
      $insert_data = trim( $insert_data, ',' );
      $insert_data .= " )";
      if( $datei == NULL )
        print "$insert_data;\r\n";			// Zeile ausgeben
      else
        $datei->write( "$insert_data;\r\n" );
    }
  }
		
  //---------------------------------------
}

//-------------------------------------------------------------------

?>