<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'radius', 'radpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('radius') or die('Could not select database');

  $replies = array();

  $query = "SELECT id, username, attribute, op, value FROM radreply;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  while ($line = mysql_fetch_assoc($res)) {
    $replies[$line['username']][] = array(
                                     "id"        => "u_".$line['id'],
                                     "groupname" => "-",
                                     "attribute" => $line['attribute'],
                                     "op"        => $line['op'],
                                     "value"     => $line['value'],
                                   );
  }

  $query = "SELECT gc.id, ug.username, gc.groupname, gc.attribute, gc.op, gc.value FROM radgroupreply AS gc left join radusergroup AS ug ON gc.groupname = ug.groupname ORDER BY ug.priority;";
  $res = mysql_query($query) or die('Query failed: ' . mysql_error());
  while ($line = mysql_fetch_assoc($res)) {
    $replies[$line['username']][] = array(
                                     "id"        => "g_".$line['id'],
                                     "groupname" => $line['groupname'],
                                     "attribute" => $line['attribute'],
                                     "op"        => $line['op'],
                                     "value"     => $line['value'],
                                   );
  }

  ksort($replies);

  if (empty($replies))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no reply in database. Click on button to add one.</p>
            <p>
              <a href=\"/radius/user/reply/add\" class=\"btn btn-primary btn-large\">Add User Reply</a>
              <a href=\"/radius/group/reply/add\" class=\"btn btn-large\">Add Group Reply</a>
            </p>
          </div>";
  } else {
    if (!empty($replies[""])) {
      echo "
          <div class=\"alert\">
            <strong>Warning!</strong> There are some unused reply rules.
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
                  <a href=\"/radius/user/reply/add\" class=\"btn btn-primary\">Add User Reply</a>
                  <a href=\"/radius/group/reply/add\" class=\"btn\">Add Group Reply</a>
                </th>
              </tr>
            </thead>
            <tbody>";
    foreach ($replies as $user => $user_replies) {
      $rowspan_control = true;
      foreach ($user_replies as $reply) {
        if ($rowspan_control && ($user != "")) {
          echo "
              <tr>
                <td rowspan=".count($user_replies).">$user</td>";
          $rowspan_control = false;
        } elseif ($rowspan_control && ($user == "")) {
          echo "
              <tr class='warning'>
                <td rowspan=".count($user_replies).">-</td>";
          $rowspan_control = false;
        } elseif ($user == "") {
          echo "
              <tr class='warning'>";
        } else {
          echo "
              <tr>";
        }     
        foreach ($reply as $th => $cell) {
          if ($th != 'id') {
            echo "
                <td>".$cell."</td>";
          }
        }

        echo "
                <td style=\"text-align: right;\">
                  <a href=\"/radius/reply/delete?reply_id=".$reply['id']."\" class=\"btn btn-danger\">Delete</a>
                </td>
              </tr>";
      }
    }
    echo "
            </tbody>
          </table>";
  }

  //also we nned to see if there is something unused

  // Closing connection
  mysql_close($radius_db);
?>