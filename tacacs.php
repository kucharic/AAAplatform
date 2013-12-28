      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li <?php is_active('tacacs/users'); ?> ><a href="/tacacs/users">Users</a></li>
              <li <?php is_active('tacacs/groups'); ?> ><a href="/tacacs/groups">Groups</a></li>
              <li <?php is_active('tacacs/auths'); ?> ><a href="/tacacs/auths">Authorizations</a></li>
              <li class="nav-header">Docs</li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">

<?php

  function tacacs_udpate() {
    $query = "SELECT gc.id, ug.username, gc.groupname, gc.attribute, '=', gc.value FROM tacauths AS gc JOIN tacusergroup AS ug ON gc.groupname = ug.groupname ORDER BY ug.username, gc.attribute;";
    $res = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($line = mysql_fetch_assoc($res)) {
      $ini['users'][$line['username']][] = $line['groupname'];
      $ini[$line['groupname']][$line['attribute']][] = $line['value'];
    }

    write_ini('/etc/tacacs+/.do_auth.ini', $ini);
  }


  switch (split("/" , $_GET['do'], 2)[1]) {
    case 'users':
      include('tacacs_users.php');
      break;
    case 'user/add':
      include('tacacs_user_add.php');
      break;
    case 'user/delete':
      include('tacacs_user_delete.php');
      break;
    case 'user/edit':
      include('tacacs_user_edit.php');
      break;
    case 'groups':
      include('tacacs_groups.php');
      break;
    case 'group/add':
      include('tacacs_group_add.php');
      break;
    case 'group/delete':
      include('tacacs_group_delete.php');
      break;
    case 'group/edit':
      include('tacacs_group_edit.php');
      break;
    case 'group/auth/add':
      include('tacacs_group_auth_add.php');
      break;
    case 'auths':
      include('tacacs_auths.php');
      break;
    case 'auth/delete':
      include('tacacs_auth_delete.php');
      break;
    default:
      include('tacacs_home.php');
  }
?>
          
        </div><!--/span-->
      </div><!--/row-->