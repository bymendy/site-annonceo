<?php
require_once('../include/init.php');

if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'connexion.php');
    exit();
}
// pagination selon les annonces

// si un indice page existe dans l'url et qu'on retrouve une valeur dedans
if(isset($_GET['page']) && !empty($_GET['page'])){
    $pageCourante = (int) strip_tags($_GET['page']);
}else{
    // dans le cas ou aucune information n'a transité dans l'URL, $pageCourante prendra la valeur de defaut qui est 1
    $pageCourante = 1;
}
// Faire une variable listeCategorie et appliquer la requete SQL
$listeCategorie = $pdo->query("SELECT * FROM categorie");

$queryAnnonces = $pdo->query("SELECT COUNT(id_annonce) AS nombreAnnonces FROM annonce" );
$resultatAnnonces = $queryAnnonces->fetch();
$nombreAnnonces = (int) $resultatAnnonces['nombreAnnonces'];
// je veux que sur chaque page s'affiche 10 annonces
$parPage =  10; 
$nombrePages = ceil($nombreAnnonces / $parPage);
//  definir la premiere annonce qui va s'afficher à chaque nouvelle page
$premierAnnonce = ($pageCourante - 1) * $parPage;

// fin pagination
$description_courte = "";
$photoActuelle = "";

$photoBdd1 = "";
$photoBdd2 = "";
$photoBdd3 = "";
$photoBdd4 = "";
$photoBdd5 = "";
// ************ CONTRAINTE ************
// 1ére contrainte
if (isset($_GET['action'])) {
// tous ce qui va concernée l'envoie en base de donnée
    if ($_POST) {
// Les contraintes pour chaque champs

        if (!isset($_POST['categorie'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format categorie !</div>';
        }
        if (!isset($_POST['titre']) || iconv_strlen($_POST['titre']) < 3 || iconv_strlen($_POST['titre']) > 20) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
        }
        if (!isset($_POST['description_courte']) || iconv_strlen($_POST['description_courte']) < 3 || iconv_strlen($_POST['description_courte']) > 500) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description !</div>';
        }
        if (!isset($_POST['description_longue']) || iconv_strlen($_POST['description_longue']) < 3 || iconv_strlen($_POST['description_longue']) > 1000) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description_longue !</div>';
        }

        if (!isset($_POST['pays']) || strlen($_POST['pays']) < 2 || strlen($_POST['pays']) > 30) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pays !</div>';
        }        
        if (!isset($_POST['ville']) || strlen($_POST['ville']) < 2 || strlen($_POST['ville']) > 30) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format ville !</div>';
        }

        if (!isset($_POST['code_postal']) || !preg_match('#^[0-9]{5}$#', $_POST['code_postal'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format code postal !</div>';
        }

        if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 5 || strlen($_POST['adresse']) > 50) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>';
        }         
        if (!isset($_POST['prix']) || !preg_match('#^[a-zA-Z0-9-_.]{1,5}$#', $_POST['prix'])) {
            $erreur .= '<div class="alert alert-danger" role="alert"> Le prix ne peut être vide !</div>';
        }

        // ***  Traitement pour la photo
        $photoBdd = (!empty($_POST['photoActuelle'])) ? $_POST['photoActuelle'] : "";
        // if($_GET['action'] == 'update'){
        //     $photoBdd = $_POST['photoActuelle'];
        // }

        if(!empty($_FILES['photo']['name'])){
            $photo_nom = uniqid() . '_' . $_FILES['photo']['name'];
            $photoBdd = "$photo_nom";
            $photoDossier = RACINE_SITE . "img/$photo_nom";
            if(is_uploaded_file($_FILES['photo']['tmp_name']) && file_exists(RACINE_SITE . "img/")){
                copy($_FILES['photo']['tmp_name'], $photoDossier);
            } else {
                echo "Une erreur est survenue lors du téléchargement du fichier.";
            }
        }
        // A VOIR POUR LES 5 AUTRES PHOTOS

        if(!empty($_FILES['photo1']['name'])){
            $photo_nom = uniqid() . '_' . $_FILES['photo1']['name'];
            $photoBdd1 = "$photo_nom";
            $photoDossier = RACINE_SITE . "img/$photo_nom";
            if(is_uploaded_file($_FILES['photo1']['tmp_name']) && file_exists(RACINE_SITE . "img/")){
                copy($_FILES['photo1']['tmp_name'], $photoDossier);
            } else {
                echo "Une erreur est survenue lors du téléchargement du fichier.";
            }
        }
        if(!empty($_FILES['photo2']['name'])){
            $photo_nom = uniqid() . '_' . $_FILES['photo2']['name'];
            $photoBdd2 = "$photo_nom";
            $photoDossier = RACINE_SITE . "img/$photo_nom";
            if(is_uploaded_file($_FILES['photo2']['tmp_name']) && file_exists(RACINE_SITE . "img/")){
                copy($_FILES['photo2']['tmp_name'], $photoDossier);
            } else {
                echo "Une erreur est survenue lors du téléchargement du fichier.";
            }
        }
        if(!empty($_FILES['photo3']['name'])){
            $photo_nom = uniqid() . '_' . $_FILES['photo3']['name'];
            $photoBdd3 = "$photo_nom";
            $photoDossier = RACINE_SITE . "img/$photo_nom";
            if(is_uploaded_file($_FILES['photo3']['tmp_name']) && file_exists(RACINE_SITE . "img/")){
                copy($_FILES['photo3']['tmp_name'], $photoDossier);
            } else {
                echo "Une erreur est survenue lors du téléchargement du fichier.";
            }
        }
        if(!empty($_FILES['photo4']['name'])){
            $photo_nom = uniqid() . '_' . $_FILES['photo4']['name'];
            $photoBdd4 = "$photo_nom";
            $photoDossier = RACINE_SITE . "img/$photo_nom";
            if(is_uploaded_file($_FILES['photo4']['tmp_name']) && file_exists(RACINE_SITE . "img/")){
                copy($_FILES['photo4']['tmp_name'], $photoDossier);
            } else {
                echo "Une erreur est survenue lors du téléchargement du fichier.";
            }
        }
        if(!empty($_FILES['photo5']['name'])){
            $photo_nom = uniqid() . '_' . $_FILES['photo5']['name'];
            $photoBdd5 = "$photo_nom";
            $photoDossier = RACINE_SITE . "img/$photo_nom";
            if(is_uploaded_file($_FILES['photo5']['tmp_name']) && file_exists(RACINE_SITE . "img/")){
                copy($_FILES['photo5']['tmp_name'], $photoDossier);
            } else {
                echo "Une erreur est survenue lors du téléchargement du fichier.";
            }
        }

        // *** Fin traitement photo
        
        // Condition si user à bien renseigner les champs et ne s'est pas tromper
        if (empty($erreur)) {
            // si dans l'URL action == update, on entame une procédure de modification
            if($_GET['action'] == 'update'){
                $modifAnnonce = $pdo->prepare("UPDATE annonce SET id_annonce = :id_annonce, titre = :titre, description_courte = :description_courte, description_longue = :description_longue, prix = :prix, pays = :pays, ville = :ville, adresse = :adresse, cp = :code_postal, prix = :prix, categorie_id = :categorie_id, photo = :photo  WHERE id_annonce = :id_annonce");
                $modifAnnonce->bindValue(':id_annonce', $_POST['id_annonce'], PDO::PARAM_INT);
                $modifAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':categorie_id', $_POST['categorie'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
                $modifAnnonce->execute();


                $queryAnnonce = $pdo->query(" SELECT titre FROM annonce WHERE id_annonce = '$_GET[id_annonce]' ");

                $annonce = $queryAnnonce->fetch(PDO::FETCH_ASSOC);

                // Requete pour afficher un message personnaliser lorsque la modification à bien été réussie
                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Modification de l\'annonce '. $annonce['titre'] .' réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {


                $inscrirePhoto = $pdo->prepare("INSERT INTO photo (photo1, photo2, photo3, photo4, photo5) VALUES (:photo1, :photo2, :photo3, :photo4, :photo5)");

                $inscrirePhoto->bindParam(':photo1', $photoBdd1);
                $inscrirePhoto->bindParam(':photo2', $photoBdd2);
                $inscrirePhoto->bindParam(':photo3', $photoBdd3);
                $inscrirePhoto->bindParam(':photo4', $photoBdd4);
                $inscrirePhoto->bindParam(':photo5', $photoBdd5);
                $inscrirePhoto->execute();

                $inscrireAnnonce = $pdo->prepare(" INSERT INTO annonce ( membre_id, categorie_id, titre, description_courte, description_longue, pays, ville, cp, adresse, photo, prix, date_enregistrement) VALUES (:membre_id, :categorie, :titre, :description_courte, :description_longue, :pays, :ville, :code_postal, :adresse, :photo, :prix, NOW(), :photo_id) ");
                $inscrireAnnonce->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
                $inscrireAnnonce->bindValue(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
                $inscrireAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);

                $inscrireAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
                $inscrireAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);


                $inscrireAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
                $inscrireAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $inscrireAnnonce->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_STR);
                $inscrireAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                $inscrireAnnonce->bindParam(':photo_id', $photo_id);
                $inscrireAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
                $inscrireAnnonce->execute();
            }
        }
    }

    // procédure de récupération des infos en BDD pour les afficher dans le formulaire lorsque on fait un update (plus pratique et plus sur)
    if ($_GET['action'] == 'update') {
        $tousAnnonce = $pdo->query("SELECT * FROM annonce WHERE id_annonce = '$_GET[id_annonce]' ");
        $annonceActuel = $tousAnnonce->fetch(PDO::FETCH_ASSOC);
    }

    $id_annonce = (isset($annonceActuel['id_annonce'])) ? $annonceActuel['id_annonce'] : "";
    $categorie = (isset($annonceActuel['categorie'])) ? $annonceActuel['categorie'] : "";
    $titre = (isset($annonceActuel['titre'])) ? $annonceActuel['titre'] : "";

    $description_courte = (isset($annonceActuel['description_courte'])) ? $annonceActuel['description_courte'] : "";
    $description_longue = (isset($annonceActuel['description_longue'])) ? $annonceActuel['description_longue'] : "";

    $pays = (isset($annonceActuel['pays'])) ? $annonceActuel['pays'] : "";
    $ville = (isset($annonceActuel['ville'])) ? $annonceActuel['ville'] : "";
    $cp = (isset($annonceActuel['code_postal'])) ? $annonceActuel['code_postal'] : "";
    $adresse = (isset($annonceActuel['adresse'])) ? $annonceActuel['adresse'] : "";
    $photoActuelle = (isset($annonceActuel['photo'])) ? $annonceActuel['photo'] : "";
    $prix = (isset($annonceActuel['prix'])) ? $annonceActuel['prix'] : "";

    // Requete pour effectuer une Supression
    if($_GET['action'] == 'delete'){
        $pdo->query(" DELETE FROM annonce WHERE id_annonce = '$_GET[id_annonce]' ");
    }
}
require_once('includeAdmin/header.php');
?>


