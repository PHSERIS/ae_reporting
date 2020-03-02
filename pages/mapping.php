<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;

include_once dirname(__FILE__)."/classes/common.php";

global $Proj;

if (!isset($project_id)) {
    die('Project ID is a required field');
}

$URI = explode("?",$_SERVER['REQUEST_URI'])[0];

// loading settings saved within the project
$record_num = 1;

$ct_tx_fields = array('token','unique_event_name_source','study_arms_num','at_risk_per_arm','study_arm_names','subject_id_source','ae_date_source','desc_event_source','location_source',
    'severity_source','expectedness_source','relatedness_source','corrective_source','date_reported_source','ae_type_source',
    'assessment_type_source','additional_desc_source','organ_system_source','source_vocab_source','ae_term_source',
    'ae_arm_source','record_details','subject_id_alt','ae_date_alt','desc_event_alt','location_alt','severity_alt',
    'expectedness_alt','relatedness_alt','corrective_alt','date_reported_alt','ae_type_alt','assessment_type_alt',
    'additional_desc_alt','organ_system_alt','source_vocab_alt','ae_term_alt','ae_arm_alt','rcrd_affected_source',
    'rcrd_affected_alt','irb_custom_1_source','irb_custom_1_alt','irb_custom_2_source','irb_custom_2_alt',
    'irb_custom_3_source','irb_custom_3_alt','irb_custom_4_source','irb_custom_4_alt','irb_custom_5_source','irb_custom_5_alt');

