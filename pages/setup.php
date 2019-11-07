<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;

include_once dirname(__FILE__)."/classes/common.php";

global $Proj;

if (!isset($project_id)) {
    die('Project ID is a required field @ Index');
}


$URI = explode("?", $_SERVER['REQUEST_URI'])[0];

// loading settings saved within the project
$record_num = 1;

$ct_tx_fields = array('study_arms_num');


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
            <center>A Harvard Catalyst project</center>
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
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=index" . "&pid=" . $project_id) ?>">Home</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/setup.php" . "&pid=" . $project_id) ?>"
               style=" background-color: #ffffff; color: #C05D4F; border-bottom: 6px solid #C05D4F;">Setup</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/mapping.php" . "&pid=" . $project_id) ?>">Mapping</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/worksheet.php" . "&pid=" . $project_id . "&rid=1") ?>">Worksheet</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/IRBAELog.php" . "&pid=" . $project_id) ?>">IRB AE Log</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/CTAELog.php" . "&pid=" . $project_id) ?>">Clinical-Trials.gov
                AE Log</a>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/FDAAELog.php" . "&pid=" . $project_id) ?>">FDA AE Log</a>
            <?php if ($warning){
                print "    <span class=\"badge\" style=\"float:right\" onclick=\"myFunction('badgeInfo')\">!</span>";
            }
            ?>
            <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/helptext.php" . "&pid=" . $project_id) ?>"
               style="float:right" rel="noopener noreferrer", target="_blank">Help</a>
        </div>
       <br>
        <center>

            <div class="Slides">

                <div class="wrapper">
                    <input id= "system_check" class="System-Check" type="submit" value="System Check" >
                </div>


                <div id="snackbar">

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







                <br>
                <div class="setup-tags" onclick="plusDivs(1)"> Training</div>
                <div class="blank-tags"></div>
                <div class="setup-tags"> <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/mapping.php" . "&pid=" . $project_id) ?>">Load AE data from existing REDCap project </a></div>
                <div class="blank-tags"></div>
                <div class="setup-tags" onclick="myFunction('snackbar_WS_conf')">

                    Capture AE data separately in this project
                </div>

            </div>
            <div class="Slides" style="height:35%; ">

                <H3>Start training by selecting one of the following project types:</H3>
                <br>
                <button class="vertical-center-left" onclick="plusDivs(-1)">&laquo;</button>
                <span id="four" class="training-tags" onclick="plusDivs(1)"> Stand <br><br>Alone
                <span id="description"> <b>Description:</b> Simple REDCap project, containing only one (REDCap) instrument collecting Adverse Event data i.e.  it only contains the Adverse Event instrument. </span></span>
                <span class="training-blank-tags">  </span>
                <span id="one" class="training-tags" onclick="plusDivs(2)"> Wide <br><br>Project
                    <span id="description">
                        <b>Description:</b> Simple, out-of-the-box REDCap project that is collecting Adverse Events in one of its instruments and has no additional settings, i.e. it is not longitudinal and has no events. </span>
                </span>
                <span class="training-blank-tags">  </span>
                <span id="two" class="training-tags" onclick="plusDivs(3)"> Wide + Events
                <span id="description"> <b>Description:</b> Longitudinal REDCap project, with only one project arm, that is collecting Adverse Events in an instrument specified by an event. </span>
                </span>
                <span class="training-blank-tags">  </span>
                <span id="three" class="training-tags" onclick="plusDivs(4)"> Arm w/ Adv.Event
                <span id="description"> <b>Description:</b> Longitudinal REDCap project, with several project arms, that is collecting Adverse Events in one of its project arms </span></span>
                <span class="training-blank-tags">  </span>
                <span id="five" class="training-tags" onclick="plusDivs(5)"> R&sup2; <br><br>Events
                <span id="description"> <b>Description:</b> REDCap project containing an Adverse Event instrument that has been set to be a repeated instrument. </span></span>

            </div>

            <div class="Slides" style="width:70%; ">

                <div class="Sample-Project"> Stand Alone Project </div>
                <div> <b>Description:</b> Simple REDCap project, containing only one (REDCap) instrument collecting Adverse Event data i.e.  it only contains the Adverse Event instrument. </div>

                <div><button class="vertical-center-left" onclick="plusDivs(-1)">&laquo;</button></div>

                <div class="left-block">
                    <div class="circle"> 1. Download Source file </div>
                    <span class="step-description-right"> Right click <a href="?prefix=ae_reporting&page=install/01Adv_Event_StandAlone.REDCap.xml"> here </a> to download the file and save it to your computer. The file contains the structure and data of a mock project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create a new project and install (XML) file. Click here to learn more.</span>
                    <div class="circle"> 2. Install<br>XML-file </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 3. Explore Source<br>Project </div>
                    <span class="step-description-right"> Get familiar with the project from step #2; look at its Code Book, and Online designer, Dashboard, Online designer.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Request an API token with export rights for the project from step#2. <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/helptext.php". "&pid=" . $project_id . "#HelpTab-MappingandLoadingdata") ?>" target="_blank">Click here to learn more</a></span>
                    <div class="circle"> 4. Request<br>its<br>API token</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 5. Launch AE Reporting app</div>
                    <span class="step-description-right"> Relaunch this Adverse Event Reporting application and go to its Home Page.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Using the fields names, map the fields in the source project to those in the Mapping page.</span>
                    <div class="circle"> 6. Go to Mappings</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 7. Save<br>&<br>Load </div>
                    <span class="step-description-right"> On the lower-right-hand-side, click Save and Load to save mapping and load records into the Worksheet.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Loaded records are displayed on the Worksheet next to a window into the source project.</span>
                    <div class="circle"> 8. Go to Worksheet </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 9. Clean and Review </div>
                    <span class="step-description-right"> Review loaded records by comparing to the original Record Details from the source project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create Adverse Events reports by clicking on the respective link.</span>
                    <div class="circle"> 10. Create Reports </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br><br>
                <br>


            </div>

            <div class="Slides" style="width:70%; ">

                <div class="Sample-Project"> Wide Project </div>
                <div> <b>Description:</b> Simple, out-of-the-box REDCap project that is collecting Adverse Events in one of its instruments and has no additional settings, i.e. it is not longitudinal and has no events.  </div>

                <button class="vertical-center-left" onclick="plusDivs(-2)">&laquo;</button>

                <div class="left-block">
                    <div class="circle"> 1. Download Source file </div>
                    <span class="step-description-right"> Right click <a href="?prefix=ae_reporting&page=install/02Adv_Event_Wide.REDCap.xml"> here </a> to download the file and save it to your computer. The file contains the structure and data of a mock project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create a new project and install (XML) file. Click here to learn more.</span>
                    <div class="circle"> 2. Install<br>XML-file </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 3. Explore Source<br>Project </div>
                    <span class="step-description-right"> Get familiar with the project from step #2; look at its Code Book, and Online designer, Dashboard, Online designer.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Request an API token with export rights for the project from step#2. <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/helptext.php". "&pid=" . $project_id . "#HelpTab-MappingandLoadingdata") ?>" target="_blank">Click here to learn more</a></span>
                    <div class="circle"> 4. Request<br>its<br>API token</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 5. Launch AE Reporting app</div>
                    <span class="step-description-right"> Relaunch this Adverse Event Reporting application and go to its Home Page.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Using the fields names, map the fields in the source project to those in the Mapping page.</span>
                    <div class="circle"> 6. Go to Mappings</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 7. Save<br>&<br>Load </div>
                    <span class="step-description-right"> On the lower-right-hand-side, click Save and Load to save mapping and load records into the Worksheet.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Loaded records are displayed on the Worksheet next to a window into the source project.</span>
                    <div class="circle"> 8. Go to Worksheet </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 9. Clean and Review </div>
                    <span class="step-description-right"> Review loaded records by comparing to the original Record Details from the source project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create Adverse Events reports by clicking on the respective link.</span>
                    <div class="circle"> 10. Create Reports </div>
                </div>
            </div>

            <div class="Slides" style="width:70%; ">

                <div class="Sample-Project"> Wide + Events </div>
                <div> <b>Description:</b> Longitudinal REDCap project, with only one project arm, that is collecting Adverse Events in an instrument specified by an event. </div>

                <button class="vertical-center-left" onclick="plusDivs(-3)">&laquo;</button>

                <div class="left-block">
                    <div class="circle"> 1. Download Source file </div>
                    <span class="step-description-right"> Right click <a href="?prefix=ae_reporting&page=install/03Adv_Event_Wide_w_Events.REDCap.xml"> here </a> to download the file and save it to your computer. The file contains the structure and data of a mock project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create a new project and install (XML) file. Click here to learn more.</span>
                    <div class="circle"> 2. Install<br>XML-file </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 3. Explore Source<br>Project </div>
                    <span class="step-description-right"> Get familiar with the project from step #2; look at its Code Book, and Online designer, Dashboard, Online designer.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Request an API token with export rights for the project from step#2. <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/helptext.php". "&pid=" . $project_id . "#HelpTab-MappingandLoadingdata") ?>" target="_blank">Click here to learn more</a></span>
                    <div class="circle"> 4. Request<br>its<br>API token</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 5. Launch AE Reporting app</div>
                    <span class="step-description-right"> Relaunch this Adverse Event Reporting application and go to its Home Page.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Using the fields names, map the fields in the source project to those in the Mapping page.</span>
                    <div class="circle"> 6. Go to Mappings</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 7. Save<br>&<br>Load </div>
                    <span class="step-description-right"> On the lower-right-hand-side, click Save and Load to save mapping and load records into the Worksheet.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Loaded records are displayed on the Worksheet next to a window into the source project.</span>
                    <div class="circle"> 8. Go to Worksheet </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 9. Clean and Review </div>
                    <span class="step-description-right"> Review loaded records by comparing to the original Record Details from the source project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create Adverse Events reports by clicking on the respective link.</span>
                    <div class="circle"> 10. Create Reports </div>
                </div>
            </div>

            <div class="Slides" style="width:70%; ">

                <div class="Sample-Project"> Arm w/ Adv.Event </div>
                <div> <b>Description:</b> Longitudinal REDCap project, with several project arms, that is collecting Adverse Events in one of its project arms </div>

                <button class="vertical-center-left" onclick="plusDivs(-4)">&laquo;</button>

                <div class="left-block">
                    <div class="circle"> 1. Download Source file </div>
                    <span class="step-description-right"> Right click <a href="?prefix=ae_reporting&page=install/04Adv_Event_in_Arm.REDCap.xml"> here </a> to download the file and save it to your computer. The file contains the structure and data of a mock project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create a new project and install (XML) file. Click here to learn more.</span>
                    <div class="circle"> 2. Install<br>XML-file </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 3. Explore Source<br>Project </div>
                    <span class="step-description-right"> Get familiar with the project from step #2; look at its Code Book, and Online designer, Dashboard, Online designer.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Request an API token with export rights for the project from step#2. <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/helptext.php". "&pid=" . $project_id . "#HelpTab-MappingandLoadingdata") ?>" target="_blank">Click here to learn more</a></span>
                    <div class="circle"> 4. Request<br>its<br>API token</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 5. Launch AE Reporting app</div>
                    <span class="step-description-right"> Relaunch this Adverse Event Reporting application and go to its Home Page.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Using the fields names, map the fields in the source project to those in the Mapping page.</span>
                    <div class="circle"> 6. Go to Mappings</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 7. Save<br>&<br>Load </div>
                    <span class="step-description-right"> On the lower-right-hand-side, click Save and Load to save mapping and load records into the Worksheet.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Loaded records are displayed on the Worksheet next to a window into the source project.</span>
                    <div class="circle"> 8. Go to Worksheet </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 9. Clean and Review </div>
                    <span class="step-description-right"> Review loaded records by comparing to the original Record Details from the source project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create Adverse Events reports by clicking on the respective link.</span>
                    <div class="circle"> 10. Create Reports </div>
                </div>
            </div>

            <div class="Slides" style="width:70%; ">

                <div class="Sample-Project"> Repeated (R&sup2;) Events</div>
                <div> <b>Description:</b> REDCap project containing an Adverse Event instrument that has been set to be a repeated instrument.  </div>

                <button class="vertical-center-left" onclick="plusDivs(-5)">&laquo;</button>

                <div class="left-block">
                    <div class="circle"> 1. Download Source file </div>
                    <span class="step-description-right"> Right click <a href="?prefix=ae_reporting&page=install/05Adv_Event_Arm_w_rep_events.REDCap.xml"> here </a> to download the file and save it to your computer. The file contains the structure and data of a mock project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create a new project and install (XML) file. Click here to learn more.</span>
                    <div class="circle"> 2. Install<br>XML-file </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 3. Explore Source<br>Project </div>
                    <span class="step-description-right"> Get familiar with the project from step #2; look at its Code Book, and Online designer, Dashboard, Online designer.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Request an API token with export rights for the project from step#2. <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/helptext.php". "&pid=" . $project_id . "#HelpTab-MappingandLoadingdata") ?>" target="_blank">Click here to learn more</a></span>
                    <div class="circle"> 4. Request<br>its<br>API token</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 5. Launch AE Reporting app</div>
                    <span class="step-description-right"> Relaunch this Adverse Event Reporting application and go to its Home Page.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Using the fields names, map the fields in the source project to those in the Mapping page.</span>
                    <div class="circle"> 6. Go to Mappings</div>
                </div>
                <div class="left-block">
                    <div class="circle"> 7. Save<br>&<br>Load </div>
                    <span class="step-description-right"> On the lower-right-hand-side, click Save and Load to save mapping and load records into the Worksheet.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Loaded records are displayed on the Worksheet next to a window into the source project.</span>
                    <div class="circle"> 8. Go to Worksheet </div>
                </div>
                <div class="left-block">
                    <div class="circle"> 9. Clean and Review </div>
                    <span class="step-description-right"> Review loaded records by comparing to the original Record Details from the source project.</span>
                </div>
                <div class="right-block">
                    <span class="step-description-right"> Create Adverse Events reports by clicking on the respective link.</span>
                    <div class="circle"> 10. Create Reports </div>
                </div>
            </div>

            </div>


        </center>

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


    <div id="snackbar_WS_conf">
        Set Parameters
        <span class="closebtn1" onclick="closeSettings('snackbar_WS_conf')">×</span> <br><br>
        Number of Study Arms: <span style="color: red">*</span>
        <input type="text" id="total_arms" value="<?php echo htmlspecialchars($ct_tx_a[$cases_ct[0]][$event_id_ct]['study_arms_num']);?>"> <br> <br>
        <input class ="save" type="submit" value="Save" id="num_of_arms">
        <?php
        if ($ct_tx_a[$cases_ct[0]][$event_id_ct]['study_arms_num'] != "") {
            print "<input class =\"save\" type=\"submit\" value=\"Set Names\" id=\"set_names\" onclick=\"moveTo('WS')\">";
        }
        ?>
    </div>


    <script>

        function moveTo(trigger){
            if(trigger = "WSet_names"){
                window.location.href = '/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/setup_ws.php&pid=<?php print htmlspecialchars( $project_id)?>&rid=1';
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


    <style>
        body {font-family: Arial, Helvetica, sans-serif;}

        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 22, 122, 0.73); /* Fallback color */
            background-color: rgba(0, 22, 122, 0.73); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <img src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/system_check.PNG") ?>" width= "100%" height="auto" >
        </div>

    </div>
    <script>

        var modal = document.getElementById("myModal");


        var btn = document.getElementById("SisX");


        var span = document.getElementsByClassName("close")[0];


       function showModal(name){
           modal = document.getElementById(name);
           modal.style.display = "block";
       }

        span.onclick = function() {
            modal.style.display = "none";
        }


        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>




    <script>

        var slideIndex = 1;
        showDivs(slideIndex);

        function plusDivs(n) {
            showDivs(slideIndex += n);
        }

        function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("Slides");
            if (n > x.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = x.length
            }
            ;
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            x[slideIndex - 1].style.display = "block";
        }

    </script>


    </body>


    <?php include_once APP_PATH_DOCROOT . "ProjectGeneral/header.php"; ?>

<script>
    $(document).ready(function() {
        $('#system_check').click( function() {


            $.ajax({type: "POST",

                url:'/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/system_check.php&pid=<?php echo htmlspecialchars($project_id)?>',
                success: function (result){
                    var x = document.getElementById('snackbar');
                    x.className = "show";

                    $("#snackbar").html(result);
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
        $('#num_of_arms').click( function() {


            closeSettings('snackbar');

            $('#loadingDiv').show()

            $.ajax({type: "POST",
                data: "pid=<?php echo htmlspecialchars($project_id)?>"
                    +"&ws=1"
                    +"&study_arms=" + $('#total_arms').val(),
                url:'/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/update_mapping_arms.php&pid=<?php echo htmlspecialchars($project_id)?>',
                success: function (result){

                    $('#loadingDiv').hide();
                    $('#successDiv').fadeIn('slow');
                    $('#successDiv').delay(250).fadeOut('slow');
                    window.location.href= '/..<?php print htmlspecialchars( $URI)?>?prefix=ae_reporting&page=pages/setup_ws.php&pid=<?php echo htmlspecialchars($project_id)?>';
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