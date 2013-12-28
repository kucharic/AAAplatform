<?php
  // Connecting, selecting database
  if (!empty($_GET['principal'])) {
    echo 
    $ktabfile = "./tmp/" . preg_replace('/[\/[:punct:]]/', '_', $_GET['principal']) .".keytab";
    $query = "rm " . $ktabfile ." ; sudo kadmin.local -q 'ktadd -k " . $ktabfile ." -norandkey ";
    $query .= $_GET['principal'] . " ' 2>&1 > /dev/null | grep -v 'WARNING: no policy specified for .*; defaulting to no policy' | sed 's/ktadd: //' ; sudo chmod oug+rw " . $ktabfile;
    exec($query, $error, $retcode);
    
    if (file_exists($ktabfile)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.basename($ktabfile));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($ktabfile));
      ob_clean();
      flush();
      readfile($ktabfile);
    }

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
              Can't create keytab for no principal.
          </div>";
  }
?>