$ct_tx_a = REDCap::getData($project_id, 'array', $record_num, $ct_tx_fields, 'map_arm_1', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

$cases_ct = array_keys($ct_tx_a);
$event_id_ct = array_keys($ct_tx_a[$cases_ct[0]])[0];

$mapped_data = $ct_tx_a[$cases_ct[0]][$event_id_ct];

if(isset($ct_tx_a[$cases_ct[0]][$event_id_ct]['token']) && sizeof($ct_tx_a[$cases_ct[0]][$event_id_ct]['token'] >5)){

    $token = $ct_tx_a[$cases_ct[0]][$event_id_ct]['token'];
    $dd_fields = access_dd_other_project($token);
    $warning = (sizeof($dd_fields) > 1 ? false : true); // returns true
}
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

?>

<head>

    <link rel="stylesheet" type="text/css" href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/ae_style.css")?>">
    <link rel="icon"
          type="image/png"
          href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=images/redcap_web_icon_32x.png")?>">
</head>
<body>

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
    <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/setup.php" . "&pid=" . $project_id )?>" style=" background-color: #ffffff;">Setup</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/mapping.php"."&pid=".$project_id)?>" style=" background-color: #ffffff; color: #C05D4F; border-bottom: 6px solid #C05D4F;">Mapping &nbsp;</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/worksheet.php"."&pid=".$project_id."&rid=1")?>">Worksheet</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/IRBAELog.php"."&pid=".$project_id)?>">IRB AE Log</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/CTAELog.php"."&pid=".$project_id)?>">Clinical-Trials.gov AE Log</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/FDAAELog.php"."&pid=".$project_id)?>">FDA AE Log</a>
    <?php if ($warning){
        print "    <span class=\"badge\" style=\"float:right\" onclick=\"myFunction('badgeInfo')\">!</span>";
    }
    ?>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/helptext.php"."&pid=".$project_id)?>" style="float:right" rel="noopener noreferrer", target="_blank">Help</a>
</div>

<br>
<div class="wrapper">
    <input class ="save" type="submit" value="Settings" onclick="myFunction('snackbar')">

</div>
<br>
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

<div id="snackbar">
    Set Parameters
    <span class="closebtn1" onclick="closeSettings('snackbar')">×</span> <br><br>
    API Token: <span style="color: red; font-size: smaller">*required</span>..........
    <input type="text" id="token" value="<?php echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['token']);?>"> <br> <br>
    # of Study Arms: <span style="color: red; font-size: smaller">*required</span>
    <input type="text" id="total_arms" value="<?php echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['study_arms_num']);?>"> <br> <br>
    <input class ="save" type="submit" value="Save" id="update_settings">

</div>

    <div id="snackbar_OK">
        <span class="closebtn1" onclick="closeSettings('snackbar_OK')">×</span> <br><br>
        <b>Loading Data Complete</b><br><br>
        <input class ="save" type="submit" value="OK" id="Continue" onclick="goto_WS()">
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
    function goto_WS(){
        window.location.href = '/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/worksheet.php&pid=<?php echo htmlspecialchars($project_id)?>&rid=1';
    }
</script>

<center>
<table>
    <tr>
        <th class="hasTooltip" style = "border-radius: 15px 0px 0px 0px; text-align: center;"> Target Field <span> <?php echo tool_tip_text('Target');?></span> </th> <th> </th> <th class="hasTooltip" style = "text-align: center;"> Source Field <span> <?php echo tool_tip_text('Source');?></span</th> <th class="hasTooltip" style = "border-radius: 0px 15px 0px 0px; text-align: center;""> Alternate Field <span> <?php echo tool_tip_text('Alternate');?></span</th>
    </tr>
    <?php build_dropdown_choices_4_events(access_event_names_xproject($token),$ct_tx_a[$cases_ct[0]][$event_id_ct]['unique_event_name_source'])?>
    <tr>
        <td class="hasTooltip"> <label class ="pill" > Subject ID <span><?php echo tool_tip_text('subject_id');?></span></label> </td>
        <td> <span style="color: red">is mapped from -->* </span> </td>
        <td>  <select type="text" id="subj_id_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'subject_id_source')?>

            </select> </td>
        <td> <select type="text" id="subj_id_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'subject_id_alt')?>

            </select> </td>
    </tr>

    <?php build_optional_irb_fields($dd_fields, $mapped_data, $irb_field_labels); ?>

    <tr>
        <td class="hasTooltip"> <label class ="pill"> Adverse Event Type <span> <?php echo tool_tip_text('AEType');?></span></label> </td>
        <td> <span style="color: red"> is mapped from -->* </span>  </td>
        <td>  <select type="text" id="ae_type_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'ae_type_source')?>

            </select> </td>
        <td> <select type="text" id="ae_type_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'ae_type_alt')?>

            </select> </td>
    </tr>
    <tr>
        <td class="hasTooltip"> <label class ="pill"> Assessment Type <span> <?php echo tool_tip_text('AssessmentType');?></span></label> </td>
        <td> is mapped from --> </td>
        <td>  <select type="text" id="Assess_type_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'assessment_type_source')?>

            </select> </td>
        <td> <select type="text" id="Assess_type_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'assessment_type_alt')?>

            </select> </td>
    </tr>
    <tr>
        <td class="hasTooltip"> <label class ="pill"> Additional Description <span> <?php echo tool_tip_text('AddDesc');?></span></label> </td>
        <td> is mapped from --> </td>
        <td>  <select type="text" id="add_desc_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'additional_desc_source')?>

            </select> </td>
        <td> <select type="text" id="add_desc_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'additional_desc_alt')?>

            </select> </td>
    </tr>
    <tr>
        <td class="hasTooltip"> <label class ="pill"> Organ System Name <span> <?php echo tool_tip_text('OrganSysName');?></span></label> </td>
        <td> is mapped from --> </td>
        <td>  <select type="text" id="organ_sys_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'organ_system_source')?>

            </select> </td>
        <td> <select type="text" id="organ_sys_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'organ_system_alt')?>

            </select> </td>
    </tr>
    <tr>
        <td class="hasTooltip"> <label class ="pill"> Source Vocabulary <span> <?php echo tool_tip_text('SourceVocab');?></span></label> </td>
        <td> is mapped from --> </td>
        <td>  <select type="text" id="source_voc_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'source_vocab_source')?>

            </select> </td>
        <td> <select type="text" id="source_voc_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'source_vocab_alt')?>

            </select> </td>
    </tr>
    <tr>
        <td class="hasTooltip"> <label class ="pill"> AE Term <span> <?php echo tool_tip_text('AETerm');?></span></label> </td>
        <td> is mapped from --> </td>
        <td>  <select type="text" id="ae_term_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'ae_term_source')?>

            </select> </td>
        <td> <select type="text" id="ae_term_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'ae_term_alt')?>

            </select> </td>
    </tr>
    <tr>
        <td class="hasTooltip"> <label class ="pill"> Specify Study Arm <span> <?php echo tool_tip_text('StudyArm');?></span></label> </td>
        <td> is mapped from --> </td>
        <td>  <select type="text" id="study_arm_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'ae_arm_source')?>

            </select> </td>
        <td> <select type="text" id="study_arm_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'ae_arm_alt')?>

            </select> </td>
    </tr>
    <tr>
        <td class="hasTooltip">
            <label class ="pill"> Affected
                <span> <?php echo tool_tip_text('Affected');?></span>
            </label>
        </td>
        <td> <span style="color: red"> is mapped from -->* </span>  </td>
        <td>  <select type="text" id="affected_dm" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'rcrd_affected_source')?>
            </select>

        </td>
        <td> <select type="text" id="affected_am" class="form-control">
                <option value = "0">  </option>
                <?php build_dropdown_choices($dd_fields,$mapped_data,'rcrd_affected_alt')?>

            </select> </td>
    </tr>
    <?php build_at_risk_fields($ct_tx_a[$cases_ct[0]][$event_id_ct]['study_arms_num'],
        $ct_tx_a[$cases_ct[0]][$event_id_ct]['at_risk_per_arm'],
        $ct_tx_a[$cases_ct[0]][$event_id_ct]['study_arm_names'])?>

    <tfoot>
    <tr>
        <th style = "border-radius: 0px 0px 0px 15px;"></th>
        <th></th>
        <th></th>
        <th style = "border-radius: 0px 0px 15px 0px;"></th>
    </tr>
    </tfoot>
