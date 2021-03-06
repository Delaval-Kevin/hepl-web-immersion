/***********************************************************/
/*Auteurs : COLLETTE Loic et DELAVAL Kevin                 */
/*Groupe : 2203                                            */
/*Labo : Programmation Web avancée                         */
/*Application : Site d'immersion à l'école                 */
/*Date de la dernière mise à jour : 11/05/2020             */
/***********************************************************/

import * as requeteAjax from "/js/requeteAjax.js"

/* Fonction pour récupérer le GET */
function $_GET(param){
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if ( param ) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}

export function ajouter(tableau){
    tableau.action = "add";

    let table = $_GET('gerer');

    console.log(table);

    function successCallback(result){
        if(result.error === false){
            toastr["success"](result.message, "Succès");

            location.reload();
        } else {
            toastr["warning"](result.message, "Attention");
        }
    }

    requeteAjax.requeteAjax("POST", "/admin/api/gerer_"+table+".php", tableau, "json", successCallback, null, null);
}

export function modifier(tableau){
    tableau.action = "modif";

    let table = $_GET('gerer');

    function successCallback(result){
        if(result.error === false){
            toastr["success"](result.message, "Succès");

            for( var tab in tableau)
            {
                $('#'+tableau['id']+"_"+tab).text(tableau[tab]);
            }

        } else {
            toastr["warning"](result.message, "Attention");
        }
    }

    requeteAjax.requeteAjax("POST", "/admin/api/gerer_"+table+".php", tableau, "json", successCallback, null, null);
}

export function supprimer(id){
    let tableau = {
        action: "delete",
        id:id,
    };

    let table = $_GET('gerer');

    function successCallback(result){
        if(result.error === false){
            toastr["success"](result.message, "Succès");

            location.reload();
        } else {
            toastr["warning"](result.message, "Attention");
        }
    }

    requeteAjax.requeteAjax("POST", "/admin/api/gerer_"+table+".php", tableau, "json", successCallback, null, null);
}

export function visible(id){
    let tableau = {
        action: "visible",
        id:id,
    };

    let table = $_GET('gerer');

    function successCallback(result){
        if(result.error === false){
            toastr["success"](result.message, "Succès");

            if($('#'+tableau['id']+"_visible").text() == 'Oui') {
                $('#' + tableau['id'] + "_visible").text("Non");
            } else {
                $('#'+tableau['id']+"_visible").text("Oui");
            }

        } else {
            toastr["warning"](result.message, "Attention");
        }
    }

    requeteAjax.requeteAjax("POST", "/admin/api/gerer_"+table+".php", tableau, "json", successCallback, null, null);

}

/* Fonction qui vérifie si le formulaire est bien rempli */
export function formValid(){

    let inputs = $('input[type=text]');                       // on récupere les differents types d'input text du formulaire
    for( let input of inputs){                                // boucle pour remplir les champs texte

        let index = (input.id).indexOf("heure");
        if(index !== -1){
            let value = input.value;
            let re = /^[0-9h0-9]+$/;

            if(re.test(value) === false)
            {
                return false;
            }
        } else {
            let value = input.value;
            let re = /^[A-Za-z]+$/;

            if(re.test(value) === false)
            {
                return false;
            }
        }

    }

    inputs = $('input[type=email]');                          // on récupere les differents types d'input email du formulaire
    for( let input of inputs){                                // boucle pour vider les champs email

        let email = input.value;
        let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if(re.test(email) === false)
        {
            return false;
        }
    }

    inputs = $('input[type=number]');                       // on récupere les differents types d'input number du formulaire
    for( let input of inputs){                                // boucle pour vider les champs number

        let value = input.value;
        let re = /^[0-9]+$/;

        if(re.test(value) === false)
        {
            return false;
        }
    }

    return true;
}

/* Fonction qui vide le formulaire  */
export function initForm(){
    let inputs = $('input[type=text]');                       // on récupere les differents types d'input text du formulaire
    for( let input of inputs){                                // boucle pour remplir les champs texte
        input.value = "";
    }

    inputs = $('input[type=email]');                          // on récupere les differents types d'input email du formulaire
    for( let input of inputs){                                // boucle pour vider les champs email
        input.value = "";
    }

    inputs = $('input[type=number]');                       // on récupere les differents types d'input number du formulaire
    for( let input of inputs){                                // boucle pour vider les champs number
        input.value = "";
    }

    inputs = $('input[type=date]');                          // on récupere les differents types d'input date du formulaire
    for( let input of inputs){                                // boucle pour vider les champs date
        input.value = "";
    }

    inputs = $('input[type=radio]');                          // on récupere les differents types d'input radio du formulaire
    for( let input of inputs){                                // boucle pour les déssélectionner
        input.checked = false;
    }

    inputs = $('select');                                     // on récupere les differents types select du formulaire
    for( let input of inputs){                                // boucle pour mettre la valeur par defaut
        $(input).val($("#"+ input.id +" option:first").val());
    }
}

/* Fonction qui permet de récupérer les données de */
/* l'eleve et de remplir le formulaire avec  */
export function remplirForm(id){
    let tableau = {
        action: "get",
        id:id,
    };

    let table = $_GET('gerer');

    function successCallback(result){
        if(result.error === false){
            toastr["success"](result.message, "Succès");

            let inputs = $('input[type=text]');                       // on récupere les differents types d'input text du formulaire
            for( let input of inputs){                                // boucle pour remplir les champs texte
                input.value = result.data[input.name];
            }

            inputs = $('input[type=email]');                          // on récupere les differents types d'input email du formulaire
            for( let input of inputs){                                // boucle pour remplir les champs email
                input.value = result.data[input.name];
            }

            inputs = $('input[type=number]');                       // on récupere les differents types d'input number du formulaire
            for( let input of inputs){                                // boucle pour remplir les champs number
                input.value = result.data[input.name];
            }

            inputs = $('input[type=date]');                          // on récupere les differents types d'input date du formulaire
            for( let input of inputs){                                // boucle pour remplir les champs date
                input.value = result.data[input.name];
            }

            inputs = $('input[type=radio]');                          // on récupere les differents types d'input radio du formulaire
            for( let input of inputs){                                // boucle pour check le bon bouton radio
                if(input.value === result.data[input.name])
                    input.checked = true;
            }

            inputs = $('select');                                     // on récupere les differents types select du formulaire
            for( let input of inputs){                                // boucle pour check le bon choix
                let options = $(input).find('option');

                for( let option of options){
                    if(option.value === result.data[input.name])
                        option.selected = true;
                }
            }
        } else {
            toastr["warning"](result.message, "Attention");
        }
    }

    requeteAjax.requeteAjax("POST", "/admin/api/gerer_"+table+".php", tableau, "json", successCallback, null, null);

}

export function depeleves(id){
    let tableau = {
        action: "depeleves",
        id:id,
    };

    let table = $_GET('gerer');

    function successCallback(result){
        if(result.error === false){
            toastr["success"](result.message, "Succès");

            let url="afficheElevesDep.php?tab="+encodeURI(JSON.stringify(result.eleves));
            window.open(url,"PopUp",
                "width=700,height=600,location=no,status=no,toolbar=no,scrollbars=no");

            location.reload();

        } else {
            toastr["warning"](result.message, "Attention");
        }
    }

    requeteAjax.requeteAjax("POST", "/admin/api/gerer_"+table+".php", tableau, "json", successCallback, null, null);
}
