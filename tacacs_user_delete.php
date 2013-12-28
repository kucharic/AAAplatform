<?php
  // Connecting, selecting database
  $tacacs_db = mysql_connect('127.0.0.1', 'tacacs', 'tacpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('tacacs') or die('Could not select database');

  if (!empty($_GET['username'])) {
    //get data from post
    $username = $_GET['username'];

    //check if exist
    $query = "DELETE FROM tacuser WHERE username = '".$username."';";
    $res = mysql_query($query) or $error = my_error($tacacs_db);

    //if ok show list
    if (empty($error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/tacacs/users";
      }
      echo "
<script type=\"text/javascript\"> location.href = '$return'; </script>";
    } else {
      echo "
          <div class=\"alert alert-error\">
            $error
          </div>";
    }
  }
  // Closing connection
  mysql_close($tacacs_db);
?>