<h1 class="text-center my-5">
    <div class="badge badge-warning text-wrap p-3">Gestion des annonces</div>
</h1>

<?= $erreur ?>
<?= $content ?>

<!-- <?= debug($_POST) ?>  -->

<?php if (isset($_GET['action']) && isset($_GET['page'])) : ?>
<div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
    <p>Gérez ici votre base de données des annonces</p>
    <p>Vous pouvez modifier leurs données, ajouter ou supprimer une annonce</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<!-- AFFICHAGE ANNONCE -->
<?php if (isset($_GET['action']) && $_GET['action'] == 'see'): ?>
    <div class="text-center py-5 col-10 mx-auto">
        <div class="d-md-flex">
            <div class="card shadow p-3 mb-5 bg-white rounded">
                <img src="<?= URL . 'img/' . $detail['photo'] ?>" class="card-img-top maxImg" alt="image du produit">
                <div>
                    <img src="<?= URL . 'img/' . $test[''] ?>" alt="">
                    <img src="" alt="">
                    <img src="" alt="">
                    <img src="" alt="">
                    <img src="" alt="">
                </div>
            </div>
            <div class="col-5 mx-auto text-center">
                <h1 class=""><?= $detail['titre'] ?></h1>
                <h2 class="mt-3"><?= $detail['description_courte']?></h2>
                <p class=""><?= $detail['description_longue']?></p>
                <h2 class="">Prix: <?= $detail['prix'] . " €" ?></h2>
            </div>
        </div>
        <div class="d-flex mx-auto">
            <h3>Adresse: <?= $detail['adresse'] . " |"?></h3>
            <h3 class="ml-2">Ville: <?= $detail['ville'] . " |"?></h3>
            <h3 class="ml-2">Code-Postal: <?= $detail['cp'] . " |"?></h3>
            <h3 class="ml-2">Pays: <?= $detail['pays'] ?></h3>
        </div>
    </div>
