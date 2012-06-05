<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
//-------------------------------------------------------------------
// AutoBackupDB.php	Backup Contao-Datenbank mit Cron-Job
//
// Copyright (c) 2007-2011 by Softleister
//
// Der Cron-Job nimmt diese Datei als Include-Datei für CronController.php
// aktueller Pfad bei Ausführung: system/modules/cron
//
//-------------------------------------------------------------------
//  Backend um die Backup-Funktionen erweitern
//-------------------------------------------------------------------
class AutoBackupDb extends Backend      	    // Datenbank ist bereits geöffnet
{
  //-------------------------
  //  Constructor
  //-------------------------
  public function __construct( )
  {
    parent::__construct(); 			    // Construktor Backend ausführen
    $this->loadLanguageFile('default');		    // Sprachenfiles laden
    $this->loadLanguageFile('modules');
    $this->loadLanguageFile('tl_BackupDB'); 
    $this->import('Database');
    $this->import('BackupDbCommon');                // Backup-Funktionalität importieren
  }

  //-------------------------
  //  Backup ausführen
  //-------------------------
  public function run( )
  {
    @set_time_limit( 600 );
    
    $pfad = TL_ROOT . '/'.$GLOBALS['TL_CONFIG']['uploadPath'].'/AutoBackupDB';
    if( !file_exists( $pfad."/.htaccess" ) )
      copy( TL_ROOT . '/system/modules/BackupDB/htacc', $pfad . '/.htaccess' );
    
    //--- alte Backups aufrutschen ---  Anzahl einstellbar 29.3.2009 Softleister, ueber localconfig 07.05.2011
    $anzBackup = 3;
    if( isset( $GLOBALS['BACKUPDB']['AutoBackupCount'] ) && is_int($GLOBALS['BACKUPDB']['AutoBackupCount']) )
      $anzBackup = $GLOBALS['BACKUPDB']['AutoBackupCount'];
    if( isset( $GLOBALS['TL_CONFIG']['AutoBackupCount'] ) && is_int($GLOBALS['TL_CONFIG']['AutoBackupCount']) )
      $anzBackup = $GLOBALS['TL_CONFIG']['AutoBackupCount'];
    
    if( file_exists( $pfad."/AutoBackupDB-".$anzBackup.".sql" ) )
      unlink( $pfad."/AutoBackupDB-".$anzBackup.".sql" );
    for( ; $anzBackup > 1; $anzBackup-- ) {
      if( file_exists( $pfad."/AutoBackupDB-".($anzBackup-1).".sql" ) )
        rename( $pfad."/AutoBackupDB-".($anzBackup-1).".sql", $pfad."/AutoBackupDB-".$anzBackup.".sql" );
    }

    //--- neue Datei AutoBackupDB-1.sql ---
    $datei = new File( $GLOBALS['TL_CONFIG']['uploadPath'].'/AutoBackupDB/AutoBackupDB-1.sql' );
    $datei->write( $this->BackupDbCommon->getHeaderInfo( true ) );

    $sqlarray = $this->BackupDbCommon->getFromDB( );
    
    if( count($sqlarray) == 0 ) print "No tables found in database.";
    else {
      foreach( array_keys($sqlarray) as $table ) {
        $datei->write( $this->BackupDbCommon->get_table_structure( $table, $sqlarray[$table] ) );

	$this->BackupDbCommon->get_table_content( $table, $datei );   // Dateninhalte in Datei schreiben
      }
    }
    $datei->write( "\r\n# --- End of Backup ---\r\n" );     // Endekennung
    $datei->close();
  }
  
  //------------------------------------------------
}

//-------------------------------------------------------------------
//  Programmstart
//-------------------------------------------------------------------
$objBackupDB = new AutoBackupDB( );
$objBackupDB->run( );

//-------------------------------------------------------------------
?>