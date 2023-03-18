<?php
require_once('include/init.php');
require_once('include/affichage.php');
if(!empty($_GET['id_annonce'])) {

    $recup_annonce = $pdo->prepare("SELECT annonce.*, membre.*, categorie.titre AS titre_categorie FROM annonce, categorie, membre WHERE id_membre = membre_id AND id_categorie = categorie_id AND id_annonce = :id_annonce");
    $recup_annonce->bindParam(':id_annonce', $_GET['id_annonce']);
    $recup_annonce->execute();

    // POUR RECUPERER LES PHOTOS
    if($recup_annonce->rowCount() > 0) {
        $infos_annonce = $recup_annonce->fetch(PDO::FETCH_ASSOC);

        $liste_photos_annexes = $pdo->prepare("SELECT * FROM photo WHERE id_photo = :id_photo");
        $liste_photos_annexes->bindParam(':id_photo', $infos_annonce['photo_id']);
        $liste_photos_annexes->execute();

        $infos_photos_annexes = $liste_photos_annexes->fetch(PDO::FETCH_ASSOC);
    } else {
        header('location:index.php');
    }

} else {
    header('location:index.php');
}

require_once('include/header.php');
?>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- debut de la colonne qui va afficher les categories -->
        <div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-md-7">
            <ul class="nav nav-pills ">
            <li class="nav-item">
                <li class="nav-item">
                <?php while($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)): ?>
                    <a class="btn btn-dark my-2" href="<?= URL ?>?categorie=<?= $menuCategorie['id_categorie'] ?>"><?= $menuCategorie['titre'] ?></a>
                <?php endwhile; ?>
            </li>
        </ul>
                </li>
            </ul>
			</div>
		</div>
	</div>

        <!-- fin de la colonne catégories -->
        <div class="container p-5">
  <div class="row">
    <div class="col-md-8">
      <h1><?php echo $infos_annonce['titre']; ?></h1>
      <div class="card shadow p-3 mb-5 bg-white rounded w-100" style="width: 22rem;"><img src=" <?= URL . 'img/' . $detail['photo'] ?>" class="card-img-top" alt="image du produit"></div>
      <p><?php echo $infos_annonce['description_longue']; ?></p>
    </div>
    <div class="col-md-4 p-3 mb-5 bg-white rounded ">
      <img src="<?php echo URL . 'img/' . $infos_photos_annexes['photo1']; ?>" alt="<?php echo $infos_annonce['titre']; ?>" class="w-100 img-fluid p-3 mb-5 bg-white rounded ">
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <p> <strong>Prix</strong> : <?php echo $infos_annonce['prix']; ?> €</p>
      <p><strong>Vendeur</strong> : <?php echo $infos_annonce['pseudo']; ?></p>
      <p><strong>Adresse</strong> : <?php echo $infos_annonce['adresse']; ?></p>
      <p><strong>Code postal</strong> : <?php echo $infos_annonce['cp']; ?></p>
    </div>
    <div class="col-md-6">
      <p><strong>Date de publication</strong> : <?php echo $infos_annonce['date_enregistrement']; ?></p>
      <p><strong>Catégorie</strong> : <?php echo $infos_annonce['titre_categorie']; ?></p>
    </div>
  </div>
</div>
<?php require_once('include/footer.php');?>