<?php endif; ?>
<!-- Titre Formulaire -->
<?php if(isset($_GET['action'])): ?>
<h2 class="pt-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des annonces</h2>
<!-- FORMULAIRE -->
<form id="monForm" class="my-5" method="POST" action="" enctype="multipart/form-data">
    <!-- id_annonce pour effectuer des modifications  -->
    <input type="hidden" name="id_annonce" value="<?= $id_annonce ?>">

    <div class="row mt-5">
        <div class="col-md-4">
            <label class="form-label" for="categorie">
                <div class="badge badge-dark text-wrap">Catégorie</div>
            </label>
            <!-- Mettre une balise select et faire une boucle While -->
            <select class="form-control"  name="categorie" id="categorie">
            <?php             
            while($categorie = $listeCategorie->fetch(PDO::FETCH_ASSOC)){
                echo "<option value='$categorie[id_categorie]'> $categorie[titre] </option> ";
            }
        
            ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label" for="titre">
                <div class="badge badge-dark text-wrap">Titre</div>
            </label>
            <input class="form-control" type="text" name="titre" id="titre" placeholder="Titre" value="<?= $titre ?>">
        </div>
    </div>

    <div class="row justify-content-around mt-5">
        <div class="col-md-12">
            <label class="form-label" for="description_courte">
                <div class="badge badge-dark text-wrap">Description</div>
            </label>
            <textarea class="form-control" name="description_courte" id="description_courte" placeholder="Description" rows="5" ><?= $description_courte ?></textarea>
        </div>
    </div>
    <div class="row justify-content-around mt-5">
        <div class="col-md-12">
            <label class="form-label" for="description_longue">
                <div class="badge badge-dark text-wrap">Description longue </div>
            </label>
            <textarea class="form-control" name="description_longue" id="description_longue" placeholder="Description longue" rows="5" ><?= $description_longue ?></textarea>
        </div>
    </div>

    <div class="row mt-5">



    <div class="col-md-4 mt-5">
                <label class="form-label" for="ville">
                    <div class="badge badge-dark text-wrap">Ville</div>
                </label>
                <input class="form-control" type="text" name="ville" id="ville" placeholder="Ville" value="<?= $ville ?>">
            </div>

            <div class="col-md-4 mt-5">
                <label class="form-label" for="code_postal">
                    <div class="badge badge-dark text-wrap">Code Postal</div>
                </label>
                    <input class="form-control" type="text" name="code_postal" id="cp"  placeholder="cp" value="<?= $cp?>">
            </div>

            <div class="col-md-4 mt-5">
                <label class="form-label" for="adresse">
                    <div class="badge badge-dark text-wrap">Adresse</div>
                </label>
                <input class="form-control" type="text" name="adresse" id="adresse" placeholder="Adresse" value="<?= $adresse ?>">
            </div>


    </div>


    <div class="row mt-5">
        <div class="col-md-4">
            <label class="form-label" for="pays">
                <div class="badge badge-dark text-wrap">Pays</div>
            </label>
            <input class="form-control" type="text" name="pays" id="pays" placeholder="pays" value="<?= $pays ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label" for="photo">
                <div class="badge badge-dark text-wrap">Photo</div>
            </label>
            <input class="form-control" type="file" name="photo" id="photo" placeholder="Photo">
        </div>
        <!-- ----------------- -->
        <!-- -->        
        <?php if(!empty($photo)): ?>
        <div class="mt-4">
            <p>Vous pouvez changer d'image
                <img src="<?= URL . 'img/' . $photo ?>" width="50px">
            </p>
        </div>
        <?php endif; ?>
        <!-- pour modifier la photo existante par une nouvelle  -->
        <input type="hidden" name="photoActuelle" value="<?= $photoActuelle ?>">
        <!-- -------------------- -->
        <div class="col-md-4">
            <label class="form-label" for="prix">
                <div class="badge badge-dark text-wrap">Prix</div>
            </label>
            <input class="form-control" type="text" name="prix" id="prix" placeholder="Prix" value="<?= $prix ?>">
        </div>
    </div>

    <div class="col-md-1 mt-5">
        <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
    </div>

