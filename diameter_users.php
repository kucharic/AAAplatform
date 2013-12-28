<?php
  // Connecting, selecting database
  $diameter_db = mysql_connect('127.0.0.1', 'diameter', 'diameter')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('diameap') or die('Could not select database');

  $users = sql_query($diameter_db, "select username from users where active = 'Y' order by username;");

  if (empty($users))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no user in database. Click on button to add one.</p>
            <p><a href=\"/diameter/user/add\" class=\"btn btn-primary btn-large\">Add User</a></p>
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
                <th style=\"text-align: right;\"><a href=\"/diameter/user/add\" class=\"btn btn-primary\">Add User</a>
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

      $query = "SELECT g.grp_name as groupname from user_grp ug JOIN grp g ON ug.grp=g.id JOIN users u ON ug.user=u.id where g.active='Y' and u.username = '".$line['username']."' ORDER BY groupname;";
      $res = mysql_query($query) or die('Query failed: ' . mysql_error());
      while ($groups = mysql_fetch_assoc($res)) {
        echo $groups['groupname']." ";
      }
      mysql_free_result($res);
      echo "
                </td>
                <td style=\"text-align: right;\">
                  <a href=\"/diameter/user/edit?username=".$line['username']."&return=/diameter/users\" class=\"btn btn-info\">Edit</a>
                  <a href=\"/diameter/user/delete?username=".$line['username']."\" class=\"btn btn-danger\">Delete</a>
                </td>
              </tr>";
    }
    echo "
            </tbody>
          </table>";
  }

  // Closing connection
  mysql_close($diameter_db);
?>