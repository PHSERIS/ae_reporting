<?php
/*
 * @author: Eduardo Morales
 *
 * List of functions, brief description, and location where they're being used.
 *
 * Function: uniqueVal_asso_REDCapArrays_index($REDCapArray, $index_name)
 * Description: Find the unique sets of values in an array given an index within the array
 * Location: CT AE Log.php
 *
 * Function: arm_aggregate_table($ct_tx_a, $arm_array, $aeTerm_array)
 * Description: Generates the aggregate count of adverse event term per study arm
 * Location: CT AE Log.php
 *
 * Function: build_at_risk_fields($study_arms_num,$at_risk_set)
 * Description: Generate Subject At Risk text input fields according to the total amount of Study Arms specified by the user.
 *          Plus, save and populate these fields so that it can be displayed in the UI.
 * Location: Mapping.php
 *
 * Function: build_dropdown_choices_4_events($event_names,$selected_field)
 * Description: Generate the html-code for displaying an array of fields as answer choices in a drop down.
 *              The code generates the "option" section of the drop down's code only for the Unique Event Name field.
 * Location: Mapping.php
 *
 * Function: access_event_names_xproject($token)
 * Description: Get the unique event names of a project using its API token
 * Location: Mapping.php

 * Function: access_dd_other_project($token)
 * Description: Get the data dictionary of a project using its API token
 * Location: Mapping.php, load_records.php
 *
 * Function: build_dropdown_choices($dd_fields)
 * Description: Generate the html-code for displaying an array of fields as answer choices in a drop down.
 *              The code generates the "option" section of the drop down's code only.
 * Location: Mapping.php
 *
 * Function: getFieldCodeLabelPair($project_id, $field)
 * Description: Obtain the field's answer choice label and code.
 * Location: CT AE Log.php
 *
 * Function: getFieldLabel($field_op, $fieldCode)
 * Description: Find the answer choice label based on a code
 * Location: CT AE Log.php
 *
 * Function: tool_tip_text($field)
 * Description: Function that sets and retrieves fields' tool tip
 * Location: Mapping.php, Worksheet.php,
**/

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;


function arm_aggregate_table($ct_tx_a, $arm_array, $aeTerm_array)
{
    $cases_ct = array_keys($ct_tx_a);
    $event_id_ct = array_keys($ct_tx_a[$cases_ct[0]])[0];

    $arm_total = [];

    foreach ($arm_array as $arm) {

        foreach ($aeTerm_array as $term) {

            $term_c = 0;
            foreach ($ct_tx_a as $sub_array) {
                if ($sub_array[$event_id_ct]['ae_term_source_ws'] == $term and
                    $sub_array[$event_id_ct]['ae_arm_source_ws'] == $arm) {
                    // echo "true <br>";
                    $term_c++;
                    $subject_ids[] = $sub_array[$event_id_ct]['subject_id_source_ws'];
                }
            }
            $subj_afx = array_unique($subject_ids);

            $subj_afx_c = sizeof($subj_afx);
            $arm_total[$term][] = array('arm' => $arm, 'term' => $term, 'count' => $term_c, 'subj_afx' => $subj_afx_c);
            unset($subject_ids);

        }
    }
    return $arm_total;
}

function uniqueVal_asso_REDCapArrays_index($REDCapArray, $index_name)
{

    $cases_ct = array_keys($REDCapArray);
    $event_id_ct = array_keys($REDCapArray[$cases_ct[0]])[0];
    $arm_array_all = array();  //setup the array you want with the sliced values.

//loop though each sub array and slice off the first 5 to a new multidimensional array
    foreach ($REDCapArray as $sub_array) {
        $offset = array_search($index_name, array_keys($sub_array[$event_id_ct]));
        $arm_current_value = array_slice($sub_array[$event_id_ct], $offset, 1);
        $arm_array_all[] = $arm_current_value[$index_name];
    }

    $arm_array = array_unique($arm_array_all);
    return $arm_array;

}

