<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'rsyslog', 'syslog')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('Syslog') or die('Could not select database');
?>
        <form action="/syslog" method="get">
          <div class="input-append">
            <input class="span2" id="filterReg" type="text" name="filterReg" placeholder="Regular expression" <?php if (!empty($_GET['filterReg'])) { echo "value=\"".$_GET['filterReg']."\""; } ?>>
            <div class="btn-group">
              <button type="submit" class="btn" value="filter" name="submit">Filter</button>
              <button class="btn dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li><a href="/syslog?filterReg=freeradius">RADIUS</a></li>
                <li><a href="/syslog?filterReg=tac_plus">TACACS+</a></li>
                <li><a href="/syslog?filterReg=freediameter">Diameter</a></li>
                <li><a href="/syslog?filterReg=krb5">KERBEROS</a></li>
                <li class="divider"></li>
                <li><a href="/syslog?filterReg=">Reset</a></li>
              </ul>
            </div>
          </div>
        </form>

<?php
  if (!empty($_GET['filterReg'])) {
    $table = sql_query($radius_db, "SELECT DeviceReportedTime, FromHost, Facility, Priority, SysLogTag, Message FROM SystemEvents WHERE SysLogTag RLIKE '".$_GET['filterReg']."' OR Message RLIKE '".$_GET['filterReg']."' ORDER BY DeviceReportedTime DESC LIMIT 100;");
  } else {
    $table = sql_query($radius_db, "SELECT DeviceReportedTime, FromHost, Facility, Priority, SysLogTag, Message FROM SystemEvents ORDER BY DeviceReportedTime DESC LIMIT 100;");
  }

  echo "
          <table class=\"table table-striped\">
            <thead>
              <tr>";
  foreach (array_keys($table[0]) as $key)
  {
    echo "
                <th>$key</th>";
      }
  echo "
              </tr>
            </thead>
            <tbody>";
  foreach ($table as $line)
  {
    echo "
              <tr>";
    if ($line['SysLogTag'] || $line['Message'])
    foreach ($line as $cell)
    {
      echo "
                <td>$cell</td>";
        }
    echo "
              </tr>";
    }
  echo "
            </tbody>
          </table>";

  // Closing connection
  mysql_close($radius_db);
?>