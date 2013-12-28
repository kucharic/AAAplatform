      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li <?php is_active('radius/groups'); ?> ><a href="/radius/groups">Groups</a></li>
              <li <?php is_active('radius/users'); ?> ><a href="/radius/users">Users</a></li>
              <li <?php is_active('radius/checks'); ?> ><a href="/radius/checks">Checks</a></li>
              <li <?php is_active('radius/replies'); ?> ><a href="/radius/replies">Replies</a></li>
              <li class="nav-header">Docs</li>
              <li ><a target="_blank" href="http://wiki.freeradius.org/config/Operators">Operators</a></li>
              <li ><a target="_blank" href="http://freeradius.org/rfc/attributes.html">Attributes</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">

<?php
  switch (split("/" , $_GET['do'], 2)[1]) {
    case 'users':
      include('radius_users.php');
      break;
    case 'user/add':
      include('radius_user_add.php');
      break;
    case 'user/delete':
      include('radius_user_delete.php');
      break;
    case 'user/edit':
      include('radius_user_edit.php');
      break;
    case 'user/check/add':
      include('radius_user_check_add.php');
      break;
    case 'user/reply/add':
      include('radius_user_reply_add.php');
      break;
    case 'groups':
      include('radius_groups.php');
      break;
    case 'group/add':
      include('radius_group_add.php');
      break;
    case 'group/delete':
      include('radius_group_delete.php');
      break;
    case 'group/edit':
      include('radius_group_edit.php');
      break;
    case 'group/check/add':
      include('radius_group_check_add.php');
      break;
    case 'group/reply/add':
      include('radius_group_reply_add.php');
      break;
    case 'checks':
      include('radius_checks.php');
      break;
    case 'check/delete':
      include('radius_check_delete.php');
      break;
    case 'replies':
      include('radius_replies.php');
      break;
    case 'reply/delete':
      include('radius_reply_delete.php');
      break;
    default:
      include('radius_home.php');
  }
?>
          
        </div><!--/span-->
      </div><!--/row-->