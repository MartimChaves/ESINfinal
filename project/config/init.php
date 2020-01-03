<?php
  require_once('database/person.php');
  require_once('database/medicalRecords.php');
  session_start();


  $dbh = new PDO('sqlite:./sql/info_database.db');
  # $db->exec( 'PRAGMA foreign_keys = ON;' );
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_SESSION['message'])) {
    $_MESSAGE = $_SESSION['message'];
    unset($_SESSION['message']);  
  }
?>