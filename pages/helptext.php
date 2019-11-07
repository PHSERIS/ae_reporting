<?php


namespace HarvardCatalystPartnersHealthCare\AEreporting;

use \REDCap as REDCap;

global $Proj;

if (!isset($project_id)) {
    die('Project ID is a required field @ Index');
}


$URI = explode("?", $_SERVER['REQUEST_URI'])[0];

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=pages/ae_style.css")?>">
    <link rel="icon"
          type="image/png"
          href="<?php print htmlspecialchars($URI."?prefix=ae_reporting&page=images/redcap_web_icon_32x.png")?>">
</head>
<body>

<script>
    function openNav() {
        document.getElementById("west").style.visibility = "visible";
        // document.getElementById("west").style.width = "300px";
        document.getElementById("main-content").style.marginLeft = "300px";
    }

    function closeNav() {
        document.getElementById("west").style.visibility = "hidden";
        // document.getElementById("west").style.width = "0";
        document.getElementById("main-content").style.marginLeft = "0";
    }
</script>

<div id="main-content" >

    <div class="title123">
        <h1>Adverse Event Reporting</h1>
        <p> <center>A Harvard Catalyst project</center></p>
    </div>

    <div class="topnav">
        <a href="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=pages/helptext.php" . "&pid=" . $project_id)?>"
           style="float:right; background-color: #ffffff; color: #C05D4F; border-bottom: 6px solid #C05D4F;">Help</a>
    </div>

    <div id="HelpTab-Content">
    <h1><u>How can I help? I can tell you more about... </u></h1>
    

    <h1 id="gettingStarted"><u>Home Page and Getting Started:</u></h1>
    <p>Welcome to the overview of the Adverse Event Reporting solution. It explains the purpose, approach, and details
        of how using this tool facilitates the accuracy of Adverse Event Reporting. First and foremost, it needs to be
        clarified that this solution can help the reporting of adverse events for:</p>
    <ul>
        <li>existing REDCap projects</li>
        <li>REDCap projects in the early stages of data collection.</li>
    </ul>

    <p><b>Purpose:</b></p>
    <p>Facilitate the reporting of adverse events by providing a structured process for ensuring adverse event reporting
        requirements are met.</p>
    <p><br/></p>

    <p id="overview"><b>Overview:</b></p>
    <p>The main functionality of the Adverse Event Reporting solution is the identification of adverse event records and
        aggregation of their corresponding adverse event terms. The goal is to facilitate its reporting to governing
        bodies, i.e. IRB, Clinical Trails.gov, and/or FDA. The proposed solution facilitates the aggregation of the
        adverse event terms for two main cases: existing, and new REDCap projects. In the case that a research study is
        being hosted in REDCap and it's been collecting adverse event data, the adverse event reporting solution can
        connect to the existing project with the goal of generating the corresponding adverse events reporting
        templates. On the other hand, in the case of a research study that is in the early stages of collecting data,
        the adverse event reporting solution can be used as the host of the adverse event data itself, and thus
        simplifying the requirements for the research project. The solution is able to connect to or different types of
        REDCap Projects:</p>
    <ol>
        <li>Wide projects</li>
        <li>Wide projects with Events</li>
        <li>Projects with adverse event data in a project Arm</li>
        <li>Projects with adverse event data in a completely separate REDCap project</li>
    </ol>
    <p>The solution connects to these types of projects through an API token, specified by the end-user. The process
        begins by setting-up a field mapping stage in which the fields of the source project (project that contains the
        Adverse Event data) are sorted through and the fields collecting adverse event data are identified. Then, in the
        case that the data being collected is not ideal, or does not meet the expected requirements, the data can be
        cleaned until it meets the expected requirements. Such step is done is the Worksheet stage. Once the data has
        been prepared, it can then be aggregated and the adverse event templates can be created. These templates are
        automatically created for the end-user.</p>
    <p>Furthermore, the proposed solution also provides an alternative for research projects that are in the beginning
        of stages of data collection. In the case that such project is considering creating a separate REDCap project to
        store adverse event data, the proposed solution can help right out-of-the-box, without the need of mapping
        adverse event fields, by using its Worksheet as the separate Adverse Event collection project. The Worksheet
        contains the required fields to create all three adverse event templates.</p>
    <p><br/></p>

        <img class = "help-text-img" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/overview.png" )?>" alt="overview"><br>


    <p><br/></p>
    <p id="requirements"><b>Requirements:</b></p>
    <ul>
        <li>API token with export rights from the source project</li>
        <li>In-depth knowledge of the source project data dictionary to identify the adverse event fields</li>
        <li>Access to the source project for determining total records and/or total of number of subjects at risk</li>
        <li>Able to identify the number of arms in the study</li>
    </ul>
    <p><br/></p>
    <p id="FAQ"><b>FAQs:</b></p>
    <ol>
        <li>What is the goal of this application?
            <ul>
                <li><span style="color: rgb(23,43,77);text-decoration: none;">Facilitate the reporting of adverse events by providing a structured process for ensuring adverse event reporting requirements are met.</span>
                </li>
            </ul>
        </li>
        <li>What is the source project?
            <ul>
                <li>The source project is an existing project where the researcher's (PI) is hosting adverse event data.
                </li>
            </ul>
        </li>
        <li>What are the target, source, and alternate fields?
            <ul>
                <li>Target fields are a set of placeholder fields for mapping values from source or alternate fields.
                </li>
            </ul>
            <ul>
                <li>Source fields are fields from the source project that contain the exact values of the target fields
                    they
                    are being mapped to.
                </li>
            </ul>
            <ul>
                <li>Alternate fields are fields from the source project that contain the best, but not exact, data type
                    available for the target field.
                </li>
            </ul>
        </li>
        <li>Who has access to sensitive adverse event data?
            <ul>
                <li>Only project staff listed in the researcher's project hosting adverse event data.</li>
            </ul>
        </li>
        <li>How is adverse event data accessed?
            <ul>
                <li>Through an API (data) Export token requested by the PI and/or project staff</li>
            </ul>
        </li>
        <li>Where does the adverse event data go after being exported using the API token?
            <ul>
                <li>The adverse event data from the researcher's source project is exported from a REDCap project, and
                    it
                    goes straight into it. It is up to the PI and/or PI's staff to make proper use the API token and
                    make
                    the API call from a safe and secure REDCap instance.
                </li>
            </ul>
        </li>
        <li>Where does the processing of the adverse event data takes place?
            <ul>
                <li>It takes place within your local instance of REDCap. The processing of adverse event data is done in
                    REDCap's backend server under the same local infrastructure environment.
                </li>
            </ul>
        </li>
        <li>Why is loaded data not being displayed in the Worksheet Review Panel?
            <ul>
                <li>If the field mapped into the Source field does not contain the expected values of its Target field,
                    the
                    Worksheet Review panel will not display the data that was mapped into it, because it's considered to
                    be
                    bad data that does not meet the Target field's requirements. In this case, it is recommended that
                    the
                    mapping used for the source field, be mapped into the respective alternate field; doing this will
                    load
                    the data as record details that can be used to compose the target field's data in the Worksheet.
                </li>
            </ul>
        </li>
        <li>Where can I find the requirements for building the adverse event report for clinical-trails.gov?
            <ul>
                <li>The requirements are available online and they can be found here: <a
                            href="https://prsinfo.clinicaltrials.gov/results_definitions.html" class="external-link"
                            rel="nofollow">https://prsinfo.clinical-trials.gov/results_definitions.html</a></li>
            </ul>
        </li>
    </ol>
    <h1 id="HelpTab-MappingandLoadingdata"><u> Mapping and Loading data </u></h1>
    <p><b>Getting Started:</b></p>
    <p>The mapping stage refers to mapping fields from the source project (the researcher's project containing the
        source
        adverse event data) to the target project (the underlying REDCap project of the Adverse Event Reporting
        solution.)
        There are 17+ fields that must be mapped in order to generate the three adverse event templates. Depending on
        the
        adverse event template you're aiming to generate, you might not be required to map all fields. All fields are
        mapped
        through dropdown options. These mapping dropdowns are initially blank (and contain a default value of Y). In
        order
        to see the fields of the source project it is necessary to connect to it using an API token. Use the &quot;Settings&quot;
        window to specify the token that has export rights from the source project.</p>
    <p><br/></p>

    <img class = "help-text-img" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/settings.png")?>" alt="settings"><br>

    <p><br/></p>

    <p id="apiToken"><b>What and how to request an API Token? </b></p>

    <p>If you don't have an API token, please see these instructions.</p>
    <p>How and what to request for the API Token?</p>

    <img class = "help-text-img" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/apiToken.png") ?>" alt="apiToken"><br>

    <p id="fieldMapping"><b>Field Mapping</b></p>
    <p>Field mapping is straight forward - each dropdown will list all the fields found in the source project data
        dictionary. It is the job of the end-user to select the appropriate field that maps to the target fields. In the
        case that the source project was built longitudinal with arm/events, the mapping options will display an
        additional
        target field asking to identify the arm in which adverse events are to be found. In practice, it might be
        unrealistic to find fields in an existing research project that directly map to the target fields. In this case,
        alternate fields have been included. The alternate fields are to be used with fields that do not have a direct
        mapping, from the source to the target project. The following image shows the ideal case in which the source
        project
        contains fields named identically to the target fields. It also shows how target fields can be mapped from
        alternate
        fields. For a complete list of target field definition please see this document.</p>

    <img class = "help-text-img" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/fieldMapping.png") ?>" alt="fieldMapping"><br>

    <p id="numStudyArms"><b>Specifying the number of Study Arms:</b></p>
    <p>Knowing the number of study arms is required for generating the FDA and the Clinical Trials.gov adverse event
        template. The requirement for these fields is to state the amount of <span
                style="color: rgb(23,43,77);text-decoration: none;">subject </span>at risk per arm. In turn, unless
        otherwise noticed by the researcher, the amount of <span
                style="color: rgb(23,43,77);text-decoration: none;">subject </span>at risk per arm is the number of
        unique
        <span style="color: rgb(23,43,77);text-decoration: none;">subject </span>per arm, which could essentially be all
        existing records per arm if a subject is enrolled only once. The amount of study arms is specified in the
        settings
        button found in the upper right side of the mapping page. Entering the number of arms in the appropriate place,
        as
        shown below, adds fields at the end of the list of mapping fields for each of the study arms. The corresponding
        totals are expected to be known and completed by the end-user.</p>

    <img class = "help-text-img" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/settings.png" )?>" alt="settings" height="150">
    <img class = "help-text-img" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/armSettings.png") ?>" alt="armSettings"
         height="150"><br>

    <p id="affectedFields"><b>Specifying Affected field mapping:</b></p>
    <p>The affected field mapping is essential for creating the adverse event reports. In essence, these adverse event
        reports only contain a list and/or information of records affected by an adverse event, and thus it cannot
        contain
        anything else. The affected field mapping must identify a field in the source project that all records with an
        adverse event, but no other, contain. It is this mapping that will identify records, from the source project, as
        records affected with an adverse event. For instance:</p>
    <ul>
        <li>if a separate REDCap project has already been built that host only adverse event data, the &quot;date of
            adverse
            event&quot; field of this project could be used as the mapping of the Affected field, because the date of
            adverse event is the most likely data point that might be available to all adverse event records.
        </li>
        <li>in a REDCap project with arms and events, in which one of the arms is dedicated for collecting adverse event
            data, the best mapping for the affected field would be a field in the project arm recording adverse event
            data
            that all records contain.
        </li>
    </ul>
    <p>If no mapping is specified for Affected field mapping, then no records will be loaded into the Worksheet for
        further
        processing. </p>

    <img class = "help-text-img" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/affectedField.png") ?>" alt="affectedField" style="width: 100%;"><br>

    <p id="savingWork"><b>Saving your work:</b></p>
    <p>The field mappings are not saved automatically. Make sure you save your work by using the save button on the
        lower
        right hand side of the screen. The save button only saves the mappings in the current page, and it does not load
        the
        data mapped by neither the source or alternate mapping field.</p>
    <p><br/></p>
    <p id="loadingData"><b>Loading mapped data:</b></p>
    <p>The loading of the data takes place by using the Load button found at the bottom right hand side of the screen.
        The
        Load button loads all of the data identified by mapped fields. Keep in mind that the data required is only
        adverse
        event records; in terms of the Clinical Trials.gov requirements, it requires only affected records. The Load
        button
        will only load records that have been identified by the Affected target field. For example, if a REDCap project
        contains a total of 238 records, of which 34 experienced an adverse event, then the Load button will only load
        34
        records if the appropriate mapping was given to the Affect target field. Otherwise, if no Affected field mapping
        is
        identified, the loading process will not load any records. Loading the data is a longer process than saving
        mappings. Please expect a longer response time when waiting for the Load to complete. The page will reload after
        its
        process has completed. At this point, you can proceed to the Worksheet stage by clicking its button found on the
        page's menu bar.</p>

    <h1 id="HelpTab-Worksheetandcleaningdata:">Worksheet and cleaning data:</h1>
    <p><b>Getting Started</b></p>
    <p>The purpose of the Worksheet stage is to provide means for the end-user to clean the adverse event data that was
        not
        able to be directly mapped into the target fields. The worksheet states the expected values and format for each
        of
        the target fields making it easy for the end-user to identify the proper field value. Furthermore, it allows
        saving
        the progress of the work done, as well as scrolling through all loaded adverse events records. The Worksheet is
        composed of four essential components (please refer to the following image):</p>
    <ol>
        <li>Worksheet Review Panel: it displays the data loaded from source fields - one record at a time; allows the
            end-user to input, update, or modify the data mapped directly to the target field from the source field.
        </li>
        <li>Alternate Field Data Panel: it displays the data loaded from alternate fields - one record at a time; its
            purpose is to act as a window to the adverse event record in the source project, allowing the end-user to
            see
            <span style="color: rgb(23,43,77);text-decoration: none;">the closest</span> (record details) adverse event
            data
            for the fields in the record found in the source project. Having a window back to the source project allows
            the
            end-user to set the expected value for each field in each record of the Worksheet Review Panel.
        </li>
        <li>Record toggle: it displays the number of the record being reviewed as well as the number of records
            available.
            Its purpose is to allow the end-user to scroll through all the available records so they can be reviewed and
            verified before creating any adverse event report.
        </li>
        <li>Save progress button: it allows to save the changes of the record being reviewed.</li>
    </ol>

    <img class = "help-text-img" src="<?php print htmlspecialchars($URI . "?prefix=ae_reporting&page=images/worksheet.png") ?>" alt="worksheet" style="width: 100%;"><br>

    <p id="cleaningData"><b>Cleaning data in the Worksheet</b></p>
    <p>The Worksheet stage of the solution provides a change for the end-user to review and validate the data before it
        is
        used for generating the adverse event templates. Also, it is important to set the Adverse Event Term and its
        corresponding Study Arm. The Alternate Field Panel can be used ensure the proper selection of the Worksheet
        field
        values, either by copying and pasting, or selecting the right answer choice from the values. Take the following
        use
        case as an example:</p>
    <p>Use Case 1 - bad data mapped to field named Description of Events:</p>
    <ul>
        <li>If the Alternate field for the mentioned field was mapped with alternate data, it is this data that can be
            used
            to determine the appropriate value needed for the target field. It can either be copied and paste it into
            it, or
            it can be typed into it.
        </li>
    </ul>
    <p>Use Case 2 - How do I know if I've finished reviewing a record?</p>
    <ul>
        <li>The alternate data field panel, under Records Details, contains a text box that can be used to leave notes
            in
            each individual record without adding it into the final adverse event report. The end-user can include
            record
            notes in the text box, which can be as simple as leaving a note stating the record has been reviewed, i.e.
            &quot;This record has been completed.&quot;
        </li>
    </ul>
    <h1 id="HelpTab-AdverseEventsReportTemplates/Logs"><u>Adverse Events Report Templates/Logs</u></h1>
    <p><b>IRB Adverse Event Log</b></p>
    <p>The IRB adverse event log is generated at the moment its menu button is triggered from the menu bar. The adverse
        event (AE) log for the IRB is no more than a list of events, as it does not require any kind of aggregation. The
        IRB
        AE report can be generated at any time. It is generated by making use of the data in the Worksheet Review Panel.
        In
        the case that the adverse event records have not been completely reviewed, the IRB AE log will still be
        generated,
        but using default data stating &quot;No Data Available&quot; for those fields still pending to be reviewed. The
        fields used to compose the IRB AE log have been selected as blanket fields that cover more than it is usually
        requested from researcher to report, and might be enough for most IRB purposes.</p>
    <p><br/></p>
    <p id="ctTemplate"><b>Clinical-Trials.gov Adverse Event Report template</b></p>
    <p>The Clinical Trials.gov adverse event report template follows a couple of steps before displaying the aggregate.
        It
        takes the cleaned adverse event records, specified in the Worksheet Review Panel, and migrates them to a
        repeatable
        event instrument in the underlying REDCap project. Then, it read the saved data and generates the report to be
        displayed on the screen. Furthermore, it shows the aggregation of adverse event terms per study arm, as well as
        the
        number of subjects affected as stated in the Mapping stage by the end-user. Further information about the
        requirements for building the adverse event report for clinical-trails.gov can be found on this website: <a
                href="https://prsinfo.clinicaltrials.gov/results_definitions.html" class="external-link" rel="nofollow">https://prsinfo.clinical-trials.gov/results_definitions.html</a>
    </p>
    <p><br/></p>
    <p id="fdaTemplate"><b>FDA Adverse Event Report template</b></p>
    <p>The FDA adverse event template is generated as a subset of the clinical-trail.gov template. It is generated using
        the
        same process, and it includes the identical aggregated fields.</p>
    <p><br/></p>
    <p id="outputFile"><b>Accessing the Adverse Event Report output file</b></p>
    <p>Each adverse event report can be downloaded from the application by using the download button found on the
        screen.
        It's as simple as that - the download button will generate a file on-the-fly, based on the data being displayed
        on
        the screen, and the browser will guide you through the rest of the process. </p>
    <p><br/></p>
    <p><br/></p>
        <h1 id="fdaTemplate"> First level debugging - White Reports</h1>

        The goal of this external module is to create the Adverse Event logs of the IRB, CT.gov, and FDA. These reports will only be generated when their respective key-fields have been populated. In the case that the reports you're trying to generated results in a White Screen, it means that the key fields needed for the report do not exist in the application. The following outlines the steps needed to address the potential White-Screen you might encountered.
        <br>
        <br>
        <ol>
            <li> White Screen at IRB AE Log: the Subject ID is the key-field for this report. A White-Screen on this report means that there are no Subject IDs found in the Worksheet. </li>
            <li> White Screen at the AE CT.gov report: the key fields for this report are Adverse Event Type, Subject ID, and AE Term. While the report will be generated without AE Term, it will result in a White Screen if Adverse Event Type and/or Subject ID are missing.</li>
            <li> White Screen at the FDA AE report: the key fields for this report are Adverse Event Type, Subject ID, and AE Term. While the report will be generated without AE Term, it will result in a White Screen if Adverse Event Type and/or Subject ID are missing.</li>
        </ol>

        <p><br/></p>
        <h1> Additional Information</h1>

        Please visit the consortium website for the latest information about this external module.
        <br>
        <br>
        <a href="https://community.projectredcap.org/articles/69745/adverse-event-reporting-external-module.html" target="_blank"> https://community.projectredcap.org/articles/69745/adverse-event-reporting-external-module.html </a>
    <p><br/></p>
    <p><br/></p>

    </div>
    <div class="footer">
        Harvard Catalyst | Partners HealthCare
    </div>

</div>



</div>



</body>
</html>

<?php include_once APP_PATH_DOCROOT . "ProjectGeneral/header.php";?>