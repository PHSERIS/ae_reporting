<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;
use ExternalModules\AbstractExternalModule as AbsEM;

include_once dirname(__FILE__)."/pages/classes/common.php";

global $Proj;

if (!isset($project_id)) {
    die('Project ID is a required field @ Index');
}


$URI = explode("?", $_SERVER['REQUEST_URI'])[0];

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
    <html style="
    padding-bottom: 5%;
">
    <head>
        <link rel="stylesheet" type="text/css"
              href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/ae_style.css") ?>">
        <link rel="icon"
              type="image/png"
              href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/redcap_web_icon_32x.png") ?>">
    </head>

    <body>
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

    <div id="main-content">

        <div class="title123">
            <h1>Adverse Event Reporting</h1>
            <p>
            <center>A Harvard Catalyst project </center>
            </p>
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
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=index" . "&pid=" . $project_id )?>"
               style=" background-color: #ffffff; color: #C05D4F; border-bottom: 6px solid #C05D4F;">Home</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/setup.php" . "&pid=" . $project_id )?>"
               style=" background-color: #ffffff;">Setup</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/mapping.php" . "&pid=" . $project_id) ?>">Mapping</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/worksheet.php" . "&pid=" . $project_id . "&rid=1") ?>">Worksheet</a>
            <a href="<?php print htmlspecialchars($URI. "?prefix=ae_reporting&page=pages/IRBAELog.php" . "&pid=" . $project_id) ?>">IRB AE Log</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/CTAELog.php" . "&pid=" . $project_id) ?>">Clinical-Trials.gov AE Log</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/FDAAELog.php" . "&pid=" . $project_id) ?>">FDA AE Log</a>
            <?php if ($warning){
                print "    <span class=\"badge\" style=\"float:right\" onclick=\"myFunction('badgeInfo')\">!</span>";
            }
            ?>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/helptext.php" . "&pid=" . $project_id) ?>"
               style="float:right" rel="noopener noreferrer", target="_blank">Help</a>
        </div>

        <div class="result" id="ctResult"></div>

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
        <br>
        <br>
        <button class="vertical-center-left" onclick="plusDivs(-1)">&laquo;</button>
        <button class="vertical-center-right" onclick="plusDivs(+1)">&raquo;</button>
        <center>

            <div class="Slide-content" style="height:45%; " >

                <img class="Slides" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/Catalyst.png") ?>"
                     style="width:50%;">

                <div class="Slides slides_text">
                    <font size="5">The REDCap</font> <font size="4">Adverse Event Reporting External Module </font>
                        <br>
                        <br>is an application designed to generate Adverse Event (AE) reports needed by Investigators at different time points for different requirements.
                        <br>The REDCap AE Reporting External module can facilitate Investigator and Institutional compliance by facilitating the  creation of aggregate Adverse Event (AE) reports for:
                        <br>Clinical-Trails.gov (CT) AE template.
                        <br>
                        <br>    (1) ClinicalTrials.gov (CTgov) AE results reporting module
                        <br>    (2) Aggregate AE reporting to your Institutional Review Board (IRB)
                        <br>    (3)Summary AE reporting for the Food and Drug Administration (FDA) Investigational <br> New Drug (IND) Annual Report according to 21 CFR 312.33(b)(1). </li>
                        <br>
                        <br>
                        <br>Notice: Please consult with the IRB of record to determine the approved usage of this tool for your study.*

                </div>
                <div class="Slides slides_text">
                    <br><font size="4">The application allows for two modes of operation:</font>
                    <br><br>  1) it can connect and load data from an existing REDCap project

                    <br><br>   2) it can act as an additional REDCap project that collects AE data.
                </div>
                <div class="Slides slides_text">
                    <font size="4">Its goal is to:</font><br><br>
                    1) Eliminate manual aggregation of individual AE events at reporting time, <br> <br>
                    2) Successfully create standard AE templates required by ClinicalTrials.gov, your IRB, and FDA <br><br>
                </div>
                <div class="Slides slides_text">
                    <font size="4">How does it do it?</font> <br><br>
                    1) It connects to your REDCap project using an API token<br><br>
                    2) Identifies AE information by matching pre-determined data fields in the adverse event records<br><br>
                    3) Loads AE information into a prebuilt REDCap project controlled through REDCap's Adverse Event External Module.

                </div>
                <div class="Slides slides_text">
                    <font size="4">PROs </font><br><br>
                    1) Maintains an audit trail - It is your user on the audit log<br>
                    2) Full access to the Adverse Event data<br>
                    3) Provides a window into your REDCap project<br>
                    4) ‘Talks’ to your REDCap project that contains your study source documentation and provides label-to-label data import<br>
                    5) Collects 19 data fields associated with AE reports to ClinicalTrials.gov, your IRB, and FDA<br>
                    6) Capable of distinguishing between ‘serious’ and ‘other’ adverse event reports<br>
                    7) Auto-creates Adverse Events template reports for CTgov, IRB, , and FDA<br>
                    8) Designed with web-security in mind iterating on VERACODE security scans
                </div>
                <div class="Slides slides_text">
                    <font size="4">CONs </font><br><br>
                    1) Enabling External Module requires manual steps<br>
                    2) Full Access to Adverse Events Project making it easy to change and easy to break<br>
                    3) Deleting Adverse Event records from Adverse Events Project must be done using REDCap<br>
                </div>
                <div class="Slides slides_text">
                   <br><br>

                    <font size="4">Click on the Setup tab to continue, <br><br>or<br><br> the Help tab to learn more.</font>
                </div>
                </font>
            </div>

    </div>



            </div>
        </center>
        <br>
        <br>
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


        <div class="footer"> Harvard Catalyst | Partners HealthCare </div>



    <script>

        var slideIndex = 1;
        showDivs(slideIndex);

        function plusDivs(n) {
            showDivs(slideIndex += n);
        }

        function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("Slides");
            if (n > x.length) {slideIndex = 1}
            if (n < 1) {slideIndex = x.length};
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            x[slideIndex-1].style.display = "block";
        }

    </script>

    </body>


    <?php include_once APP_PATH_DOCROOT . "ProjectGeneral/header.php"; ?>