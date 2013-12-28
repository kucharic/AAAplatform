<?php
  $policies = preg_split('/\n|[[:space:]]/', shell_exec("sudo kadmin.local -q 'list_policies' | tail -n+2 ; "), 0, PREG_SPLIT_NO_EMPTY);

  if (empty($policies))
  {
    echo "
          <div class=\"hero-unit\">
            <h2>No DATA.</h2>
            <p>There is no policies in Kerberos. Set some on server!</p>
          </div>";
  } 
  else 
  {
    echo "
          <table class=\"table table-striped\">
            <thead>
              <tr>
                <th>Policy</th>
                <th>Settings</th>
              </tr>
            </thead>
            <tbody>";
    foreach ($policies as $policy)
    {
      echo "
              <tr>
                <td>".$policy."</td>
                <td>".preg_replace('/\n/', '<br />', shell_exec("sudo kadmin.local -q 'get_policy $policy' | tail -n+3"))."</td>
              </tr>";
    }
    echo "
            </tbody>
          </table>";
  }

?>