<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'radius', 'radpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('radius') or die('Could not select database');

  if (!empty($_GET['groupname'])) {
    //get data from post
    $groupname = $_GET['groupname'];

    //check if exist
    $query = "DELETE FROM radgroup WHERE groupname = '".$groupname."';";
    $res = mysql_query($query) or $error = my_error($radius_db);

    //if ok show list
    if (empty($error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/radius/groups";
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