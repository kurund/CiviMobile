<?php 
require_once('civimobile.header.php');
require_once('initialise.php');


//Get the id from the URL
$path=$_SERVER['REQUEST_URI'];
$split = explode ( "/" , $path );
$id = end($split);
//api call to get fields from profile
$params=array('version' =>'3','uf_group_id' => $id);
$results=civicrm_api("UFField","get", $params);

$results=$results['values'];

foreach($results as $result){
//	print_r($result['field_name']."\n");
//api call to get profile details

$ufgroup_results=civicrm_api("UFGroup","get", array ('version' =>'3','id' => $id));
$profile_title=$ufgroup_results['values'][$id]['title'];
}
//print_r($results);exit;
?>
<div data-role="page">
	<div data-role="header">
		<h1>Available Profiles</h1>
	</div><!-- /header -->
	<div data-role="content" id="profile-display-list">
		<div class="content-primary">
			<ul data-role="listview" data-theme="g" id="profile-list">
	    	</ul>	
	    </div><!-- /content-primary -->
	</div><!-- /content -->
	<?php require_once('civimobile.navbar.php') ?>
</div><!-- /page -->

<script>
// var contactId = <?php echo $id; ?>;
// var contact = <?php echo json_encode($results); ?>;

$( function(){
	    $().crmAPI ('UFGroup','get',{'version' :'3', 'is_active' : '1' }
	          ,{
	            ajaxURL: crmajaxURL,
	            success:function (data){
				console.log(data.values);
				$.each(data.values, function(index, value) {
				$('#profile-list').append('<li role="option" tabindex="-1" data-theme="c" id="profile-'+value.id+'" ><a href="'+value.id+'" data-ajax="false">'+value.title+' </a></li>');
				});		
				$("#profile-list").listview('refresh');
				}
		});	
});

</script>

<?php require_once('civimobile.footer.php')?>