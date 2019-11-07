<img src="\images\doc_title.png" style="width: 100%;"/>

## REDCap Adverse Event Reporting
The REDCap Adverse Event Reporting External Module is an application that facilitates the creation of Adverse Event (AE) reports aiming to create the Clinical-Trails.gov (CT) AE template. 
It also creates a generalized IRB AE template, as well as an FDA AE Template. The application allows for two modes of operation:
 
1) it can connect and load data from an existing REDCap project  

2) it can act as an additional REDCap project that collects AE data. 

In both cases it provides means for aggregating the project's adverse event data, and thus creating the mentioned AE reports. 
Its goal is to eliminate aggregation steps, minimize redundant steps, and successfully create the CT AE template. 
Continue reading to learn how to get started.

### System Requirements
* REDCap v8.5 or greater
* Secure Website Protocol (i.e. HTTPS and not HTTP)
* REDCap's API up-and-running, enabled, and ready to go
* PHP 7.2 

## Recommended System Setup and Notice for REDCap Admin
Download the application from REDCap's External Module Repo, and enable it according to your institution's policies around External Modules.

The application requires an underlying REDCap project, referred to as host project, to function properly. 
The application will not work if this External Module is enabled on a project that does not contain the expected project structure and field requirements.
As a simple system check the application was designed with a read-only system check that will inform the user if the underlying REDCap project (that is, the project in which this External Module was enabled) meets the expected requirements. 
The read-only system check is not intrusive and should pose no problem if it run on an existing project by mistake.
The required (host) REDCap project is provided with this External Module, as and XML-file, and can be downloaded from within this document.
Alternatively, the REDCap Administrator can opt for creating a REDCap template of this XML-file. 
Plus, the application provides a training submodule that aims to guide the REDCap user, step by step, how to use the entire application.
It provides the appropriate XML-files to create mock REDCap Studies that contain mock adverse event data, and it explains how to connect these mock projects to the Adverse Event Reporting external module.
It is important to note that a REDCap user can enable this External Module as many times as needed in their respective host projects, but every instance can only connect to one REDCap Project.

Furthermore, the application provides documentation in every phase of its functionality. It starts on this document by outlining what it is and how to get started.
Then, it provides a training submodule with its own documentation, as well as a Help tab available at any time from within the application.
The Help tab contains a FAQ section, plus other how-to and what-and-why explanations of different aspects of the application. 

Finally, it is recommended that REDCap users new to this External Module first enable it on a test project and practice with it (using the provided host and mock projects, taking advantage of the its mock adverse event data).
Once comfortable with its look, feel, and function, the REDCap user can start collecting Adverse Event data in this test project or create a new project - it's their choice. 

## External Module Configuration

The external module allows for configuring how many fields to be collected, and it allows adding up to 5 customizable fields. Additionally, it allows to specify IRB contact information. The purpose of this feature it to allow REDCap Admins to customize the external module with their local IRB contact information. Please take a moment to review these settings before enabling for end-users. It is recommended that you check with your local IRB and determine the expected IRB requirements for Adverse Events reporting.


## End-User Instructions
### 1. Getting Started - Setting up a host project
The functionality of the Adverse Event Reporting application is based on an underlying REDCap project, referred to as host project, that must be installed before the application can be used. 
The REDCap project needed is provided with this application in form of an XML-file. **Download this file by right-clicking the next link, select Save As and save it in your local machine**:

[Download Host Project](?prefix=ae_reporting&page=/install/00Adv_Event_host_project_v01.REDCap.xml)   
 
The file contains the required metadata for the host project. It must be installed in REDCap as a new project by uploading its XML-file when prompted by REDCap.
Follow these instructions to install it successfully:

1) Go to "My Projects" and create a new project by clicking the "+ New Project"

2) Choose and enter the project's title, i.e. "Adverse Event Reporting - Study Trial"

3) Enter the rest of the required fields

4) On the section of "Start project from scratch or begin with a template?", select "Upload REDCap project XML file (CDISC ODM Format)" as shown by the following image:

