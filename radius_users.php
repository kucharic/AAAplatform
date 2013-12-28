<?php
  // Connecting, selecting database
  $radius_db = mysql_connect('127.0.0.1', 'radius', 'radpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('radius') or die('Could not select database');

  $users = sql_query($radius_db, "select username from raduser order by username;");

  if (empty($users))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no user in database. Click on button to add one.</p>
            <p><a href=\"/radius/user/add\" class=\"btn btn-primary btn-large\">Add User</a></p>
          </div>";
  } 
  else 
  {
    echo "
          <table class=\"table table-striped\">
            <thead>
              <tr>
                <th>User</th>
                <th>In groups</th>
                <th style=\"text-align: right;\"><a href=\"/radius/user/add\" class=\"btn btn-primary\">Add User</a>
            </th>
              </tr>
            </thead>
            <tbody>";
    foreach ($users as $line)
    {
      echo "
              <tr>
                <td>".$line['username']."</td>
                <td>";

      $query = "SELECT groupname from radusergroup where username = '".$line['username']."' ORDER BY priority;";
      $res = mysql_query($query) or die('Query failed: ' . mysql_error());
      while ($groups = mysql_fetch_assoc($res)) {
        echo $groups['groupname']." ";
      }
      mysql_free_result($res);
      echo "
                </td>
                <td style=\"text-align: right;\">
                  <a href=\"/radius/user/edit?username=".$line['username']."&return=/radius/users\" class=\"btn btn-info\">Edit</a>
                  <a href=\"/radius/user/delete?username=".$line['username']."\" class=\"btn btn-danger\">Delete</a>
                </td>
              </tr>";
    }
    echo "
            </tbody>
          </table>";
  }

  // Closing connection
  mysql_close($radius_db);
?>