</table>
    <span style="color: red">*Required</span>
    </center>
<br>

<div class="wrapper">
    <input class ="save" type="submit" value="Save" id="update_mappings">
    <input class ="save" type="submit" value="Load" id="load_prep" onclick="myFunction('snackbar_load')">
</div>

<div id="snackbar_load">
    <br><b>Load Data From Mappings</b>
    <span class="closebtn1" onclick="closeSettings('snackbar_load')">×</span> <br><br>
    <input type="checkbox" id="all" name="all_maps" checked disabled> Import all Mappings <br>
    <span style="font-size: small">Load rate: 1.2-seconds/record</span>
    <br>
    <input class ="save" type="submit" value="Load Now" id="load_data_from_mappings">
    <br>
</div>

<div class="loading-gif"  id="loadingDiv">

</div>

    <div class="success-gif"  id="successDiv">

    </div>

    <div class="footer">
        Harvard Catalyst | Partners HealthCare
    </div>

</div>

<?php include_once APP_PATH_DOCROOT . "ProjectGeneral/header.php";?>

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


<script>
    $(document).ready(function(){
        $('#loadingDiv').hide();
        $('#successDiv').hide();
    });
</script>



<script>
    $(document).ready(function() {
        $('#load_data_from_mappings').click( function() {
            closeSettings('snackbar_load');
            $('#loadingDiv').show();
            $.ajax({type: "POST",
                data: "pid=<?php echo htmlspecialchars($project_id)?>"
                    +"&all=" + $('#all').val()
                    +"&selected=" + $('#selected').val()
                    +"&token=<?php echo($token)?>",
                url:'/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/load_records.php&pid=<?php echo htmlspecialchars($project_id)?>',
                success: function (result){
                    $('#loadingDiv').hide();
                    myFunction('snackbar_OK');
                }, error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#loadingDiv').hide();
                    alert("some error \n"  +
                        "XMLHttpRequest: "  + XMLHttpRequest +
                        "\ntextStatus: " + textStatus +
                        "\nerrorThrown: " + errorThrown);
                }
            });
            return false;
        } );
    } );
</script>

<script>
    $(document).ready(function() {
        $('#update_settings').click( function() {
            closeSettings('snackbar');
            $('#loadingDiv').show()
            $.ajax({type: "POST",
                data: "pid=<?php echo htmlspecialchars($project_id)?>"
                    +"&token=" + $('#token').val()
                    +"&study_arms=" + $('#total_arms').val(),
                url:'/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/update_mapping.php&pid=<?php echo htmlspecialchars($project_id)?>',
                success: function (result){
                    location.reload();
                }, error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#loadingDiv').hide();
                    alert("some error \n"  +
                        "XMLHttpRequest: "  + XMLHttpRequest +
                        "\ntextStatus: " + textStatus +
                        "\nerrorThrown: " + errorThrown);
                }
            });
            return false;
        } );
    } );
</script>

