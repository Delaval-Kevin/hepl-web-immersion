<!-- ---------------------------------------------------- -->
<!--Auteurs : COLLETTE Loic et DELAVAL Kevin              -->
<!--Groupe : 2203                                         -->
<!--Labo : Programmation Web avancée                      -->
<!--Application : Site d'immersion à l'école              -->
<!--Date de la dernière mise à jour : 05/05/2020          -->
<!-- ---------------------------------------------------- -->


<?php require_once(__DIR__."/../php/require_all.php"); ?>


<html>
<head>
    <title>Immersion HEPL - Gérer</title>
    <?php require_once(__DIR__."/../inc/head.php"); ?>
</head>
<body>
<?php require_once(__DIR__."/../inc/nav_admin.php"); ?>
<section id="content">
    <?php

    redirectIfnotLoggedIn();

    /* Requête SQL pour récupérer la table reçue par le $_GET */

    /* Évite les attaques SQL (securite)  échape --> ' " \ */
    $gerer = addslashes(htmlspecialchars($_GET['gerer']));

    $db = new Database();
    if($gerer == "eleves")
    {
        $result = $db->conn->query("
            SELECT *
            FROM ".$gerer."
            WHERE archive = 0;");
    }
    else
    {
        $result = $db->conn->query("
            SELECT *
            FROM ".$gerer.";");
    }

    if($result == false) /* Si la requête SQl ne donne pas de résultat alors on affiche le message */
    {
        echo("<h1>Erreur lors de la requête</h1>");
    }
    else /* Sinon on effectue le traitement */
    {
        $array = $result->fetchAll(PDO::FETCH_ASSOC);

        /* Requête SQL pour avoir le nom des colonnes */
        $colums = $db->conn->query(" SHOW COLUMNS FROM ".$gerer.";");
        $colname = $colums->fetchAll(PDO::FETCH_ASSOC);

        if($gerer == "eleves") // on retire la colonne archive
        {
            array_pop($colname);
        }

    ?>
    <article id="ajout_modif" class="hidden">
        <?php
        $champs = array();
        foreach ($colname as $col)
        {
            array_push($champs, generateArray($col));
        }

        ?>


        <h2 id="entete_ajout_modif"></h2>

        <?php generateForm($champs); ?>

        <label id="messErr" class="messErr"></label><br>
        <button class="btn btn-success valid" id="send_btn">Valider</button>
        <button class="btn btn-danger annul" id="cancel_btn">Annuler</button>

    </article>
    <article id="table_list">
        <h2 id="entete_gestion"></h2>
        <a class="btn btn-info add-row">Ajouter</a>

        <table class="table table-hover">
            <thead>
            <tr> <!-- Permet d'afficher dans le tableau chaque nom de colonne récupéré -->
                <?php foreach ($colname as $ligne){ ?>
                <th scope="col" id="col_<?php echo($ligne["Field"]); ?>"><?php echo($ligne["Field"]); ?> </th>
                <?php } ?>
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>
                <?php if($gerer == "eleves"){ ?>
                    <th scope="col">Attestation</th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($array as $ligne){ ?>
                <tr class="table"> <!-- Boucle imbriquée pour affiché les valeurs en fonction du nom de la colonne -->
                    <?php foreach ($colname as $ligne2){ ?>
                    <th scope="row" id="<?php echo($ligne["id"]."_".$ligne2["Field"]); ?>"><?php echo($ligne[$ligne2["Field"]]); ?></th>
                    <?php } ?>
                    <th scope="row"><a class="btn btn-success modif"  data-course-id="<?php echo($ligne["id"]); ?>">Modifier</a></th>
                    <th scope="row"><a class="btn btn-danger del"  data-course-id="<?php echo($ligne["id"]); ?>">Supprimer</a></th>
                    <?php if($gerer == "eleves"){ ?>
                        <th scope="col"><a class="btn btn-info" target="_blank" href="./api/export_pdf.php?nom=<?php echo($ligne["nom"]); ?>&prenom=<?php echo($ligne["prenom"]); ?>">Attestation</a></th>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    </article>
    <?php } ?>

</section>
<!-- pour pouvoir récuperer la valeur de $gerer dans la page JS -->
<input type="hidden" id="page" value="<?php echo $gerer; ?>" />
</body>
<!-- type="module" permet de dire que le fichier JS est composé de plusieurs librairies -->
<script type="module" src="./js/gerer.js"></script>
<script>

    $(document).ready(function () {

        $("#col_id, label[for=id]").text("ID");
        $("#col_email, label[for=email]").text("Adresse e-mail");
        $("#col_nom, label[for=nom]").text("Nom");
        $("#col_prenom, label[for=prenom]").text("Prénom");
        $("#col_sexe, label[for=sexe]").text("Sexe");
        $("#col_etablissement, label[for=etablissement]").text("Établissement");
        $("#col_indus, label[for=indus]").text("Industriel");
        $("#col_gestion, label[for=gestion]").text("Gestion");
        $("#col_reseau, label[for=reseau]").text("Réseau");
        $("#col_intitule, label[for=intitule]").text("Intitulé du cours");
        $("#col_bloc, label[for=bloc]").text("Bloc");
        $("#col_local, label[for=local]").text("Local");
        $("#col_type, label[for=type]").text("Type de cours");
        $("#col_heure_debut, label[for=heure_debut]").text("Heure début du cours");
        $("#col_heure_fin, label[for=heure_fin]").text("Heure fin du cours");
        $("#col_tranche_horaire, label[for=tranche_horaire]").text("Tranche horaire");

    });

</script>
</html>