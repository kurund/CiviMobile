<?php
    $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $parse_url = parse_url($url, PHP_URL_PATH);
?>

  <div id="jqm-contactsheader" data-role="header">
        <h3>Contacts proximity search</h3>
 <a href="#" data-rel="back" class="ui-btn-left" data-icon="arrow-l" data-transition="slideup">Back</a>
        <a href="#menu" data-direction="reverse" data-role="button" data-icon="home" data-transition="slideup" data-iconpos="notext" class="ui-btn-right jqm-home">Home</a>
	</div>
 
<div data-role="content" id="contact-contentz"> 
	<div class="ui-listview-filter ui-bar-c">
		<input type="search" name="postcode" id="postcode" placeholder="Postcode" onkeyup="getPostal();"/>
	</div>
	<div data-role="fieldcontain">
		<input type="checkbox" name="curLocation" id="useLocation" class="custom" />
		<label for="useLocation">Use current location</label>
	</div> <br/>
<div>
<ul id="contactz" data-role="listview" data-inset="false" data-filter="false" ></ul>
</div>
  <br/> <div> 
        <a href="#contactslist"  data-role="button"  data-transition="slideup" >Back to contact list</a>
  </div>  
</div>

 
      <script>

       function getPostal(){
         searchContactByPostcode ($("#postcode").val());
       }

function searchContactByPostcode(postcode){
      $.mobile.showPageLoadingMsg( 'Searching' );
      console.log(postcode)
      if(postcode != null){
        $().crmAPI ('Address','get',{'version' :'3', 'postal_code' : postcode}
          ,{
           ajaxURL: crmajaxURL,
           success:function (data){
 if (data.count == 0) {
                cmd = null;
               
                $('#contactz').hide();
                                           
              }
              else {
                cmd = "refresh";
                 $('#contactz').show();
                $('#contactz').empty();
              }
               $.each(data.values, function(key, value) {
                if(value.contact_id != null){
                  $().crmAPI ('Contact','getsingle',{'version' :'3', 'contact_id' :value.contact_id}
                      ,{ 
                       ajaxURL: crmajaxURL,
                       success:function (data){    
                           $('#contactz').append('<li role="option" tabindex="-1" data-ajax="true" data-theme="c" id="event-'+value.contact_id+'" ><a href="<?php print url('civicrm/mobile/contact/')?>'+value.contact_id+'" data-transition="slideup"  data-role="contact-'+value.contact_id+'">'+data.display_name+'</a></li>');
                          $('#contactz').listview(cmd);
                       }
                  });
                }
              });  
          }
        }); 
      }     
      $.mobile.hidePageLoadingMsg( );
} 
 
    		   	$( function(){
     				// Add a click listener on the button to get the location data
       				$('#useLocation').click(function(){
              $('#postcode').val('');
     					 if (this.checked) {
                  $('#contactz').remove();
            				if (navigator.geolocation) {
                 				navigator.geolocation.getCurrentPosition(onSuccess, onError);
            				} else {
                 				// If location is not supported on this platform, disable it
                 				$('#useLocation').value = "Geolocation not supported";
                 				$('#useLocation').unbind('click');
                        //$('#postcode').show();
            				}
            		}else{
                  $('#contactz').remove();
                }
     				 });

     				function onSuccess(location) {  			
  						console.log('latitude' + location.coords.latitude );
  						console.log('longitude' + location.coords.longitude );
  						console.log('Accuracy: ' + location.coords.accuracy);
  						searchContactByGeoLocation(location);
					}
     				 
					// Error function for Geolocation call
					function onError(msg){
						$('#locationResult').html("Geolocation not supported");
	  				$('#useLocation').unbind('click');
					}
 
				});

function searchContactByGeoLocation (params){
    $.mobile.showPageLoadingMsg( 'Searching' );
    $().crmAPI ('Location','get_by_location',{'version' :'3', 'latitude': params.coords.latitude, 'longitude' :  params.coords.longitude, 'distance' : 200, 'units' : 'miles'}
          ,{ 
            ajaxURL: crmajaxURL,
            success:function (data){ 
              if (data.count == 0) {                
                 $('#contactz').hide();    
              }
              else {
               
                $.each(data.values, function(key, value) {
                    $('#contactz').append('<li role="option" tabindex="-1" data-ajax="true" data-theme="c" id="event-'+value.contact_id+'" ><a href="<?php print url('civicrm/mobile/contact/')?>'+value.contact_id+'" data-transition="slideup" data-role="contact-'+value.contact_id+'">'+data.display_name+'</a></li>');
                });
                $('#contactz').listview();
              }
              $.mobile.hidePageLoadingMsg( );             
          }
   });
}


</script>