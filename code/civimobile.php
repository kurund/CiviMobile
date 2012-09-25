<?php 
class civimobile {
  function home() {
    require_once 'civimobile.home.php';
    exit;
  }

  function contacts() {
		$action = $_GET['action'];
				//think of something clever to do if action is not one of view or edit
    require_once 'civimobile.contact_'.$action.'.html';
    exit;
  }

	function editContact() {
    require_once 'civimobile.contact_edit.html';
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
}
