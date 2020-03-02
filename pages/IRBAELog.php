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
    <link rel="stylesheet" type="text/css" href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/ae_style.css")?>">
    <link rel="icon"
          type="image/png"
          href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=images/redcap_web_icon_32x.png")?>">
</head>

<div id="main-content" >
    <div class="title123">
        <h1>Adverse Event Reporting</h1>
        <p> <center>A Harvard Catalyst project</center></p>
    </div>

    <div id="toggle">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">
        <img src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/redcap_icon.png") ?>"
             alt="fieldMapping"
             width="40" height="40">
        </span>
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>

<div class="topnav">
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=index"."&pid=".$project_id)?>">Home</a>
    <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/setup.php" . "&pid=" . $project_id )?>"
       style=" background-color: #ffffff;">Setup</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/mapping.php"."&pid=".$project_id)?>">Mapping</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/worksheet.php"."&pid=".$project_id."&rid=1")?>">Worksheet</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/IRBAELog.php"."&pid=".$project_id)?>" style=" background-color: #ffffff; color: #C05D4F; border-bottom: 6px solid #C05D4F;">IRB AE Log</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/CTAELog.php"."&pid=".$project_id)?>" >Clinical-Trials AE Log</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/FDAAELog.php"."&pid=".$project_id)?>">FDA AE Log</a>
    <?php if ($warning){
        print "    <span class=\"badge\" style=\"float:right\" onclick=\"myFunction('badgeInfo')\">!</span>";
    }
    ?>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/helptext.php"."&pid=".$project_id)?>" style="float:right" rel="noopener noreferrer", target="_blank">Help</a>
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

        function myFunction(div_name) {
            var x = document.getElementById(div_name);
            x.className = "show";
        }
        function closeSettings(div_name) {
            var x = document.getElementById(div_name);
            x.className = x.className.replace("show", "");
        }
    </script>

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

<!--------------------------------- The IRB Code Starts here --------------------------------------------->
<br>
<div align="right">

    <div role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Download
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <span id="button_03" class="dropdown-item">as csv-file</span>
            <span id="button_04" class="dropdown-item">as txt-file</span>
        </div>
    </div>
</div>

<?php

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

?>

<?php if (count($ct_tx_a) > 0): ?>
    <center>
    <table class ="irb">
        <thead>
        <tr>
            <th class="rotate"><?php echo implode('</th><th class="rotate">', $IRB_field_headers); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ct_tx_a as $row): array_map('htmlentities', $row[$event_id_ct]); ?>
            <tr>

                <td><a href="<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/worksheet.php&pid=<?php echo htmlspecialchars($project_id)?>&rid=<?php echo htmlspecialchars($row[$event_id_ct]['record_id'])?>">
                    <?php echo implode('</a></td> <td contenteditable=\'false\'>', $row[$event_id_ct]); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <th><?php echo implode('</th><th>', $IRB_field_headers); ?></th>
        </tr>
        </tfoot>
    </table>
    </center>
<?php endif; ?>


    <div class="footer">
        Harvard Catalyst | Partners HealthCare
    </div>
</body>


</div>
<?php include_once APP_PATH_DOCROOT . "ProjectGeneral/header.php";?>

<script>
    $(document).ready(function() {
        $('#button_03').click( function() {
            document.location.href = '/..<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/output_stream_irb_csv.php&pid=<?php echo htmlspecialchars($project_id)?>';
        } );
    } );
</script>

<script>
    $(document).ready(function() {
        $('#button_04').click( function() {
            document.location.href = '/..<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/output_stream_irb_txt.php&pid=<?php echo htmlspecialchars($project_id)?>';
        } );
    } );
</script>
