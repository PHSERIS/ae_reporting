<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;


include_once dirname(__FILE__)."/classes/common.php";

global $Proj;

if (!isset($project_id)) {
    die('Project ID is a required field');
}



$URI = explode("?",$_SERVER['REQUEST_URI'])[0];

$record_num = $_GET['rid'];

$ct_tx_fields = array('subject_id_source_ws','ae_date_source_ws','desc_event_source_ws','location_source_ws','severity_source_ws',
    'expectedness_source_ws','relatedness_source_ws','corrective_source_ws','date_reported_source_ws','ae_type_source_ws',
    'assessment_type_source_ws','additional_desc_source_ws','organ_system_source_ws','source_vocab_source_ws','ae_term_source_ws',
    'ae_arm_source_ws','admin_notes_ws',
    'irb_custom_1_source_ws','irb_custom_2_source_ws','irb_custom_3_source_ws','irb_custom_4_source_ws','irb_custom_5_source_ws',
    'subject_id_alt_ws','ae_date_alt_ws','desc_event_alt_ws','location_alt_ws','severity_alt_ws',
    'expectedness_alt_ws','relatedness_alt_ws','corrective_alt_ws','date_reported_alt_ws','ae_type_alt_ws','assessment_type_alt_ws',
    'additional_desc_alt_ws','organ_system_alt_ws','source_vocab_alt_ws','ae_term_alt_ws','ae_arm_alt_ws',
    'record_details_ws','admin_notes_ws',
    'irb_custom_1_alt_ws','irb_custom_2_alt_ws', 'irb_custom_3_alt_ws','irb_custom_4_alt_ws','irb_custom_5_alt_ws');

$ct_tx_count = REDCap::getData($project_id, 'array', NULL, $ct_tx_fields, 'worksheet_arm_2', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

$ct_tx_a = REDCap::getData($project_id, 'array', $record_num, $ct_tx_fields, 'worksheet_arm_2', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

$cases_ct = array_keys($ct_tx_a);
$event_id_ct = array_keys($ct_tx_a[$cases_ct[0]])[0];

$API_test = array('token');

$API_settings = REDCap::getData($project_id, 'array', 1, $API_test, 'map_arm_1', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

$cases_ct_api = array_keys($API_settings);
$event_id_ct_api = array_keys($API_settings[$cases_ct_api[0]])[0];

if(isset($API_settings[$cases_ct_api[0]][$event_id_ct_api]['token']) && sizeof($API_settings[$cases_ct_api[0]][$event_id_ct_api]['token'] >5)){

    $token = $API_settings[$cases_ct_api[0]][$event_id_ct_api]['token'];
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
<link rel="stylesheet" type="text/css" href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/ae_style.css")?>">
<link rel="icon"
      type="image/png"
      href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=images/redcap_web_icon_32x.png")?>">
</head>


</head>
<body>

<div id="main-content" >

    <div class="title123">
        <h1>Adverse Event Reporting</h1>
        <p> <center>A Harvard Catalyst project</center></p>
    </div>

    <div id="toggle">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">
        <img src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/redcap_icon.png" )?>"
             alt="fieldMapping"
             width="40" height="40">
        </span>
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>


<div class="topnav">
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=index"."&pid=".$project_id)?>">Home</a>
    <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/setup.php" . "&pid=" . $project_id )?>" style=" background-color: #ffffff;">Setup</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/mapping.php"."&pid=".$project_id)?>">Mapping</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/worksheet.php"."&pid=".$project_id."&rid=1")?>" style=" background-color: #ffffff; color: #C05D4F; border-bottom: 6px solid #C05D4F;">Worksheet</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/IRBAELog.php"."&pid=".$project_id)?>">IRB AE Log</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/CTAELog.php"."&pid=".$project_id)?>">Clinical-Trials.gov AE Log</a>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/FDAAELog.php"."&pid=".$project_id)?>">FDA AE Log</a>
    <?php if ($warning){
        print "    <span class=\"badge\" style=\"float:right\" onclick=\"myFunction('badgeInfo')\">!</span>";
    }
    ?>
    <a href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/helptext.php"."&pid=".$project_id)?>" style="float:right" rel="noopener noreferrer", target="_blank">Help</a>
</div>

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

<div class="row">
    <div class="leftcolumn ws">

        <h2 ><label class="hasTooltip"> Data Review <span style="font-size: small"> <?php echo htmlspecialchars(tool_tip_text('data_review'));?></span></label></h2>
        <br><br><br><br><br>
        <form>
            <div class="form-group row">
                <label for="subject_id_raw" class="col-sm-3 col-form-label hasTooltip"> Subject ID <span> <?php echo htmlspecialchars(tool_tip_text('subject_id'));?></span></label>
                <div class="col-sm-9">
                    <input type="text" id="subject_id_raw"
                           value="<?php echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['subject_id_source_ws']);?>" class="form-control">
                </div>
            </div>

            <?php             build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'date-adverse-event');?>


            <?php             build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'location');?>


            <?php             build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'severity');?>


            <?php             build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'expectedness');?>


            <?php             build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'relatedness');?>


            <?php             build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'corrective-actions');?>


            <?php             build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'date-reported');?>

            <?php
            build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'irb_custom_1');
            build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'irb_custom_2');
            build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'irb_custom_3');
            build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'irb_custom_4');
            build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'irb_custom_5');
            ?>

