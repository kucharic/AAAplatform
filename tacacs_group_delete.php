<?php
  // Connecting, selecting database
  $tacacs_db = mysql_connect('127.0.0.1', 'tacacs', 'tacpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('tacacs') or die('Could not select database');

  if (!empty($_GET['groupname'])) {
    //get data from post
    $groupname = $_GET['groupname'];

    //check if exist
    $query = "DELETE FROM tacgroup WHERE groupname = '".$groupname."';";
    $res = mysql_query($query) or $error = my_error($tacacs_db);

    //if ok show list
    if (empty($error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/tacacs/groups";
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