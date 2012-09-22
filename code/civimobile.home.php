<?php require_once 'civimobile.header.php'; ?>

<!-- start of menu page -->
<div data-role="page" id="crm-jqm-home">
  <div data-role="header">
    <h1>CiviMobile</h1>
	</div><!-- /header -->

	<div data-role="content">	
    <a data-role="button" data-icon="search" href="#crm-contact-search" title="Contacts" class="icons" data-transition="slideup" >Contact</a>
    <a data-role="button" data-icon="grid" href="#crm-events" title="Events" class="icons" data-transition="slideup" >Events</a>
    <a data-role="button" data-icon="info" href="#crm-survey" title="Survey" class="icons" data-transition="slideup" >Survey</a>
  </div><!-- /content -->
</div>
<!-- end of menu page -->

<div data-role="page" id="crm-contact-search" >
    <?php require_once 'civimobile.contact_search.html'; ?>
</div>

<!-- start of event page -->
<div data-role="page" id="crm-events" >
  <?php require_once 'civimobile.event_search.html';?>
</div>
<!-- end of event page -->

<!-- start of survey page -->
<div data-role="page" id="crm-survey" >
   <?php  //require_once 'civimobile.survey_search.html';?>
</div>
<!-- end of survey page -->

<?php require_once 'civimobile.footer.php'; ?> 