<!--            End of Custom IRB Fields-->
            <div class="form-group row">
                <label for="adverse_event_type_raw" class="col-sm-3 col-form-label hasTooltip"> Adverse Event Type: <span> <?php echo htmlspecialchars( tool_tip_text('AEType'));?></span></label>
                <div class="col-sm-9">
                    <select type="text" id="adverse_event_type_raw"
                            value="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_type_source_ws']);?>" class="form-control">
                        <option selected="selected" value ="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_type_source_ws']);?>">
                            <?php $field_op = getFieldCodeLabelPair($project_id, 'ae_type_source_ws');
                            $field_label = getFieldLabel($field_op, $ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_type_source_ws']);
                            echo $field_label;?>
                        </option>
                        <option value = "1">Serious</option>
                        <option value = "9">Other</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="assessment_type_raw" class="col-sm-3 col-form-label hasTooltip"> Assessment Type: <span> <?php echo htmlspecialchars( tool_tip_text('AssessmentType'));?></span></label>
                <div class="col-sm-9">
                    <select type="text" id="assessment_type_raw"
                            value="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['assessment_type_source_ws']);?>" class="form-control">
                        <option selected="selected" value ="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['assessment_type_source_ws']);?>">
                            <?php $field_op = getFieldCodeLabelPair($project_id, 'assessment_type_source_ws');
                            $field_label = getFieldLabel($field_op, $ct_tx_a[$cases_ct[0]][$event_id_ct]['assessment_type_source_ws']);
                            echo $field_label;?>
                        </option>
                        <option value = "1">Systematic Assessment</option>
                        <option value = "2">Non-systematic Assessment</option>
                    </select>
                </div>
            </div>

            <?php             build_optional_irb_fields_ws($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,$project_id,'description-event');?>

            <div class="form-group row">
                <label for="additional_description_raw" class="col-sm-3 col-form-label hasTooltip"> Additional Description: <span> <?php echo htmlspecialchars( tool_tip_text('AddDesc'));?></span></label>
                <div class="col-sm-9">
                    <textarea type="text" id="additional_description_raw" rows="3"
                              class="form-control"><?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['additional_desc_source_ws']);?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="organ_system_name_raw" class="col-sm-3 col-form-label hasTooltip"> Organ System Name: <span> <?php echo htmlspecialchars( tool_tip_text('OrganSysName'));?></span></label>
                <div class="col-sm-9">
                    <select type="text" id="organ_system_name_raw"
                            value="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['organ_system_source_ws']);?>" class="form-control">
                        <option selected="selected" value ="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['organ_system_source_ws']);?>">
                            <?php $field_op = getFieldCodeLabelPair($project_id, 'organ_system_source_ws');
                            $field_label = getFieldLabel($field_op, $ct_tx_a[$cases_ct[0]][$event_id_ct]['organ_system_source_ws']);
                            echo $field_label;?>
                        </option>
                        <option value = "10">Blood and Lymphatic System Disorders</option>
                        <option value = "20">Cardiac Disorders</option>
                        <option value = "3">Congenital, Familial and Genetic Disorders</option>
                        <option value = "40">Ear and Labyrinth Disorders</option>
                        <option value = "50">Endocrine Disorders</option>
                        <option value = "60">Eye Disorders</option>
                        <option value = "70">Gastrointestinal Disorders</option>
                        <option value = "80">General Disorders</option>
                        <option value = "90">Hepatobiliary Disorders</option>
                        <option value = "100">Immune System Disorders</option>
                        <option value = "110">Infections and Infestations</option>
                        <option value = "120">Injury, Poisoning and Procedural Complications</option>
                        <option value = "130">Investigations</option>
                        <option value = "140">Metabolism and Nutrition Disorders</option>
                        <option value = "150">Musculoskeletal and Connective Tissue Disorders</option>
                        <option value = "160">Neoplasms Benign, Malignant and Unspecified (Including Cysts and Polyps)</option>
                        <option value = "170">Nervous System Disorders</option>
                        <option value = "180">Pregnancy, Puerperium and Perinatal Conditions</option>
                        <option value = "190">Product Issues</option>
                        <option value = "200">Psychiatric Disorders</option>
                        <option value = "210">Renal and Urinary Disorders</option>
                        <option value = "220">Reproductive System and Breast Disorders</option>
                        <option value = "230">Respiratory, Thoracic and Mediastinal Disorders</option>
                        <option value = "240">Skin and Subcutaneous Tissue Disorders</option>
                        <option value = "250">Social Circumstances</option>
                        <option value = "260">Surgical and Medical Procedures</option>
                        <option value = "270">Vascular Disorders</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="source_vocabulary_raw" class="col-sm-3 col-form-label hasTooltip"> Source Vocabulary: <span> <?php echo htmlspecialchars( tool_tip_text('SourceVocab'));?></span></label>
                <div class="col-sm-9">
                    <input type="text" id="source_vocabulary_raw"
                           value="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['source_vocab_source_ws']);?>" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="ae_term_raw" class="col-sm-3 col-form-label hasTooltip"> AE Term: <span> <?php echo htmlspecialchars( tool_tip_text('AETerm'));?></span></label>
                <div class="col-sm-9">
                    <input type="text" id="ae_term_raw"
                           value="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_term_source_ws']);?> " class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="ae_arm_raw" class="col-sm-3 col-form-label hasTooltip"> Specify Study Arm: <span> <?php echo htmlspecialchars( tool_tip_text('StudyArm'));?></span></label>
                <div class="col-sm-9">
                    <input type="text" id="ae_arm_raw"
                           value="<?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_arm_source_ws']);?> " class="form-control">
                </div>
            </div>
        </form>

    </div>
    <div class="rightcolumn">
        <div class="ws">

            <div class="wrapper">

                <div class="pagination">
                    <?php $next = ($record_num == 1 ? 0 : 1);?>
                    <a href="<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/worksheet.php<?php echo htmlspecialchars( '&pid='.$project_id.'&rid='.($record_num-$next)); ?>">&laquo;</a>
                    <span class="pag-active"> <?php echo htmlspecialchars(($record_num));?>/<?php echo htmlspecialchars( (sizeof($ct_tx_count))); ?></span>
                    <a href="<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/worksheet.php<?php echo htmlspecialchars( '&pid='.$project_id.'&rid='.($record_num+1)); ?>">&raquo;</a>
                    <a class ="add_new hasTooltip" href="<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/worksheet.php<?php echo htmlspecialchars( '&pid='.$project_id.'&rid='.(sizeof($ct_tx_count) +1 )); ?>">
                        +<span style="font-size: small"> <?php echo htmlspecialchars(tool_tip_text('add_new'));?></span>
                    </a>
                </div>
            </div>
            <h2><label class="hasTooltip"> Record Details<span style="font-size: small"> <?php echo htmlspecialchars(tool_tip_text('record_details'));?></span></label></h2>

            <table>
                <col width="30%">
                <col width="60%">
                <tr> <td> <b> AE Record #: </b> </td> <td><?php echo htmlspecialchars(($record_num));?> </td> </tr>
                <tr> <td> <b> Subject ID: </b> </td> <td><?php echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['subject_id_alt_ws']));?>  </td> </tr>

                <?php
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'date-adverse-event');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'location');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'severity');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'expectedness');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'relatedness');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'corrective-actions');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'date-reported');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'irb_custom_1');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'irb_custom_2');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'irb_custom_3');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'irb_custom_4');
                build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'irb_custom_5');
                ?>

                <tr> <td> <b> Adverse Event Type: </b> </td> <td><?php echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_type_alt_ws']));?>  </td> </tr>
                <tr> <td> <b> Assessment Type: </b> </td> <td><?php echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['assessment_type_alt_ws']));?>  </td> </tr>

                <?php build_optional_irb_fields_ws_alt($ct_tx_a, $cases_ct,$event_id_ct,$irb_field_labels,'description-event');?>
                <tr> <td> <b> Additional Description: </b> </td> <td><?php echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['additional_desc_alt_ws']));?>  </td> </tr>
                <tr> <td> <b> Organ System Name: </b> </td> <td><?php echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['organ_system_alt_ws']));?>  </td> </tr>
                <tr> <td> <b> Source Vocabulary: </b> </td> <td><?php echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['source_vocab_alt_ws']));?>  </td> </tr>
                <tr> <td> <b> AE Term: </b> </td> <td><?php echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_term_alt_ws']));?>  </td> </tr>
                <tr> <td> <b> Specify Study Arm: </b> </td> <td><?php echo htmlspecialchars(($ct_tx_a[$cases_ct[0]][$event_id_ct]['ae_arm_alt_ws']));?>  </td> </tr>
            </table>
            <br>
            <div>
                <label for="admin_notes" >Admin Notes:</label> <br>
                <div>
                    <textarea type="text" id="admin_notes" rows="15"
                              class="form-control"><?php echo htmlspecialchars( $ct_tx_a[$cases_ct[0]][$event_id_ct]['admin_notes_ws']);?></textarea>
                </div>
            </div>

            <div class="wrapper">
                <input class ="save" type="submit" value="Save" id="update_record">
            </div>
        </div>
    </div>
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
    $(document).ready(function(){
        $('#loadingDiv').hide();
        $('#successDiv').hide();
    });
