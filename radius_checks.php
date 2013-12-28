<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'radius', 'radpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('radius') or die('Could not select database');

  $checks = array();

  $query = "SELECT id, username, attribute, op, value FROM radcheck;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  while ($line = mysql_fetch_assoc($res)) {
    $checks[$line['username']][] = array(
                                     "id"        => "u_".$line['id'],
                                     "groupname" => "-",
                                     "attribute" => $line['attribute'],
                                     "op"        => $line['op'],
                                     "value"     => $line['value'],
                                   );
  }

  $query = "SELECT gc.id, ug.username, gc.groupname, gc.attribute, gc.op, gc.value FROM radgroupcheck AS gc left join radusergroup AS ug ON gc.groupname = ug.groupname ORDER BY ug.priority, ug.username;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  while ($line = mysql_fetch_assoc($res)) {
    $checks[$line['username']][] = array(
                                     "id"        => "g_".$line['id'],
                                     "groupname" => $line['groupname'],
                                     "attribute" => $line['attribute'],
                                     "op"        => $line['op'],
                                     "value"     => $line['value'],
                                   );
  }

  ksort($checks);

  if (empty($checks))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no check in database. Click on button to add one.</p>
            <p>
              <a href=\"/radius/user/check/add\" class=\"btn btn-primary btn-large\">Add User Rule</a>
              <a href=\"/radius/group/check/add\" class=\"btn btn-large\">Add Group Rule</a>
            </p>
          </div>";
  } else {
    if (!empty($checks[""])) {
      echo "
          <div class=\"alert\">
            <strong>Warning!</strong> There are some unused check rules.
          </div>";
    }
    echo "
          <table class=\"table table-striped\">
            <thead>
              <tr>
                <th>User</th>
                <th>By group</th>
                <th>Attribute</th>
                <th>Operator</th>
                <th>Value</th>
                <th style=\"text-align: right;\">
                  <a href=\"/radius/user/check/add\" class=\"btn btn-primary\">Add User Rule</a>
                  <a href=\"/radius/group/check/add\" class=\"btn\">Add Group Rule</a>
                </th>
              </tr>
            </thead>
            <tbody>";
    foreach ($checks as $user => $user_checks) {
      $rowspan_control = true;
      foreach ($user_checks as $check) {
        if ($rowspan_control && ($user != "")) {
          echo "
              <tr>
                <td rowspan=".count($user_checks).">$user</td>";
          $rowspan_control = false;
        } elseif ($rowspan_control && ($user == "")) {
          echo "
              <tr class='warning'>
                <td rowspan=".count($user_checks).">-</td>";
          $rowspan_control = false;
        } elseif ($user == "") {
          echo "
              <tr class='warning'>";
        } else {
          echo "
              <tr>";
        }
        foreach ($check as $th => $cell) {
          if ($th != 'id') {
            echo "
                <td>".$cell."</td>";
          }
        }

        echo "
                <td style=\"text-align: right;\">
                  <a href=\"/radius/check/delete?rule_id=".$check['id']."\" class=\"btn btn-danger\">Delete</a>
                </td>
              </tr>";
      }
    }
    echo "
            </tbody>
          </table>";
  }

  // Closing connection
  mysql_close($radius_db);
?>