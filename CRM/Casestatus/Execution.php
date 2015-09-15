<?php

/**
 * Class dealing with execution case status tasks
 * (issue 2857 <http://redmine.pum.nl/issues/2857>)
 * for Case Status PUM
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 15 Sep 2015
 * @license AGPL-3.0
 */
class CRM_Casestatus_Execution {
  /**
   * Method to create debriefing activity if status case is set to Execution
   *
   * @param int $caseId
   * @param int $statusId
   *
   */
  public static function processExecution($caseId, $statusId) {
    $caseStatusConfig = CRM_Casestatus_Config::singleton();
    if ($statusId == $caseStatusConfig->getExecutionCaseStatusId()) {
      $caseTypeId = civicrm_api3("Case", "Getvalue", array('id' => $caseId, 'return' => 'case_type_id'));
      if (array_key_exists($caseTypeId, $caseStatusConfig->getDebriefingCaseTypes())) {
        self::createDebriefingActivity($caseId, $caseTypeId);
      }
    }
  }

  /**
   * Method to create the required debriefing activity
   *
   * @param $caseId
   * @param $caseTypeId
   * @throws Exception when error in API
   */
  public static function createDebriefingActivity($caseId, $caseTypeId) {
    $caseStatusConfig = CRM_Casestatus_Config::singleton();
    $activityTypes = $caseStatusConfig->getDebriefingActvityTypes();
    $activityTypeId = $activityTypes[$caseTypeId];
    $expertId = CRM_Threepeas_BAO_PumCaseRelation::getCaseExpert($caseId);
    if (!empty($expertId)) {
      $countParams = array(
        'case_id' => $caseId,
        'activity_type_id' => $activityTypeId
      );
      try {
        $activityCount = civicrm_api3("CaseActivity", "Getcount", $countParams);
        if ($activityCount == 0) {
          $activityParams = array(
            'activity_type_id' => $activityTypeId,
            'subject' => "Debriefing Expert",
            'target_id' => $expertId,
            'assignee_id' => $expertId,
            'case_id' => $caseId,
            'activity_date_time' => date("Y-m-d H:i:s", strtotime("+ 10 days"))
          );
          try {
            civicrm_api3("Activity", "Create", $activityParams);
          } catch (CiviCRM_API3_Exception $ex) {
            throw new API_Exception("Could not create activity with params: " . implode("; ", $activityParams)
              . ", error from API Activity Create: " . $ex->getMessage());
          }
        }
      } catch (CiviCRM_API3_Exception $ex) {
        throw new API_Exception("API CaseActivity Getcount did not process properly with case_id "
          . $caseId . " and activity_type_id " . $activityTypeId . ", error from API: " . $ex->getMessage());
      }
    }
  }
}