<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'radius', 'radpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('radius') or die('Could not select database');

  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //replyif valid
    if (empty($_POST['groupname']) || ($_POST['groupname'] == '---')) {
      $form_error['groupname'] = 'You must select a Group!';
    } elseif (empty($_POST['attribute']) || ($_POST['attribute'] == '---')) {
      $form_error['attribute'] = 'You must select attribute!';
    } elseif (empty($_POST['op']) || ($_POST['op'] == '---')) {
      $form_error['op'] = 'You must select op!';
    } elseif (empty($_POST['value'])) {
      $form_error['value'] = 'You must select value!';
    } else {
      //save data to DB
      $query = "INSERT INTO radgroupreply(groupname, attribute, op, value) VALUES ('".$_POST['groupname']."', '".$_POST['attribute']."', '".$_POST['op']."', '".$_POST['value']."' );";
      $res = mysql_query($query) or $form_error['__db'] = my_error($radius_db);
    }

    //if ok show list
    if (empty($form_error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/radius/replies";
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

  $query = "SELECT groupname FROM radgroup;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  $groups = array();
  while ($line = mysql_fetch_assoc($res)) {
      $groups[] = $line['groupname'];
  }

  $query = "SELECT op FROM radop;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  $ops = array();
  while ($line = mysql_fetch_assoc($res)) {
      $ops[] = $line['op'];
  }

  $query = "SELECT attribute FROM radattr;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  $attrs = array();
  while ($line = mysql_fetch_assoc($res)) {
      $attrs[] = $line['attribute'];
  }

?>

          <form class="form-horizontal" action="/radius/group/reply/add<?php if (!empty($_GET['return'])) { echo "?return=".$_GET['return']; } ?>" method="post">
            <legend>For</legend>
            <div class="control-group <?php if (!empty($form_error["groupname"])) { echo "error"; } ?>">
              <label class="control-label" for="groupname">Groupname:</label>
              <div class="controls">
                <select id="groupname" name="groupname" class="span5">
                  <option>---</option>
<?php
  foreach ($groups as $groupname) {
    if (!empty($_POST['groupname']) && ($_POST['groupname'] == $groupname)) {
      echo "
                  <option selected=\"selected\">$groupname</option>";
    } else {
      echo "
                  <option>$groupname</option>";
    }
  }
?>
                </select>
                <?php if (!empty($form_error["groupname"])) { echo "<span class=\"help-inline\">".$form_error["groupname"]."</span>" ; } ?>
              </div>
            </div>
            
            <legend>Reply</legend>
            <div class="control-group <?php if (!empty($form_error["attribute"])) { echo "error"; } ?>">
              <label class="control-label" for="attribute">Check:</label>
              <div class="controls">
                <select id="attribute" name="attribute" class="span5">
                  <option>---</option>
<?php
  foreach ($attrs as $attribute) {
    if (!empty($_POST['attribute']) && ($_POST['attribute'] == $attribute)) {
      echo "
                  <option selected=\"selected\">$attribute</option>";
    } else {
      echo "
                  <option>$attribute</option>";
    }
  }
?>
                </select>
                <?php if (!empty($form_error["attribute"])) { echo "<span class=\"help-inline\">".$form_error["attribute"]."</span>" ; } ?>
              </div>
            </div>

            <div class="control-group <?php if (!empty($form_error["op"])) { echo "error"; } ?>">
              <label class="control-label" for="op">by:</label>
              <div class="controls">
                <select id="op" name="op" class="span5">
                  <option>---</option>
<?php
  foreach ($ops as $op) {
    if ((!empty($_POST['op']) && ($_POST['op'] == $op)) || ($op == '=')) {
      echo "
                  <option selected=\"selected\">$op</option>";
    } else {
      echo "
                  <option>$op</option>";
    }
  }
?>
                </select>
                <?php if (!empty($form_error["op"])) { echo "<span class=\"help-inline\">".$form_error["op"]."</span>" ; } ?>
              </div>
            </div>

            <div class="control-group <?php if (!empty($form_error["value"])) { echo "error"; } ?>">
              <label class="control-label" for="value">For:</label>
              <div class="controls">
                <input type="text" id="value" placeholder="value" name="value" class="span5" <?php if (!empty($_POST['value'])) { echo "value=\"".$_POST['value']."\""; } ?>>
                <?php if (!empty($form_error["value"])) { echo "<span class=\"help-inline\">".$form_error["value"]."</span>" ; } ?>
              </div>
            </div>
            <legend>Action</legend>
            <div class="control-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary" value="yes" name="submit">Add Reply</button>
              </div>
            </div>
          </form>

<?php
  // Closing connection
  mysql_close($radius_db);
?>