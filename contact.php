<?php
require_once('include/init.php');

$pageTitle = "Formulaire contact";


if(internauteConnecte()){
    header('location:' . URL . 'profil.php');
}


if($_POST){

    if(!isset($_POST['pseudo']) || !preg_match('#^[a-zA-Z0-9-_.]{3,20}$#', $_POST['pseudo'])){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>';
    }

    if(!isset($_POST['nom']) || iconv_strlen($_POST['nom']) < 3 || iconv_strlen($_POST['nom']) > 20 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
    }


    if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format email !</div>';
    }

    if(!isset($_POST['message']) || !preg_match('#^[a-zA-Z0-9-_.]{3,200}$#', $_POST['message'])){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format message !</div>';
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
    $inscrireUser = $pdo->prepare(" INSERT INTO membre( pseudo,  nom,  email, message,  date_enregistrement, statut) VALUES (:pseudo,  :nom, :email, :message,  NOW(), 2 ");
    $inscrireUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $inscrireUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
    $inscrireUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $inscrireUser->bindValue(':message', $_POST['message'], PDO::PARAM_STR);

    $inscrireUser->execute();


header('location'. URL . 'connexion.php?action=validate');



}



}

require_once('include/header.php');
?>




<section id="contact" class="bg-light my-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <h2>Contactez-nous</h2>
        <hr class="my-4">
        <form action="contact.php" method="post" role="form">
          <div class="form-floating mb-3">
          <label class="form-label" for="nom">Nom</label>
			<input class="form-control" type="text" name="nom" id="nom" placeholder="Votre nom">
          </div>
          <div class="form-floating mb-3">
          <label class="form-label" for="email">Adresse e-mail</label>
			<input class="form-control" type="email" name="email" id="email" placeholder="Votre email" required>
          </div>
          <div class="form-floating mb-3">
            <textarea class="form-control" id="message" name="message" placeholder="Message" style="height: 10rem;" required></textarea>
            <label for="message">Message</label>
          </div>
          <div class="d-grid">
            <button class="btn btn-dark btn-lg" type="submit">Envoyer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once('include/footer.php') ?>