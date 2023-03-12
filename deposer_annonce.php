<?php
require_once('include/init.php');

// code a venir

// récupération de l'annonce
// variable listeCategorie et appliquer la requete SQL
$listeCategorie = $pdo->query("SELECT * FROM categorie");
// Traitement
$id_annonce = (isset($_POST['id_annonce'])) ? $_POST['id_annonce'] : "";
$titre = (isset($_POST['titre'])) ? $_POST['titre'] : "";
$description_courte = (isset($_POST['description_courte'])) ? $_POST['description_courte'] : "";
$description_longue = (isset($_POST['description_longue'])) ? $_POST['description_longue'] : "";
$prix = (isset($_POST['prix'])) ? $_POST['prix'] : "";
$photo = (isset($_POST['photo'])) ? $_POST['photo'] : "";
$pays = (isset($_POST['pays'])) ? $_POST['pays'] : "";
$ville = (isset($_POST['ville'])) ? $_POST['ville'] : "";
$adresse = (isset($_POST['adresse'])) ? $_POST['adresse'] : "";
$cp = (isset($_POST['cp'])) ? $_POST['cp'] : "";

if ($_POST) {
    if(isset($_POST['valider'])) {
        $annonce_deposee = true; // une variable pour indiquer si l'annonce a été déposée avec succès ou non

    }
    echo (isset($annonce_deposee)) ? '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
    <p>Votre annonce a été déposée avec succès 😉 !</p> 
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>' : '';



    if (!isset($_POST['categorie'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format categorie !</div>';
    }
    if (!isset($_POST['titre']) || strlen($_POST['titre']) < 3 || strlen($_POST['titre']) > 30) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
    }
    if (!isset($_POST['description_courte']) || strlen($_POST['description_courte']) < 3 || strlen($_POST['description_courte']) > 255) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description_courte !</div>';
    }
    if (!isset($_POST['description_longue']) || strlen($_POST['description_longue']) < 3 || strlen($_POST['description_longue']) > 2000) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description_longue !</div>';
    }
    if (!isset($_POST['prix']) || !preg_match('#^[0-9]{1,7}$#', $_POST['prix'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prix !</div>';
    }
    if (!isset($_POST['pays']) || strlen($_POST['pays']) < 3 || strlen($_POST['pays']) > 20) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pays !</div>';
    }
    if (!isset($_POST['ville']) || strlen($_POST['ville']) < 3 || strlen($_POST['ville']) > 20) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format ville !</div>';
    }
    if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 3 || strlen($_POST['adresse']) > 50) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>';
    }
    if (!isset($_POST['cp']) || !preg_match('#^[0-9]{1,5}$#', $_POST['cp'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format cp !</div>';
    }

    $photo_bdd = "";

    // à verifier : n'ayant pas la référence de l'annonce je prends le titre 
    if (!empty($_FILES['photo']['name'])) {
        $photo_nom = $_POST['titre'] . '_' . $_FILES['photo']['name'];
        $photo_bdd = "$photo_nom";
        $photo_dossier = RACINE_SITE . "img/$photo_nom";
        copy($_FILES['photo']['tmp_name'], $photo_dossier);
    }
    $photo_bdd1 = "";
    $photo_bdd2 = "";
    $photo_bdd3 = "";
    $photo_bdd4 = "";
    $photo_bdd5 = "";
    // verifier si $_FILES ['photo] ou photo1 etc']
    if (!empty($_FILES['photo1']['name'])) {
        $photo_nom = $_POST['titre'] . '_' . $_FILES['photo1']['name'];
        $photo_bdd1 = "$photo_nom";
        $photo_dossier = RACINE_SITE . "img/$photo_nom";
        copy($_FILES['photo1']['tmp_name'], $photo_dossier);
    }
    if (!empty($_FILES['photo2']['name'])) {
        $photo_nom = $_POST['titre'] . '_' . $_FILES['photo2']['name'];
        $photo_bdd2 = "$photo_nom";
        $photo_dossier = RACINE_SITE . "img/$photo_nom";
        copy($_FILES['photo2']['tmp_name'], $photo_dossier);
    }
    if (!empty($_FILES['photo3']['name'])) {
        $photo_nom = $_POST['titre'] . '_' . $_FILES['photo3']['name'];
        $photo_bdd3 = "$photo_nom";
        $photo_dossier = RACINE_SITE . "img/$photo_nom";
        copy($_FILES['photo3']['tmp_name'], $photo_dossier);
    }
    if (!empty($_FILES['photo4']['name'])) {
        $photo_nom = $_POST['titre'] . '_' . $_FILES['photo4']['name'];
        $photo_bdd4 = "$photo_nom";
        $photo_dossier = RACINE_SITE . "img/$photo_nom";
        copy($_FILES['photo4']['tmp_name'], $photo_dossier);
    }
    if (!empty($_FILES['photo5']['name'])) {
        $photo_nom = $_POST['titre'] . '_' . $_FILES['photo5']['name'];
        $photo_bdd5 = "$photo_nom";
        $photo_dossier = RACINE_SITE . "img/$photo_nom";
        copy($_FILES['photo5']['tmp_name'], $photo_dossier);
    }
    if (empty($erreur)) {

            $inscrirePhoto = $pdo->prepare("INSERT INTO photo (photo1, photo2, photo3, photo4, photo5) VALUES (:photo1, :photo2, :photo3, :photo4, :photo5)");
            $inscrirePhoto->bindValue(':photo1', $photo_bdd1, PDO::PARAM_STR);
            $inscrirePhoto->bindValue(':photo2', $photo_bdd2, PDO::PARAM_STR);
            $inscrirePhoto->bindValue(':photo3', $photo_bdd3, PDO::PARAM_STR);
            $inscrirePhoto->bindValue(':photo4', $photo_bdd4, PDO::PARAM_STR);
            $inscrirePhoto->bindValue(':photo5', $photo_bdd5, PDO::PARAM_STR);
            $inscrirePhoto->execute();

            $photo_id = $pdo->lastInsertId();

            $inscrireAnnonce = $pdo->prepare(" INSERT INTO annonce (titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, membre_id, categorie_id, date_enregistrement, photo_id) VALUES (:titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :cp, :membre_id, :categorie, NOW(), :photo_id )");
            $inscrireAnnonce->bindValue(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':photo', $photo_bdd, PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':cp', $_POST['cp'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
            $inscrireAnnonce->bindValue(':photo_id', $photo_id, PDO::PARAM_STR);
            $inscrireAnnonce->execute();
            $content .= '<div class="alert alert-success alert-dismissible fade show
            mt-5" role="alert">
            <strong>Félicitations !</strong> Ajout de l\'annonce réussie !
            <button type="button" class="close" data-dismiss="alert" arialabel="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';        
    }
}


// require_once('include/affichage.php');
require_once('include/header.php');
?>
</div>
<div class=" my-5">
                <img class='img-fluid' src="img/banniere_depose.png" alt="Bandeau de La Boutique" loading="lazy">
            </div>

<div class="container">
<!-- FORMULAIRE ANNONCE -->
<h2 class="pt-5">Créez votre annonce en quelques clics</h2>
<?php if(internauteConnecte()) : ?>
    <form id="monForm" class="my-5" method="POST" action="" enctype="multipart/form-data">
        <?= $erreur ?>
        <input type="hidden" name="id_annonce" value="<?= $id_annonce ?>">
        <!-- ajouter titre -->

        <div class="row mt-5">
            <div class="col-md-6">
                <label class="form-label" for="titre">
                    <div class="badge badge-dark text-wrap">Titre</div>
                </label>
                <input class="form-control" type="text" name="titre" id="titre" placeholder="titre" value="<?= $titre ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="prix">
                    <div class="badge badge-dark text-wrap">Prix</div>
                </label>
                <input class="form-control" type="text" name="prix" id="prix" placeholder="Prix" value="<?= $prix ?>">
            </div>            
        </div>
        <div class="row justify-content-around mt-5">
            <div class="col-md-6">
                <label class="form-label" for="description_courte">
                    <div class="badge badge-dark text-wrap">Description</div>
                </label>
                <textarea class="form-control" name="description_courte" id="description_courte" placeholder="description courte" rows="5"><?= $description_courte ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="description_longue">
                    <div class="badge badge-dark text-wrap">Description longue</div>
                </label>
                <textarea class="form-control" name="description_longue" id="description_longue" placeholder="description longue" rows="5"><?= $description_longue ?></textarea>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="pays">
                    <div class="badge badge-dark text-wrap">Pays</div>
                </label>
                <input class="form-control" type="text" name="pays" id="pays" placeholder="Pays" value="<?= $pays ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label" for="ville">
                    <div class="badge badge-dark text-wrap">Ville</div>
                </label>
                <input class="form-control" type="text" name="ville" id="ville" placeholder="ville" value="<?= $ville ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label" for="adresse">
                    <div class="badge badge-dark text-wrap">Adresse</div>
                </label>
                <input class="form-control" type="text" name="adresse" id="adresse" placeholder="adresse" value="<?= $adresse ?>">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="cp">
                    <div class="badge badge-dark text-wrap">CP</div>
                </label>
                <input class="form-control" type="text" name="cp" id="cp" placeholder="cp" value="<?= $cp ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="categorie">
                    <div class="badge badge-dark text-wrap">Catégorie</div>
                </label>
                <select class="form-control" id="categorie" name="categorie">
                    <?php
                    while ($categorie = $listeCategorie->fetch(PDO::FETCH_ASSOC)) {
                        //var_dump($categorie);
                        echo "<option value = '$categorie[id_categorie]'> $categorie[titre]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo Principale</div>
                </label>
                <input class="form-control" type="file" name="photo" id="photo" placeholder="Photo">
                <?php if (!empty($photo)) : ?>
                    <div class="mt-4">
                        <p>Vous pouvez changer d'image
                            <img src="<?= URL . 'img/' . $photo ?>" width="50px">
                        </p>
                    </div>
                <?php endif; ?>
                <input type="hidden" name="photoActuelle" value="<?= $photo ?>">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo 1</div>
                </label>
                <input class="form-control" type="file" name="photo1" id="photo1" placeholder="Photo1">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo 2</div>
                </label>
                <input class="form-control" type="file" name="photo1" id="photo2" placeholder="Photo2">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo 3</div>
                </label>
                <input class="form-control" type="file" name="photo3" id="photo3" placeholder="Photo3">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo 4</div>
                </label>
                <input class="form-control" type="file" name="photo4" id="photo4" placeholder="Photo4">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="photo">
                    <div class="badge badge-dark text-wrap">Photo 5</div>
                </label>
                <input class="form-control" type="file" name="photo5" id="photo5" placeholder="Photo5">
            </div>
        </div>

        <div class="col-md-1 mt-5">
            <button type="submit" name="valider" class="btn btn-dark btn-outline-success">Valider</button>
        </div>

    </form>
    <!-- Fin du Formulaire -->

<?php endif; ?>
</div>


</div>

</div>
<div class="container">

    <?php require_once('include/footer.php') ?>