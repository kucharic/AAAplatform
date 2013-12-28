<?php
  // Connecting, selecting database
  $tacacs_db = mysql_connect('127.0.0.1', 'tacacs', 'tacpass')
      or die('Could not connect: ' . mysql_error());
  mysql_select_db('tacacs') or die('Could not select database');

  $groups = sql_query($tacacs_db, "select groupname from tacgroup order by groupname;");

  if (empty($groups))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no group in database. Click on button to add one.</p>
            <p><a href=\"/tacacs/group/add\" class=\"btn btn-primary btn-large\">Add Group</a></p>
          </div>";
  } 
  else 
  {
    echo "
          <table class=\"table table-striped\">
            <thead>
              <tr>
                <th>Group</th>
                <th>Has users</th>
                <th style=\"text-align: right;\"><a href=\"/tacacs/group/add\" class=\"btn btn-primary\">Add Group</a>
            </th>
              </tr>
            </thead>
            <tbody>";
    foreach ($groups as $line)
    {
      echo "
              <tr>
                <td>".$line['groupname']."</td>
                <td>";

      $query = "SELECT username from tacusergroup where groupname = '".$line['groupname']."';";
      $res = mysql_query($query) or die('Query failed: ' . mysql_error());
      while ($users = mysql_fetch_assoc($res)) {
        echo $users['username']." ";
      }
      mysql_free_result($res);
      echo "
                </td>
                <td style=\"text-align: right;\">
                  <a href=\"/tacacs/group/delete?groupname=".$line['groupname']."\" class=\"btn btn-danger\">Delete</a>
                </td>
              </tr>";
    }
    echo "
            </tbody>
          </table>";
  }

  // Closing connection
  mysql_close($tacacs_db);
?>