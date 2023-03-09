<?php
require_once('include/init.php');

// code a venir

require_once('include/affichage.php');
require_once('include/header.php');
?>

</div>
<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-md-7">
            <ul class="nav nav-pills ">
            <li class="nav-item">
                <?php while($menuCategorie = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)): ?>
                    <a class="btn btn-dark my-2" href="<?= URL ?>?categorie=<?= $menuCategorie['id_categorie'] ?>"><?= $menuCategorie['titre'] ?></a>
                <?php endwhile; ?>
            </li>
        </ul>
			</div>
		</div>
	</div>

    <div class="row my-5">

    <div class="col-md-2">


        
    </div>

        <!-- --------------------------- -->
        <!-- Afficher les annonces par catégories -->
        <?php if(isset($_GET['categorie'])): ?>
        <div class="col-md-8">

            <div class="text-center my-5">
                <img class='img-fluid' src="img/banniere_annonceo.png" alt="Bandeau de La Boutique" loading="lazy">
            </div>

            <div class="row justify-content-around">
                <h2 class="py-5">
                    <div class="badge badge-dark text-wrap"><?= $titreCategorie['titre'] ?> </div>
                </h2>
            </div>

            <div class="row justify-content-around text-center">


                <?php while($annonce = $afficheAnnonces->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                    <a href="fiche_annonce.php?id_annonce=<?= $annonce['id_annonce']?>"><img src="<?= URL . 'img/' . $annonce['photo'] ?>" class="card-img-top" alt="Photo de <?= $annonce['titre'] ?>"></a>
                    <div class="card-body">
                        <h3 class="card-title"><?= $annonce['titre'] ?></h3>
                        <h3 class="card-title">
                            <div class="badge badge-dark text-wrap"><?= $annonce['prix'] ?> €</div>
                        </h3>
                        <p class="card-text"><?= $annonce['description_courte'] ?></p>
                        <!-- Requete pour véhiculer l'id de chaque annonce et pouvoir l'afficher et basculer sur la page fiche annonce  -->
                        <a href="fiche_annonce.php?id_annonce=<?= $annonce['id_annonce']?>" class="btn btn-outline-dark"><i class='bi bi-search'></i> Voir Annonce</a>
                    </div>
                </div>
                <?php endwhile; ?>

            </div>

            <nav aria-label="">
                <ul class="pagination justify-content-end">
                    <li class="mx-1 page-item  ">
                        <a class="page-link text-success" href="" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <!--  -->
                    <li class="mx-1 page-item ">
                        <a class="btn btn-outline-success " href=""></a>
                    </li>
                    <!--  -->
                    <li class="mx-1 page-item ">
                        <a class="page-link text-success" href="" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

        <!-- ----------------------- -->
        <!-- pour afficher les vetements  par titre -->
        <?php elseif(isset($_GET['titre'])): ?>

        <div class="col-md-8">

            <div class="text-center my-5">
                <img class='img-fluid' src="img/la_boutique_bis.webp" alt="Bandeau de La Boutique" loading="lazy">
            </div>

            <div class="row justify-content-around">

                <h2 class="py-5">
                    <div class="badge badge-dark text-wrap">Annonce <?= ucfirst($titreAnnonce['titre']) ?>s </div>
                </h2>
            </div>

            <div class="row justify-content-around text-center">
            <!-- boucle while qui récupérer toutes lesannonces  s'adressant à un même titre -->
            <?php while($annonce = $afficheAnnonces->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                    <a href="fiche_annonce.php?id_annonce=<?= $annonce['id_categorie']?>"><img src="<?= URL . 'img/' . $annonce['id_categorie'] ?>" class="card-img-top" alt="Photo de <?= $annonce['titre'] ?>"></a>
                    <div class="card-body">
                        <h3 class="card-title"><?= $annonce['titre'] ?></h3>
                        <h3 class="card-title">
                            <div class="badge badge-dark text-wrap"><?= $annonce['prix'] ?> €</div>
                        </h3>
                        <p class="card-text"><?= $annonce['description'] ?></p>
                        <a href="fiche_annonce.php?id_annonce=<?= $annonce['id_annonce']?>" class="btn btn-outline-success"><i class='bi bi-search'></i> Voir les annonces</a>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>

            <nav aria-label="">
                <!-- dans les 3 <a href> je dois faire référence à la catégorie, en plus de la page, sinon cela ne fonctionnera pas -->
                <ul class="pagination justify-content-end">
                    <li class="mx-1 page-item  ">
                        <a class="page-link text-success" href="" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>

                    <li class="mx-1 page-item ">
                        <a class="btn btn-outline-success " href=""></a>
                    </li>

                    <li class="mx-1 page-item ">
                        <a class="page-link text-success" href="" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

        <!-- ------------------------------ -->
        <?php else: ?>
        <div class="col-md-8">

            <div class="row justify-content-around py-5">
                <img class='img-fluid' src="img/banniere-annonceo.png" alt="Bandeau de La Boutique" loading="lazy">
            </div>

        </div>
        <?php endif; ?>

    </div>

</div>
<div class="container">

    <?php require_once('include/footer.php') ?>