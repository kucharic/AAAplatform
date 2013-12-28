      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li <?php is_active('kerberos/policies'); ?> ><a href="/kerberos/policies">Policies</a></li>
              <li <?php is_active('kerberos/principals'); ?> ><a href="/kerberos/principals">Principals</a></li>
              <li class="nav-header">Docs</li>
              <li ><a target="_blank" href="http://techpubs.spinlocksolutions.com/dklar/kerberos.html#krb-inst-krberized">SPINLOCK - Installing kerberized service</a></li>
              <li ><a target="_blank" href="http://www.debian-administration.org/articles/570#krb-inst-krberized">Debian - Installing kerberized service</a></li>
              <li ><a target="_blank" href="http://web.mit.edu/Kerberos/krb5-1.3/krb5-1.3.5/doc/krb5-admin.html">krb5-admin manual</a></li>
              <li ><a target="_blank" href="http://www.kerberos.org/software/adminkerberos.pdf">The MIT's Kerberos Administrator's How-to Guide</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">

<?php
  switch (split("/" , $_GET['do'], 2)[1]) {
    case 'principals':
      include('kerberos_principals.php');
      break;
    case 'principal/add':
      include('kerberos_principal_add.php');
      break;
    case 'principal/delete':
      include('kerberos_principal_delete.php');
      break;
    case 'principal/chngpw':
      include('kerberos_principal_chngpw.php');
      break;
    case 'principal/getkeytab':
      include('kerberos_principal_getkeytab.php');
      break;
    case 'policies':
      include('kerberos_policies.php');
      break;
    default:
      include('kerberos_home.php');
  }
?>
          
        </div><!--/span-->
      </div><!--/row-->