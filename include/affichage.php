<?php
// affichage des catégories dans la navigation latérale
$afficheMenuCategories = $pdo->query(" SELECT * FROM categorie ORDER BY titre ");
// fin de navigation laterale catégories

// tout l'affichage par categorie
if(isset($_GET['categorie'])){
    // pagination pour les categories
    
    // fin pagination pour les categories

    // affichage de tous les annonces concernés par une categorie
    $afficheAnnonces = $pdo->query(" SELECT * FROM annonce WHERE categorie_id = '$_GET[categorie]' ORDER BY prix ASC ");
    // fin affichage des annonces par categorie

    // affichage de la categorie dans le <h2>
    $afficheTitreCategorie = $pdo->query(" SELECT titre FROM categorie WHERE id_categorie = '$_GET[categorie]' ");
    $titreCategorie = $afficheTitreCategorie->fetch(PDO::FETCH_ASSOC);
    // fin du h2 categorie

    // pour les onglets categories
    $pageTitle = "Nos modèles de " . $_GET['categorie'];
    // fin onglets categories
}
// fin affichage par categorie

// -----------------------------------------------------------------------------------

// tout l'affichage par annonce
if(isset($_GET['annonce'])){
    // pagination annonces par annonce
    
    // fin pagination annonces par annonce

    // affichage des annonces par annonce
    // requete qui va cibler tous les annonces qui ont en commun le annonce récupéré dans l'URL
    $afficheAnnonces = $pdo->query(" SELECT * FROM annonce WHERE titre = '$_GET[titre]' ORDER BY prix ASC ");
    // fin affichage des annonces par annonce

    // affichage de l'annonce dans le <h2>
    $afficheTitreAnnonce = $pdo->query(" SELECT titre FROM annonce WHERE titre = '$_GET[titre]' ");
    $titreAnnonce = $afficheTitreAnnonce->fetch(PDO::FETCH_ASSOC);
    // fin du </h2> pour le annonce

    // pour les onglets annonces
    $pageTitle = "Nos vetements " . ucfirst($_GET['annonce']) . 's'; 
    // fin onglets annonces
}
// fin affichage par annonce

// ---------------------------------------------------------------------------------------
// Tout ce qui concerne la fiche annonce

// affichage d'une annonce
if(isset($_GET['id_annonce'])){
    $detailAnnonce = $pdo->query(" SELECT * FROM annonce WHERE id_annonce = '$_GET[id_annonce]' ");
    // pour se protéger de qlq'un qui tenterait de modifier l'id-annonce dans l'URL...si la valeur n'existe pas en BDD, on le redirige vers notre index (URL). Le <= 0 est fait dans le cas ou il injecte une valeur négative
    if($detailAnnonce->rowCount() <= 0){
        header('location:' . URL);
        exit;
    }
    // si on n'est pas rentré dans la condition, si le annonce existe, on fait le fetch, et le resultat de la requete sera affecté dans la variable/tableau $detail
    $detail = $detailAnnonce->fetch(PDO::FETCH_ASSOC);
}
// fin affichage d'un seul annonce


//  fin fiche annonce

// --------------------------------------------------------------------------------------------