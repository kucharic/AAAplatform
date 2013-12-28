<?php
  // Connecting, selecting database
  $tacacs_db = mysql_connect('127.0.0.1', 'tacacs', 'tacpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('tacacs') or die('Could not select database');

  if (!empty($_GET['rule_id'])) {
    $query = "DELETE FROM tacauths WHERE id = '".$_GET['rule_id']."';";
    $res = mysql_query($query) or $error = my_error($tacacs_db);

    tacacs_udpate();
    
    //if ok show list
    if (empty($error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/tacacs/auths";
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