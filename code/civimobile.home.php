<?php 
require_once 'civimobile.header.php';
?>

<!-- start of menu page -->
<div data-role="page" id="menu">
  <div data-role="header">
    <h1>CiviMobile</h1>
	</div><!-- /header -->

	<div data-role="content">	
    <a data-role="button" data-icon="search" href="#contact-search" title="Contacts" class="icons" data-transition="slideup" >Contact</a>
    <a data-role="button" data-icon="grid" href="#events" title="Events" class="icons" data-transition="slideup" >Events</a>
    <a data-role="button" data-icon="info" href="#survey" title="Survey" class="icons" data-transition="slideup" >Survey</a>
  </div><!-- /content --> 
</div>
<!-- end of menu page -->

<div data-role="page" id="contact-search" >
    <?php require_once 'civimobile.contact_list.tpl.php'; ?>
</div>
<div data-role="page" id="add-contact-page" >
    <?php require_once 'civimobile.contact.tpl.php'; ?>
</div>


<!-- start of contact-edit page -->
<div data-role="page" id="contact-edit" >
    <?php //require_once 'civimobile.contact.tpl.php'; ?>
</div>
<!-- end of contact-edit page -->

<div data-role="page" id="contactslist" >
  <?php //require_once 'civimobile.contact.search.tpl.php'; ?>
</div>

<!-- start of event page -->
<div data-role="page" id="events" >
  <?php  require_once 'civimobile.event_list.tpl.php';?>
</div>
<!-- end of event page -->

<!-- start of survey page -->
<div data-role="page" id="survey" >
   <?php  require_once 'civimobile.survey_list.tpl.php';?>
</div>
<!-- end of survey page -->

<?php require_once 'civimobile.footer.php'; ?> 
