<?php 
class civimobile {
  function home() {
    require_once 'civimobile.home.php';
    exit;
  }

  function contacts() {
		$action = $_GET['action'];
    if ( $action == 'view' ) {
      require_once 'civimobile.contact_view.html';
    }
    else {
      require_once 'civimobile.contact.html';
    }
    exit;
  }

  function participantCheckin() {
    require_once 'civimobile.participant_checkin.html';
    exit;
  }

  function surveyContacts() {
    require_once 'civimobile.survey_contacts.html';
    exit;
  }
  
  function surveyInterview() {
    require_once 'civimobile.survey_interview.html';
    exit;
  }

  function login() {
    require_once 'civimobile.login.html';
    exit;
  }
}
