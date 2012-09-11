<?php 
class civimobile {
  function home() {
    require_once 'civimobile.home.php';
    exit;
  }

  function contacts() {
    require_once 'civimobile.contacts.tpl.php';
  }

  function activities() {
    require_once 'civimobile.contact.activities.tpl.php';
  }

  function relationships() {
    require_once 'civimobile.contact.relationships.tpl.php';
  }

  function notes() {
    require_once 'civimobile.contact.notes.tpl.php';
  }

  function cases() {
    require_once 'civimobile.contact.cases.tpl.php';
  }

  function membership() {
    require_once 'civimobile.membership.tpl.php';
  }

  function contribution() {
    require_once 'civimobile.contribution.tpl.php';
  }

  function participant() {
    require_once 'civimobile.participant.tpl.php';
  }

  function event() {
    require_once 'civimobile.participants.tpl.php';
  }

  function survey() {
    require_once 'civimobile.survey.tpl.php';
  }
}
