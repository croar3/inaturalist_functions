<?php

//where is this even used?
function get_inat_taxon_info($idlist) {
    $magicnumber = 30;
    //Break out into groups of 10, maybe 20

    $listarr = explode(',', $idlist);

    // print_r($listarr);

    // echo '<br>';

    // print_r(max(1,floor(count($listarr) / $magicnumber)));

    // echo '<br>';

$output = array();

    // $output = '';

    for ($i = 0; $i < max(1,ceil(count($listarr) / $magicnumber)); $i++) {

        $newidlist = '';
        for ($j = $i*$magicnumber; $j < min(count($listarr),($i+1)*$magicnumber); $j++) {
            $newidlist .= $listarr[$j] . ',';
        }
        $newidlist = substr($newidlist,0,strlen($newidlist)-1);
        // print_r($newidlist);
        // $output .= $newidlist . '<br />';
        // echo '<br>';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.inaturalist.org/v1/observations/' . $newidlist,
            CURLOPT_RETURNTRANSFER => true
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);



        if (isset($json['results'])) {
            for ($j = 0; $j < count($json['results']); $j++) {
                $output[] = array(
                    "observation_id" => $json['results'][$j]['id'],
                    "taxon_id" => $json['results'][$j]['taxon']['id'],
                    "taxon_name" => $json['results'][$j]['taxon']['name']
                );
            }
        }

    }
    return $output;
}


function getMonthlyObservations($placeId, $taxonId, $monthNumber) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.inaturalist.org/v1/observations/?place_id='.$placeId.'&per_page=0&month='.$monthNumber.'&taxon_id='.$taxonId,
        CURLOPT_RETURNTRANSFER => true
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response, true);
    return $json;
}

function getYearlyObservations($placeId, $taxonId) {
    $array = array();
    for ($i = 1; $i <= 12; $i++) {
        $monthData = getMonthlyObservations($placeId, $taxonId, $i);
        if (isset($monthData['total_results'])) {
            $array[] = $monthData['total_results'];
        }
        sleep(0.5);
    }
    return $array;
}


function getStateObservations($taxonId) {
    $state_map = array(
        array('Alabama', 19),
        array('Alaska', 6),
        array('Arizona', 40),
        array('Arkansas', 36),
        array('California', 14),
        array('Colorado', 34),
        array('Connecticut', 49),
        array('Delaware', 4),
        array('Florida', 21),
        array('Georgia', 23),
        array('Hawaii', 11),
        array('Idaho', 22),
        array('Illinois', 35),
        array('Indiana', 20),
        array('Iowa', 24),
        array('Kansas', 25),
        array('Kentucky', 26),
        array('Louisiana', 27),
        array('Maine', 17),
        array('Maryland', 39),
        array('Massachusetts', 2),
        array('Michigan', 29),
        array('Minnesota', 38),
        array('Mississippi', 37),
        array('Missouri', 28),
        array('Montana', 16),
        array('Nebraska', 3),
        array('Nevada', 50),
        array('New Hampshire', 41),
        array('New Jersey', 51),
        array('New Mexico', 9),
        array('New York', 48),
        array('North Carolina', 30),
        array('North Dakota', 13),
        array('Ohio', 31),
        array('Oklahoma', 12),
        array('Oregon', 10),
        array('Pennsylvania', 42),
        array('Rhode Island', 8),
        array('South Carolina', 43),
        array('South Dakota', 44),
        array('Tennessee', 45),
        array('Texas', 18),
        array('Utah', 52),
        array('Vermont', 47),
        array('Virginia', 7),
        array('Washington', 46),
        array('West Virginia', 33),
        array('Wisconsin', 32),
        array('Wyoming', 15)
    );


    for ($i = 0; $i < count($state_map); $i++) {
        $state_map[$i][2] = getYearlyObservations($state_map[$i][1], $taxonId);
    }
    return $state_map;
}



?>