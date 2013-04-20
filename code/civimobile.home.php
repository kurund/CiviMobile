<?php require_once 'civimobile.header.php'; ?>

<!-- start of menu page -->
<div data-role="page" id="cm-home">
  <div data-role="header">
    <h1>CiviMobile</h1>
  </div><!-- /header -->

  <div data-role="content">
    <?php
    if ( CRM_Utils_System::isUserLoggedIn() ) {
      ?>
      <a data-role="button" data-icon="search" href="#cm-contact-search" title="Contacts" class="icons" data-transition="slideup" >Contact</a>
      <a data-role="button" data-icon="grid" href="#cm-events" title="Events" class="icons" data-transition="slideup" >Events</a>
      <a data-role="button" data-icon="info" href="#cm-surveys" title="Survey" class="icons" data-transition="slideup" >Survey</a>
      <a data-role="button" data-icon="delete" href="<?php echo CRM_Utils_System::url('civicrm/mobile/logout'); ?>" title="click to logout" class="icons" data-transition="slideup"  data-ajax="false">Logout</a>
      <?php
    }
    else {
      CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/mobile/login'));
      CRM_Utils_System::civiExit();
    }
    ?>
  </div><!-- /content -->
</div>
<!-- end of menu page -->

<!-- start of contact search -->
<div data-role="page" id="cm-contact-search">
  <?php require_once 'civimobile.contact_search.html'; ?>
</div>
<!-- end of contact search -->

<!-- start of event page -->
<div data-role="page" id="cm-events" >
  <?php require_once 'civimobile.event_search.html';?>
</div>
<!-- end of event page -->

<!-- start of proximity search page -->
<div data-role="page" id="cm-proximity-search" >
  <?php require_once 'civimobile.proximity_search.html';?>
</div>
<!-- end of proximity search page -->

<!-- start of survey page -->
<div data-role="page" id="cm-surveys" >
  <?php require_once 'civimobile.survey_search.html';?>
</div>
<!-- end of survey page -->

<?php require_once 'civimobile.footer.php'; ?>