</form>
<?php endif; ?>

<?php $queryAnnonces = $pdo->query(" SELECT id_annonce FROM annonce "); ?>
<h2 class="py-5">Nombre de Annonces en base de données: <?= $queryAnnonces->rowCount() ?></h2>

<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Ajouter une annonce
        </button>
    </a>
</div>

<table class="table table-dark text-center table-responsive">
    <!-- Complété pour n'afficher que 10 prduits dans le tableau le OFFST détermine quel annonce affichée dans la nouvelle page -->
    <?php $afficheAnnonces = $pdo->query("SELECT * FROM annonce ORDER BY prix ASC LIMIT $parPage OFFSET $premierAnnonce") ?>
    <thead>
        <tr>
            <?php for ($i = 0; $i < $afficheAnnonces->columnCount(); $i++) :
                $colonne = $afficheAnnonces->getColumnMeta($i) ?>
                <th><?= $colonne['name'] ?></th>
            <?php endfor; ?>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($annonce = $afficheAnnonces->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($annonce as $key => $value) : ?>
                    <?php if ($key == 'prix') : ?>
                        <td><?= $value ?> €</td>
                    <?php elseif ($key == 'photo') : ?>
                        <td><img class="img-fluid" src="<?= URL . 'img/' . $value ?>" width="50" loading="lazy"></td>
                    <?php else : ?>
                        <td><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <!-- Crayon pour modifier (UPDATE) et poubelle pour supprimer (DELETE) -->
                <td><a href='?action=update&id_annonce=<?= $annonce['id_annonce'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href="?action=delete&id_annonce=<?= $annonce['id_annonce'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<!-- Debut de pagignation -->
<nav aria-label="">
    <ul class="pagination justify-content-end">
        <li class="page-item <?= ($pageCourante == 1) ? 'disabled' : "" ?> ">
            <a class="page-link text-dark" href="?page=<?= $pageCourante - 1 ?>" aria-label="Previous">
                <span aria-hidden="true">précédente</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <?php for($page = 1; $page <= $nombrePages; $page++): ?>
        <li class="mx-1 page-item">
            <a class="btn btn-outline-success <?= ($pageCourante == $page) ? 'active' : "" ?>" href="?page=<?= $page ?>"><?= $page ?> </a>
        </li>
        <?php endfor; ?>
        <li class="page-item <?= ($pageCourante == $nombrePages)? 'disabled' : '' ?>">
            <a class="page-link text-dark" href="?page=<?= $pageCourante + 1 ?>" aria-label="Next">
                <span aria-hidden="true">suivante</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>

<!-- MODAL DE SUP/MDF-->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer article
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer cet article de votre panier ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- modal -->

<?php require_once('includeAdmin/footer.php'); ?>