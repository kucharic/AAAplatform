<?php
  $form_error = array();
     
  $isstatic = shell_exec("if $(sudo grep -qc 'eth0.*static' /etc/network/interfaces) ; then echo -n 'true' ; else echo -n 'false' ; fi ");
  if (!empty($_POST['submit']) && ($_POST['submit'] == 'yes')) {
    //somebody send data via form
    if (!empty($_POST['static']) && ($_POST['static'] == 'static')) {
      //we will configure network but safety first
      if (empty($_POST['ipAdd']) || (filter_var($_POST['ipAdd'], FILTER_VALIDATE_IP) == false) ) {
        $form_error['ipAdd'] = 'You must specifi IP address!';
      }
      if (empty($_POST['mask']) || (filter_var($_POST['mask'], FILTER_VALIDATE_IP) == false) ) {
        $form_error['mask'] = 'You must specifi Net Mask!';
      }
      if (empty($_POST['gw']) || (filter_var($_POST['gw'], FILTER_VALIDATE_IP) == false) ) {
        $form_error['gw'] = 'You must specifi Gateway!';
      }

      if (empty($form_error)) {
        shell_exec("sudo ./bin/network-settings.sh -i ".$_POST['ipAdd']." -m ".$_POST['mask']." -g ".$_POST['gw'].";");
      }
    } elseif ($isstatic) {
      shell_exec('sudo ./bin/network-settings.sh -d');
    }
  }

  $_POST['ipAdd'] = shell_exec("sudo ifconfig eth0 | awk '/inet addr/ {print $2}' | cut -f2 -d:");
  $_POST['mask'] = shell_exec("sudo ifconfig eth0 | awk '/Mask/ {print $4}' | cut -f2 -d:");
  $_POST['gw'] = shell_exec("sudo ip route show | awk  '/default/ {print $3}'");


  if (!empty($form_error)) {
    foreach ($form_error as $error) {
      echo "
        <div class=\"error\">
          <strong>Warning!</strong> $error
        </div>";
    }
  } 

?>

<div >
  <h2>Network info:</h2>
  <p><a href="#setNetwork" role="button" class="btn" data-toggle="modal">Set Network</a></p>
  <!-- Modal -->
  <form class="form-horizontal" action="/network" method="post">
    <div id="setNetwork" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Change Network Settings</h3>
      </div>

      <div class="control-group">
        <div class="controls">
          <label class="checkbox">
            <input type="checkbox" name="static" value="static" <?php if (!empty($isstatic) && ($isstatic=='true')) { echo "checked"; } ?>> Use Static
          </label>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="ipAdd">IP Address:</label>
        <div class="controls">
          <input type="text" id="ipAdd" name="ipAdd" placeholder="IP Address" <?php if (!empty($_POST['ipAdd'])) { echo "value=\"".$_POST['ipAdd']."\""; } ?>>
          <?php if (!empty($form_error["ipAdd"])) { echo "<span class=\"help-inline\">".$form_error["ipAdd"]."</span>" ; } ?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="mask">Net Mask:</label>
        <div class="controls">
          <input type="text" id="mask" name="mask" placeholder="Net Mask" <?php if (!empty($_POST['mask'])) { echo "value=\"".$_POST['mask']."\""; } ?> >
          <?php if (!empty($form_error["mask"])) { echo "<span class=\"help-inline\">".$form_error["mask"]."</span>" ; } ?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="gw">Gateway:</label>
        <div class="controls">
          <input type="text" id="gw" name="gw" placeholder="Gateway" <?php if (!empty($_POST['gw'])) { echo "value=\"".$_POST['gw']."\""; } ?> >
          <?php if (!empty($form_error["gw"])) { echo "<span class=\"help-inline\">".$form_error["gw"]."</span>" ; } ?>
        </div>
      </div>
      

      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button type="submit" class="btn btn-primary" value="yes" name="submit">Save Changes</button>
      </div>
      
    </div>
  </form>

<pre>
<?php
echo shell_exec('sudo ./bin/network-info.sh ');
?>
</pre>

  </p>
</div>

