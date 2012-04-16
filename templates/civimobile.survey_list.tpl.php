<?php require_once('civimobile.header.php'); ?>

<div data-role="page">

	<div data-role="header">
		<h1>Surveys</h1>
		<a href="/civimobile" data-ajax="false" data-direction="reverse" data-role="button" data-icon="home" data-iconpos="notext" class="ui-btn-left jqm-home">Home</a>
	</div><!-- /header -->
	
	<div data-role="content" id="survey-content">
		<div class="content-primary">
		
			<ul data-role="listview" data-theme="c" id="survey-list">
		    </ul>
		
		</div><!-- /content-primary -->
	</div><!-- /content -->

<?php require_once('civimobile.navbar.php') ?>

</div><!-- /page -->

<?php require_once('civimobile.footer.php') ?>


<script>
var page_path = '<?php echo $_SERVER['REQUEST_URI'];  ?>'
//console.log(page_path);
$( function(){
	$().crmAPI ('Survey','get',{'version' :'3'}
          ,{
            ajaxURL: crmajaxURL,
            success:function (data){
			console.log(data.values);
			//alert("Sorry I couldn't find any surveys to display");
			
              if (data.count == 0) {
				alert("Sorry, I couldn't find any surveys to display");
              }
              else {
			 $.each(data.values, function(index, value) {
				$("#survey-list").append('<li><a href="'+value.id+'/" data-ajax="false">'+value.title+'</a></li>');
			 			});
			$("#survey-list").listview('refresh');
				}
			}
			});
	
});
</script>