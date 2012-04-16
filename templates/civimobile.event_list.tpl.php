<?php require_once('civimobile.header.php'); ?>

<div data-role="page">

	<div data-role="header">
		<h1>Events</h1>
		<a href="/civimobile" data-direction="reverse" data-role="button" data-icon="home" data-iconpos="notext" class="ui-btn-left jqm-home"></a>
		<a href="#menu" data-role="button" data-icon="menu" data-iconpos="notext" class="ui-btn-right jqm-home"></a>
	</div><!-- /header -->
	
	<div data-role="content" id="event-content">
		<div class="content-primary">
		
			<ul data-role="listview" data-theme="c" id="event-list">
		    </ul>
		
		</div><!-- /content-primary -->
	</div><!-- /content -->
</div><!-- /page -->

<?php require_once('civimobile.footer.php') ?>


<script>
var page_path = '<?php echo $_SERVER['REQUEST_URI'];  ?>'
  
$( function(){
	$().crmAPI ('Event','get',{'version' :'3'}
          ,{
            ajaxURL: crmajaxURL,
            success:function (data){
			console.log(data.values);
			//alert("Sorry I couldn't find any events to display");
			
              if (data.count == 0) {
				alert("Sorry, I couldn't find any events to display");
              }
              else {
			 $.each(data.values, function(index, value) {
				$("#event-list").append('<li><a href="'+value.id+'" data-ajax="false">'+value.title+'<br />('+value.start_date+')</a></li>');
			 			});
			$("#event-list").listview('refresh');
				}
			}
			});
	
});
</script>