function build_at_risk_fields($study_arms_num, $at_risk_set, $arm_name_set)
{
    $at_risk_counts = explode(";", $at_risk_set);
    $arm_names = explode(";", $arm_name_set);
    for ($i = 1; $i <= intval($study_arms_num); $i++) {
        //echo $i;
        print("<tr>
        <td class=\"hasTooltip\"> <label class =\"pill\"> Name of Study Arm {$i} <br>and <br># of Subjects at Risk <span>" . tool_tip_text('SubjAtRisk') . "</span></label> </td>
        <td> Enter name <br>&<br> value --> </td>                
        <td>  <input type=\"text\" id=\"arm_name_{$i}\"
                     value=\"{$arm_names[$i-1]}\" class=\"form-control\">
        </td>
        <td>  <input type=\"text\" id=\"at_risk_{$i}\"
                     value=\"{$at_risk_counts[$i-1]}\" class=\"form-control\">
        </td>
        </tr>");
    }
}


function build_dropdown_choices_4_events($event_names, $selected_field)
{

    $loc = array_search('Y', $event_names);


    if (strlen($loc) > 0) {

    } else {

        print("<tr>
        <td class=\"hasTooltip\"> <label class =\"pill\"> Unique Event Name <span> " . tool_tip_text('UniqueEventName') . "</span></label> </td>
        <td> <span style=\"color: red\">is mapped from -->*</span> </td>
        <td>  <select type=\"text\" id=\"event_name\" class=\"form-control\">
                <option value = \"0\">  </option>");

        foreach ($event_names as $event_name) {
            if ($event_name == $selected_field) {
                print("<option value = \"" . $event_name . "\" selected=\"selected\">" . $event_name . " </option>");
            } else {
                print("<option value = \"" . $event_name . "\">" . $event_name . "</option>");
            }

        }

        print("</select> </td>
                </tr>");
    }


}

function access_event_names_xproject($token)
{
    global $redcap_base_url;
    $data = array(
        'token' => $token,
        'content' => 'event',
        'format' => 'json',
        'arms' => array(),
        'returnFormat' => 'json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $redcap_base_url.'api/');
//    curl_setopt($ch, CURLOPT_URL, 'https://' . $_SERVER["SSL_TLS_SNI"] . '/redcap/api/');
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
    $events = json_decode($output, true);
    curl_close($ch);

    $event_names = [];
    foreach ($events as $event) {
        $event_names[] = $event['unique_event_name'];
    }

    return $event_names;

}

function access_dd_other_project($token)
{
    global $redcap_base_url;
    $data = array(
        'token' => $token,
        'content' => 'metadata',
        'format' => 'json',
        'returnFormat' => 'json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $redcap_base_url.'api/');
//    curl_setopt($ch, CURLOPT_URL, 'https://' . $_SERVER["SSL_TLS_SNI"] . '/redcap/api/');
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

    $dd_array = json_decode($output, true);
//    var_dump($dd_array);
    curl_close($ch);

    $dd_fields = [];
    $count = -1;
    foreach ($dd_array as $field) {
        $count++;

        $dd_fields[] = array('field_label' => $field["field_label"], 'field_name' => $field["field_name"], 'choices' => $field["select_choices_or_calculations"]);
    }
    return ($dd_fields);
}

function build_dropdown_choices($dd_fields, $mapped_data, $selected_field)
{
    foreach ($dd_fields as $field) {
        if ($field["field_name"] == $mapped_data[$selected_field]) {
            print("<option value = \"" . $field["field_name"] . "\" selected=\"selected\">" . $field["field_label"] . " </option>");
        } else {
            print("<option value = \"" . $field["field_name"] . "\">" . $field["field_label"] . "</option>");
        }

    }
}

function build_optional_irb_fields($dd_fields, $mapped_data,$irb_field_labels)
{
    foreach($irb_field_labels as $k => $v) {
        if ($v["enable"] != false) {
            print "   <tr>
        <td class=\"hasTooltip\"> <label class =\"pill\"> {$v['label']} <span>" . tool_tip_text($k) . "</span></label> </td>
        <td> is mapped from --> </td>
        <td>  <select type=\"text\" id=\"{$v['element_id']}_dm\" class=\"form-control\">
                <option value = \"0\">  </option> ";
            build_dropdown_choices($dd_fields, $mapped_data, $v['source']);

            print "    </select> </td>
        <td> <select type=\"text\" id=\"{$v['element_id']}_am\" class=\"form-control\">
                <option value = \"0\">  </option>";
            build_dropdown_choices($dd_fields, $mapped_data, $v['alt']);

            print"     </select> </td>
        </tr>";
        }
    }
}

function build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,$trigger)
{
    switch ($trigger) {
        case 'date-adverse-event':
            if ($irb_field_labels['date-adverse-event']['enable'] != false) {
                print "            <div class=\"form-group row\">
                <label for=\"ae_date_raw\" class=\"col-sm-3 col-form-label hasTooltip\"> Date of Adverse Event: <span>";
                echo tool_tip_text('date-adverse-event');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <input type=\"date\" id=\"ae_date_raw\"
                           value=\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_date_source_ws']);
                print "\" class=\"form-control\">
                </div>
            </div>";
            }
            break;
        case 'description-event':
            if ($irb_field_labels['description-event']['enable'] != false) {
                print "            <div class=\"form-group row\">
                <label for=\"event_desc_raw\" class=\"col-sm-3 col-form-label hasTooltip\"> Description of events: <span>";
                echo tool_tip_text('description-event');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <textarea type=\"text\" id=\"event_desc_raw\" rows=\"3\"
                              class=\"form-control\">";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['desc_event_source_ws']);
                print "</textarea>
                </div>
                </div>";
            }
            break;
        case 'location':
            if ($irb_field_labels['location']['enable'] != false) {
                print"<div class=\"form-group row\">
                <label for=\"location_raw\" class=\"col-sm-3 col-form-label hasTooltip\"> Location: <span>";
                echo tool_tip_text('location');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <select type=\"text\" id=\"location_raw\"
                           value=\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['location_source_ws']);
                print "\" class=\"form-control\">
                        <option selected=\"selected\" value =\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['location_source_ws']);
                print "\">";
                $field_op = getFieldCodeLabelPair($project_id, 'location_source_ws');
                $field_label = getFieldLabel($field_op, $ct_tx_a[$cases_ct[0]][$event_id_ct]['location_source_ws']);
                echo $field_label;
                print "
                        </option>
                        <option value = \"1\"> Internal (Partners)</option>
                        <option value = \"2\"> External (Coordinating Sites)</option>
                    </select>
                </div>
            </div>";
            }
            break;
        case 'severity':
            if ($irb_field_labels['severity']['enable'] != false) {
                print "<div class=\"form-group row\">
                <label for=\"severity_raw\" class=\"col-sm-3 col-form-label hasTooltip\"> Severity: <span>";
                echo tool_tip_text('severity');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <select type=\"text\" id=\"severity_raw\"
                            value=\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['severity_source_ws']);
                print "\" class=\"form-control\">
                        <option selected=\"selected\" value =\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['severity_source_ws']);
                print "\">";
                $field_op = getFieldCodeLabelPair($project_id, 'severity_source_ws');
                $field_label = getFieldLabel($field_op, $ct_tx_a[$cases_ct[0]][$event_id_ct]['severity_source_ws']);
                echo $field_label;
                print "</option>
                        <option value = \"1\">Serious</option>
                        <option value = \"2\">Non-Serious</option>
                    </select>
                </div>
            </div>";
            }
            break;
        case 'expectedness':
            if ($irb_field_labels['expectedness']['enable'] != false) {
                print "            <div class=\"form-group row\">
                <label for=\"expectedness_raw\" class=\"col-sm-3 col-form-label hasTooltip\"> Expectedness: <span>";
                echo tool_tip_text('expectedness');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <select type=\"text\" id=\"expectedness_raw\"
                            value=\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['expectedness_source_ws']);
                print "\" class=\"form-control\">
                        <option selected=\"selected\" value =\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['expectedness_source_ws']);
                print "\">";
                $field_op = getFieldCodeLabelPair($project_id, 'expectedness_source_ws');
                $field_label = getFieldLabel($field_op, $ct_tx_a[$cases_ct[0]][$event_id_ct]['expectedness_source_ws']);
                echo $field_label;
                print "
                        </option>
                        <option value = \"1\">Expected</option>
                        <option value = \"2\">Unexpected</option>
                    </select>
                </div>
            </div>";
            }
            break;
        case 'relatedness':
            if ($irb_field_labels['relatedness']['enable'] != false) {
                print "<div class=\"form-group row\">
                <label for=\"relatedness_raw\" class=\"col-sm-3 col-form-label hasTooltip\"> Relatedness: <span>";
                echo tool_tip_text('relatedness');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <select type=\"text\" id=\"relatedness_raw\"
                            value=\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['relatedness_source_ws']);
                print "\" class=\"form-control\">
                        <option selected=\"selected\" value =\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['relatedness_source_ws']);
                print "\">";
                $field_op = getFieldCodeLabelPair($project_id, 'relatedness_source_ws');
                $field_label = getFieldLabel($field_op, $ct_tx_a[$cases_ct[0]][$event_id_ct]['relatedness_source_ws']);
                echo $field_label;
                print "
                        </option>
                        <option value = \"1\">Relates</option>
                        <option value = \"2\">Possible Related</option>
                        <option value = \"3\">Unrelated</option>
                    </select>
                </div>
            </div>";
            }
            break;
        case 'corrective-actions':
            if ($irb_field_labels['corrective-actions']['enable'] != false) {
                print "<div class=\"form-group row\">
                <label for=\"corrective_action_raw\" class=\"col-sm-3 col-form-label hasTooltip\"> Requires Changes / Corrective Action: <span>";
                echo tool_tip_text('corrective-actions');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <textarea type=\"text\" id=\"corrective_action_raw\" rows=\"3\"
                              class=\"form-control\">";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['corrective_source_ws']);
                print"</textarea>
                </div>
                </div>";
            }
            break;
        case 'date-reported':
            if ($irb_field_labels['date-reported']['enable'] != false) {
                print "<div class=\"form-group row\">
                <label for=\"date_reported_raw\" class=\"col-sm-3 col-form-label hasTooltip\"> Date Reported to PHRC, if available: <span>";
                echo tool_tip_text('date-reported');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <input type=\"date\" id=\"date_reported_raw\"
                           value=\"";
                echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['date_reported_source_ws']);
                print "\" class=\"form-control\">
                    </div>
                </div>";
            }
            break;
        case 'irb_custom_1':
            if ($irb_field_labels['irb_custom_1']['enable'] != false) {
                print " <div class=\"form-group row\">
                <label for=\"ae_arm_raw\" class=\"col-sm-3 col-form-label hasTooltip\">";
                echo htmlspecialchars($irb_field_labels['irb_custom_1']['label']);
                print ": <span> ";
                echo tool_tip_text('irb_custom_1');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <input type=\"text\" id=\"irb_c1_raw\"
                           value=\"";
                echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_1_source_ws']);
                print "\" class=\"form-control\">
                </div>
            </div>";
            }
            break;
        case 'irb_custom_2':
            if ($irb_field_labels['irb_custom_2']['enable'] != false) {
                print " <div class=\"form-group row\">
                <label for=\"ae_arm_raw\" class=\"col-sm-3 col-form-label hasTooltip\">";
                echo htmlspecialchars($irb_field_labels['irb_custom_2']['label']);
                print ": <span> ";
                echo tool_tip_text('irb_custom_2');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <input type=\"text\" id=\"irb_c2_raw\"
                           value=\"";
                echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_2_source_ws']);
                print "\" class=\"form-control\">
                </div>
            </div>";
            }
            break;
        case 'irb_custom_3':
            if ($irb_field_labels['irb_custom_3']['enable'] != false) {
                print " <div class=\"form-group row\">
                <label for=\"ae_arm_raw\" class=\"col-sm-3 col-form-label hasTooltip\">";
                echo htmlspecialchars($irb_field_labels['irb_custom_3']['label']);
                print ": <span> ";
                echo tool_tip_text('irb_custom_4');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <input type=\"text\" id=\"irb_c3_raw\"
                           value=\"";
                echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_3_source_ws']);
                print "\" class=\"form-control\">
                </div>
            </div>";
            }
            break;
        case 'irb_custom_4':
            if ($irb_field_labels['irb_custom_4']['enable'] != false) {
                print " <div class=\"form-group row\">
                <label for=\"ae_arm_raw\" class=\"col-sm-3 col-form-label hasTooltip\">";
                echo htmlspecialchars($irb_field_labels['irb_custom_4']['label']);
                print ": <span> ";
                echo tool_tip_text('irb_custom_4');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <input type=\"text\" id=\"irb_c4_raw\"
                           value=\"";
                echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_4_source_ws']);
                print "\" class=\"form-control\">
                </div>
            </div>";
            }
            break;
        case 'irb_custom_5':
            if ($irb_field_labels['irb_custom_5']['enable'] != false) {
                print " <div class=\"form-group row\">
                <label for=\"ae_arm_raw\" class=\"col-sm-3 col-form-label hasTooltip\">";
                echo htmlspecialchars($irb_field_labels['irb_custom_5']['label']);
                print ": <span> ";
                echo tool_tip_text('irb_custom_5');
                print "</span></label>
                <div class=\"col-sm-9\">
                    <input type=\"text\" id=\"irb_c5_raw\"
                           value=\"";
                echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_5_source_ws']);
                print "\" class=\"form-control\">
                </div>
            </div>";
            }
            break;


    }
}

