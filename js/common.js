/*
 * function to build profile
 */

var fieldIds = {};
function buildProfile( profileId, profileContainerId, contactId, dataUrl ) {
  // add contact id
  if ( contactId ) {
    dataUrl += '&id=' + contactId;
  }

  // if profileId we know which profile to render, else retrieve from contact info
  // based on contact type
  if ( profileId ) {
    buildProfileForm( profileId, profileContainerId, dataUrl );
  }
  else {
    CRM.api('Contact','get',{'version' :'3', 'id' : contactId},
      {
        success:function (data){
          var contactInfo = data.values[contactId];
          var jsonProfile = {};

          if (!profileId) {
            var profileName = getContactProfileName(contactInfo.contact_type);
            CRM.api('uf_group', 'getvalue', {'version': '3', 'name': profileName, 'return': 'id'}, {
              success: function(data) {
                buildProfileForm( data.result, profileContainerId, dataUrl );
              }
            });
          }
          else {
            buildProfileForm( profileId, profileContainerId, dataUrl );
          }
        }
      }
    );
  }
}

function buildProfileForm( profileId, profileContainerId, dataUrl ) {
  // append appropriate profile id
  dataUrl += '&gid=' + profileId;

  $.getJSON( dataUrl,
    function(data) {
      jsonProfile = data;

      var locationFields = [
        'street_address',
        'street_number',
        'street_name',
        'street_unit',
        'supplemental_address_1',
        'supplemental_address_2',
        'city',
        'postal_code',
        'postal_code_suffix',
        'geo_code_1',
        'geo_code_2',
        'state_province',
        'country',
        'county',
        'email',
        'im',
        'address_name'];

      CRM.api('UFField','get',{'version' :'3', 'uf_group_id' : profileId, 'sort': 'weight' },
        {
          success:function (data){
            $.each(data.values, function(index, value) {
              if ( value.location_type_id ) {
                if (value.field_name == 'phone') {
                  var field = jsonProfile[value.field_name+"-"+value.location_type_id+"-"+value.phone_type_id];
                }
                else{
                  var field = jsonProfile[value.field_name+"-"+value.location_type_id];
                }
              }
              else if (value.field_name == 'phone') {
                var field = jsonProfile[value.field_name+"-Primary-"+value.phone_type_id];
              }
              else if ($.inArray(value.field_name, locationFields) >= 0 ) {
                var field = jsonProfile[value.field_name+"-Primary"];
              }
              else if (value.field_name.substr(0, 7)=='custom_') {
                var field = jsonProfile[value.field_name];
              }
              else {
                var field = jsonProfile[value.field_name];
              }

              var field = field.html;

              $('#' + profileContainerId ).append('<div data-role="fieldcontain" class="ui-field-contain ui-body ui-br">'+field+'</div>');

              var id = $(field).attr('id');
              var tagName = $(field).get(0).tagName;

              if (tagName == 'INPUT' || tagName == 'TEXTAREA') {
                if ($(field).get(0).type == 'text' || $(field).get(0).type == 'textarea') {
                  $('#'+id).textinput().attr( 'placeholder', value.label );
                }
                else if ( $(field).get(0).type == 'radio' ) {
                  $('#'+id).parent().prepend('<label for="'+id+'">'+value.label+':</label>');
                }
              }
              else if (tagName == 'SELECT'){
                $('#'+id).selectmenu().parent().parent().prepend('<label for="'+id+'">'+value.label+':</label>');
              }

              //gather all the processes field ids
              fieldIds[$(field).get(0).id] = "";

            });
          }
        }
      );
    }
  );
}

/**
 * Function to build profile view
 *
 * @param contactId
 * @param profileContainerId
 */
function buildProfileView( contactId, profileContainerId ) {
  CRM.api('Contact', 'getvalue', {'sequential': 1, 'contact_id': contactId, 'return': 'contact_type'}, {
      success: function(data) {
        var profileName = getContactProfileName(data.result);
        CRM.api('uf_group', 'getvalue', {'version': '3', 'name': profileName, 'return': 'id'}, {
          success: function(data) {

            // append appropriate profile id
            var dataUrl = CRM.url('civicrm/profile/view','reset=1&snippet=5&id=' + contactId + '&gid=' + data.result );

            $.get(
              dataUrl,
              function ( response ) {
                //console.log(response);
                var content = '';
                var elementValue = '';
                var row = '';
                $('<div>').html(response).
                find('#crm-container div[id^="row-"] div').each(function(i) {
                  if ($(this).hasClass('label')) {
                    content += '<li data-role="list-divider">'+$(this).html()+'</li>';
                  }
                  else if ($(this).hasClass('content')) {
                    //special case for email and phone
                    row = $(this).parent().attr('id').replace('row-', '').split('_');
                    switch (row[0]) {
                      case 'email':
                        elementValue = '<a href="mailto:">' + $(this).html() + '</a>';
                        break;
                      case 'phone':
                        elementValue = '<a href="tel:">' + $(this).html() + '</a>';
                        break;
                      default :
                        elementValue = $(this).html();
                        break
                    }
                    content += '<li role="option" tabindex="-1" data-theme="c">' + elementValue +'</li>';
                  }
                });
                $('#' + profileContainerId).append(content);
                $('#' + profileContainerId).listview('refresh');
              },
              'html'
            );

          }
        });
      }
    }
  );
}

/**
 * A function to get the contact type and return the relevant ID
 */
function getContactProfileName(contactType) {
  var profileIds = {
    "Individual": 'new_individual',
    "Organization": 'new_organization',
    "Household": 'new_household'
  };

  return profileIds[contactType];
}

/**
 * Save profile values, this used for saving contact add/edit mode
 * This function is also called to record the survey responses
 */
function saveProfile( profileId, contactId, viewUrl, activityId ) {
  // if contact id means either contact edit or survey interview mode
  if (contactId) {
    if (!profileId) {
      // contact edit case
      CRM.api('Contact','getvalue',{'version' :'3', 'id' : contactId, 'sequential': '1', 'return' : 'contact_type'}
        ,{
          success:function (data){
            var profileName = getContactProfileName(data.result);
            CRM.api('uf_group', 'getvalue', {'version': '3', 'name': profileName, 'return': 'id'}, {
              success: function(data) {
                processProfileSave( data.result, viewUrl, contactId );
              }
            });
          }
        }
      );
    }
    else {
      // record survey respondant case
      processProfileSave( profileId, viewUrl, contactId, activityId );
    }
  }
  else {
    processProfileSave( profileId, viewUrl );
  }
}

function processProfileSave( profileId, viewUrl, contactId, activityId ) {
  $.each(fieldIds, function(index, value) {
    fieldIds[index] = $('#'+index).val();
  });

  fieldIds.profile_id = profileId;
  fieldIds.version = "3";

  if ( contactId ) {
    fieldIds.contact_id = contactId;
  }

  if (activityId) {
    fieldIds.activity_id = activityId;
  }

  CRM.api('Profile','set', fieldIds ,{
      success:function (data) {
        if (viewUrl) {
          $.mobile.changePage( viewUrl + data.id );
        }
      }
    }
  );
}
