<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use REDCap as REDCap;

include_once dirname(__FILE__)."/classes/common.php";

$project_id = $_POST["pid"];
$token = $_POST["token"];
////
if (!isset($project_id) & sizeof($token)>5) {
    die('Project ID and Token is a required field');
}

// em@partners.org: generate the array of field_names (variable names) to be imported from the list of mapped fields in the mapping record
$all_mapping_names = array('unique_event_name_source','subject_id_source','subject_id_alt','ae_date_source','ae_date_alt','desc_event_source','desc_event_alt',
    'location_source','location_alt','severity_source','severity_alt','expectedness_source','expectedness_alt',
    'relatedness_source','relatedness_alt','corrective_source','corrective_alt','date_reported_source',
    'date_reported_alt','ae_type_source','ae_type_alt','assessment_type_source','assessment_type_alt','additional_desc_source',
    'additional_desc_alt','organ_system_source','organ_system_alt','source_vocab_source','source_vocab_alt',
    'ae_term_source','ae_term_alt','ae_arm_source','ae_arm_alt','rcrd_affected_source','rcrd_affected_alt','irb_custom_1_source',
    'irb_custom_1_alt','irb_custom_2_source','irb_custom_2_alt','irb_custom_3_source','irb_custom_3_alt','irb_custom_4_source',
    'irb_custom_4_alt','irb_custom_5_source','irb_custom_5_alt');



// em@partners.org :    -Getting the existing mappings
//                      -there is only one mapping record containing all the mappings needed
$record_num = 1;

$source_mappings = REDCap::getData($project_id, 'array', $record_num, $all_mapping_names, 'map_arm_1', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);
$event_id = array_keys($source_mappings[1])[0];

$mappings = $source_mappings[1][$event_id];

$temp = [];

foreach ($mappings as $k => $v){
    if ($v != "undefined"){
       $temp[$k]= $v;
    }
}

unset($mappings);
$mappings = $temp;


// em@partners.org: API call for project with several project arms

$data_p1 = array(
    'token' => $token,
    'content' => 'record',
    'format' => 'json',
    'type' => 'flat');

if ($mappings['unique_event_name_source'] != "undefined") {
    $event_mapping = $mappings['unique_event_name_source'];
    $mappings['record_id_source'] = 'record_id';
    unset($mappings['unique_event_name_source']);
    $data_p2 = array(
        'fields' => array_values($mappings),
        'events' =>  $event_mapping,//array('ae_data_arm_4'),
        'rawOrLabel' => 'raw',
        'rawOrLabelHeaders' => 'raw',
        'exportCheckboxLabel' => 'false',
        'exportSurveyFields' => 'false',
        'exportDataAccessGroups' => 'false',
        'returnFormat' => 'json'
    );
    $data = array_merge($data_p1, $data_p2);
} else {
    echo "else statement";
    unset($mappings['unique_event_name_source']);
    $mappings['record_id_source'] = 'record_id';
    $data_p2 = array(
    'fields' => array_values($mappings),
    'rawOrLabel' => 'raw',
    'rawOrLabelHeaders' => 'raw',
    'exportCheckboxLabel' => 'false',
    'exportSurveyFields' => 'false',
    'exportDataAccessGroups' => 'false',
    'returnFormat' => 'json'
    );
    $data = array_merge($data_p1, $data_p2);
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://' . $_SERVER["SSL_TLS_SNI"] . '/redcap/api/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
$output = curl_exec($ch);

$source_array = json_decode($output, true);


curl_close($ch);

//em@partners.org : Load the Researcher's project Data Dictionary to catch those fields that have answer choices

$dd_fields = access_dd_other_project($token);

foreach($dd_fields as $dd_field){

    if( strlen($dd_field['choices'][0]) > 0 ){

        $options = explode("|", $dd_field['choices']);

        $var_labels = [];
        $var_codes = [];

        foreach ($options as $option) {
            $code_labels = explode(", ", $option);
            $var_codes[] = $code_labels[0];
            $var_labels[] = $code_labels[1];
        }

        $codeLabelPairs = Array(
            'codes' => $var_codes,
            'labels' => $var_labels
        );

        $dd_fields_choices[$dd_field['field_name']][] = $codeLabelPairs;
    }

}


$ae_record_num = 0;

    foreach ($source_array as $record) {

        if (strlen($record[$mappings[rcrd_affected_source]]) > 0) {



            $ae_record_num++;


            foreach ($mappings as $k => $mapping) {

                if ($k != "unique_event_name_source") {

                    if (sizeof($dd_fields_choices[$mapping][0]) > 0) {


                        $value_to_load = getFieldLabel($dd_fields_choices[$mapping][0], $record[$mapping]);

                        $dataX = array(
                            $ae_record_num => array(//$record['record_id'] => array(
                                REDCap::getEventIdFromUniqueEvent('worksheet_arm_2') => array(
                                    // em@partners.org: prototype of call for saving source record data into target field in the worksheet.
                                    $k . '_ws' => $value_to_load
                                )
                            ));
                    } else {
                        $value_to_load = $record[$mapping];

                        $dataX = array(
                            $ae_record_num => array(
                                REDCap::getEventIdFromUniqueEvent('worksheet_arm_2') => array(
                                    // em@partners.org: prototype of call for saving source record data into target field in the worksheet.
                                    $k . '_ws' => $value_to_load
                                )
                            ));
                    }

                    // save raw value to all Worksheet fields including Raw Fields.
                    $response = REDCap::saveData($project_id, 'array', $dataX);

                    // if $k is one of the target fields that is expecting code instead of label
                    if($k == "location_source" || $k == "severity_source" || $k == "expectedness_source" || $k == "relatedness_source" || $k == "ae_type_source" || $k == "assessment_type_source" || $k == "organ_system_source"){
                        $target_field_opts = getFieldCodeLabelPair($project_id, $k . '_ws');

                        if (sizeof($dd_fields_choices[$mapping][0]) > 0) {
                            $needle_temp = getFieldLabel($dd_fields_choices[$mapping][0], $record[$mapping]);
                            $needle_array = array($needle_temp);
                            $needle = $needle_array[0];
                            print("\n Source Dropdown");
                            $test = (trim($needle) == trim('External')) ? 'yes' : 'no';
                            print("\nIs the needle the same as 'External'?");
                            echo $test;
                        } else {
                            // Raw data to be imported into target drop down fields
                            
                            $needle = $record[$mapping];
                        }
                        $key = array_search(strtolower(trim($needle)),  array_map('strtolower',$target_field_opts['labels']));
                        $value_to_load = $target_field_opts['codes'][$key];

                        $dataX = array(
                            $ae_record_num => array(//$record['record_id'] => array(
                                REDCap::getEventIdFromUniqueEvent('worksheet_arm_2') => array(
                                    // em@partners.org: prototype of call for saving source record data into target field in the worksheet.
                                    $k . '_ws' => $value_to_load
                                )
                            ));

                    }

                    $response = REDCap::saveData($project_id, 'array', $dataX);
                }
                }
            } else {

        }

        }
?>