function build_optional_irb_fields_ws_u($irb_field_labels,$trigger)
{
    switch ($trigger) {
        case 'date-adverse-event':
            if ($irb_field_labels['date-adverse-event']['enable'] != false) {
                print "+\"&ae_date_raw=\" + $('#ae_date_raw').val() \n";
            }
            break;
        case 'description-event':
            if ($irb_field_labels['description-event']['enable'] != false) {
                print "+\"&event_desc_raw=\" + $('#event_desc_raw').val()\n";
            }
            break;
        case 'location':
            if ($irb_field_labels['location']['enable'] != false) {
                print "+\"&location_raw=\" + $('#location_raw').val()\n";
            }
            break;
        case 'severity':
            if ($irb_field_labels['severity']['enable'] != false) {
                print "+\"&severity_raw=\" + $('#severity_raw').val()\n";
            }
            break;
        case 'expectedness':
            if ($irb_field_labels['expectedness']['enable'] != false) {
                print "+\"&expectedness_raw=\" + $('#expectedness_raw').val()\n";
            }
            break;
        case 'relatedness':
            if ($irb_field_labels['relatedness']['enable'] != false) {
                print "+\"&relatedness_raw=\" + $('#relatedness_raw').val()\n";
            }
            break;
        case 'corrective-actions':
            if ($irb_field_labels['corrective-actions']['enable'] != false) {
                print "+\"&corrective_action_raw=\" + $('#corrective_action_raw').val()\n";
            }
            break;
        case 'date-reported':
            if ($irb_field_labels['date-reported']['enable'] != false) {
                print "+\"&date_reported_raw=\" + $('#date_reported_raw').val()\n";
            }
            break;
        case 'irb_custom_1':
            if ($irb_field_labels['irb_custom_1']['enable'] != false) {
                print "+\"&irb_c1_raw=\" + $('#irb_c1_raw').val()\n";
            }
            break;
        case 'irb_custom_2':
            if ($irb_field_labels['irb_custom_2']['enable'] != false) {
                print "+\"&irb_c2_raw=\" + $('#irb_c2_raw').val()\n";
            }
            break;
        case 'irb_custom_3':
            if ($irb_field_labels['irb_custom_3']['enable'] != false) {
                print "+\"&irb_c3_raw=\" + $('#irb_c3_raw').val()\n";
            }
            break;
        case 'irb_custom_4':
            if ($irb_field_labels['irb_custom_4']['enable'] != false) {
                print "+\"&irb_c4_raw=\" + $('#irb_c4_raw').val()\n";
            }
            break;
        case 'irb_custom_5':
            if ($irb_field_labels['irb_custom_5']['enable'] != false) {
                print "+\"&irb_c5_raw=\" + $('#irb_c5_raw').val()\n";
            }
            break;


    }
}

