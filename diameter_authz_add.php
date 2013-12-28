<?php
  // Connecting, selecting database
  $diameter_db = mysql_connect('127.0.0.1', 'diameter', 'diameter')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('diameap') or die('Could not select database');

  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //authz if valid
    if (empty($_POST['groupname']) || ($_POST['groupname'] == '---')) {
      $form_error['groupname'] = 'You must select a Group!';
    } elseif (empty($_POST['attribute']) || ($_POST['attribute'] == '---')) {
      $form_error['attribute'] = 'You must select attribute!';
    } elseif (empty($_POST['op'])) {
      $form_error['op'] = 'You must select operator!';
    } elseif (empty($_POST['value'])) {
      $form_error['value'] = 'You must set value!';
    } else {
      //save data to DB
      $query = "INSERT INTO authz (grp, attribute, value, op) VALUES ((SELECT id FROM grp WHERE grp_name='".$_POST['groupname']."'), '".$_POST['attribute']."', '".$_POST['value']."', '".$_POST['op']."' );";
      $res = mysql_query($query) or $form_error['__db'] = my_error($diameter_db);
    }

    //if ok show list
    if (empty($form_error)) {
      if (!empty($_GET['return'])) {
        $return = $_GET['return'];
      } else {
        $return = "/diameter/authzs";
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

  $query = "SELECT grp_name as groupname FROM grp WHERE active='Y';";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  $groups = array();
  while ($line = mysql_fetch_assoc($res)) {
      $groups[] = $line['groupname'];
  }

  $attrs = array('NAS-IPv6-Address','NAS-Identifier','NAS-IP-Address','NAS-Port','NAS-Port-Id','NAS-Port-Type','Called-Station-Id','Calling-Station-Id','Connect-Info','Originating-Line-Info','Service-Type','Callback-Number','Callback-Id','Idle-Timeout','Port-Limit','NAS-Filter-Rule','Filter-Id','Configuration-Token','QoS-Filter-Rule','Framed-Protocol','Framed-Routing','Framed-MTU','Framed-Compression','Framed-IP-Address','Framed-IP-Netmask','Framed-Route','Framed-Pool','Framed-Interface-Id','Framed-IPv6-Prefix','Framed-IPv6-Pool','Framed-IPv6-Route','Framed-IPX-Network','Framed-Appletalk-Link','Framed-Appletalk-Network','Framed-Appletalk-Zone');
  $ops = array('==','>','>=','<','<=','!=','~=','=+','+==','+>','+>=','+<','+<=','+!=','+~=','==+','>+','>=+','<+','<=+','!=+');
  
  ksort($attrs);
?>

          <form class="form-horizontal" action="/diameter/authz/add<?php if (!empty($_GET['return'])) { echo "?return=".$_GET['return']; } ?>" method="post">
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
            
            <legend>Rule</legend>
            <div class="control-group <?php if (!empty($form_error["attribute"])) { echo "error"; } ?>">
              <label class="control-label" for="attribute">Attribute:</label>
              <div class="controls">
                <select id="attribute" name="attribute" class="span5">
                  <option>---</option>
<?php
  foreach ($attrs as $attribute) {
    var_dump($attrs);
    var_dump($attribute);
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
    if ((!empty($_POST['op']) && ($_POST['op'] == $op)) || ($op == '==')) {
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
              <label class="control-label" for="value">Value:</label>
              <div class="controls">
                <input type="text" id="value" placeholder="value" name="value" class="span5" <?php if (!empty($_POST['value'])) { echo "value=\"".$_POST['value']."\""; } ?>>
                <?php if (!empty($form_error["value"])) { echo "<span class=\"help-inline\">".$form_error["value"]."</span>" ; } ?>
              </div>
            </div>
            <legend>Action</legend>
            <div class="control-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary" value="yes" name="submit">Add Rule</button>
              </div>
            </div>
          </form>

<?php
  // Closing connection
  mysql_close($diameter_db);
?> 