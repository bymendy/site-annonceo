<?php
require_once('../include/init.php');

// CONNEXION ADMIN
if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'connexion.php');
    exit();
}

// PAGINATION
if(isset($_GET['page']) && !empty($_GET['page'])){
    $pageCourante = (int) strip_tags($_GET['page']);
}else{
    $pageCourante = 1;
}
$queryAnnonces = $pdo->query(" SELECT COUNT(id_annonce) AS nombreAnnonces FROM annonce ");
$resultatAnnonces = $queryAnnonces->fetch();
$nombreAnnonces = (int) $resultatAnnonces['nombreAnnonces'];
$parPage = 10;
$nombrePages = ceil($nombreAnnonces / $parPage);
$premierAnnonce = ($pageCourante - 1) * $parPage;

$id_annonce = "";
$titre = "";
$description_courte = "";
$description_longue = "";
$prix =  "";
$pays =  "";
$ville = "";
$adresse =  "";
$cp =  "";
$id_membre = $_SESSION['membre']['id_membre'];
$photo_id = "";
$categorie_id = "";
$photo_actuelle = "";

$photoBdd1 = "";
$photoBdd2 = "";
$photoBdd3 = "";
$photoBdd4 = "";
$photoBdd5 = "";

//&& !empty($_POST)

// REQUETTE GET POUR TRAVAIILLER SUR L'URL
if(isset($_GET['action'])){

    if ($_POST){
        // TITRE
        if(!isset($_POST['titre']) || !preg_match('#^[a-zA-Z0-9 -_.éà\'è]{2,50}$#', $_POST['titre'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
        }
        // DESCRIPTION COURTE
            if(!isset($_POST['description_courte']) || iconv_strlen($_POST['description_courte']) <1 || iconv_strlen($_POST['pays']) > 200 ){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description courte !</div>';
        }
        // DESCRIPTION LONGUE
        if(!isset($_POST['description_longue']) || iconv_strlen($_POST['description_longue']) <1 || iconv_strlen($_POST['pays']) > 200 ){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description longue !</div>';
        }
        // PRIX
        if(!isset($_POST['prix']) || !preg_match('#^[0-9]{1,10}$#', $_POST['prix'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format code prix !</div>';
        }
        // CATEGORIE
        if(!isset($_POST['categorie'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format categorie !</div>';
        }
        // PHOTO
        if(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
            if ($_FILES['fichier']['size'] > $_POST['MAX_FILE_SIZE']) {
                echo "Le fichier téléchargé est trop volumineux.";
            }
            $allowed = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png');
            $filename = $_FILES['fichier']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(!in_array($ext, $allowed)) {
                echo "Le type de fichier n'est pas autorisé.";
            }
        }
        // PAYS
        if(!isset($_POST['pays']) || iconv_strlen($_POST['pays']) < 3 || iconv_strlen($_POST['pays']) > 20 ){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pays !</div>';
        }
        // VILLE
        if(!isset($_POST['ville']) || strlen($_POST['ville']) < 2 || strlen($_POST['ville']) > 30 ){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format ville !</div>';
        }
        //ADRESSE
        if(!isset($_POST['adresse']) || strlen($_POST['adresse']) < 5 || strlen($_POST['adresse']) > 50 ){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>';
        }
        // CODE POSTAL
        if(!isset($_POST['cp']) || !preg_match('#^[0-9]{5}$#', $_POST['cp'])){
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format code postal !</div>';
        }
        // TRAITEMENT PHOTO
        $photoBdd = (!empty($_POST['photo_actuelle'])) ? $_POST['photo_actuelle'] : "";
        // if($_GET['action'] == 'update'){
        //     $photoBdd = $_POST['photo_actuelle'];
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

        // REQUETTE DE SI PAS DE ERREUR ON PEUT CONTINUER
        if(empty($erreur)){
            
            // REQUETTE DE UPDATE
            if($_GET['action'] == 'update'){
                $modifAnnonce = $pdo->prepare("UPDATE annonce SET id_annonce = :id_annonce, titre = :titre, description_courte = :description_courte, description_longue = :description_longue, prix = :prix, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, prix = :prix, stock = :stock, categorie_id = :categorie_id, photo = :photo  WHERE id_annonce = :id_annonce");
                $modifAnnonce->bindValue(':id_annonce', $_POST['id_annonce'], PDO::PARAM_INT);
                $modifAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':cp', $_POST['cp'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':categorie_id', $_POST['categorie'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
                $modifAnnonce->execute();

                // MESSAGE DE VALIDATION A L'ENVOI
                $queryProduit = $pdo->query("SELECT titre FROM annonce WHERE id_annonce = '$_GET[id_annonce]'");
                $annonce = $queryAnnonce->fetch(PDO::FETCH_ASSOC);
                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                                <strong>Félicitations !</strong> Modification de produit ' . $annonce['titre'] .  ' est réussie 😉!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
            }else{

                $incluirePhoto = $pdo->prepare("INSERT INTO photo (photo1, photo2, photo3, photo4, photo5) VALUES (:photo1, :photo2, :photo3, :photo4, :photo5)");

                $incluirePhoto->bindParam(':photo1', $photoBdd1);
                $incluirePhoto->bindParam(':photo2', $photoBdd2);
                $incluirePhoto->bindParam(':photo3', $photoBdd3);
                $incluirePhoto->bindParam(':photo4', $photoBdd4);
                $incluirePhoto->bindParam(':photo5', $photoBdd5);
                $incluirePhoto->execute();

                $photo_id = $pdo->lastInsertId();

                // REQUETTE DE INSERTION A LA BDD
                $incluireAnnonce = $pdo->prepare("INSERT INTO annonce (titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, membre_id, categorie_id, date_enregistrement, photo_id) VALUES (:titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :cp, :membre_id, :categorie_id, NOW(), :photo_id)");

                $incluireAnnonce->bindParam(':titre', $_POST['titre']);
                $incluireAnnonce->bindParam(':description_courte', $_POST['description_courte']);
                $incluireAnnonce->bindParam(':description_longue', $_POST['description_longue']);
                $incluireAnnonce->bindParam(':prix', $_POST['prix']);
                $incluireAnnonce->bindParam(':photo', $photoBdd);
                $incluireAnnonce->bindParam(':pays', $_POST['pays']);
                $incluireAnnonce->bindParam(':ville', $_POST['ville']);
                $incluireAnnonce->bindParam(':adresse', $_POST['adresse']);
                $incluireAnnonce->bindParam(':cp', $_POST['cp']);
                $incluireAnnonce->bindParam(':membre_id', $id_membre);
                $incluireAnnonce->bindParam(':photo_id', $photo_id);
                $incluireAnnonce->bindValue(':categorie_id', $_POST['categorie'], PDO::PARAM_STR);
                $incluireAnnonce->execute();
            }
        }
    }

    // REQUETTE DE UPDATE
    if($_GET['action'] == 'update'){
        $tousAnnonce = $pdo->query("SELECT * FROM annonce WHERE id_annonce = '$_GET[id_annonce]'");
        $annonceActuel = $tousAnnonce->fetch(PDO::FETCH_ASSOC);
    }

    // REQUETTE DE RECUPERATION DE BDD PAR APPORT A LES DONNES EN FORM
    $id_annonce = "";
    $titre = (isset($annonceActuel['titre'])) ? $annonceActuel['titre'] : "";
    $description_courte = (isset($annonceActuel['description_courte'])) ? $annonceActuel['description_courte'] : "";
    $description_longue = (isset($annonceActuel['description_longue'])) ? $annonceActuel['description_longue'] : "";
    $prix = (isset($annonceActuel['prix'])) ? $annonceActuel['prix'] : "";
    $pays = (isset($annonceActuel['pays'])) ? $annonceActuel['pays'] : "";
    $ville = (isset($annonceActuel['ville'])) ? $annonceActuel['ville'] : "";
    $adresse = (isset($annonceActuel['adresse'])) ? $annonceActuel['adresse'] : "";
    $cp = (isset($annonceActuel['cp'])) ? $annonceActuel['cp'] : "";
    $id_membre = (isset($annonceActuel['membre_id'])) ? $annonceActuel['membre_id'] : "";
    $photo_id = (isset($annonceActuel['photo_id'])) ? $annonceActuel['photo_id'] : "";
    $photo_actuelle = (isset($annonceActuel['photo'])) ? $annonceActuel['photo'] : "";
    $categorie_id = (isset($annonceActuel['categorie_id'])) ? $annonceActuel['categorie_id'] : "";
    // A FAIRE -> PHOTO

    // REQUETTE POUR DELETE
    if($_GET['action'] == 'delete'){
        $pdo->query("DELETE FROM annonce WHERE id_annonce = '$_GET[id_annonce]'");
    } 
}

require_once('includeAdmin/header.php');
// require_once('../include/affichage.php');
?>

<?= $erreur ?>
<?= $content ?>

<?= 
debug($id_annonce);
debug($id_membre);
?>
<!-- TITLE GESTION -->
<h1 class="text-center my-5">
    <div class="badge badge-success text-wrap p-3">Gestion des annonces</div>
</h1>

<!-- MESSAGE D'INFORMATION -->
<?php if (!isset($_GET['action']) && !isset($_GET['page'])) : ?>
    <div class="blockquote col-5 mx-auto alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
        <p class="text-center">Gérez ici votre base de données des annonces</p>
        <p class="text-center">Vous pouvez modifier leurs données ou supprimer les annonces</p>
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

<!-- FORMULAIRE -->
<?php if(isset($_GET['action']) && $_GET['action'] != 'see'): ?>

    <!-- TITLE FORMULAIRE -->
    <h2 class="my-5 text-center"><u>Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des annonces</u></h2>


    <form id="monForm" class="my-5 col-12 mx-auto" method="POST" action="" enctype="multipart/form-data">

        <input type="" name="id_annonce" value="<?= $id_annonce  ?>">
        <input type="" name="membre_id" value="<?= $id_membre?>">
        <input type="" name="photo_id" value="<?= $photo_id?>">
        <input type="" name="categorie_id" value="<?= $categorie_id  ?>">
    
        <div class="d-flex mx-auto mt-5">
            <!-- TITRE -->
            <div class="col-md-4 mx-auto">
                <label class="form-label" for="titre">
                    <div class="badge badge-dark text-wrap">Titre</div>
                </label>
                <input class="form-control" type="text" name="titre" id="titre" placeholder="titre" value="<?= $titre ?>">
            </div>
            <!-- DESCRIPTION COURTE -->
            <div class="col-md-4 mx-auto">
                <label class="form-label" for="description_courte">
                    <div class="badge badge-dark text-wrap">Description courte</div>
                </label>
                <input class="form-control" type="text" name="description_courte" id="description_courte" placeholder="Description courte"  value="<?= $description_courte ?>">
            </div>
            <!-- DESCRIPTION LONGUE -->
            <div class="col-md-4 mx-auto">
                <label class="form-label" for="description_longue">
                    <div class="badge badge-dark text-wrap">Description longue</div>
                </label>
                <textarea class="form-control" name="description_longue" id="description_longue" placeholder="Description longue" rows="5"><?= $description_longue ?></textarea>
            </div>
        </div>

        <div class="d-flex mx-auto mt-5">
                <!-- PAYS -->
                <div class="col-md-4 mx-auto mt-5">
                    <label class="form-label" for="pays">
                        <div class="badge badge-dark text-wrap">Pays</div>
                    </label>
                    <input class="form-control" type="text" name="pays" id="pays" placeholder="Pays" value="<?= $pays?>">
                </div>
                <!-- VILLE -->
                <div class="col-md-4 mx-auto mt-5">
                    <label class="form-label" for="ville">
                        <div class="badge badge-dark text-wrap">Ville</div>
                    </label>
                    <input class="form-control" type="text" name="ville" id="ville"  placeholder="Ville" value="<?= $ville?>">
                </div>
                <!-- ADRESSE -->
                <div class="col-md-4 mx-auto mt-5">
                    <label class="form-label" for="adresse">
                        <div class="badge badge-dark text-wrap">Adresse</div>
                    </label>
                    <input class="form-control" type="text" name="adresse" id="adresse"  placeholder="Adresse" value="<?= $adresse?>">
                </div>
        </div>

        <div class="d-flex mx-auto mt-5">
            <!-- CODE POSTAL -->
            <div class="col-md-4 mt-5">
                    <label class="form-label" for="cp">
                        <div class="badge badge-dark text-wrap">Code Postal</div>
                    </label>
                    <input class="form-control" type="text" name="code_postal" id="cp"  placeholder="cp" value="<?= $cp?>">
                </div>
            <!-- PRIX -->
            <div class="col-md-4 mt-5">
                <label class="form-label" for="prix">
                    <div class="badge badge-dark text-wrap">Prix</div>
                </label>
                <input class="form-control" type="text" name="prix" id="prix" placeholder="Prix" value="<?= $prix ?>">
            </div>
            <!-- PHOTO -->
            <div class="col-md-4 mt-5">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo Affiche</div>
                </label>
                <input class="form-control" type="file" name="photo" id="photo" placeholder="Photo">
            </div>
            <?php if(!empty($photo)): ?>
                <div class="mt-4">
                    <p>Vous pouvez changer d'image
                        <img src="<?= URL . 'img/' . $photo ?>" width="50px">
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- VALIDATION -->
        <div class="col-md-3 mt-5 mx-auto">
            <button type="submit" class="btn btn-outline-dark btn-success w-100 text-white">Valider</button>
        </div>

    </form>
<?php endif; ?>

<!-- NOMBRE DE ANNONCES EN BDD -->
<?php $nbAnnonces = $pdo->query(" SELECT id_annonce FROM annonce "); ?>
<h2 class="py-5 text-center">Nombre de annonces en base de données:</h2>
<h3 class="text-center display-3 mb-5">
    <div class="badge badge-success p-3"><?= $nbAnnonces->rowCount() ?> </div>
</h3>

<!-- TABLEAU DE RESCUPERATION DONNES -->
<div class="col-12">
    <table class="table table-white text-center">
        <?php $afficheAnnonces = $pdo->query("SELECT * FROM annonce ORDER BY date_enregistrement DESC LIMIT $parPage OFFSET $premierAnnonce") ?>
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
                    <td><a href='?action=update&id_annonce=<?= $annonce['id_annonce'] ?>'><i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i></a></td>
                    <td><a href='../fiche_annonce.php?id_annonce=<?= $annonce['id_annonce'] ?>'><i class="bi bi-eye" style="font-size: 1.5rem;"></i></a></td>
                    <!-- <td><a href='?action=see&id_annonce=<?= $annonce['id_annonce'] ?>'><i class="bi bi-eye" style="font-size: 1.5rem;"></i></a></td> -->
                    <td><a data-href="?action=delete&id_annonce=<?= $annonce['id_annonce'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash" style="font-size: 1.5rem;"></i></a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- PAGINATION -->
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

<?php require_once('includeAdmin/footer.php'); ?>