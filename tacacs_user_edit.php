<?php
  if (!empty($_GET['return'])) {
    $return = $_GET['return'];
  } else {
    $return = "/tacacs/users";
  }
  // Connecting, selecting database
  $tacacs_db = mysql_connect('127.0.0.1', 'tacacs', 'tacpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('tacacs') or die('Could not select database');

  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //check if valid
    if(empty($_POST['username']) || (strlen($_POST['username']) >= 64)) {
      $form_error['username'] = 'Username must be 1 - 64 char length!';
    } else {
      if(!empty($_POST['password'])) {
        $query = "UPDATE tacuser SET password=ENCRYPT('".$_POST['password']."') WHERE username='".$_POST['username']."';";
        $res = mysql_query($query) or $form_error['__db'] = my_error($tacacs_db);
      }
      //save data to DB
      $query = "DELETE FROM tacusergroup WHERE username='".$_POST['username']."';";
      $res = mysql_query($query) or $form_error['__db'] = my_error($tacacs_db);
        
      if (!empty($_POST['groups'])) {
        foreach ($_POST['groups'] as $group) {
          $query = "INSERT INTO tacusergroup (username, groupname) VALUES ('".$_POST['username']."', '".$group."');";
          $res = mysql_query($query) or $form_error['__db'] = my_error($tacacs_db);
        }
      }
      mysql_free_result($res);
    }

    //if ok show list
    if (empty($form_error)) {
      echo "
<script type=\"text/javascript\"> location.href = '$return'; </script>";
    } elseif (!empty($form_error['__db'])) {
      echo "
          <div class=\"alert alert-error\">
            ".$form_error['__db']."
          </div>";
    }
  }

  if (empty($_GET['username']) && empty($_POST['username'])) {
    echo "
<script type=\"text/javascript\"> location.href = '$return'; </script>";
  } elseif (!empty($_GET['username']) && empty($_POST['username'])) {
    $query = "SELECT groupname FROM tacusergroup WHERE username = '".$_GET['username']."';";
    $res = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($line = mysql_fetch_assoc($res)) {
      $_POST['groups'][] = $line['groupname'];
    }
    $_POST['username'] = $_GET['username'];
  }

  $query = "SELECT groupname FROM tacgroup;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  $groups = array();
  while ($line = mysql_fetch_assoc($res)) {
      $groups[] = $line['groupname'];
  }

?>
          <form class="form-horizontal" action="/tacacs/user/edit<?php if (!empty($_GET['return'])) { echo "?return=".$_GET['return']; } ?>" method="post" onsubmit="$('input[name=username]').removeAttr('disabled');">
            <legend>User</legend>
            <div class="control-group <?php if (!empty($form_error["username"])) { echo "error"; } ?>">
              <label class="control-label" for="username">Username: </label>
              <div class="controls">
                <input type="text" id="username" placeholder="Username" name="username" class="span5" <?php if (!empty($_POST['username'])) { echo "value=\"".$_POST['username']."\""; } ?> disabled>
                <?php if (!empty($form_error["username"])) { echo "<span class=\"help-inline\">".$form_error["username"]."</span>" ; } ?>
              </div>
            </div>
            <div class="control-group <?php if (!empty($form_error["password"])) { echo "error"; } ?>">
              <label class="control-label" for="password">Password: </label>
              <div class="controls">
                <input type="password" id="password" placeholder="Password" name="password" class="span5" <?php if (!empty($_POST['password'])) { echo "value=\"".$_POST['password']."\""; } ?>>
                <?php if (!empty($form_error["password"])) { echo "<span class=\"help-inline\">".$form_error["password"]."</span>" ; } ?>
              </div>
            </div>

<?php
  echo "
            <legend>User groups</legend>
            <div class=\"control-group\">
              <div class=\"controls\">";
  if (!empty($groups)) {
    foreach ($groups as $group) {
      if (in_array($group, $_POST['groups'])) {
        echo "
                <label class=\"checkbox\">
                  <input type=\"checkbox\" name=\"groups[]\" value=\"$group\" checked=\"checked\">$group
                </label>";
      } else {
        echo "
                <label class=\"checkbox\">
                  <input type=\"checkbox\" name=\"groups[]\" value=\"$group\">$group
                </label>";
      }
    }
  }
  echo "

                <a href=\"/tacacs/group/add?return=/tacacs/user/add\" class=\"btn btn-mini\">Set Group</a>
              </div>
            </div>";
?>
            <legend>Action</legend>
            <div class="control-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary" value="yes" name="submit">Edit User</button>
              </div>
            </div>
          </form>

<?php
  // Closing connection
  mysql_close($tacacs_db);
?>