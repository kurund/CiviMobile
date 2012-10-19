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
    $().crmAPI ('Contact','get',{'version' :'3', 'id' : contactId},
      { ajaxURL: crmajaxURL,
        success:function (data){
          var contactInfo = data.values[contactId];
          var jsonProfile = {};

          if (!profileId) {
            profileId = getContactProfileId(contactInfo.contact_type);
          }
          buildProfileForm( profileId, profileContainerId, dataUrl );
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

      $().crmAPI ('UFField','get',{'version' :'3', 'uf_group_id' : profileId},
        { ajaxURL: crmajaxURL,
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
 * @param profileId
 * @param profileContainerId
 * @param contactId
 */
function buildProfileView( profileId, profileContainerId, contactId ) {
  $().crmAPI ('Contact','get',{'version' :'3', 'id' : contactId}
    ,{
      ajaxURL: crmajaxURL,
      success:function (data){
        var contactInfo = data.values[contactId];
        if (!profileId) {
          profileId = getContactProfileId(contactInfo.contact_type);
        }
        $().crmAPI ('UFField','get',{'version' :'3', 'uf_group_id' : profileId}
          ,{ajaxURL: crmajaxURL,
            success:function (data){
              $.each(data.values, function(index, value) {
                if ( contactInfo[value.field_name] ) {
                  var content = '<li data-role="list-divider">'+value.label+'</li>' +
                    '<li role="option" tabindex="-1" data-theme="c" id="contact-'+value.field_name+'" >'+
                    contactInfo[value.field_name] +'</li>';
                }
                $('#' + profileContainerId).append(content);
              });
              $('#' + profileContainerId).listview('refresh');
            }
          });
      }
    });
}

/**
 * A function to get the contact type and return the relevant ID
 */
function getContactProfileId(contactType){
  var profileIds = {
    "Individual":'4',
    "Organization":'5',
    "Household":'6'};
  return profileIds[contactType];
}

function profileIdToContactType(profileId){
  var contactTypes = {
    '4':"Individual",
    '5':"Organization",
    '6':"Household"};
  return contactTypes[profileId];
}

/**
 * Save profile values
 */
function saveProfile( profileId, contactId, viewUrl ) {
  if (contactId){
    $().crmAPI ('Contact','getvalue',{'version' :'3', 'id' : contactId, 'sequential': '1', 'return' : 'contact_type'}
      ,{
        ajaxURL: crmajaxURL,
        success:function (data){
          var contactType = data.result;
          var profileId = getContactProfileId(contactType);
          $.each(fieldIds, function(index, value) {
            fieldIds[index] = $('#'+index).val();
          });
          fieldIds.version = "3";
          fieldIds.profile_id = profileId;
          fieldIds.contact_type = contactType;
          if ( contactId ) {
            fieldIds.contact_id = contactId;
          }
          $().crmAPI ('Profile','set', fieldIds
            ,{
              ajaxURL: crmajaxURL,
              success:function (data) {
                if ( viewUrl ) {
                  $.mobile.changePage( viewUrl + contactId );
                }
              }
            }
          );
        }
      }
    );
  }
  else{
    $.each(fieldIds, function(index, value) {
      fieldIds[index] = $('#'+index).val();
    });
    fieldIds.version = "3";
    fieldIds.profile_id = profileId;
    fieldIds.contact_type = profileIdToContactType(profileId);
    if ( contactId ) {
      fieldIds.contact_id = contactId;
    }
    $().crmAPI ('Profile','set', fieldIds
      ,{
        ajaxURL: crmajaxURL,
        success:function (data) {
          $.mobile.changePage( viewUrl + data.id );
        }
      }
    );
  }
}
