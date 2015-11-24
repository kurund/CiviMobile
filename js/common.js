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
  else if ( contactId ) {
    CRM.api('Contact','get',{'version' :'3', 'id' : contactId },
      {
        success:function (data){
          var contactInfo = data.values[contactId];
          var jsonProfile = {};

          var profileId = getContactProfileId(contactInfo.contact_type);
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

      CRM.api('UFField','get',{'version' :'3', 'uf_group_id' : profileId, 'sort': 'weight' },
        {
          success:function (data){
            $.each(data.values, function(index, value) {
              var frozen = false;
              if ( value.location_type_id ) {
                if (value.field_name == 'phone') {
                  var field = jsonProfile[value.field_name+"-"+value.location_type_id+"-"+value.phone_type_id];
                  frozen = jsonProfile[value.field_name+"-"+value.location_type_id+"-"+value.phone_type_id]['frozen'];
                }
                else{
                  var field = jsonProfile[value.field_name+"-"+value.location_type_id];
                  frozen = jsonProfile[value.field_name+"-"+value.location_type_id]['frozen'];
                }
              }
              else if (value.field_name == 'phone') {
                var field = jsonProfile[value.field_name+"-Primary-"+value.phone_type_id];
                frozen = jsonProfile[value.field_name+"-Primary-"+value.phone_type_id]['frozen'];
              }
              else if ($.inArray(value.field_name, locationFields) >= 0 ) {
                var field = jsonProfile[value.field_name+"-Primary"];
                frozen = jsonProfile[value.field_name+"-Primary"]['frozen'];
              }
              else if (value.field_name.substr(0, 7)=='custom_') {
                var field = jsonProfile[value.field_name];
                frozen = jsonProfile[value.field_name]['frozen'];
              }
              else {
                var field = jsonProfile[value.field_name];
                frozen = jsonProfile[value.field_name]['frozen'];
              }

              if(field === undefined) {
                console.log("Failed to load the profile to edit this contact.");
                return;
              }
              if(frozen) {
                $('#' + profileContainerId ).append('<div data-role="fieldcontain" class="ui-field-contain ui-body ui-br">'+field.value+'</div>');
                return;
              }
              $('#' + profileContainerId ).append('<div data-role="fieldcontain" class="ui-field-contain ui-body ui-br">'+field.html+'</div>');

              var id = $(field.html).attr('id');
              var tagName = $(field.html).get(0).tagName;

              if (tagName == 'INPUT' || tagName == 'TEXTAREA') {
                if ($(field.html).get(0).type == 'text' || $(field.html).get(0).type == 'textarea') {
                  $('#'+id).textinput().attr( 'placeholder', value.label );
                }
                else if ( $(field.html).get(0).type == 'radio' ) {
                  $('#'+id).parent().prepend('<label for="'+id+'">'+value.label+':</label>');
                }
              }
             else if (tagName == 'SELECT') {
//                $('#'+id).selectmenu().parent().parent().prepend('<label for="'+id+'">'+value.label+':</label>');
	           $('<div class="ui-select"><label for="select-choice-0" class="select">' +value.label+ ':</label>').insertBefore('#'+id) ;
	           $('#'+id).selectmenu();
             }

              //gather all the processes field ids
              if($(field).get(0).type != 'group') {
                fieldIds[$(field).get(0).id] = "";
              }
              else {
                // Group types are for checkboxes, which won't have an id available,
                // so use the name instead.
                name = $(field).get(0).name;
                fieldIds[name] = "";
              }

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
        var profileId = getContactProfileId(data.result);
        // append appropriate profile id
        var dataUrl = CRM.url('civicrm/profile/view','reset=1&snippet=5&id=' + contactId + '&gid=' + profileId );

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
                elementValue = $(this).html();
                if ($(this).html() != 0) {
                  switch (row[0]) {
                    case 'email':
                      elementValue = '<a href="mailto:' + $(this).html() + '">' + $(this).html() + '</a>';
                      break;
                    case 'phone':
                      elementValue = '<a href="tel:' + $(this).html() + '">' + $(this).html() + '</a>';
                      break;
                  }
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
    }
  );
}

/**
 * A function to get the contact type and return the relevant ID of the
 * profile to use.
 */
function getContactProfileId(contactType) {
  var name;
  if(contactType == 'Individual') {
    name = 'ind_profile_id';
  }
  else if(contactType == 'Organization') {
    name = 'org_profile_id';
  }
  else if(contactType == 'Household') {
    name = 'house_profile_id';
  } 
  
  return window.defaultProfileIds[name];
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
            var profileId = getContactProfileId(data.result);
            processProfileSave( profileId, viewUrl, contactId );
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
    if($('#'+index).length == 1) {
      fieldIds[index] = $('#'+index).val();
    }
    else {
      // Handle check boxes
      var value = '';
      var values = {};
      $('input[name^=' + index + ']').each(function() {
        if($(this).is(' :checked')) {
          value = $(this)['context']['id'].replace(index + '_', '');
          values[value] = value;
        }
      });
      if(!$.isEmptyObject(values)) {
        fieldIds[index] = values;
      }
    }
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
      },
      error:function(data) {
        alert("There was an error saving the record: " + data.error_message);
      }
    }
  );
}

//added code from Common.js & crm.ajax.js

// https://civicrm.org/licensing
/* global CRM:true */
var CRM = CRM || {};
var cj = CRM.$ = jQuery;

// https://civicrm.org/licensing
/**
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/AJAX+Interface
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/Ajax+Pages+and+Forms
 */
(function($, CRM, undefined) {
  /**
   * @param string path
   * @param string|object query
   * @param string mode - optionally specify "front" or "back"
   */
  var tplURL;
  CRM.url = function (path, query, mode) {
    if (typeof path === 'object') {
      tplURL = path;
      return path;
    }
    if (!tplURL) {
      CRM.console('error', 'Error: CRM.url called before initialization');
    }
    if (!mode) {
      mode = CRM.config && CRM.config.isFrontend ? 'front' : 'back';
    }
    query = query || '';
    var frag = path.split('?');
    var url = tplURL[mode].replace("*path*", frag[0]);

    if (!query) {
      url = url.replace(/[?&]\*query\*/, '');
    }
    else {
      url = url.replace("*query*", typeof query === 'string' ? query : $.param(query));
    }
    if (frag[1]) {
      url += (url.indexOf('?') < 0 ? '?' : '&') + frag[1];
    }
    return url;
  };

  // @deprecated
  $.extend ({'crmURL':
    function (p, params) {
      CRM.console('warn', 'Calling crmURL from jQuery is deprecated. Please use CRM.url() instead.');
      return CRM.url(p, params);
    }
  });

  $.fn.crmURL = function () {
    return this.each(function() {
      if (this.href) {
        this.href = CRM.url(this.href);
      }
    });
  };

  /**
   * AJAX api
   */
  CRM.api3 = function(entity, action, params, status) {
    if (typeof(entity) === 'string') {
      params = {
        entity: entity,
        action: action.toLowerCase(),
        json: JSON.stringify(params || {})
      };
    } else {
      params = {
        entity: 'api3',
        action: 'call',
        json: JSON.stringify(entity)
      };
      status = action;
    }
    var ajax = $.ajax({
      url: CRM.url('civicrm/ajax/rest'),
      dataType: 'json',
      data: params,
      type: params.action.indexOf('get') < 0 ? 'POST' : 'GET'
    });
    if (status) {
      // Default status messages
      if (status === true) {
        status = {success: params.action === 'delete' ? ts('Removed') : ts('Saved')};
        if (params.action.indexOf('get') === 0) {
          status.start = ts('Loading...');
          status.success = null;
        }
      }
      var messages = status === true ? {} : status;
      CRM.status(status, ajax);
    }
    return ajax;
  };

  /**
   * @deprecated
   * AJAX api
   */
  CRM.api = function(entity, action, params, options) {
    // Default settings
    var settings = {
      context: null,
      success: function(result, settings) {
        return true;
      },
      error: function(result, settings) {
        $().crmError(result.error_message, ts('Error'));
        return false;
      },
      callBack: function(result, settings) {
        if (result.is_error == 1) {
          return settings.error.call(this, result, settings);
        }
        return settings.success.call(this, result, settings);
      },
      ajaxURL: 'civicrm/ajax/rest'
    };
    action = action.toLowerCase();
    // Default success handler
    switch (action) {
      case "update":
      case "create":
      case "setvalue":
      case "replace":
        settings.success = function() {
          CRM.status(ts('Saved'));
          return true;
        };
        break;
      case "delete":
        settings.success = function() {
          CRM.status(ts('Removed'));
          return true;
        };
    }
    params = {
      entity: entity,
      action: action,
      json: JSON.stringify(params)
    };
    // Pass copy of settings into closure to preserve its value during multiple requests
    (function(stg) {
      $.ajax({
        url: stg.ajaxURL.indexOf('http') === 0 ? stg.ajaxURL : CRM.url(stg.ajaxURL),
        dataType: 'json',
        data: params,
        type: action.indexOf('get') < 0 ? 'POST' : 'GET',
        success: function(result) {
          stg.callBack.call(stg.context, result, stg);
        }
      });
    })($.extend({}, settings, options));
  };
}(jQuery, CRM));

