 
	<div id="jqm-contactsheader" data-role="header">
        <h3>Search Contacts</h3>
        <a href="#menu" data-ajax="true" data-transition="slideup" data-direction="reverse" data-role="button" data-icon="home" data-iconpos="notext" class="ui-btn-right jqm-home">Home</a>
		<a href="#" id="add-contact-button" data-role="button" data-icon="plus" class="ui-btn-left jqm-home">Add</a>
		<a href="#" id="back-contact-button" data-role="button" data-icon="arrow-l" class="ui-btn-left jqm-home" style="display:none">Back</a>

	</div> 
	
	<div data-role="content" id="contact-content"> 
    <div class="ui-listview-filter ui-bar-c">
        <input type="search" name="sort_name" id="sort_name" value="" onkeyup="findContact()" />
    </div>
<ul id="contacts" data-role="listview" data-inset="false" data-filter="false" >
<?php
     $results=civicrm_api("Contact","get", array ('sequential' =>'1', 'version'=>3, 'return' =>'display_name,phone'));
 $contacts  = $results['values'];

 foreach($contacts as $key => $value) { ?>
     <li role="option" tabindex="-1" data-theme="c" id="contact-<?php print $value['contact_id']; ?>" >
       <a href="<?php print url('civicrm/mobile/contact/').$value['contact_id']; ?>" data-role="contact-<?php print $value['contact_id']; ?>" data-transition="slideup" >
       <?php print $value['display_name']; ?></a>
                                          </li>
                                       
                                          <?php    } ?>
</ul>
    </div>
    	
    <div style="display:none" id="add_contact">
    <div data-role="fieldcontain">
        <input type="text" name="first_name" id="firstName" value="" placeholder="First Name" />
    </div>
    <div data-role="fieldcontain">
        <input type="text" name="last_name" id="lastName" value="" placeholder="Last Name" />
    </div>
    <div data-role="fieldcontain">
        <input type="email" name="email" id="e-mail" value="" placeholder="Email" />
    </div>    
    <div data-role="fieldcontain">
        <input type="tel" name="tel" id="tell" value="" placeholder="Phone" />
    </div>
    <div data-role="fieldcontain">
    	<textarea cols="40" rows="8" name="Note" id="note" placeholder="Note"></textarea>
    </div>
    <a href="#" id="save-contact" data-role="button" data-ajax="false" data-theme="b" onclick="saveContact()">Save Contact</a> 
    </div>
<script>
     function findContact()
  {
    contactSearch($("#sort_name").val());

}

function contactSearch (q){
    $.mobile.showPageLoadingMsg( 'Searching' );
    $().crmAPI ('Contact','get',{'version' :'3', 'sort_name': q, 'return' : 'display_name,phone' }
          ,{ 
            ajaxURL: crmajaxURL,
            success:function (data){
              if (data.count == 0) {
                cmd = null;
                                          
              }
              else {
                cmd = "refresh";
                $('#contacts').show();
                $('#add_contact').hide();
                $('#contacts').empty();
              }
              $.each(data.values, function(key, value) {
                $('#contacts').append('<li role="option" tabindex="-1" data-ajax="false" data-theme="c" id="event-'+value.contact_id+'" ><a href="<?php print url('civicrm/mobile/contact/')?>'+value.contact_id+'" data-transition="slideup"  data-role="contact-'+value.contact_id+'">'+value.display_name+'</a></li>');
              });
           $.mobile.hidePageLoadingMsg( );
           $('#contacts').listview(cmd);
          }
   });
}
	$('#add-contact-button').click(function(){ addContact(); });
	$('#back-contact-button').click(function(){ goBack(); });
	
function goBack() {
	$('#back-contact-button').hide();
	$('#add_contact').hide();
	$('#contacts').show();
	$('#add-contact-button').show();
	}
function addContact() {
	$('#contact-content').append($('#add_contact'));
	$('#contacts').hide();
	$('#add-contact-button').hide();
	$('#add_contact').show();
	$('#back-contact-button').show();
}

function saveContact() {
  
 first_name = $('#firstName').val(); 
      last_name = $('#lastName').val(); 
      phone = $('#tell').val(); 
      email = $('#e-mail').val(); 
      note = $('#Note').val(); 

   
        $().crmAPI ('Contact','create',{
            'version' :'3', 
            'contact_type' :'Individual', // only individuals for now
            'first_name' :first_name, 
            'last_name' : last_name, 
            'phone' : phone, 
            'email' : email, 
            'notes' : note
            }
          ,{ 
              ajaxURL: crmajaxURL,
              success:function (data){    
              $.each(data.values, function(key, value) { 
                $.mobile.changePage("<?php print url('civicrm/mobile/contact/') ?>"+value.id);
                });
            }
        });
       
}

</script>
