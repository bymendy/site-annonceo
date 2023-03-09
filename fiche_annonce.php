<?php
require_once('include/init.php');

require_once('include/affichage.php');
// positionner le PageTitle sous le require once de affichage, car c'est dedans 
// $pageTitle = "Fiche" . substr($detail['categorie'], 0,-1) . " " .  $detail['titre'];

require_once('include/header.php');
?>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- debut de la colonne qui va afficher les categories -->
        <div class="col-md-2">

            <div class="list-group text-center">
                
            <?php while($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)): ?>
                <a class="btn btn-outline-dark my-2" href="<?= URL ?>?titre=<?= $menuCategorie['titre'] ?>"><?= $menuCategorie['titre'] ?></a>
            <?php endwhile; ?>
                
            </div>

        </div>
        <!-- fin de la colonne catégories -->
        <div class="col-md-8">

            <h2 class='text-center my-5'>
                <div class="badge badge-dark text-wrap p-3">Fiche annonce <?= substr($detail['titre'], 0,-1) . " " .  $detail['titre'] ?></div>
            </h2>   

            <div class="row justify-content-around text-center py-5">
                <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 22rem;">
                    <img src=" <?= URL . 'img/' . $detail['photo'] ?>" class="card-img-top" alt="image annonce" <?= substr($detail['photo'], 0,-1) . " " .  $detail['titre'] ?>>
                    <div class="card-body">
                        <h3 class="card-title"><div class="badge badge-dark text-wrap"><?= $detail['prix'] ?> €</div></h3>
                        <p class="card-text"><?= $detail['description_courte'] ?></p>
                        <!-- ------------------- -->
                        <!-- condition pour savoir si on affiche un selecteur pour choisir le nombre de annonce que l'on veut (s'il y a du stock) ou si on affiche le message d'alerte qui indique une rupture de stock-->
                        <?php if($detail['description_longue'] > 0): ?>
                        <!-- La quantite désirée sera récupérée sur la page panier (pour savoir combien il veut acheter) donc on indique dans l'attribut action, le nom du fichier panier.php -->
                        <form method="POST" action="panier.php"><input type="hidden" name="id_annonce" value="<?= $detail['id_annonce'] ?>">
                            
                            <label for="">J'en achète</label>
                            <select class="form-control col-md-5 mx-auto" name="quantite" id="quantite">
                                <!-- ----------- -->
                                <!-- boucle qui va récupérer en stock pour permettre de choisir la quantité -->
                                <?php for($quantite = 1 ;$quantite <= min($detail['stock'],5); $quantite++): ?>
                                    <!-- La fonction prédéfinie min (au dessus)permet de n'afficher que 5 au maximum dans le selecteur -->
                                <option class="bg-dark text-light" value="<?= $quantite ?>" ><?= $quantite ?></option>
                                <?php endfor; ?>

                                <!-- ----------- -->
                            </select>
                            <button type="submit" class="btn btn-outline-success my-2" name="ajout_panier" value="ajout_panier"><i class="bi bi-plus-circle"></i> Panier <i class="bi bi-cart3"></i></button>
                        </form>
                        <?php else: ?>
                        <!-- ----------- -->
                            <p class="card-text"><div class="badge badge-danger text-wrap p-3">annonce en rupture de stock</div></p>
                        <?php endif; ?>
                            
                        <!-- ------------ -->
                        <!-- Lien pour voir tous les annonces de la meme catégorie -->
                        <<p>Voir tous les modèles <a href="<?= URL ?>?categorie=<?= $detail['categorie'] ?>">de la même catégorie</a> ou <a href="<?= URL ?>?categorie=<?= $detail['categorie'] ?>">pour le même titre</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">

<?php require_once('include/footer.php');?>
