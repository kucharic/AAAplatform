<?php
  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //check if valid
    if (empty($_POST['principal'])) {
      $form_error['principal'] = 'Principal can not be blank!';
    }
    
    //if ok show list
    if (empty($form_error)) {
      $query = "sudo kadmin.local -q 'add_principal ";
      $query .= (!empty($_POST['maxlife'])      ? ' -maxlife "' . $_POST['maxlife'] . ' seconds" '      : ' ') ;
      $query .= (!empty($_POST['maxrenewlife']) ? ' -maxrenewlife "' . $_POST['maxlife'] . ' seconds" ' : ' ') ;
      $query .= (($_POST['policy'] == '---')    ? ' '                                : ' -policy ' . $_POST['policy'] . ' ' ) ;
      $query .= (!empty($_POST['password'])     ? ' -pw ' . $_POST['password'] . ' ' : ' -randkey ') ;
      $query .= (!empty($_POST['forwardable'])  ? ' +allow_forwardable '             : ' -allow_forwardable ') ;
      $query .= (!empty($_POST['renewable'])    ? ' +allow_renewable '               : ' -allow_renewable ') ;
      $query .= " " . $_POST['principal'] . " ' 2>&1 > /dev/null | grep -v 'WARNING: no policy specified for .*; defaulting to no policy' | sed 's/add_principal: //'";
      exec($query, $form_error['__exec'], $retcode);
      
      if (!empty($form_error['__exec'])) {
        echo "
          <div class=\"alert alert-error\">";
        foreach ($form_error['__exec'] as $line) {
          echo $line . "</br>";
        }
        echo "  </div>";
      } elseif (!empty($_GET['return'])) {
        echo "
<script type=\"text/javascript\"> location.href = '" . $_GET['return'] . "'; </script>";
      } else {
        echo "
<script type=\"text/javascript\"> location.href = '/kerberos/principals'; </script>";
      }
    }


  }

?>
          <form class="form-horizontal" action="/kerberos/principal/add" method="post">
            <legend>Principal</legend>
<?php
  if (empty($form_error['principal'])) {
    echo '
            <div class="control-group">
              <label class="control-label" for="principal">Principal: </label>
              <div class="controls">
                <input type="text" id="principal" placeholder="Principal" name="principal" class="span5" >';
  } else {
    echo '
            <div class="control-group error">
              <label class="control-label" for="principal">Principal: </label>
              <div class="controls">
                <input type="text" id="principal" placeholder="Principal" name="principal" class="span5" >
                <span class="help-inline">Can not be blank!</span>';
  }
?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="password">Password: </label>
              <div class="controls">
                <input type="password" id="password" placeholder="Password" name="password" class="span5" >
                <span class="help-inline">Random password will be generated if blank.</span>
              </div>
            </div>


            <legend>Settings</legend>
            <div class="control-group ">
              <label class="control-label" for="policy">Policy: </label>
              <div class="controls">
                <select id="policy" name="policy" class="span5">
                  <option>---</option>
<?php
  $policies = preg_split('/\n|[[:space:]]/', shell_exec("sudo kadmin.local -q 'list_policies' | tail -n+2 ; "), 0, PREG_SPLIT_NO_EMPTY);
  foreach ($policies as $policy)
  {
    echo "<option>$policy</option>";
  }                  
?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="maxlife">Maximum ticket life: </label>
              <div class="controls">
                <input type="maxlife" id="maxlife" placeholder="Maximum life of ticket" name="maxlife" class="span5" >
                <span class="help-inline">In seconds.</span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="maxrenewlife">Maximum renewable life: </label>
              <div class="controls">
                <input type="maxrenewlife" id="maxrenewlife" placeholder="Maximum renewable life of ticket" name="maxrenewlife" class="span5" >
                <span class="help-inline">In seconds.</span>
              </div>
            </div>
            <div class="control-group">
              <div class="controls">
                <label class="checkbox">
                  <input type="checkbox" name="forwardable" value="forwardable">Forwardable
                </label>
                <label class="checkbox">
                  <input type="checkbox" name="renewable" value="renewable">Renewable
                </label>
              </div>
            </div>

            <legend>Action</legend>
            <div class="control-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary" value="yes" name="submit">Add Principal</button>
              </div>
            </div>
          </form>