function build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$trigger)
{
    switch ($trigger) {
        case 'date-adverse-event':
            if ($irb_field_labels['date-adverse-event']['enable'] != false) {
                print "<tr> <td> <b> Date of Adverse Event: </b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_date_alt_ws']));
                print "</td> </tr>";
            }
            break;
        case 'description-event':
            if ($irb_field_labels['description-event']['enable'] != false) {
                print "<tr> <td> <b> Description of events: </b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['desc_event_alt_ws']));
                print "</td> </tr>";
            }
            break;
        case 'location':
            if ($irb_field_labels['location']['enable'] != false) {
                print "<tr> <td> <b> Location: </b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['location_alt_ws']));
                print "</td> </tr>";
            }
            break;
        case 'severity':
            if ($irb_field_labels['severity']['enable'] != false) {
                print "<tr> <td> <b> Severity: </b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['severity_alt_ws']));
                print "</td> </tr>";
            }
            break;
        case 'expectedness':
            if ($irb_field_labels['expectedness']['enable'] != false) {
                print "<tr> <td> <b> Expectedness: </b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['expectedness_alt_ws']));
                print "</td> </tr>";
            }
            break;
        case 'relatedness':
            if ($irb_field_labels['relatedness']['enable'] != false) {
                print "<tr> <td> <b> Relatedness: </b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['relatedness_alt_ws']));
                print "</td> </tr>";
            }
            break;
        case 'corrective-actions':
            if ($irb_field_labels['corrective-actions']['enable'] != false) {
                print "<tr> <td> <b> Requires Changes / Corrective Action: </b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['corrective_alt_ws']));
                print "</td> </tr>";
            }
            break;
        case 'date-reported':
            if ($irb_field_labels['date-reported']['enable'] != false) {
                print "<tr> <td> <b> Date Reported to PHRC, if available: </b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['date_reported_alt_ws']));
                print "</td> </tr>";
            }
            break;
        case 'irb_custom_1':
            if ($irb_field_labels['irb_custom_1']['enable'] != false) {
                print "<tr> <td> <b>";
                echo htmlspecialchars($irb_field_labels['irb_custom_1']['label']);
                print "</b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_1_alt_ws']));
            }
            break;
        case 'irb_custom_2':
            if ($irb_field_labels['irb_custom_2']['enable'] != false) {
                print "<tr> <td> <b>";
                echo htmlspecialchars($irb_field_labels['irb_custom_2']['label']);
                print "</b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_2_alt_ws']));
            }
            break;
        case 'irb_custom_3':
            if ($irb_field_labels['irb_custom_3']['enable'] != false) {
                print "<tr> <td> <b>";
                echo htmlspecialchars($irb_field_labels['irb_custom_3']['label']);
                print "</b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_3_alt_ws']));
            }
            break;
        case 'irb_custom_4':
            if ($irb_field_labels['irb_custom_4']['enable'] != false) {
                print "<tr> <td> <b>";
                echo htmlspecialchars($irb_field_labels['irb_custom_4']['label']);
                print "</b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_4_alt_ws']));
            }
            break;
        case 'irb_custom_5':
            if ($irb_field_labels['irb_custom_5']['enable'] != false) {
                print "<tr> <td> <b>";
                echo htmlspecialchars($irb_field_labels['irb_custom_5']['label']);
                print "</b> </td> <td>";
                echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['irb_custom_5_alt_ws']));
            }
            break;
    }
}

