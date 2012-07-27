<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.1                                                |
 +--------------------------------------------------------------------+
 | Copyright Tech To The People (c) 2010                              |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 * File for the CiviCRM APIv3 Survey Respondents functions
 *
 * @package CiviCRM_APIv3
 * @subpackage API_Contribute
 */

/**
 * Include utility functions
 */
require_once 'api/v3/utils.php';
require_once 'CRM/Utils/Rule.php';
require_once 'CRM/Campaign/BAO/Survey.php';

/**
 * Get the list of voters (respondents, yet to record survey response) for a survey
 *
 * @param  array   $params           (reference ) input parameters
 *
 * @return array (reference )        contact_id
 * @static void
 * @access public
 */
function &civicrm_api3_survey_voter_get( $params ) {

    civicrm_api3_verify_one_mandatory($params,'CRM_Campaign_BAO_Survey',array('survey_id','id'));

    if (array_key_exists ( 'status_id', $params ) ) {
      $status_id=$params['status_id'];
    } else {
      $status_id=null;
    }
    $surveyID = empty($params['survey_id'])?$params['id']:$params['survey_id'];
	 
	require_once 'CRM/Campaign/BAO/Survey.php';
	$survey = new CRM_Campaign_BAO_Survey();
    $voters = $survey->getSurveyVoterInfo($surveyID);
	
	require_once 'CRM/Contact/BAO/Contact.php';
	foreach ($voters as $key => $voterArray){
        $voters[$key]['voter_name'] = CRM_Contact_BAO_Contact::displayName( $voterArray['voter_id'] );
    }
    return (civicrm_api3_create_success($voters,$params));
}

