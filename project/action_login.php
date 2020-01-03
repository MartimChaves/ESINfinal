<?php
  require_once('config/init.php');
  require_once('database/person.php');

  $username = $_POST['username'];
  $password = $_POST['password'];
  $_SESSION['loggedIn_Emp'] = new employee();

  if ($_SESSION['loggedIn_Emp']->isLoginCorrect($username, $password)) {
    $_SESSION['username'] = $username;
    $_SESSION['loggedIn_Emp']->getEmployeeInfoFromUsername($username);
  } else {
    $_SESSION['message'] = 'Login failed!';
  }
    
  header('Location: ' . $_SERVER['HTTP_REFERER']);
?>