</script>

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
    $(document).ready(function() {
        $('#update_record').click( function() {


            $('#loadingDiv').show();


            $.ajax({type: "POST",
                data: "pid=<?php echo htmlspecialchars(($project_id))?>"
                +"&subject_id_raw=" + $('#subject_id_raw').val()
                +"&record_id=<?php echo htmlspecialchars(($record_num));?>"
                <?php
                    build_optional_irb_fields_ws_u($irb_field_labels,'date-adverse-event');
                    build_optional_irb_fields_ws_u($irb_field_labels,'description-event');
                    build_optional_irb_fields_ws_u($irb_field_labels,'location');
                    build_optional_irb_fields_ws_u($irb_field_labels,'severity');
                    build_optional_irb_fields_ws_u($irb_field_labels,'expectedness');
                    build_optional_irb_fields_ws_u($irb_field_labels,'relatedness');
                    build_optional_irb_fields_ws_u($irb_field_labels,'corrective-actions');
                    build_optional_irb_fields_ws_u($irb_field_labels,'date-reported');
                    build_optional_irb_fields_ws_u($irb_field_labels,'irb_custom_1');
                    build_optional_irb_fields_ws_u($irb_field_labels,'irb_custom_2');
                    build_optional_irb_fields_ws_u($irb_field_labels,'irb_custom_3');
                    build_optional_irb_fields_ws_u($irb_field_labels,'irb_custom_4');
                    build_optional_irb_fields_ws_u($irb_field_labels,'irb_custom_5');
                    ?>
                +"&adverse_event_type_raw=" + $('#adverse_event_type_raw').val()
                +"&assessment_type_raw=" + $('#assessment_type_raw').val()
                +"&additional_description_raw=" + $('#additional_description_raw').val()
                +"&organ_system_name_raw=" + $('#organ_system_name_raw').val()
                +"&source_vocabulary_raw=" + $('#source_vocabulary_raw').val()
                +"&ae_term_raw=" + $('#ae_term_raw').val()
                +"&ae_arm_raw=" + $('#ae_arm_raw').val()
                +"&admin_notes=" + $('#admin_notes').val()
                ,
                url:'/..<?php print htmlspecialchars($URI)?>?prefix=ae_reporting&page=pages/update_record.php&pid=<?php echo htmlspecialchars(($project_id))?>',
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






</body>


