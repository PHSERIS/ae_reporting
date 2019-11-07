<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;

include_once dirname(__FILE__)."/classes/common.php";

global $Proj;

if (!isset($project_id)) {
    die('Project ID is a required field');
}

$URI = explode("?",$_SERVER['REQUEST_URI'])[0];

$record_num = 1;

$ct_tx_fields = array('token','unique_event_name_source','study_arms_num','at_risk_per_arm','study_arm_names','subject_id_source','ae_date_source','desc_event_source','location_source',
    'severity_source','expectedness_source','relatedness_source','corrective_source','date_reported_source','ae_type_source',
    'assessment_type_source','additional_desc_source','organ_system_source','source_vocab_source','ae_term_source',
    'ae_arm_source','record_details','subject_id_alt','ae_date_alt','desc_event_alt','location_alt','severity_alt',
    'expectedness_alt','relatedness_alt','corrective_alt','date_reported_alt','ae_type_alt','assessment_type_alt',
    'additional_desc_alt','organ_system_alt','source_vocab_alt','ae_term_alt','ae_arm_alt','rcrd_affected_source',
    'rcrd_affected_alt');



$ct_tx_a = REDCap::getData($project_id, 'array', $record_num, $ct_tx_fields, 'map_arm_1', NULL, FALSE, FALSE, FALSE, NULL, FALSE, FALSE);

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
    <p> <center>A Harvard Catalyst project .</center></p>
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
    <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/setup.php" . "&pid=" . $project_id )?>" style=" background-color: #ffffff; color: #C05D4F; border-bottom: 6px solid #C05D4F;">Setup</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/mapping.php"."&pid=".$project_id)?>" >Mapping</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/worksheet.php"."&pid=".$project_id."&rid=1")?>">Worksheet</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/IRBAELog.php"."&pid=".$project_id)?>">IRB AE Log</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/CTAELog.php"."&pid=".$project_id)?>">Clinical-Trials.gov AE Log</a>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/FDAAELog.php"."&pid=".$project_id)?>">FDA AE Log</a>
    <?php if ($warning){
        print "    <span class=\"badge\" style=\"float:right\" onclick=\"myFunction('badgeInfo')\">!</span>";
    }
    ?>
    <a href="<?php print htmlspecialchars( $URI."?prefix=ae_reporting&page=pages/helptext.php"."&pid=".$project_id)?>" style="float:right">Help</a>
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
            <li>This external module only works with secure connections (i.e. using "https")</li>
            <li>Please read the section on the documentation on API Token Requirements.</li>
        </ol>
        <b>* If the problem continues, please contact your REDCap administrator.</b>
    </div>

<div class="wrapper">
    <input class ="save" type="submit" value="Settings" onclick="myFunction('snackbar')">

</div>
<br>
<div id="snackbar">
    Set Parameter
    <span class="closebtn1" onclick="closeSettings('snackbar')">×</span> <br><br>

    Number of Study Arms:
    <input type="text" id="total_arms" value="<?php echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['study_arms_num']);?>"> <br> <br>
    <input class ="save" type="submit" value="Save" id="update_settings">

</div>


<script>

    function moveTo(trigger){
        if(trigger = "WS"){
            window.location.href = '/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/worksheet.php&pid=<?php print htmlspecialchars( $project_id)?>&rid=1';
        }
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

<center>

    <H3>Enter study arm names and number of subjects at risk</H3>
<table>

    <tr>
        <th class="hasTooltip" style = "border-radius: 15px 0px 0px 0px; text-align: center;"> Target Field <span> <?php echo tool_tip_text('Target');?></span> </th> <th> </th> <th class="hasTooltip" style = "text-align: center;"> Study Arm Name <span> <?php echo tool_tip_text('Source');?></span</th> <th class="hasTooltip" style = "border-radius: 0px 15px 0px 0px; text-align: center;""> # of Subjects <at>    </at> Risk <span> <?php echo tool_tip_text('Alternate');?></span</th>
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

    </center>
<br>

<div class="wrapper">
    <input class ="save" type="submit" value="Save" id="update_mappings">
    <input class ="save" type="submit" value="Continue" id="load_prep" onclick="moveTo('WS')">
</div>

<div id="snackbar_load">
    <br><b>Load Data From Mappings</b>
    <span class="closebtn1" onclick="closeSettings('snackbar_load')">×</span> <br><br>
    <input type="checkbox" id="all" name="all_maps" checked disabled> Import all Mappings <br>
<!--    <input type="checkbox" id="selected" name="slct_maps" value="slct"> Import Selected Mappings<br> <br> <br>-->
    <br>
    <input class ="save" type="submit" value="Load Now" id="load_data_from_mappings">
    <br>
</div>

<div class="loading-gif"  id="loadingDiv">

</div>

    <div class="success-gif"  id="successDiv">

    </div>
    <?php
    $irb_wb = $module->getProjectSetting('irb-website');
    $irb_pn = $module->getProjectSetting('irb-phone-number');
    if (!is_null($irb_wb)){
        print "<div class=\"footer_x\"> *Contact your local IRB <a href=\"{$irb_wb}\">here</a>";

        if (!is_null($irb_pn)){
            print " or by phone at {$irb_pn}";
        }
        print "</div>";
    }
    ?>
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
                    $('#successDiv').fadeIn('slow');
                    $('#successDiv').delay(250).fadeOut('slow');
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
                    +"&ws=2"
                    +"&at_risk=" + at_risk_set
                    +"&arm_names=" + arm_name_set,
                url:'/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/update_mapping_arms.php&pid=<?php echo htmlspecialchars($project_id)?>&ws=2',
                success: function (result){
                    $('#loadingDiv').hide();
                    $('#successDiv').fadeIn('slow');
                    $('#successDiv').delay(250).fadeOut('slow');
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


