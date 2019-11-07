<?php
namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;

include_once dirname(__FILE__)."/classes/common.php";

global $Proj;

$project_id = $_GET['pid'];
$ae_type = $_GET['aet'];

if (!isset($project_id)) {
    die('Project ID is a required field');
}

$URI = explode("?",$_SERVER['REQUEST_URI'])[0];


$f_loc = explode("pages/",$GLOBALS["pagePath"])[0];



############### Generating CT AE Log Table and Export
$ct_fields_a = array('adverse_event_type', 'assessment_type', 'organ_system_name',
    'source_vocabulary', 'ae_term');
$ct_fields_b = array('numevents', 'numsubjectsaffected', 'numsubjectsatrisk');


$ct_rawdat_a = REDCap::getData($project_id, 'array', NULL, $ct_fields_a, 'ct_ae_log_arm_4', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);
$ct_rawdat_b = REDCap::getData($project_id, 'array', NULL, $ct_fields_b, 'ct_ae_log_arm_4', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);



$repeated_form_name = "clinicaltrails_ae_arm_total";
$cases = array_keys($ct_rawdat_a);

$event_id = array_keys($ct_rawdat_a[$cases[0]])[0];

$result_array = Array();
$result_fields = Array();
foreach ($cases as $case) {
// first form
    $result_case_array = $ct_rawdat_a[$case][$event_id]; // First dataset

    $instances = array_keys($ct_rawdat_b[$case]["repeat_instances"][$event_id][$repeated_form_name]); // Number of repeated instances

// instances of repeated form
    foreach ($instances as $instance) {
        $instance_data = $ct_rawdat_b[$case]["repeat_instances"][$event_id][$repeated_form_name][$instance];

// change array keys adding instance number as suffix
        foreach ($ct_fields_b as $field_b) {
            $instance_data[$field_b . "_" . $instance] = $instance_data[$field_b];
            unset($instance_data[$field_b]);
            //           print_r($instance_data);
        }
        $result_case_array = array_merge($result_case_array, $instance_data);
        //       print_r($result_case_array);
    }
    $result_array[$case] = $result_case_array;
    $result_fields = $result_fields + array_diff(array_keys($result_case_array), $result_fields);

}


$field = 'organ_system_name';
$field_op = getFieldCodeLabelPair($project_id, $field);

$field_label = getFieldLabel($field_op, '60');

$count_r = -1;
foreach ($result_array as $row) {
    $count_r++;

    $count = 0;
    foreach ($row as $fields_value) {

        $field_name = array_keys($row)[$count]; // field name


        $field_op = getFieldCodeLabelPair($project_id, $field_name);
        $field_label = getFieldLabel($field_op, $fields_value);

        if ($field_label != NULL) {

            $current_key = array_keys($result_array)[$count_r];
            $result_array[$current_key][$field_name] = $field_label;

        }

        $count++;
    }
}

###################################################
$raw_header = Array("adverseEventType","assessmentType",
    "organSystemName","sourceVocabulary","term");


$at_risk = array('study_arm_names');

$at_risk_set = REDCap::getData($project_id, 'array', 1, $at_risk, 'map_arm_1', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

$study_arm_names = explode(";",$at_risk_set[1][REDCap::getEventIdFromUniqueEvent('map_arm_1')]['study_arm_names']);

$arm_raw_names = array_slice($result_fields,5);


$i = 0;
$j = 0;
foreach ($arm_raw_names as $arm_raw_name){

    $arm_name[] = $study_arm_names[$j].'{'.explode("_", $arm_raw_name)[0].'}';
    $i = $i + 1;
    $j = (fmod($i,3) == 0 ? $j + 1 : $j + 0);
}


$raw_header = array_merge( $raw_header, $arm_name);


if ($ae_type == 's'){
    $type = 'Serious';
}
if ($ae_type == 'o'){
    $type = 'Other';
}



// : tab delimited txt export
$todays_date = date("Ymd");
header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=CT_FDA_AdvE_{$type}_pid_{$project_id}_{$todays_date}.csv");
$out = fopen('php://output', 'w');
fputcsv($out, $raw_header) or die("cannot write header");
foreach ($result_array as $fields) {
    if ($fields['adverse_event_type'] == $type) {
        fputcsv($out, $fields) or die("cannot write array");
    }
}
fclose($out);
unset($out);

?>