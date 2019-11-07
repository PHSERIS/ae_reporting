<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use REDCap as REDCap;


$dd_json = REDCap::getDataDictionary('json');
$dd_array = json_decode($dd_json, true);
// Open the file to get existing content
$current = file_get_contents(__DIR__ . '/dd_expected.json');
$dd_array_expected = json_decode($current, true);
$difference = array_diff_assoc($dd_array_expected, $dd_array);

$difference_sample = sizeof($difference);
$dd_size = sizeof($dd_array);

$sample = ($difference_sample == 0 && $dd_size > 112 ? 999 : $difference_sample);

switch ($sample) {
    case 0:
        print "<span class=\"closebtn1\" onclick=\"closeSettings('snackbar')\">×</span> <br> System Check <span onclick= showModal('myModal')><u>#{$difference_sample}-{$dd_size}</u> </span>: Pass! <br>";
        break;
    case 111:
        print "<span class=\"closebtn1\" onclick=\"closeSettings('snackbar')\">×</span> <br> System Check <span onclick= showModal('myModal')><u>#{$difference_sample}-{$dd_size}</u>: Failed! <br> <br> This external module must be enabled on its corresponding project.";
        break;
    case 999:
        print "<span class=\"closebtn1\" onclick=\"closeSettings('snackbar')\">×</span> <br> System Check <span onclick= showModal('myModal')><u>#{$difference_sample}-{$dd_size}</u>: Warning! <br> <br> The project contains more fields than expected by this external module.";
        break;
    default :
        print "<span class=\"closebtn1\" onclick=\"closeSettings('snackbar')\">×</span> <br> System Check <span onclick= showModal('myModal')><u>#{$difference_sample}-{$dd_size}</u>: Failed! <br> <br> The project contains fewer fields than expected by this external module.";
        break;
}


?>