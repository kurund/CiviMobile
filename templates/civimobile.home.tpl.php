<?php require_once('civimobile.header.php') ?>

<div data-role="page" id="home">
	<div data-role="header">
		<h1>CiviMobile</h1>
	</div><!-- /header -->
	
	<div data-role="content">	
    <a data-role="button" data-icon="search" id="csearch" href="/civimobile/contact" title="Contacts" class="icons" rel=external>Contact</a>
     <a data-role="button" data-icon="grid" href="/civimobile/event" title="Events" class="icons" data-ajax="false" rel=external>Events</a>
     <a data-role="button" data-icon="info" href="/civimobile/survey" title="Survey" class="icons" rel=external>Survey</a>
 
	</div><!-- /content -->
</div><!-- /page -->

<?php require_once('civimobile.footer.php') ?>

