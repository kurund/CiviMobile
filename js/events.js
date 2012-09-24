// events for contact search
$( document ).delegate("#cm-contact-search", "pageinit", function() {
  $('#sort_name').keyup( function() {
    if ($(this).val()) {
      contactSearch($(this).val());
    }
    else {
      $('#contacts').empty(); 
    }
  });
});

// events for event search
$( document ).delegate("#cm-events", "pageinit", function() {
  $('#event_name').keyup( function() {
    if ($(this).val()) {
      eventSearch($(this).val());
    }
    else {
      $('#events').empty(); 
    }
  });
});

// events for participant checkin
$( document ).delegate("#cm-participant-checkin", "pageinit", function() {
  $('#participant_name').keyup( function() {
    if ($(this).val()) {
      participantSearch($(this).val());
    }
    else {
      $('#participant-checkins').empty(); 
    }
  });
  participantSearch();
});

// events for survey search
$( document ).delegate("#cm-surveys", "pageinit", function() {
  $('#survey_name').keyup( function() {
    if ($(this).val()) {
      surveySearch($(this).val());
    }
    else {
      $('#surveys').empty(); 
    }
  });
});

// events for survey contact listing
$( document ).delegate("#cm-survey-contacts", "pageinit", function() {
  $('#sc_name').keyup( function() {
    if ($(this).val()) {
      surveyRespondantSearch($(this).val());
    }
    else {
      $('#survey-contacts').empty(); 
    }
  });

  surveyRespondantSearch();
});

// events for survey interview screen
$( document ).delegate("#cm-survey-interview", "pageinit", function() {
  surveyInterview();

  $('#save-survey-button').click(function(){ saveSurvey(); });
});

// proximity search
$( document ).delegate("#cm-proximity-search", "pageinit", function() {
    // Add a click listener on the button to get the location data
    $('#useLocation').click(function(){
      $('#postcode').val('');
      if ($('#useLocation').is(":checked")) {
        $('#contactz').remove();
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(onSuccess, onError, {maximumAge:600000});
          } else {
          // If location is not supported on this platform, disable it
          $('#useLocation').value = "Geolocation not supported";
          $('#useLocation').unbind('click');
          //$('#postcode').show();
        }
        } else{
        $('#contactz').remove();
      }
    });

    function onSuccess(location) {			
      //console.log('latitude' + location.coords.latitude );
      //console.log('longitude' + location.coords.longitude );
      //console.log('Accuracy: ' + location.coords.accuracy);
      searchContactByGeoLocation(location);
    }

    // Error function for Geolocation call
    function onError(msg){
      //console.log('error: '+msg);
      $('#locationResult').html("Geolocation not supported");
      $('#useLocation').unbind('click');
    }
}); 
