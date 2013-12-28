<?php
  // Connecting, selecting database
  $diameter_db = mysql_connect('127.0.0.1', 'diameter', 'diameter')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('diameap') or die('Could not select database');

  $authes = array();

  $query = "select a.id, u.username, g.grp_name as grp, a.attribute, a.value from authe a join grp g on a.grp = g.id left join user_grp ug on a.grp=ug.grp left join users u on ug.user=u.id ;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  while ($line = mysql_fetch_assoc($res)) {
    $authes[$line['username']][] = array(
                                     "id"        => $line['id'],
                                     "groupname" => $line['grp'],
                                     "attribute" => $line['attribute'],
                                     "value"     => $line['value'],
                                   );
  }

  ksort($authes);

  if (empty($authes))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no authe in database. Click on button to add one.</p>
            <p>
              <a href=\"/diameter/authe/add\" class=\"btn btn-large\">Add Authentication Rule</a>
            </p>
          </div>";
  } else {
    if (!empty($authes[""])) {
      echo "
          <div class=\"alert\">
            <strong>Warning!</strong> There are some unused authentication rules.
          </div>";
    }
    echo "
          <table class=\"table table-striped\">
            <thead>
              <tr>
                <th>User</th>
                <th>By group</th>
                <th>Attribute</th>
                <th>Value</th>
                <th style=\"text-align: right;\">
                  <a href=\"/diameter/authe/add\" class=\"btn\">Add Authentication Rule</a>
                </th>
              </tr>
            </thead>
            <tbody>";
    foreach ($authes as $user => $user_authes) {
      $rowspan_control = true;
      foreach ($user_authes as $authe) {
        if ($rowspan_control && ($user != "")) {
          echo "
              <tr>
                <td rowspan=".count($user_authes).">$user</td>";
          $rowspan_control = false;
        } elseif ($rowspan_control && ($user == "")) {
          echo "
              <tr class='warning'>
                <td rowspan=".count($user_authes).">-</td>";
          $rowspan_control = false;
        } elseif ($user == "") {
          echo "
              <tr class='warning'>";
        } else {
          echo "
              <tr>";
        }
        foreach ($authe as $th => $cell) {
          if ($th != 'id') {
            echo "
                <td>".$cell."</td>";
          }
        }

        echo "
                <td style=\"text-align: right;\">
                  <a href=\"/diameter/authe/delete?rule_id=".$authe['id']."\" class=\"btn btn-danger\">Delete</a>
                </td>
              </tr>";
      }
    }
    echo "
            </tbody>
          </table>";
  }

  // Closing connection
  mysql_close($diameter_db);
?>