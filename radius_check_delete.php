<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'radius', 'radpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('radius') or die('Could not select database');

  if (!empty($_GET['rule_id'])) {
    //get data from post
    list($type, $id) = split('[_]', $_GET['rule_id']);
    
    switch ($type) {
      case 'u':
        $query = "DELETE FROM radcheck WHERE id = '".$id."';";
        $res = mysql_query($query) or $error = my_error($radius_db);
        break;
      case 'g':
        $query = "DELETE FROM radgroupcheck WHERE id = '".$id."';";
        $res = mysql_query($query) or $error = my_error($radius_db);
        break;

    }

    //if ok show list
    if (empty($error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/radius/checks";
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
  mysql_close($radius_db);
?>