<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;

include_once dirname(__FILE__) . "/classes/common.php";

global $Proj;

$project_id = $_GET['pid'];

if (!isset($project_id)) {
    die('Project ID is a required field');
}

$URI = explode("?", $_SERVER['REQUEST_URI'])[0];

$f_loc = explode("pages/", $GLOBALS["pagePath"])[0];


$irb_field_labels = array(
    "date-adverse-event" => array(
        "source" => "ae_date_source",
        "alt" => "ae_date_source",
        "label" => "Date of Adverse Event",
        "element_id" => "ae_date",
        "enable" => $module->getProjectSetting('date-adverse-event')
    ),
    "description-event" => array(
        "source" => "desc_event_source",
        "alt" => "desc_event_alt",
        "label" => "Description of events",
        "element_id" => "event_desc",
        "enable" => $module->getProjectSetting('description-event')
    ),
    "location" => array(
        "source" => "location_source",
        "alt" => "location_alt",
        "label" => "Location",
        "element_id" => "location",
        "enable" => $module->getProjectSetting('location')
    ),
    "severity" => array(
        "source" => "severity_source",
        "alt" => "severity_alt",
        "label" => "Severity",
        "element_id" => "severity",
        "enable" => $module->getProjectSetting('severity')
    ),
    "expectedness" => array(
        "source" => "expectedness_source",
        "alt" => "expectedness_alt",
        "label" => "Expectedness",
        "element_id" => "Expectedness",
        "enable" => $module->getProjectSetting('expectedness')
    ),
    "relatedness" => array(
        "source" => "relatedness_source",
        "alt" => "relatedness_alt",
        "label" => "Relatedness",
        "element_id" => "Relatedness",
        "enable" => $module->getProjectSetting('relatedness')
    ),
    "corrective-actions" => array(
        "source" => "corrective_source",
        "alt" => "corrective_alt",
        "label" => "Requires Changes / Corrective Action",
        "element_id" => "req_chang",
        "enable" => $module->getProjectSetting('corrective-actions')
    ),
    "date-reported" => array(
        "source" => "date_reported_source",
        "alt" => "date_reported_alt",
        "label" => "Date Reported to PHRC, if available",
        "element_id" => "date_rep",
        "enable" => $module->getProjectSetting('date-reported')
    ),
    "irb_custom_1" => array(
        "source" => "irb_custom_1_source",
        "alt" => "irb_custom_1_alt",
        "label" => $module->getProjectSetting('irb_custom_1'),
        "element_id" => "irb_custom_1",
        "enable" => $module->getProjectSetting('irb_custom_1')
    ),
    "irb_custom_2" => array(
        "source" => "irb_custom_2_source",
        "alt" => "irb_custom_2_alt",
        "label" => $module->getProjectSetting('irb_custom_2'),
        "element_id" => "irb_custom_2",
        "enable" => $module->getProjectSetting('irb_custom_2')
    ),
    "irb_custom_3" => array(
        "source" => "irb_custom_3_source",
        "alt" => "irb_custom_3_alt",
        "label" => $module->getProjectSetting('irb_custom_3'),
        "element_id" => "irb_custom_3",
        "enable" => $module->getProjectSetting('irb_custom_3')
    ),
    "irb_custom_4" => array(
        "source" => "irb_custom_4_source",
        "alt" => "irb_custom_4_alt",
        "label" => $module->getProjectSetting('irb_custom_4'),
        "element_id" => "irb_custom_4",
        "enable" => $module->getProjectSetting('irb_custom_4')
    ),
    "irb_custom_5" => array(
        "source" => "irb_custom_5_source",
        "alt" => "irb_custom_5_alt",
        "label" => $module->getProjectSetting('irb_custom_5'),
        "element_id" => "irb_custom_5",
        "enable" => $module->getProjectSetting('irb_custom_5')
    )
);

$ct_tx_fields[] = 'record_id';
$ct_tx_fields[] = 'subject_id_source_ws';

foreach ($irb_field_labels as $k => $v){
    if ($v["enable"] != false) {
        $ct_tx_fields[] = $v["source"] . "_ws";
    }
}


$ct_tx_a = REDCap::getData($project_id, 'array', NULL, $ct_tx_fields, 'worksheet_arm_2', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);
$cases_ct = array_keys($ct_tx_a);
$event_id_ct = array_keys($ct_tx_a[$cases_ct[0]])[0];


### : replacing drop down codes with drop down labels
$count_r = 0;
foreach ($ct_tx_a as $row) {
    $count_r++;

    $count = 0;
    foreach ($row[$event_id_ct] as $fields_value) {

        $field_name = array_keys($row[$event_id_ct])[$count]; // field name

        $field_op = getFieldCodeLabelPair($project_id, $field_name);
        $field_label = getFieldLabel($field_op, $fields_value);

        if ($field_label != NULL) {

            $ct_tx_a[$count_r][$event_id_ct][$field_name] = $field_label;

        }

        $count++;
    }
}

$IRB_field_headers[] = 'Item';
$IRB_field_headers[] = 'Subject ID';

foreach ($irb_field_labels as $k => $v){
    if ($v["enable"] != false) {
        $IRB_field_headers[] = $v["label"];
    }
}

// Generating output file

$todays_date = date("Ymd");
header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=IRB_AdvE_pid_{$project_id}_{$todays_date}.csv");
$out = fopen('php://output', 'w');
fputcsv($out, $IRB_field_headers) or die("cannot write header");
foreach ($ct_tx_a as $row) {
    fputcsv($out, $row[$event_id_ct]) or die("cannot write array");
}
fclose($out);
unset($out);


?>
