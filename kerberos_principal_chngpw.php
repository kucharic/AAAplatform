<?php
  if (!empty($_GET['return'])) {
    $return = $_GET['return'];
  } else {
    $return = '/kerberos/policies';
  }

  if (empty($_GET['principal']) && empty($_POST['principal'])) {
    echo "
<script type=\"text/javascript\"> location.href = '" . $return . "'; </script>";
  }

  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //check if valid
    if (empty($_POST['principal'])) {
      $form_error['principal'] = 'Principal can not be blank!';
    }
    
    //if ok show list
    if (empty($form_error)) {
      $query = "sudo kadmin.local -q 'change_password ";
      $query .= (!empty($_POST['password'])     ? ' -pw ' . $_POST['password'] . ' ' : ' -randkey ') ;
      $query .= " " . $_POST['principal'] . " ' 2>&1 > /dev/null | grep -v 'WARNING: no policy specified for .*; defaulting to no policy' | sed 's/change_password: //'";
      exec($query, $form_error['__exec'], $retcode);
      
      if (!empty($form_error['__exec'])) {
        echo "
          <div class=\"alert alert-error\">";
        foreach ($form_error['__exec'] as $line) {
          echo $line . "</br>";
        }
        echo "  </div>";
      } else {
        echo "
<script type=\"text/javascript\"> location.href = '" . $return . "'; </script>";
      }
    }
  }
  
?>
          <form class="form-horizontal" action="/kerberos/principal/chngpw<?php if (!empty($_GET['return'])) { echo '?return='.$_GET['return']; } ?>" method="post" onsubmit="$('input[name=principal]').removeAttr('disabled');"" method="post">
            <legend>Principal</legend>
            <div class="control-group">
              <label class="control-label" for="principal">Principal: </label>
              <div class="controls">
                <input type="text" id="principal" placeholder="Principal" name="principal" class="span5" value="<?php echo $_GET['principal'] ?>" disabled>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="password">Password: </label>
              <div class="controls">
                <input type="password" id="password" placeholder="Password" name="password" class="span5" >
                <span class="help-inline">Random password will be generated if blank.</span>
              </div>
            </div>

            <legend>Action</legend>
            <div class="control-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary" value="yes" name="submit">Change Pass</button>
              </div>
            </div>
          </form>

