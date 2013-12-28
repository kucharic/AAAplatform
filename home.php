<div >
  <h2>AAAplatform status:</h2>

<table class="table">
<?php
$output = shell_exec('
    sudo ./bin/service-status.sh freeradius RADIUS ;
    sudo ./bin/service-status.sh tac_plus TACACS+ ;
    sudo ./bin/service-status.sh freeDiameterd Diameter ;
    sudo ./bin/service-status.sh krb KERBEROS ; 
    sudo echo -n $(uptime) ');

$str_search  = array("OK", "ERR", "\n", ": ");
$str_replace = array("<span class=\"label label-success\">OK</span>", "<span class=\"label label-important\">Err</span>", "</td></tr><tr><td>" , "</td><td>");
echo "<tr><td>".str_replace($str_search, $str_replace, $output);
?>
</table>

  </p>
</div>
