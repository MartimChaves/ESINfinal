<?php
  require_once('config/init.php');

  session_destroy();

  header('Location: entrance.php');
?>