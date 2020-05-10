<?php
//-- ---------------------------------------------------- -->
//--Auteurs : COLLETTE Loic et DELAVAL Kevin              -->
//--Groupe : 2203                                         -->
//--Labo : Programmation Web avancée                      -->
//--Application : Site d'immersion à l'école              -->
//--Date de la dernière mise à jour : [DATE DU JOUR]      -->
//-- ---------------------------------------------------- -->


@session_start();

$posts = array("cours1", "cours2", "cours3", "cours4", "date");
$data = array();


$message = "Attention, les champs ";
$error = false;
$input_error = array();
foreach ($posts as $post) {
    if (!isset($_POST[$post])) {
        $message .= $post . ", ";
        array_push($input_error, $post);
        $error = true;
    } else {
        $data[$post] = addslashes(htmlspecialchars($_POST[$post]));
    }
}
$message .= " ne sont pas défini.";

if ($error) {

    $toReturn = array(
        "error" => true,
        "message" => $message,
    );

} else {

    $affDay = $_SESSION["currJour"]+1;
    $nbDay = $_SESSION["jours"];

    if(($affDay -1) >= $nbDay){


        $toReturn = array(
            "error" => false,
            "message" => "Le jour ".$affDay." à été enregistré, encore ".$nbDay - $affDay." à enregistrer !",
        );

        if($nbDay > 1){

            if($data["cours1"] == 0 || $data["cours2"] == 0){

                $toReturn = array(
                    "error" => true,
                    "message" => "Le cours 1 ou/et 2 est/sont vide !",
                );
            }

        }else{

            if($data["cours1"] == 0 || $data["cours2"] == 0 || $data["cours3"] == 0){

                $toReturn = array(
                    "error" => true,
                    "message" => "Le cours 1, 2 ou/et 3 est/sont vide !",
                );
            }

        }

    }else{

        $_SESSION["data_jours"][$affDay-1]["date"] = $data["date"];
        for($i = 1; $i <= 4; $i++){
            $_SESSION["data_jours"][$affDay-1]["cours"][$i-1] = $data["cours".$i];
        }
        $_SESSION["currJour"]++;
        $toReturn = array(
            "error" => false,
            "message" => "Le jour ".$affDay." à été enregistré, encore ".($nbDay - $affDay)." à enregistrer !",
        );

    }



}

echo(json_encode($toReturn));