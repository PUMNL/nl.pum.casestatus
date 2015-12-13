<?php

/**
 * PumCaseStatus.Get API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_pum_case_status_get($params) {
  $returnValues = array();
  $config = CRM_Casestatus_Config::singleton();
  $caseTypeDefaultStatus = $config->getDefaultCaseStatus();
  foreach ($caseTypeDefaultStatus as $caseType => $status) {
    $returnValues[$caseType] = array('case_type_id' => $caseType, 'status_id' => $status);
  }
  return civicrm_api3_create_success($returnValues, $params, 'PumCaseStatus', 'Get');
}
