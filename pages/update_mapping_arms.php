<?php

namespace HarvardCatalystPartnersHealthCare\AEreporting;

use REDCap as REDCap;


print "testing update mapping arms";

$project_id = $_POST["pid"];
$ws_setting= $_POST["ws"];


$record_id = 1;
$study_arms = $_POST["study_arms"];
$at_risk = $_POST["at_risk"];
$arm_names = $_POST["arm_names"];

if (!isset($project_id) & !isset($ws_setting)) {
    die('Project ID is a required field');
}

if ($ws_setting == 1) { // Initial setup of number of study arms

    $dataX = array(
        $record_id => array(
            REDCap::getEventIdFromUniqueEvent('map_arm_1') => array(

                'study_arms_num' => $study_arms,

            )
        ));

    $response = REDCap::saveData($project_id, 'array', $dataX);

}

if ($ws_setting == 2) {

    $dataX = array(
        $record_id => array(
            REDCap::getEventIdFromUniqueEvent('map_arm_1') => array(

            'at_risk_per_arm' => $at_risk,
            'study_arm_names' => $arm_names

            )
        ));

    $response = REDCap::saveData($project_id, 'array', $dataX);

}

return $response;
?>

