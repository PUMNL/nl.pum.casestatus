<?php
/**
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_case_status_getdefault_spec(&$spec) {
  $spec['case_type_id']['api_required'] = 1;
}

/**
 * CaseStatus.Getdefault API
 * API to get the default case status for specific PUM case types
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception when case_type_id not set or empty
 */
function civicrm_api3_case_status_getdefault($params) {
  if (!isset($params['case_type_id']) || empty($params['case_type_id'])) {
    throw new API_Exception('Mandatory parameter case_type_id is missing or empty');
  }
  $caseStatusConfig = CRM_Casestatus_Config::singleton();
  $returnValues[] = $caseStatusConfig->getCaseTypeDefaultStatusId($params['case_type_id']);
  return civicrm_api3_create_success($returnValues, $params, 'CaseStatus', 'getdefault');
}

