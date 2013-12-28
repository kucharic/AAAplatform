<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'radius', 'radpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('radius') or die('Could not select database');

  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //check if valid
    if(empty($_POST['groupname']) || (strlen($_POST['groupname']) >= 64)) {
      $form_error['groupname'] = 'Groupname must be 1 - 64 char length!';
    } else {
      //save data to DB
      $query = "INSERT INTO radgroup VALUES ('".$_POST['groupname']."');";
      $res = mysql_query($query) or $form_error['__db'] = my_error($radius_db);;
      mysql_free_result($res);
    }

    //if ok show list
    if (empty($form_error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/radius/groups";
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

?>
          <form class="form-horizontal" action="/radius/group/add<?php if (!empty($_GET['return'])) { echo "?return=".$_GET['return']; } ?>" method="post">
            <legend>Group</legend>
            <div class="control-group <?php if (!empty($form_error["groupname"])) { echo "error"; } ?>">
              <label class="control-label" for="groupname">Groupname:</label>
              <div class="controls">
                <input type="text" id="groupname" placeholder="Groupname" name="groupname" class="span5" <?php if (!empty($_POST['groupname'])) { echo "value=\"".$_POST['groupname']."\""; } ?>>
                <?php if (!empty($form_error["groupname"])) { echo "<span class=\"help-inline\">".$form_error["groupname"]."</span>" ; } ?>
              </div>
            </div>
            <legend>Action</legend>
            <div class="control-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary" value="yes" name="submit">Add Group</button>
              </div>
            </div>
          </form>

<?php
  // Closing connection
  mysql_close($radius_db);
?>