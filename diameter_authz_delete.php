<?php
  // Connecting, selecting database
  $diameter_db = mysql_connect('127.0.0.1', 'diameter', 'diameter')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('diameap') or die('Could not select database');

  if (!empty($_GET['rule_id'])) {
    $query = "DELETE FROM authz WHERE id = '".$_GET['rule_id']."';";
    $res = mysql_query($query) or $error = my_error($diameter_db);

    //if ok show list
    if (empty($error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/diameter/authzs";
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