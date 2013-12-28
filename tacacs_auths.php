<?php
  // Connecting, selecting database
  $tacacs_db = mysql_connect('127.0.0.1', 'tacacs', 'tacpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('tacacs') or die('Could not select database');

  $auths = array();

  // $query = "SELECT id, username, attribute, op, value FROM tacauths;";
  // $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  // while ($line = mysql_fetch_assoc($res)) {
  //   $auths[$line['username']][] = array(
  //                                    "id"        => "u_".$line['id'],
  //                                    "groupname" => "-",
  //                                    "attribute" => $line['attribute'],
  //                                    "op"        => '=',
  //                                    "value"     => $line['value'],
  //                                  );
  // }

  $query = "SELECT gc.id, ug.username, gc.groupname, gc.attribute, '=', gc.value FROM tacauths AS gc left join tacusergroup AS ug ON gc.groupname = ug.groupname ORDER BY ug.username, gc.attribute;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  while ($line = mysql_fetch_assoc($res)) {
    $auths[$line['username']][] = array(
                                     "id"        => $line['id'],
                                     "groupname" => $line['groupname'],
                                     "attribute" => $line['attribute'],
                                     "op"        => '=',
                                     "value"     => $line['value'],
                                   );
  }

  if (empty($auths))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no auth in database. Click on button to add one.</p>
            <p>
              <a href=\"/tacacs/group/auth/add\" class=\"btn btn-primary btn-large\">Add Group Rule</a>
            </p>
          </div>";
  } else {
    if (!empty($auths[""])) {
      echo "
          <div class=\"alert\">
            <strong>Warning!</strong> There are some unused auth rules.
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
                  <a href=\"/tacacs/group/auth/add\" class=\"btn btn-primary\">Add Group Rule</a>
                </th>
              </tr>
            </thead>
            <tbody>";
    foreach ($auths as $user => $user_auths) {
      $rowspan_control = true;
      foreach ($user_auths as $auth) {
        if ($rowspan_control && ($user != "")) {
          echo "
              <tr>
                <td rowspan=".count($user_auths).">$user</td>";
          $rowspan_control = false;
        } elseif ($rowspan_control && ($user == "")) {
          echo "
              <tr class='warning'>
                <td rowspan=".count($user_auths).">-</td>";
          $rowspan_control = false;
        } elseif ($user == "") {
          echo "
              <tr class='warning'>";
        } else {
          echo "
              <tr>";
        }
        foreach ($auth as $th => $cell) {
          if ($th != 'id') {
            echo "
                <td>".$cell."</td>";
          }
        }

        echo "
                <td style=\"text-align: right;\">
                  <a href=\"/tacacs/auth/delete?rule_id=".$auth['id']."\" class=\"btn btn-danger\">Delete</a>
                </td>
              </tr>";
      }
    }
    echo "
            </tbody>
          </table>";
  }

  // Closing connection
  mysql_close($tacacs_db);
?>