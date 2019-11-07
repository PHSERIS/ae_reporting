<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use REDCap as REDCap;

$project_id = $_POST["pid"];

$record_id = 1;
$token = $_POST["token"];
$study_arms = $_POST["study_arms"];
$at_risk = $_POST["at_risk"];
$arm_names = $_POST["arm_names"];
$event_name = ("" == $_POST["event_name"]) ? "": $_POST["event_name"];
$subject_id_source = $_POST["subject_id_source"];
$subject_id_alt = $_POST["subject_id_alt"];
$ae_date_source = $_POST["ae_date_source"];
$ae_date_alt = $_POST["ae_date_alt"];
$desc_event_source = $_POST["desc_event_source"];
$desc_event_alt = $_POST["desc_event_alt"];
$location_source = $_POST["location_source"];
$location_alt = $_POST["location_alt"];
$severity_source = $_POST["severity_source"];
$severity_alt = $_POST["severity_alt"];
$expectedness_source = $_POST["expectedness_source"];
$expectedness_alt = $_POST["expectedness_alt"];
$relatedness_source = $_POST["relatedness_source"];
$relatedness_alt = $_POST["relatedness_alt"];
$corrective_action_source = $_POST["corrective_action_source"];
$corrective_action_alt = $_POST["corrective_action_alt"];
$date_reported_source = $_POST["date_reported_source"];
$date_reported_alt = $_POST["date_reported_alt"];
$ae_type_source = $_POST["ae_type_source"];
$ae_type_alt = $_POST["ae_type_alt"];
$assessment_type_source = $_POST["assessment_type_source"];
$assessment_type_alt = $_POST["assessment_type_alt"];
$additional_desc_source = $_POST["additional_desc_source"];
$additional_desc_alt = $_POST["additional_desc_alt"];
$organ_system_name_source = $_POST["organ_system_name_source"];
$organ_system_name_alt = $_POST["organ_system_name_alt"];
$source_vocabulary_source = $_POST["source_vocabulary_source"];
$source_vocabulary_alt = $_POST["source_vocabulary_alt"];
$ae_term_source = $_POST["ae_term_source"];
$ae_term_alt = $_POST["ae_term_alt"];
$ae_arm_source = $_POST["ae_arm_source"];
$ae_arm_alt = $_POST["ae_arm_alt"];
$rcrd_affected_source = $_POST["rcrd_affected_source"];
$rcrd_affected_alt = $_POST["rcrd_affected_alt"];

$irb_c1_source = $_POST["irb_1_source"];
$irb_c1_alt = $_POST["irb_1_alt"];

$irb_2_source = $_POST["irb_2_source"];
$irb_2_alt = $_POST["irb_2_alt"];

$irb_3_source = $_POST["irb_3_source"];
$irb_3_alt = $_POST["irb_3_alt"];

$irb_4_source = $_POST["irb_4_source"];
$irb_4_alt = $_POST["irb_4_alt"];

$irb_5_source = $_POST["irb_5_source"];
$irb_5_alt = $_POST["irb_5_alt"];

//
if (!isset($project_id)) {
    die('Project ID is a required field');
}


$dataX = array(
    $record_id => array(
        REDCap::getEventIdFromUniqueEvent('map_arm_1') => array(
            'token' => $token,
            'study_arms_num' => $study_arms,
            'at_risk_per_arm' => $at_risk,
            'study_arm_names' => $arm_names,
            'unique_event_name_source' => $event_name,
            'subject_id_source' => $subject_id_source,
            'subject_id_alt' => $subject_id_alt,
            'ae_date_source' => $ae_date_source,
            'ae_date_alt' => $ae_date_alt,
            'desc_event_source' => $desc_event_source,
            'desc_event_alt' => $desc_event_alt,
            'location_source' => $location_source,
            'location_alt' => $location_alt,
            'severity_source' => $severity_source,
            'severity_alt' => $severity_alt,
            'expectedness_source' => $expectedness_source,
            'expectedness_alt' => $expectedness_alt,
            'relatedness_source' => $relatedness_source,
            'relatedness_alt' => $relatedness_alt,
            'corrective_source' => $corrective_action_source,
            'corrective_alt' => $corrective_action_alt,
            'date_reported_source' => $date_reported_source,
            'date_reported_alt' => $date_reported_alt,
            'ae_type_source' => $ae_type_source,
            'ae_type_alt' => $ae_type_alt,
            'assessment_type_source' => $assessment_type_source,
            'assessment_type_alt' => $assessment_type_alt,
            'additional_desc_source' => $additional_desc_source,
            'additional_desc_alt' => $additional_desc_alt,
            'organ_system_source' => $organ_system_name_source,
            'organ_system_alt' => $organ_system_name_alt,
            'source_vocab_source' => $source_vocabulary_source,
            'source_vocab_alt' => $source_vocabulary_alt,
            'ae_term_source' => $ae_term_source,
            'ae_term_alt' => $ae_term_alt,
            'ae_arm_source' => $ae_arm_source,
            'ae_arm_alt' => $ae_arm_alt,
            'rcrd_affected_source' => $rcrd_affected_source,
            'rcrd_affected_alt' => $rcrd_affected_alt,

            'irb_custom_1_source' => $irb_c1_source,
            'irb_custom_1_alt'=> $irb_c1_alt,

            'irb_custom_2_source' => $irb_2_source,
            'irb_custom_2_alt'=> $irb_2_alt,

            'irb_custom_3_source' => $irb_3_source,
            'irb_custom_3_alt'=> $irb_3_alt,

            'irb_custom_4_source' => $irb_4_source,
            'irb_custom_4_alt'=> $irb_4_alt,

            'irb_custom_5_source' => $irb_5_source,
            'irb_custom_5_alt'=> $irb_5_alt


        )
    ));

$response = REDCap::saveData($project_id, 'array', $dataX);

return $response;
?>