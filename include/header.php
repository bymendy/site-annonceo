<?php

// requete pour afficher les onglets public (enfant, femme etc... par ordre alphabétique) dans la barre de navigation
// DISTINCT permet de n'afficher qu'une seule fois l'onglet, sinon, il sera affiché pour autant de produits concernés par ce public

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- favicon -->
    <link rel="icon" type="image/png" href="logo-annonceo.png" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <script src="https://kit.fontawesome.com/896637ab26.js" crossorigin="anonymous"></script>


         <!-- links pour les icon bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

  <!-- code pour récupérer le nom de chaque page de manière dynamique on declare pour chaque fichier, une valeur à pageTitle
  Dans le cas de la page d'accueil/index, impossible d'avoir une valeur si on a cliqué sur rien, donc on ne peut pas déclarer dans index.php une valeur unique. Cela empecherait d'avoir un onglet dynamiqu si on veut afficher les manteaux, ou les vestes etc...
  Pour résoudre ce problème, on dit que si pageTitle existe (dans un fichier), on affiche sa valeur, si elle n'existe pas, on affiche La Boutique -->
    <title><?= (isset($pageTitle) ? $pageTitle : "Annonceo") ?></title>
</head>
<body>

<header>

<!-- ------------------- -->

<nav class="navbar navbar-expand-lg navbar-black gray-100">
  <a class="navbar-brand" href="<?= URL ?>"><img src="<?= URL ?>logo_annonceo.png"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <ul class="navbar-nav mr-auto">
      <li class="nav-item mt-2">
      <a><button class="btn btn-outline-dark" data-toggle="modal" id="model" data-target="#connexionModal">Déposer une annonce<?= (isset($_SESSION['membre'])) ? '<style>#model{ display: none; }</style>' : ''; ?></button> </a>
      </li>
      <!-- ---------- -->
    </ul>
    <ul class="navbar-nav ml-auto">
      <!-- -------------------------- -->
    <?php if(internauteConnecte()): ?>
      <!-- si l'internaute est connecté il aura accés aux pages profil, panier et un bouton de deconnexion  (mais pas aux autres) -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle btn btn-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-dark">Espace <strong><?= $_SESSION['membre']['pseudo'] ?></strong></button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>profil.php">Profil <?= $_SESSION['membre']['pseudo'] ?></a>
     

          <a class="dropdown-item" href="<?= URL ?>connexion.php?action=deconnexion">Déconnexion</a>
        </div>
      </li>
    <?php else: ?>
      <!-- ---------------------------- -->
      <!-- si il n'est pas connecté, il aura droit aux pages inscription, connexion et panier (mais pas aux autres)-->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle mr-5" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-dark">Espace Membre</button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>inscription.php"><button class="btn btn-outline-dark">Inscription</button></a>
          <a class="dropdown-item" href="<?= URL ?>connexion.php"><button class="btn btn-outline-dark px-4">Connexion</button></a>

          <a class="dropdown-item" href="<?= URL ?>contact.php"><button class="btn btn-outline-dark px-4">Contact</button></a>
        </div>
      </li>
      <?php endif; ?>
    
     <!-- ------------------------------------ -->
     <!-- le bouton admin n'apparaitra que pour un utilisateur qui a les droits d'admin -->
    <?php if(internauteConnecteAdmin()): ?>
      <li class="nav-item mr-5">
          <a class="nav-link" href="admin/index.php"><button type="button" class="btn btn-dark">Admin</button></a>
      </li>
    <?php endif; ?>

      <!-- ------------------------------------ -->
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-dark my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>

</header>

<div class="container">
          <!-- Modal -->
          <div class="modal fade" id="connexionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title " id="exampleModalLabel"><img src="<?= URL ?>logo_annonceo.png ">Bonjour !  <br> Connectez-vous ou créez un compte pour déposer votre annonce </h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center">
                <a class="dropdown-item" href="<?= URL ?>inscription.php"><button class="btn btn-outline-dark">Créer un compte</button></a>
                <a class="" href="<?= URL ?>connexion.php"><button class="btn btn-outline-dark px-4">Me connecter</button></a>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                </div>
              </div>
            </div>
          </div>
          <!-- ------------- -->

<h1 class="text-center mt-5"><div class="badge badge-dark text-wrap p-3">Annonceo </div></h1>
<h2 class="text-center pb-5">Le meilleur site d'annonce en France !</h2>