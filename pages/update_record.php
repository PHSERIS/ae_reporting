<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use REDCap as REDCap;

//include_once APP_PATH_DOCROOT . "ProjectGeneral/header.php";


//global $Proj;
//
//$project_id = '13971';
$project_id = $_POST["pid"];
$record_id = $_POST["record_id"];
$subject_id_raw = $_POST["subject_id_raw"];
$ae_date_raw = $_POST["ae_date_raw"];

$event_desc_raw = $_POST["event_desc_raw"];
$location_raw = $_POST["location_raw"];
$severity_raw = $_POST["severity_raw"];
$expectedness_raw = $_POST["expectedness_raw"];
$relatedness_raw = $_POST["relatedness_raw"];
$corrective_action_raw = $_POST["corrective_action_raw"];
$date_reported_raw = $_POST["date_reported_raw"];
$adverse_event_type_raw = $_POST["adverse_event_type_raw"];
$assessment_type_raw = $_POST["assessment_type_raw"];
$additional_description_raw = $_POST["additional_description_raw"];
$organ_system_name_raw = $_POST["organ_system_name_raw"];
$source_vocabulary_raw = $_POST["source_vocabulary_raw"];
$ae_term_raw = $_POST["ae_term_raw"];
$ae_arm_raw = $_POST["ae_arm_raw"];
$admin_notes = $_POST["admin_notes"];
$irb_c1_raw = $_POST["irb_c1_raw"];
$irb_c2_raw = $_POST["irb_c2_raw"];
$irb_c3_raw = $_POST["irb_c3_raw"];
$irb_c4_raw = $_POST["irb_c4_raw"];
$irb_c5_raw = $_POST["irb_c5_raw"];
//
if (!isset($project_id)) {
    die('Project ID is a required field');
}
//

//$test = REDCap::getEventIdFromUniqueEvent('worksheet_arm_2');


$dataX = array(
    $record_id => array(
        REDCap::getEventIdFromUniqueEvent('worksheet_arm_2') => array(
            'subject_id_source_ws' => $subject_id_raw,
            'ae_date_source_ws' => $ae_date_raw,
            'desc_event_source_ws' => $event_desc_raw,
            'location_source_ws' => $location_raw,
            'severity_source_ws' => $severity_raw,
            'expectedness_source_ws' => $expectedness_raw,
            'relatedness_source_ws' => $relatedness_raw,
            'corrective_source_ws' => $corrective_action_raw,
            'date_reported_source_ws' => $date_reported_raw,
            'ae_type_source_ws' => $adverse_event_type_raw,
            'assessment_type_source_ws' => $assessment_type_raw,
            'additional_desc_source_ws' => $additional_description_raw,
            'organ_system_source_ws' => $organ_system_name_raw,
            'source_vocab_source_ws' => $source_vocabulary_raw,
            'ae_term_source_ws' => $ae_term_raw,
            'ae_arm_source_ws' => $ae_arm_raw,
            'admin_notes_ws' => $admin_notes,
            'irb_custom_1_source_ws' => $irb_c1_raw,
            'irb_custom_2_source_ws' => $irb_c2_raw,
            'irb_custom_3_source_ws' => $irb_c3_raw,
            'irb_custom_4_source_ws' => $irb_c4_raw,
            'irb_custom_5_source_ws' => $irb_c5_raw
        )
    ));

$response = REDCap::saveData($project_id, 'array', $dataX);

//var_dump($response);

return $response;
?>

