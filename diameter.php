      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li <?php is_active('diameter/groups'); ?> ><a href="/diameter/groups">Groups</a></li>
              <li <?php is_active('diameter/users'); ?> ><a href="/diameter/users">Users</a></li>
              <li <?php is_active('diameter/authes'); ?> ><a href="/diameter/authes">Authentications</a></li>
              <li <?php is_active('diameter/authzs'); ?> ><a href="/diameter/authzs">Authorizations</a></li>
              <li class="nav-header">Docs</li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">

<?php
  switch (split("/" , $_GET['do'], 2)[1]) {
    case 'users':
      include('diameter_users.php');
      break;
    case 'user/add':
      include('diameter_user_add.php');
      break;
    case 'user/delete':
      include('diameter_user_delete.php');
      break;
    case 'user/edit':
      include('diameter_user_edit.php');
      break;
    case 'groups':
      include('diameter_groups.php');
      break;
    case 'group/add':
      include('diameter_group_add.php');
      break;
    case 'group/delete':
      include('diameter_group_delete.php');
      break;
    case 'group/edit':
      include('diameter_group_edit.php');
      break;
    case 'authes':
      include('diameter_authes.php');
      break;
    case 'authe/delete':
      include('diameter_authe_delete.php');
      break;
    case 'authe/add':
      include('diameter_authe_add.php');
      break;
    case 'authzs':
      include('diameter_authzs.php');
      break;
    case 'authz/delete':
      include('diameter_authz_delete.php');
      break;
    case 'authz/add':
      include('diameter_authz_add.php');
      break;
    default:
      include('diameter_home.php');
  }
?>
          
        </div><!--/span-->
      </div><!--/row-->