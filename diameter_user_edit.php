<?php
  if (!empty($_GET['return'])) {
    $return = $_GET['return'];
  } else {
    $return = "/diameter/users";
  }
  // Connecting, selecting database
  $diameter_db = mysql_connect('127.0.0.1', 'diameter', 'diameter')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('diameap') or die('Could not select database');

  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //check if valid
    if(empty($_POST['username']) || (strlen($_POST['username']) >= 64)) {
      $form_error['username'] = 'Username must be 1 - 64 char length!';
    } else {
      //save data to DB
      $query = "DELETE FROM user_grp WHERE user=(SELECT id FROM users WHERE username='".$_POST['username']."');";
      $res = mysql_query($query) or $form_error['__db'] = my_error($diameter_db);
        
      if (!empty($_POST['groups'])) {
        foreach ($_POST['groups'] as $group) {
          $query = "INSERT INTO user_grp (user, grp) VALUES ((SELECT id FROM users WHERE username='".$_POST['username']."'), (SELECT id FROM grp WHERE grp_name='".$group."'));";
          $res = mysql_query($query) or $form_error['__db'] = my_error($diameter_db);
        }
      }

      if (!empty($_POST['pass'])) {
        $query = "UPDATE users SET password=MD5('".$_POST['pass']."') WHERE username='".$_POST['username']."';";
        $res = mysql_query($query) or $form_error['__db'] = my_error($diameter_db);
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
    $query = "SELECT g.grp_name as groupname from user_grp ug JOIN grp g ON ug.grp=g.id JOIN users u ON ug.user=u.id where g.active='Y' and u.username = '".$_GET['username']."' ORDER BY groupname;";
    $res = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($line = mysql_fetch_assoc($res)) {
      $_POST['groups'][] = $line['groupname'];
    }
    $_POST['username'] = $_GET['username'];
  }

  $query = "SELECT grp_name AS groupname FROM grp where active='Y';";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  $groups = array();
  while ($line = mysql_fetch_assoc($res)) {
      $groups[] = $line['groupname'];
  }

?>
          <form class="form-horizontal" action="/diameter/user/edit<?php if (!empty($_GET['return'])) { echo "?return=".$_GET['return']; } ?>" method="post" onsubmit="$('input[name=username]').removeAttr('disabled');">
            <legend>User</legend>
            <div class="control-group <?php if (!empty($form_error["username"])) { echo "error"; } ?>">
              <label class="control-label" for="username">Username: </label>
              <div class="controls">
                <input type="text" id="username" placeholder="Username" name="username" class="span5" <?php if (!empty($_POST['username'])) { echo "value=\"".$_POST['username']."\""; } ?> disabled>
                <?php if (!empty($form_error["username"])) { echo "<span class=\"help-inline\">".$form_error["username"]."</span>" ; } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="pass">Password: </label>
              <div class="controls">
                <input type="password" id="pass" placeholder="Password" name="pass" class="span5">
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

                <a href=\"/diameter/group/add?return=/diameter/user/add\" class=\"btn btn-mini\">Set Group</a>
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
  mysql_close($diameter_db);
?>