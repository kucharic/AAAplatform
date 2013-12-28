<?php
  // Connecting, selecting database
  $diameter_db = mysql_connect('127.0.0.1', 'diameter', 'diameter')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('diameap') or die('Could not select database');

  if (!empty($_GET['username'])) {
    //get data from post
    $username = $_GET['username'];

    //check if exist
    $query = "DELETE FROM users WHERE username = '".$username."';";
    $res = mysql_query($query) or $error = my_error($diameter_db);

    //if ok show list
    if (empty($error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/diameter/users";
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
  mysql_close($diameter_db);
?>