<?php 
require_once('civimobile.header.php');
require_once('initialise.php');

$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

  
$parse_url = parse_url($url, PHP_URL_PATH);
    // get last arg of path (survey id)
$id = arg(3);

?>
<div data-role="page">
	<div data-role="header">
		<h1>The Survey</h1>
		<a href="#" id="back-contact-button"data-rel="back" class="ui-btn-left" data-icon="arrow-l">Back</a>
		<a href="#" id="save-contact-button" data-role="button" data-icon="check" class="ui-btn-right jqm-save">Save</a>
	</div><!-- /header -->

	<div data-role="content" id="survey-content">
	</div><!-- /content -->
	
</div><!-- /page -->


<script>
var respondentId = <?php echo $id; ?>;
var contact = <?php echo json_encode($results); ?>;

$( function(){
console.log(respondentId);	
});

</script>

<?php require_once('civimobile.footer.php')?>
