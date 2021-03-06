<!-- ---------------------------------------------------- -->
<!--Auteurs : COLLETTE Loic et DELAVAL Kevin              -->
<!--Groupe : 2203                                         -->
<!--Labo : Programmation Web avancée                      -->
<!--Application : Site d'immersion à l'école              -->
<!--Date de la dernière mise à jour : 12/05/2020          -->
<!-- ---------------------------------------------------- -->


<?php require_once(__DIR__."/../php/require_all.php"); ?>


<html>
<head>
    <title>Immersion HEPL - Afficher</title>
    <?php require_once(__DIR__."/../inc/head.php"); ?>
</head>
<body>
<?php require_once(__DIR__."/../inc/nav_admin.php"); ?>
    <section id="content">
        <?php

        redirectIfnotLoggedIn();

        $db = new Database();

            /* Requête SQL pour avoir le nom des colonnes */
            $colums = $db->conn->query(" SHOW COLUMNS FROM eleves_horaires;");
            $colname = $colums->fetchAll(PDO::FETCH_ASSOC);

            $champs = array();
            foreach ($colname as $col)
            {
                array_push($champs, generateArray($col));
            }
            /* pour conserver juste le champs qui nous intéresse */
            array_shift($champs); //'pop' le premier élément
            array_shift($champs);
            $champs[0]["label"] = "Sélectionner un élève";
            ?>
            <article>
                <h2>Horaire des élèves</h2>
                <br><a class="btn btn-success" target="_blank" href="./api/export_csv.php">Export CSV (tout les élèves)</a><br>
                <?php $champ = $champs[0]; ?>
                <div class="form-group">
                    <label class="col-form-label" for="<?php echo($champ["id"]); ?>"><?php echo($champ["label"]); ?></label>
                    <select id="id_eleves" name="<?php echo($champ["name"]); ?>" class="custom-select">
                        <option selected="" value="0" disabled>Veuillez selectionner une option</option>
                        <?php foreach($champ["options"] as $option){ ?>
                            <option value="<?php echo($option["value"]); ?>"><?php echo($option["text"]); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br><br><br>
                <h4 id="nomEleve"></h4>
                <div id="tables">

                </div>
            </article>
    </section>
</body>
<template id="templateTable">
    <table class="table table-hover" style="display: inline">
        <thead>
        <tr>
            <th colspan="4" class="date">La Date</th>
        </tr>
        </thead>
        <tbody class="tbody">

        </tbody>
    </table>
</template>
<!-- type="module" permet de dire que le fichier JS est composé de plusieurs librairies -->
<script type="module" src="./js/affiche.js"></script>
</html>