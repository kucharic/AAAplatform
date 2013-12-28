<?php
  $principals = preg_split('/\n|[[:space:]]/', shell_exec("sudo kadmin.local -q 'list_principals' | tail -n+2 | egrep -v 'kadmin|krbtg|K/M'; "), 0, PREG_SPLIT_NO_EMPTY);

  if (empty($principals))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no principal in Kerberos (instead system principal). Click on button to add one.</p>
            <p><a href=\"/kerberos/principal/add\" class=\"btn btn-primary btn-large\">Add principal</a></p>
          </div>";
  } 
  else 
  {
    echo "
          <table class=\"table table-striped\">
            <thead>
              <tr>
                <th>Policy</th>
                <th>Realm</th>
                <th>Settings</th>
                <th style=\"text-align: right;\"><a href=\"/kerberos/principal/add\" class=\"btn btn-primary\">Add Principal</a>
              </tr>
            </thead>
            <tbody>";
    foreach ($principals as $principal)
    {
      $pri=preg_replace('/[@].*$/', '', $principal);
      $rel=preg_replace('/^.*[@]/', '', $principal);
      $set=preg_replace('/\n/', '<br />', shell_exec("sudo kadmin.local -q 'get_principal $pri' | tail -n+3 | egrep -v '^Last|Key:|Number of keys:'"));
      echo "
              <tr>
                <td>".$pri."</td>
                <td>@".$rel."</td>
                <td>".$set."</td>
                <td style=\"text-align: right;\">
                  <a href=\"/kerberos/principal/chngpw?principal=".$pri."&return=/kerberos/principals\" class=\"btn btn-info\">Change pass</a>
                  <a href=\"/kerberos/principal/getkeytab?principal=".$pri."&return=/kerberos/principals\" class=\"btn btn-info\">KeyTab</a>
                  <a href=\"/kerberos/principal/delete?principal=".$pri."\" class=\"btn btn-danger\">Delete</a>
                </td>
              </tr>";
    }
    echo "
            </tbody>
          </table>";
  }

?>