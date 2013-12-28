<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'radius', 'radpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('radius') or die('Could not select database');

  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //check if valid
    if(empty($_POST['username']) || (strlen($_POST['username']) >= 64)) {
      $form_error['username'] = 'Username must be 1 - 64 char length!';
    } else {
      //save data to DB
      $query = "INSERT INTO raduser VALUES ('".$_POST['username']."');";
      $res = mysql_query($query) or $form_error['__db'] = my_error($radius_db);
      
      if (!empty($_POST['groups'])) {
        foreach ($_POST['groups'] as $group) {
          $query = "INSERT INTO radusergroup (username, groupname) VALUES ('".$_POST['username']."', '".$group."');";
          $res = mysql_query($query) or $form_error['__db'] = my_error($radius_db);
        }
      }
      mysql_free_result($res);
    }

    //if ok show list
    if (empty($form_error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/radius/users";
      }
      echo "
<script type=\"text/javascript\"> location.href = '$return'; </script>";
    } elseif (!empty($form_error['__db'])) {
      echo "
          <div class=\"alert alert-error\">
            ".$form_error['__db']."
          </div>";
    }
  }

  $query = "SELECT groupname FROM radgroup;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  $groups = array();
  while ($line = mysql_fetch_assoc($res)) {
      $groups[] = $line['groupname'];
  }

?>
          <form class="form-horizontal" action="/radius/user/add<?php if (!empty($_GET['return'])) { echo "?return=".$_GET['return']; } ?>" method="post">
            <legend>User</legend>
            <div class="control-group <?php if (!empty($form_error["username"])) { echo "error"; } ?>">
              <label class="control-label" for="username">Username: </label>
              <div class="controls">
                <input type="text" id="username" placeholder="Username" name="username" class="span5" <?php if (!empty($_POST['username'])) { echo "value=\"".$_POST['username']."\""; } ?>>
                <?php if (!empty($form_error["username"])) { echo "<span class=\"help-inline\">".$form_error["username"]."</span>" ; } ?>
              </div>
            </div>

<?php
  echo "
            <legend>User groups</legend>
            <div class=\"control-group\">
              <div class=\"controls\">";
  if (!empty($groups)) {
    foreach ($groups as $group) {
      echo "
                <label class=\"checkbox\">
                  <input type=\"checkbox\" name=\"groups[]\" value=\"$group\">$group
                </label>";
    }
  }
  echo "

                <a href=\"/radius/group/add?return=/radius/user/add\" class=\"btn btn-mini\">Add Group</a>
              </div>
            </div>";
?>
            <legend>Action</legend>
            <div class="control-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary" value="yes" name="submit">Add User</button>
              </div>
            </div>
          </form>

<?php
  // Closing connection
  mysql_close($radius_db);
?>