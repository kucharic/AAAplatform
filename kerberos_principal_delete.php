<?php
  // Connecting, selecting database
  if (!empty($_GET['principal'])) {
    $query = "sudo kadmin.local -q 'delete_principal -force ";
    $query .= $_GET['principal'] . " ' 2>&1 > /dev/null | grep -v 'WARNING: no policy specified for .*; defaulting to no policy' | sed 's/delete_principal: //'";
    exec($query, $error, $retcode);
    
    //if ok show list
    if (empty($error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/kerberos/principals";
      }
      echo "
<script type=\"text/javascript\"> location.href = '$return'; </script>";
    } else {
      echo "
          <div class=\"alert alert-error\">";
      foreach ($error as $line) {
        echo $line . "</br>";
      }
      echo "  </div>";
    }
  } else {
    echo "
          <div class=\"alert alert-error\">
              Can't delete no principal.
          </div>";
  }
?>