![Picture](\images\load_template.png)

5) Click on "Choose File" and select the file downloaded above named "adv_event_host_project.xml". Click OK.

6) Once successful, the new project's "Project Setup" page will load. Verify that the project loaded successfully clicking on "Online Designer" and ensuring the following instruments are present.

![Picture](\images\ae_instruments.png)

7) Click on "Define Events" to verify that the project contains the expected following four arms:

* Project Mappings
* Worksheet
* IRB AE Log
* CT AE Log

8) Verify that the "CT AE Log" is the only instrument set to be a Repeated Instrument, and "ClinicalTrails AE Arm Total" is the only instrument marked as repeatable.

9) Finally, set the project to Production Mode.

### 2. Getting Started - Enabling and Starting the AE Reporting External Module in a Project

**Enabling the Module**

REDCap External Modules are enabled withing a project by going to:

* Applications >> External Modules >> Enable Module

Click the "Enable a Module" button, find the Adverse Event External Module and click enable to make it available within your project. Here's what you should be looking for\*:

![Picture](\images\enable_module.png)

\*If your project's "Enable a Module" link does not show the Adverse Event External Module is because the REDCap administrator has not installed/enabled it on the system. 
At this point, you have to contact the REDCap administrator and request it.

**Starting the Module**

Enabling the Adverse Event Reporting module in a project adds a link on the External Module section on the left-hand side REDCap menu.
Click this link, as shown below, to start the application.

![Picture](\images\ae_link.png) 

If done successfully, the application will load and it will show its home page. It should look as follows:
 
<img src="\images\home_page_sc.png" style="width: 100%;"/>

## First Steps - Getting Familiar with the Application

1) Use the floating REDCap icon to display the REDCap left-hand-side menu

2) Use the floating cross ("x"), next to the REDCap icon, to close the left-hand-side menu

3) Click on the Setup tab (second item from the left of the horizontal menu bar), and click green button on the upper right-hand side labeled "System Check".

3a) A pop-up message will be displayed stating the whether or not the installation process was successful.

3b) If the installation was done successfully, the code #0-112 should be displayed as shown below: 

![Picture](\images\system_check_message.PNG)

3c) Click on the underlined code to see an explanation of the potential coded messages. Here's a screenshoot of these messages:

<img src="\images\system_check.PNG" style="width: 100%;"/>


**4) If at some point the underlying REDCap project is modified, the application will not work as expected and you should expect it to behave erroneously. The "System Check" validates an initial setup, not a modification to the project.**

5) Finally, use the Setup tab to select from one of the following modes of operation:
* Training
* Load data from other project
* Use as additional project collecting adverse event data

5a) Each mode contains its own documentation and a step-by-step guide on how-to and what-to-do. In
other words, the rest of the documentation needed to make good use of this application is found within the application itself.


## First level debugging - White Reports

The goal of this external module is to create the Adverse Event logs of the IRB, CT.gov, and FDA. These reports will only be generated when their respective key-fields have been populated. In the case that the reports you're trying to generated results in a White Screen, it means that the key fields needed for the report do not exist in the application. The following outlines the steps needed to address the potential White-Screen you might encountered.

1) White Screen at IRB AE Log: the Subject ID is the key-field for this report. A White-Screen on this report means that there are no Subject IDs found in the Worksheet.
2) White Screen at the AE CT.gov report: the key fields for this report are Adverse Event Type, Subject ID, and AE Term. While the report will be generated without AE Term, it will result in a White Screen if Adverse Event Type and/or Subject ID are missing.
3) White Screen at the FDA AE report: the key fields for this report are Adverse Event Type, Subject ID, and AE Term. While the report will be generated without AE Term, it will result in a White Screen if Adverse Event Type and/or Subject ID are missing. 

## Additional Information

Please visit the consortium website for the latest information about this external module.
https://community.projectredcap.org/articles/69745/adverse-event-reporting-external-module.html

## Good Luck!!!

##### {v1.1.0}