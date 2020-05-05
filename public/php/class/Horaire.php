<?php
/***********************************************************/
/*Auteurs : COLLETTE Loic et DELAVAL Kevin                 */
/*Groupe : 2203                                            */
/*Labo : Programmation Web avancée                         */
/*Application : Site d'immersion à l'école                 */
/*Date de la dernière mise à jour : 26/04/2020             */
/***********************************************************/


require_once(__DIR__."/Database.php");

class Horaire
{

    /*récupérer tous les cours de l'horaire dans la base de données*/
    public static function getAllLessons()
    {

        $db = new Database();

        $result = $db->conn->query("
        SELECT * 
        FROM horaires
            INNER JOIN cours c on horaires.id_cours = c.id
            INNER JOIN type_cours tc on horaires.id_type_cours = tc.id
        ");
        $array = $result->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    /*récupérer tous les cours de l'horaire dans la base de données d'après l'intitulé*/
    public static function getLessonsByName($name)
    {

        /* evite les attaques SQL (securite)  échape --> ' " \ */
        $name = addslashes(htmlspecialchars($name));

        $db = new Database();
        $result = $db->conn->query("
        SELECT *
        FROM horaires
            INNER JOIN cours c on horaires.id_cours = c.id
            INNER JOIN type_cours tc on horaires.id_type_cours = tc.id
        WHERE intitule like '%".$name."%';");
        $array = $result->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }
    /* recupere tout les cours de l'horaire d'après le bloc */
    public static function getLessonsDateByBloc($bloc){

        $bloc = addslashes(htmlspecialchars($bloc));

        $db = new Database();
        $result = $db->conn->query("
        SELECT DISTINCT date_cours
        FROM horaires
            INNER JOIN cours c on horaires.id_cours = c.id
            INNER JOIN type_cours tc on horaires.id_type_cours = tc.id
        WHERE bloc = ".$bloc.";");
        $array = $result->fetchAll(PDO::FETCH_ASSOC);

        return $array;

    }
    /* recupère les cours par date et tranche horaire*/
    public static function getLessonsByDateAndTrancheHoraire($date, $tranche){

        $date = addslashes(htmlspecialchars($date));

        $db = new Database();
        $result = $db->conn->query("
        SELECT horaires.id, c.intitule, c.bloc, tc.type, horaires.gestion, horaires.indus, horaires.reseau, th.heure_debut, th.heure_fin
        FROM horaires
            INNER JOIN tranches_horaires th on horaires.id_tranche_horaire = th.id
            INNER JOIN cours c on horaires.id_cours = c.id
            INNER JOIN type_cours tc on horaires.id_type_cours = tc.id
        WHERE date_cours = '".$date."' AND th.tranche_horaire = ".$tranche.";");
        $array = $result->fetchAll(PDO::FETCH_ASSOC);

        return $array;

    }

    /* recupère toutes les dates de cours dispo et affichable */
    public static function getAllDateLessons(){

        $db = new Database();
        $result = $db->conn->query("
        SELECT DISTINCT date_cours
        FROM horaires
            INNER JOIN cours c on horaires.id_cours = c.id
            INNER JOIN type_cours tc on horaires.id_type_cours = tc.id
        WHERE 1;");
        $array = $result->fetchAll(PDO::FETCH_ASSOC);

        return $array;

    }


}