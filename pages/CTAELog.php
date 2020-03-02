<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;

include_once dirname(__FILE__)."/classes/common.php";

global $Proj;

$project_id = $_GET['pid'];

if (!isset($project_id)) {
    die('Project ID is a required field');
}

$URI = explode("?",$_SERVER['REQUEST_URI'])[0];


$API_test = array('token');

$API_settings = REDCap::getData($project_id, 'array', 1, $API_test, 'map_arm_1', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

$cases_ct_api = array_keys($API_settings);
$event_id_ct_api = array_keys($API_settings[$cases_ct_api[0]])[0];

if(isset($API_settings[$cases_ct_api[0]][$event_id_ct_api]['token']) && sizeof($API_settings[$cases_ct_api[0]][$event_id_ct_api]['token'] >5)){

    $token = $API_settings[$cases_ct_api[0]][$event_id_ct_api]['token'];
    $dd_fields = access_dd_other_project($token);
    $warning = (sizeof($dd_fields) > 1 ? false : true); // returns true
}
?>

<head>
    <link rel="stylesheet" type="text/css" href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/ae_style.css")?>">
    <link rel="icon"
          type="image/png"
          href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=images/redcap_web_icon_32x.png")?>">
</head>

<div id="main-content" >
<div class="title123">
    <h1>Adverse Event Reporting</h1>
    <p> <center>A Harvard Catalyst project</center></p>
</div>

<div id="toggle">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">
        <img src="<?php print htmlspecialchars( $URI . "?prefix=ae_reporting&page=images/redcap_icon.png") ?>"
             alt="fieldMapping"
             width="40" height="40">
        </span>
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
</div>


<div class="topnav">
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=index"."&pid=".$project_id)?>">Home</a>
    <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/setup.php" . "&pid=" . $project_id )?>"
       style=" background-color: #ffffff;">Setup</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/mapping.php"."&pid=".$project_id)?>">Mapping</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/worksheet.php"."&pid=".$project_id."&rid=1")?>">Worksheet</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/IRBAELog.php"."&pid=".$project_id)?>">IRB AE Log</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/CTAELog.php"."&pid=".$project_id)?>" style=" background-color: #ffffff; color: #C05D4F; border-bottom: 6px solid #C05D4F;">Clinical-Trials AE Log</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/FDAAELog.php"."&pid=".$project_id)?>">FDA AE Log</a>
    <?php if ($warning){
        print "    <span class=\"badge\" style=\"float:right\" onclick=\"myFunction('badgeInfo')\">!</span>";
    }
    ?>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/helptext.php"."&pid=".$project_id)?>" style="float:right" rel="noopener noreferrer", target="_blank">Help</a>
</div>


<div class="result" id="ctResult"> </div>

    <div id="badgeInfo">
        <b>System Diagnosis</b>
        <span class="closebtn1" onclick="closeSettings('badgeInfo')">×</span> <br><br>
        The system has detected discrepancies between the expected project settings and REDCap resulting in a failed API call to your source project.
        Please troubleshoot by double checking the following:
        <ol>
            <li>Ensure a valid API token is saved in Mapping >> Settings</li>
            <li>The API token must have export rights</li>
            <li>Ensure that at least two fields are present in your source project</li>
            <li>Ensure REDCap's base URL has been defined in the Control Center</li>
            <li>This external module only works with secure connections (i.e. using "https")</li>
            <li>Please read the section on the documentation on API Token Requirements.</li>
        </ol>
        <b>* If the problem continues, please contact your REDCap administrator.</b>
    </div>

    <script>
        function myFunction(div_name) {
            var x = document.getElementById(div_name);
            x.className = "show";
        }
        function closeSettings(div_name) {
            var x = document.getElementById(div_name);
            x.className = x.className.replace("show", "");
        }
    </script>
<?php
### Reading Subject At Risk per arm field

$record_num = 1;

$at_risk = array('at_risk_per_arm','study_arms_num','study_arm_names');

$at_risk_set = REDCap::getData($project_id, 'array', $record_num, $at_risk, 'map_arm_1', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

$at_risk_counts = explode(";",$at_risk_set[1][REDCap::getEventIdFromUniqueEvent('map_arm_1')]['at_risk_per_arm']);
$study_arms_num = $at_risk_set[1][REDCap::getEventIdFromUniqueEvent('map_arm_1')]['study_arms_num'];
$study_arm_names = explode(";",$at_risk_set[1][REDCap::getEventIdFromUniqueEvent('map_arm_1')]['study_arm_names']);


### Transferring Data from Worksheet into CT AE Log

$ct_tx_fields = array('subject_id_source_ws','ae_type_source_ws', 'assessment_type_source_ws', 'additional_desc_source_ws', 'organ_system_source_ws',
    'source_vocab_source_ws', 'ae_term_source_ws', 'ae_arm_source_ws');

$ct_tx_a = REDCap::getData($project_id, 'array', NULL, $ct_tx_fields, 'worksheet_arm_2', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

$arm_array = uniqueVal_asso_REDCapArrays_index($ct_tx_a, 'ae_arm_source_ws');

$aeTerm_array = uniqueVal_asso_REDCapArrays_index($ct_tx_a, 'ae_term_source_ws');


$arm_table = arm_aggregate_table($ct_tx_a, $arm_array, $aeTerm_array);


################# - move selected column to CT AE form

$cases_ct = array_keys($ct_tx_a);
$event_id_ct = array_keys($ct_tx_a[$cases_ct[0]])[0];

// : finding the subjects affected (unique number of subject id)

foreach ($ct_tx_a as $record){
    $subject_ids[] = $record[$event_id_ct]['subject_id_source_ws'];
}

$subject_ids[] = 'AE-451239';

$subj_afx = array_unique($subject_ids);

// em@partners.org: split dataset into Serious (1) and Other (9)
foreach($ct_tx_a as $record){
    if ($record[$event_id_ct]['ae_type_source_ws'] == 1){
        $ct_tx_1[] = $record;
    } else if ($record[$event_id_ct]['ae_type_source_ws'] == 9){
        $ct_tx_9[] = $record;
    }
}

$aeTerm_array_serious = uniqueVal_asso_REDCapArrays_index($ct_tx_1, 'ae_term_source_ws');
$aeTerm_array_other = uniqueVal_asso_REDCapArrays_index($ct_tx_9, 'ae_term_source_ws');

$arm_table = arm_aggregate_table($ct_tx_1, $arm_array, $aeTerm_array_serious);

$next_index = 0;
foreach ($aeTerm_array_serious as $aeTerm) {

    $count = 0;

    foreach ($ct_tx_1 as $sub_array) {
        $offset = array_search('ae_term_source_ws', array_keys($sub_array[$event_id_ct]));
        $arm_current_value = array_slice($sub_array[$event_id_ct], $offset, 1);

        if ($arm_current_value['ae_term_source_ws'] == $aeTerm && $count == 0) {
            $count++;

            $next_index++;
            $ct_ae_record = 1000 + $next_index;
            $armX = $arm_current_value['arm'];


            // : create the array that contains the repeated study arms columns
            for ($i = 0; $i <= $study_arms_num - 1; $i++) {
                $study_arms[$i+1] = array(
                    'numevents' => $arm_table[$aeTerm][$i]["count"], // em@partners: how many times did it happen - calculated from ae-term
                    'numsubjectsaffected' => $arm_table[$aeTerm][$i]["subj_afx"], //em@partners: to how many patients it happened? - unique number of patients from data
                    'numsubjectsatrisk' => $at_risk_counts[$i] // em@partners: to how many patients it could've happened? - total number of patients in this arm
                );
            }

            $dataX = array(
                $ct_ae_record => array(
                    REDCap::getEventIdFromUniqueEvent('ct_ae_log_arm_4') => array(
                        'adverse_event_type' => $sub_array[$event_id_ct]['ae_type_source_ws'],
                        'assessment_type' => $sub_array[$event_id_ct]['assessment_type_source_ws'],
                        'additional_description' => $sub_array[$event_id_ct]['additional_desc_source_ws'],
                        'organ_system_name' => $sub_array[$event_id_ct]['organ_system_source_ws'],
                        'source_vocabulary' => $sub_array[$event_id_ct]['source_vocab_source_ws'],
                        'ae_term' => $sub_array[$event_id_ct]['ae_term_source_ws']
                    ),
                    'repeat_instances' => array(
                        REDCap::getEventIdFromUniqueEvent('ct_ae_log_arm_4') => array(
                            'clinicaltrails_ae_arm_total' =>
                                $study_arms
                        )
                    )
                ));

            $response = REDCap::saveData($project_id, 'array', $dataX);
        }
    }
}
//////////////////////////////////////////////////////////////////////////////////////// AE other loading
$arm_table = arm_aggregate_table($ct_tx_9, $arm_array, $aeTerm_array_other);

foreach ($aeTerm_array_other as $aeTerm) {

    $count = 0;

    foreach ($ct_tx_9 as $sub_array) {
        $offset = array_search('ae_term_source_ws', array_keys($sub_array[$event_id_ct]));
        $arm_current_value = array_slice($sub_array[$event_id_ct], $offset, 1);

        if ($arm_current_value['ae_term_source_ws'] == $aeTerm && $count == 0) {
            $count++;

            $next_index++;
            $ct_ae_record = 1000 + $next_index;
            $armX = $arm_current_value['arm'];


            // : create the array that contains the repeated study arms columns
            for ($i = 0; $i <= $study_arms_num - 1; $i++) {
                $study_arms[$i+1] = array(
                    'numevents' => $arm_table[$aeTerm][$i]["count"], // em@partners: how many times did it happen - calculated from ae-term
                    'numsubjectsaffected' => $arm_table[$aeTerm][$i]["subj_afx"], //em@partners: to how many patients it happened? - unique number of patients from data
                    'numsubjectsatrisk' => $at_risk_counts[$i] // em@partners: to how many patients it could've happened? - total number of patients in this arm
                );
            }

            $dataX = array(
                $ct_ae_record => array(
                    REDCap::getEventIdFromUniqueEvent('ct_ae_log_arm_4') => array(
                        'adverse_event_type' => $sub_array[$event_id_ct]['ae_type_source_ws'],
                        'assessment_type' => $sub_array[$event_id_ct]['assessment_type_source_ws'],
                        'additional_description' => $sub_array[$event_id_ct]['additional_desc_source_ws'],
                        'organ_system_name' => $sub_array[$event_id_ct]['organ_system_source_ws'],
                        'source_vocabulary' => $sub_array[$event_id_ct]['source_vocab_source_ws'],
                        'ae_term' => $sub_array[$event_id_ct]['ae_term_source_ws']
                    ),
                    'repeat_instances' => array(
                        REDCap::getEventIdFromUniqueEvent('ct_ae_log_arm_4') => array(
                            'clinicaltrails_ae_arm_total' =>
                                $study_arms
                        )
                    )
                ));

            $response = REDCap::saveData($project_id, 'array', $dataX);
        }
    }
}


############### Generating CT AE Log Table and Export
$ct_fields_a = array('adverse_event_type', 'assessment_type', 'additional_description', 'organ_system_name',
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

    $result_case_array = $ct_rawdat_a[$case][$event_id]; // First dataset

    $instances = array_keys($ct_rawdat_b[$case]["repeat_instances"][$event_id][$repeated_form_name]); // Number of repeated instances

    foreach ($instances as $instance) {
        $instance_data = $ct_rawdat_b[$case]["repeat_instances"][$event_id][$repeated_form_name][$instance];

        foreach ($ct_fields_b as $field_b) {
            $instance_data[$field_b . "_" . $instance] = $instance_data[$field_b];
            unset($instance_data[$field_b]);

        }
        $result_case_array = array_merge($result_case_array, $instance_data);

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



?>

    <br>
    <div align="right">

        <div role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Download Raw Table
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <span id="button_03_s" class="dropdown-item">Serious AE as csv-file</span>
                <span id="button_04_s" class="dropdown-item">Serious AE as txt-file</span>
                <span id="button_03_o" class="dropdown-item">Other AE as csv-file</span>
                <span id="button_04_o" class="dropdown-item">Other AE as txt-file</span>
            </div>
        </div>

</div>


<?php

$raw_header = Array("adverseEventType","assessmentType","additionalDescription",
    "organSystemName","sourceVocabulary","term");

$arm_raw_names = array_slice($result_fields,6);

$i = 0;
$j = 0;
foreach ($arm_raw_names as $arm_raw_name){

    $arm_name[] = $study_arm_names[$j].'{'.explode("_", $arm_raw_name)[0].'}';
    $i = $i + 1;
    $j = (fmod($i,3) == 0 ? $j + 1 : $j + 0);
}



$raw_header = array_merge( $raw_header, $arm_name);

if (count($result_array) > 0): ?>
    <table class ="irb" style ="margin-left: 80px;">
        <thead>
        <tr>
            <th class="rotate"><?php echo implode('</th><th class="rotate">', $raw_header); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($result_array as $row): array_map('htmlentities', $row); ?>
            <tr>
                <td contenteditable='false'><?php echo implode('</td><td contenteditable=\'false\'>', $row); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>

        </tr>
        </tfoot>
    </table>
<?php endif; ?>


    <div align="right">
    <button id="CTAggregate" type="button" class="btn-primary" aria-haspopup="true" aria-expanded="false" style ="font-size: initial; padding: 5px 5px 5px 5px; border-radius: 5px;">
        CT.gov Aggregate
    </button>

    <br><br><br>
    </div>

    <div class="footer">
        Harvard Catalyst | Partners HealthCare
    </div>

</div>

<script>
    function openNav() {
        document.getElementById("west").style.visibility = "visible";
        document.getElementById("west").style.width = "300px";
        document.getElementById("main-content").style.marginLeft = "300px";
    }

    function closeNav() {
        document.getElementById("west").style.visibility = "hidden";
        document.getElementById("west").style.width = "0";
        document.getElementById("main-content").style.marginLeft = "0";
    }
</script>

</body>

<?php include_once APP_PATH_DOCROOT . "ProjectGeneral/header.php";?>

<script>
    $(document).ready(function() {
        $(document).on('click','#CTAggregate',function(){
            document.location.href = '/..<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/CTAEagg.php&pid=<?php echo htmlspecialchars($project_id)?>&aet=o';
        } );
    } );
</script>


<script>
    $(document).ready(function() {
        $(document).on('click','#button_03_s',function(){
            document.location.href = '/..<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/output_stream_ct_csv.php&pid=<?php echo htmlspecialchars($project_id)?>&aet=s';
        } );
    } );
</script>

<script>
    $(document).ready(function() {
        $(document).on('click','#button_03_o',function(){
            document.location.href = '/..<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/output_stream_ct_csv.php&pid=<?php echo htmlspecialchars($project_id)?>&aet=o';
        } );
    } );
</script>

<script>
    $(document).ready(function() {
        $(document).on('click','#button_04_s',function(){
            document.location.href = '/..<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/output_stream_ct_txt.php&pid=<?php echo htmlspecialchars($project_id)?>&aet=s';
        } );
    } );
</script>

<script>
    $(document).ready(function() {
        $(document).on('click','#button_04_o',function(){
            document.location.href = '/..<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/output_stream_ct_txt.php&pid=<?php echo htmlspecialchars($project_id)?>&aet=o';
        } );
    } );
</script>