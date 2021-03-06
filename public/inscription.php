<?php
/***********************************************************/
/*Auteurs : COLLETTE Loic et DELAVAL Kevin                 */
/*Groupe : 2203                                            */
/*Labo : Programmation Web avancée                         */
/*Application : Site d'immersion à l'école                 */
/*Date de la dernière mise à jour : 28/04/2020             */
/***********************************************************/

require_once(__DIR__."/php/require_all.php");
@session_start();
if(!isset($_SESSION["currJour"])){

    // si les la clé dans l'url GET 'nbDay' n'existe pas OU
    // que la valeur de la clé 'nbDay' est vide (ou = à 0)
    // todo: redirige l'utilisateur vers la page precédente + msg erreur

    die();

}else if($_SESSION["currJour"] < 0){

    // si le nombre de jour que l'utilisateur à entré est plus petit ou égal à zéro
    // idem todo plus haut
    die("?????");

}else if($_SESSION["currJour"] >= $_SESSION["jours"]){
    header("Location: /enregistrement.php");
    die();
}
$afficheJour = $_SESSION["currJour"] + 1;;

?>


<html>
<head>
    <title>Immersion HEPL - Accueil</title>
    <?php require_once(__DIR__."/inc/head.php"); ?>

</head>
<body>

<?php require_once(__DIR__."/inc/nav.php"); ?>

<section id="content">

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Inscription</a></li>
        <li class="breadcrumb-item active">Jour <?php echo($afficheJour); ?></li>
    </ol>


    <div class="row">
        <div class="col-xl-12">
            <div id="block-contentJour">

                <fieldset>
                    <legend>
                        Inscription - Jour <?php echo($afficheJour);  ?>
                    </legend>

                    <?php $champs = array(
                        array(
                            "label" => "Choix du jour ".$afficheJour,
                            "type" => "select",
                            "name" => "dateJour",
                            "id" => "dateJour",
                            "options" => [],
                        ),

                    );

                    /* todo, à changer lorsqu'on aura + que un champ généré.. ou alors le champ de
                        la date devra rester le premier truc généré
                    */


                    foreach (Horaire::getAllDateLessons() as $date){
                        if($_SESSION["data_jours"][$afficheJour-1]["date"] != "" || !dateAlreadyChosen($date["date_cours"])){
                            array_push($champs[0]["options"], array(
                                "value" => $date["date_cours"],
                                "text" => strftime("%A %d %B %G", strtotime($date["date_cours"]))
                            ));
                        }

                    }

                    generateForm($champs);
                    ?>

                </fieldset>

            </div>
        </div>
    </div>


    <!-- todo:  previsualiser ses choix ici -->
    <h2>Votre selection</h2>
    <div>
        <?php for($i = 1; $i <= $_SESSION["jours"];$i++){ ?>
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Jour <?php echo($i) ?>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item"><?php $date = $_SESSION["data_jours"][$i-1]["date"]; if($date != ""){ echo($date); } else{ echo("Aucune date choisie"); } ?></a>
                    <div class="dropdown-divider"></div>
                    <?php for($j = 0; $j < 4; $j++){ ?>
                        <?php $idHoraire = $_SESSION["data_jours"][$i-1]["cours"][$j]; ?>
                        <?php if($idHoraire != 0){ ?>
                            <a class="dropdown-item"><?php echo(Horaire::getLabelById($idHoraire)); ?></a>
                        <?php }else{ ?>
                            <a class="dropdown-item">Aucun cours</a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <br />
    <!-- section des cours a afficher  -->

    <div class="row">
        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
            <h3>Horaire 1</h3>
            <div id="cours-tranche-1">
                <div class="list-group" id="liste-cours-horaire-tranche-1">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6">
            <h3>Horaire 2</h3>
            <div id="cours-tranche-2">
                <div class="list-group" id="liste-cours-horaire-tranche-2">
                </div>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-12 col-md-6 col-lg-6">
            <h3>Horaire 3</h3>
            <div id="cours-tranche-3">
                <div class="list-group" id="liste-cours-horaire-tranche-3">
                    <?php if($_SESSION["jours"] > 1){ ?>
                    <a href="#" data-cours-id="0" data-cours-name="Pas de cours" data-tranche="3" class="list-group-item list-group-item-action clickable tranche3">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Pas de cours</h5>
                        </div>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h3>Horaire 4</h3>
            <div id="cours-tranche-4">
                <div class="list-group" id="liste-cours-horaire-tranche-4">
                    <a href="#" data-cours-id="0" data-cours-name="Pas de cours" data-tranche="4" class="list-group-item list-group-item-action clickable tranche4">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Pas de cours</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">
            <div id="cours-buttons" class="center-elements">
                <a class="btn btn-warning" id="prev_button">
                    <?php if($_SESSION["currJour"] == 0){ ?>Retour<?php } else {?>Précédent<?php } ?>
                </a>
                <a class="btn btn-success" id="next_button">
                    Suivant
                </a>
            </div>
        </div>
    </div>

    <script>

        var nbJours = <?php echo($_SESSION["jours"]); ?>;
        var currJour = <?php echo($_SESSION["currJour"]+1); ?>

        var cours1 = 0;
        var cours2 = 0;
        var cours3 = 0;
        var cours4 = 0;
        var dateChoisie = "";

    </script>
    <script  type="module" src="/js/inscription.js"></script>



</section>
</body>

</html>