function getFieldCodeLabelPair($project_id, $field)
{
    $dd_array = REDCap::getDataDictionary($project_id, 'array', false, $field);

    $options = explode("|", $dd_array[$field]['select_choices_or_calculations']);

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

    return $codeLabelPairs;
}

function getFieldLabel($field_op, $fieldCode)
{
    $key = array_search($fieldCode, $field_op['codes']);
    return $field_op['labels'][$key];
}

function tool_tip_text($field)
{
    /* Fields are used in several locations in the project. It is better to identify their tool-tip by
    the field name they belong to.*/
    static $text = array(
        'UniqueEventName' => "Event Name with Adverse Event data in the source project",
        'subject_id' => "Unique identifier given to subjects in the study",
        'date-adverse-event' => "Date of when the adverse event took place",
        'description-event' => "Detailed description of adverse event",
        'location' => "Internal or External",
        'severity' => "Serious or Non-serious",
        'expectedness' => "Expected or Unexpected",
        'relatedness' => "Related, Possibly Related, or Unrelated",
        'corrective-actions' => "Description of response measures to adverse event if any",
        'date-reported' => "Date Reported",
        'AEType' => "Serious or Other",
        'AssessmentType' => "Systematic or Non-systematic Assessment",
        'AddDesc' => "Detail description of events essential for a clear understanding of adverse event",
        'OrganSysName' => "Closest group for adverse event terms by body or organ system",
        'SourceVocab' => "Field specifying terminology, vocabulary, or classification (and version) used to identify adverse event term (for example, SNOMED CT, MedDRA 10.0).",
        'AETerm' => "Descriptive word or phrase for the adverse event",
        'StudyArm' => "Single field identifying adverse event arms",
        'Affected' => "Single field identifying records that experienced an adverse event",
        'SubjAtRisk' => "Total number of records in the specified study arm.",
        'Target' => "Field used for storing the respective data type in the target project.",
        'Source' => "Field containing direct data mapping from the source project to the target field.",
        'Alternate' => "Field containing closest data type for the expected target field.",
        'irb_custom_1' => "Custom Field - No Description",
        'irb_custom_2' => "Custom Field - No Description",
        'irb_custom_3' => "Custom Field - No Description",
        'irb_custom_4' => "Custom Field - No Description",
        'irb_custom_5' => "Custom Field - No Description",
        'data_review' => "Review the data loaded from the specified mappings. Use the Record Details on the right to compare to their respective alternate mappings.",
        'record_details' => "Record's data from fields mapped as alternate fields.",
        'add_new' => "Add new record"
    );

    return $text[$field];
}

?>