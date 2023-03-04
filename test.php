<?php
require_once('include/init.php');

$pageTitle = "Formulaire d'inscription";


if(internauteConnecte()){
    header('location:' . URL . 'profil.php');
}


if($_POST){

    
    if(!isset($_POST['pseudo']) || !preg_match('#^[a-zA-Z0-9-_.]{3,20}$#', $_POST['pseudo'])){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>';
    }

    
    if(!isset($_POST['mdp']) || strlen($_POST['mdp']) < 3 || strlen($_POST['mdp']) > 20 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mdp !</div>';
    }


    if(!isset($_POST['nom']) || iconv_strlen($_POST['nom']) < 3 || iconv_strlen($_POST['nom']) > 20 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
    }

    if(!isset($_POST['prenom']) || iconv_strlen($_POST['prenom']) < 3 || iconv_strlen($_POST['prenom']) > 20 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prénom !</div>';
    }


    if(!isset($_POST['telephone']) || !preg_match('#^[0-9]{10}$#', $_POST['telephone'])){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format téléphone !</div>';
    }

    if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format email !</div>';
    }


    if(!isset($_POST['civilite']) || $_POST['civilite'] != 'femme' && $_POST['civilite'] != 'homme' ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format civilité !</div>';
    }




   
$verifPseudo = $pdo->prepare('SELECT pseudo FROM membre WHERE pseudo = :pseudo');
$verifPseudo-> bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
$verifPseudo->execute();

//verif pseudo
if($verifPseudo->rowCount() == 1){
    $erreur .= '<div class="alert alert-danger" role="alert">Erreur ce pseudo existe déjà, vous devez en choisir un autre !</div>';
}

$_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

if(empty($erreur)){
    $inscrireUser = $pdo->prepare(" INSERT INTO membre(pseudo, mdp, nom, prenom, telephone, email, civilite, date_enregistrement, statut) VALUE (:pseudo, :mdp, :nom, :prenom,:telephone, :email, :civilite, NOW(), 2 ");
    $inscrireUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $inscrireUser->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
    $inscrireUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
    $inscrireUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
    $inscrireUser->bindValue(':telephone', $_POST['telephone'], PDO::PARAM_INT);
    $inscrireUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $inscrireUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
    $inscrireUser->execute();


header('location'. URL . 'connexion.php?action=validate');



}



}

require_once('include/header.php');
?>


<h2 class="text-center py-5"><div class="badge badge-dark text-wrap p-3">Inscription</div></h2>

<?= $erreur ?>



<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>'; -->

<form class="my-5" method="POST" action="">

<div class="container mt-5">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<h2 class="mb-4">Formulaire d'inscription</h2>
				<form>
                <div class="col-md-4 mt-5 pt-2">
              <p><div class="badge badge-dark text-wrap">Civilité</div></p> 
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="civilite" id="civilite1" value="femme">
                <label class="form-check-label mx-2" for="civilite1">Femme</label>
            </div>

      

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="civilite" id="civilite2" value="homme" checked>
                <label class="form-check-label mx-2" for="civilite2">Homme</label>
            </div>
        </div>                    

					<div class="form-group">
						<label class="form-label" for="pseudo">Pseudo</label>
						<input class="form-control" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" max-length="20" pattern="[a-zA-Z0-9-_.]{3,20}" title="caractères acceptés: majuscules et minuscules, chiffres, signes tels que: - _ . , entre trois et vingt caractères." required>
					</div>

					<div class="form-group">
						<label class="form-label" for="mdp">Mot de passe</label>
						<input class="form-control" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe" required>
					</div>

					<div class="form-group">
						<label class="form-label" for="nom">Nom</label>
						<input class="form-control" type="text" name="nom" id="nom" placeholder="Votre nom">
					</div>

					<div class="form-group">
						<label class="form-label" for="prenom">Prénom</label>
						<input class="form-control" type="text" name="prenom" id="prenom" placeholder="Votre prénom">
					</div>

					<div class="form-group">
						<label  class="form-label" for="telephone">Téléphone</label>
						<input class="form-control" type="text" name="telephone" id="telephone" placeholder="telephone">
					</div>

					<div class="form-group">
						<label class="form-label" for="email">Adresse e-mail</label>
						<input class="form-control" type="email" name="email" id="email" placeholder="Votre email" required>
					</div>
					<button type="submit" class="btn btn-dark">S'inscrire</button>

				</form>
			</div>
		</div>
	</div>
    
</form>

<?php require_once('include/footer.php') ?>