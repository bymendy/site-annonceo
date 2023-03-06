<?php
require_once('include/init.php');

$pageTitle = "Connexion";

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
    unset($_SESSION['membre']);
    header('location:' . URL . 'connexion.php');
    exit();
}
if(internauteConnecte()){
    header('location:' . URL . 'profil.php');
    // ce exit est une sécurité supplémentaire par rapport à la redirection
    // le exit empeche l'exécution du code qui suit (code inaccessible au hacker, même à partir d'une autre page (code injecté vie URL))
    exit();
}

// condition a mettre obligatoirement pour éviter un undefined key $action (si lapersonne veut se connecter sans passer par la phase inscription)
if(isset($_GET['action']) && $_GET['action'] == 'validate' ){
$validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <strong>Félicitations !</strong> Votre inscription est réussie 😉, vous pouvez vous connecter !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}

if($_POST){

    // requete qui va comparer le pseudo entré dans le champs du form avec les infos en BDD (Ce pseudo existe t-il ?)
    $verifPseudo = $pdo->prepare(" SELECT * FROM membre WHERE pseudo = :pseudo ");
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifPseudo->execute();

    // si un même pseudo existe en BDD (rowCount == 1), alors on continue la procédure d'authentification
    if($verifPseudo->rowCount() == 1){
        // on fait un fetch pour récupérer toutes les valeurs de cette entrée en BDD qui à le même pseudo
        $user = $verifPseudo->fetch(PDO::FETCH_ASSOC);
        // si le mot de passe correspond, on authentifie
        // password_verify est une fonction prédéfinie qui permet de comparer le mdp en BDD hashé, avec le vrai mdp du user (elle va déhasher le mdp en BDD)
        if(password_verify($_POST['mdp'], $user['mdp'])){
            // les deux mots de passe correspondent, on crée une session utilisateur qui va enregistrer toutes les infos le concernant, il en aura besoin sur le site
            foreach($user as $key => $value){
                // on récupère toutes les infos en BDD sauf son mot de passe, dangeureux et inutile
                if($key != 'mdp'){
                    // boucle qui permet de ne pas taper toutes les lignes en dessous
                    $_SESSION['membre'][$key] = $value;
                    // $_SESSION['membre']['id_membre'] = $user['id_membre'];
                    // $_SESSION['membre']['pseudo'] = $user['pseudo'];
                    // $_SESSION['membre']['nom'] = $user['nom'];
                    // $_SESSION['membre']['prenom'] = $user['prenom'];
                    // $_SESSION['membre']['email'] = $user['email'];
                    // $_SESSION['membre']['civilite'] = $user['civilite'];
                    // $_SESSION['membre']['ville'] = $user['ville'];
                    // $_SESSION['membre']['code_postal'] = $user['code_postal'];
                    // $_SESSION['membre']['adresse'] = $user['adresse'];
                    // $_SESSION['membre']['statut'] = $user['statut'];

                    // une fois qu'il s'est authentifié et crée la session['membre'], on fait nos redirections UX (expérience utilisateur)
                    // premier cas de redirection, le user est l'admin du site, on l'envoie vers le back-office
                    if(internauteConnecteAdmin()){
                        header('location:' . URL . 'admin/index.php?action=validate' );
                        // deuxième cas de figure, il arrive de la page panier car il doit etre connecté pour acheter. Une fois connecté, on le renvoie vers le panier
                    }elseif(isset($_GET['action']) && $_GET['action'] == 'acheter'){
                        header('location:' . URL . 'panier.php' );
                    }else{
                        // dernier cas de figure, ni il est admin, ni il arrive du panier, on l'envoie vers son profil
                        header('location:' . URL . 'profil.php?action=validate' );
                    }
                    
                }
            }
        }else{
            // si le mot de passe ne correspond pas, message d'erreur
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur ce mot de passe ne correspond pas !</div>';
        }
    }else{
        // si le pseudo n'est pas référencé en BDD, on en avertit l'utilisateur
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur ce pseudo n\'existe pas, vérifiez !<br> Etes vous inscrit ?</div>';
    }

}

require_once('include/header.php');
?>

<h2 class="text-center py-5"><div class="badge badge-dark text-wrap p-3">Connexion</div></h2>

<?= $validate ?>


<form class="my-5" method="POST" action="">

<div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="card">
          <div class="card-body">
            <h3 class="text-center mb-4">Se connecter</h3>
              <div class="form-group">
              <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
                <input class="form-control btn btn-outline-success mb-4" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo">
              </div>
              <div class="form-group">
              <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
                <input class="form-control btn btn-outline-success mb-4" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe">
              </div>
              <button type="submit" class="btn btn-primary btn-block">Connexion</button>
          </div>
        </div>
      </div>
    </div>
  </div>



    
</form>

<?php require_once('include/footer.php') ?>