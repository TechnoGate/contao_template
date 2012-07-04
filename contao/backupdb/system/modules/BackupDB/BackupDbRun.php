<?php
//-------------------------------------------------------------------
// BackupDbRun.php	Backup Contao-Datenbank
//
// Copyright (c) 2007-2011 by Softleister
//-------------------------------------------------------------------
//  Systeminitialisierung
//-------------------------------------------------------------------
define('TL_MODE', 'BE');
require('../../initialize.php');

//-------------------------------------------------------------------
//  Backend um die Backup-Funktionen erweitern
//-------------------------------------------------------------------
class BackupDb extends Backend 			    // Datenbank ist bereits geöffnet
{
  //-------------------------
  //  Constructor
  //-------------------------
  public function __construct( )
  {
    $this->import('BackendUser', 'User');	    // User importieren
    parent::__construct(); 			    // Construktor Backend ausführen
    $this->User->authenticate(); 		    // Authentifizierung überprüfen
    $this->loadLanguageFile('default');		    // Sprachenfiles laden
    $this->loadLanguageFile('modules');
    $this->loadLanguageFile('tl_BackupDB'); 
    $this->import('BackupDbCommon');                // Backup-Funktionalität importieren
  }

  //-------------------------
  //  Backup ausführen
  //-------------------------
  public function run( )
  {
    @set_time_limit( 600 );

    header( "Pragma: public" );
    header( "Expires: 0" );
    header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
    header( "Cache-Control: private", false );
    header( "Content-type: application/octet-stream" );
    header( "Content-disposition: attachment; filename=Database_".$GLOBALS['TL_CONFIG']['dbDatabase']."_".date( "Y-m-d" )."_".date("His").".sql" );
    header( "Content-Transfer-Encoding: binary" );

    print $this->BackupDbCommon->getHeaderInfo( true, 'Saved by User    : '.$this->User->username.' ('.$this->User->name.')' );

    $sqlarray = $this->BackupDbCommon->getFromDB( );

    if( count($sqlarray) == 0 ) print "No tables found in database.";
    else {
      foreach( array_keys($sqlarray) as $table ) {
        print $this->BackupDbCommon->get_table_structure( $table, $sqlarray[$table] );

	$this->BackupDbCommon->get_table_content( $table );	  // Dateninhalte ausgeben
      }
    }
    print "\r\n# --- End of Backup ---\r\n";     		  // Endekennung
  }
  
  //------------------------------------------------
}

//-------------------------------------------------------------------
//  Programmstart
//-------------------------------------------------------------------
$objBackupDB = new BackupDb( );
$objBackupDB->run( );

//-------------------------------------------------------------------
?>