<script>
    $(document).ready(function() {
        $('#update_mappings').click( function() {
            var i;
            var at_risk_set = "";
            var arm_name_set = "";
            for (i = 1; i <= <?php echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['study_arms_num']);?>; i++) {
                arm_name_set = arm_name_set + $('#arm_name_'+ i ).val() + ';';
                at_risk_set = at_risk_set + $('#at_risk_'+ i ).val() + ';';
            }
            $('#loadingDiv').show();
            $.ajax({type: "POST",
                data: "pid=<?php echo htmlspecialchars($project_id)?>"
                    +"&token=" + $('#token').val()
                    +"&study_arms=" + $('#total_arms').val()
                    +"&at_risk=" + at_risk_set
                    +"&arm_names=" + arm_name_set
                    +"&event_name=" + $('#event_name').val()
                    +"&subject_id_source=" + $('#subj_id_dm').val()
                    +"&subject_id_alt=" + $('#subj_id_am').val()
                    +"&ae_date_source=" + $('#ae_date_dm').val()
                    +"&ae_date_alt=" + $('#ae_date_am').val()
                    +"&desc_event_source=" + $('#event_desc_dm').val()
                    +"&desc_event_alt=" + $('#event_desc_am').val()
                    +"&location_source=" + $('#location_dm').val()
                    +"&location_alt=" + $('#location_am').val()
                    +"&severity_source=" + $('#severity_dm').val()
                    +"&severity_alt=" + $('#severity_am').val()
                    +"&expectedness_source=" + $('#Expectedness_dm').val()
                    +"&expectedness_alt=" + $('#Expectedness_am').val()
                    +"&relatedness_source=" + $('#Relatedness_dm').val()
                    +"&relatedness_alt=" + $('#Relatedness_am').val()
                    +"&corrective_action_source=" + $('#req_chang_dm').val()
                    +"&corrective_action_alt=" + $('#req_chang_am').val()
                    +"&date_reported_source=" + $('#date_rep_dm').val()
                    +"&date_reported_alt=" + $('#date_rep_am').val()
                    +"&irb_1_source=" + $('#irb_custom_1_dm').val()
                    +"&irb_1_alt=" + $('#irb_custom_1_am').val()
                    +"&irb_2_source=" + $('#irb_custom_2_dm').val()
                    +"&irb_2_alt=" + $('#irb_custom_2_am').val()
                    +"&irb_3_source=" + $('#irb_custom_3_dm').val()
                    +"&irb_3_alt=" + $('#irb_custom_3_am').val()
                    +"&irb_4_source=" + $('#irb_custom_4_dm').val()
                    +"&irb_4_alt=" + $('#irb_custom_4_am').val()
                    +"&irb_5_source=" + $('#irb_custom_5_dm').val()
                    +"&irb_5_alt=" + $('#irb_custom_5_am').val()
                    +"&ae_type_source=" + $('#ae_type_dm').val()
                    +"&ae_type_alt=" + $('#ae_type_am').val()
                    +"&assessment_type_source=" + $('#Assess_type_dm').val()
                    +"&assessment_type_alt=" + $('#Assess_type_am').val()
                    +"&additional_desc_source=" + $('#add_desc_dm').val()
                    +"&additional_desc_alt=" + $('#add_desc_am').val()
                    +"&organ_system_name_source=" + $('#organ_sys_dm').val()
                    +"&organ_system_name_alt=" + $('#organ_sys_am').val()
                    +"&source_vocabulary_source=" + $('#source_voc_dm').val()
                    +"&source_vocabulary_alt=" + $('#source_voc_am').val()
                    +"&ae_term_source=" + $('#ae_term_dm').val()
                    +"&ae_term_alt=" + $('#ae_term_am').val()
                    +"&ae_arm_source=" + $('#study_arm_dm').val()
                    +"&ae_arm_alt=" + $('#study_arm_am').val()
                    +"&rcrd_affected_source=" + $('#affected_dm').val()
                    +"&rcrd_affected_alt=" + $('#affected_am').val(),
                url:'/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/update_mapping.php&pid=<?php echo htmlspecialchars($project_id)?>',
                success: function (result){
                    $('#loadingDiv').hide();
                    $('#successDiv').fadeIn('slow');
                    $('#successDiv').delay(250).fadeOut('slow');
                     location.reload();
                }, error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#loadingDiv').hide();
                    alert("some error \n"  +
                        "XMLHttpRequest: "  + XMLHttpRequest +
                        "\ntextStatus: " + textStatus +
                        "\nerrorThrown: " + errorThrown);
                }
            });
            return false;
        } );
    } );
</script>


<?php
if($warning){
    print "<script>
            myFunction('badgeInfo');
            </script>";
}
?>

</body>


