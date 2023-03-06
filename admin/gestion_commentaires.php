<?php
require_once('../include/init.php');

if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'connexion.php');
    exit();
}
// pagination selon les commentaires

// si un indice page existe dans l'url et qu'on retrouve une valeur dedans
if(isset($_GET['page']) && !empty($_GET['page'])){
    $pageCourante = (int) strip_tags($_GET['page']);
}else{
    // dans le cas ou aucune information n'a transité dans l'URL, $pageCourante prendra la valeur de defaut qui est 1
    $pageCourante = 1;
}

$queryCommentaires = $pdo->query("SELECT COUNT(id_commentaire) AS nombreCommentaires FROM commentaire" );
$resultatCommentaires = $queryCommentaires->fetch();
$nombreCommentaires = (int) $resultatCommentaires['nombreCommentaires'];
// je veux que sur chaque page s'affiche 10 commentaires
$parPage =  10; 
$nombrePages = ceil($nombreCommentaires / $parPage);
//  definir le premier commentaire qui va s'afficher à chaque nouvelle page
$premierCommentaire = ($pageCourante - 1) * $parPage;

// fin pagination
// ************ CONTRAINTE ************
// 1ére contrainte
if (isset($_GET['action'])) {
// tous ce qui va concernée l'envoie en base de donnée
    if ($_POST) {
// Les contraintes pour chaque champs

        if (!isset($_POST['commentaire']) || iconv_strlen($_POST['commentaire']) < 3 || iconv_strlen($_POST['commentaire']) > 250) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format commentaire !</div>';
        }

        // Condition si la personne à bien renseigner les champs et ne s'est pas tromper
        if (empty($erreur)) {
            // si dans l'URL action == update, on entame une procédure de modification
            if ($_GET['action'] == 'update') {
                $modifCommentaire = $pdo->prepare(" UPDATE commentaire SET id_commentaire = :id_commentaire, commentaire = :commentaire WHERE id_commentaire = :id_commentaire ");
                $modifCommentaire->bindValue(':id_commentaire', $_POST['id_commentaire'], PDO::PARAM_INT);
                $modifCommentaire->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
                $modifCommentaire->execute();
                // Requete pour afficher un message personnaliser lorsque la modification à bien été réussie
                $queryCommentaire = $pdo->query(" SELECT titre FROM commentaire WHERE id_commentaire = '$_GET[id_commentaire]' ");
                // le query permet de cibler un élément tandis que le fetch permet de récupérer la cible
                $commentaire = $queryCommentaire->fetch(PDO::FETCH_ASSOC);

                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Modification du commentaire '. $commentaire['titre'] .' réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {

                $inscrireCommentaire = $pdo->prepare(" INSERT INTO commentaire ( commentaire) VALUES (:commentaire) ");
                $inscrireCommentaire->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
                $inscrireCommentaire->execute();
            }
        }
    }

    // procédure de récupération des infos en BDD pour les afficher dans le formulaire lorsque on fait un update (plus pratique et plus sur)
    if ($_GET['action'] == 'update') {
        $tousCommentaire = $pdo->query("SELECT * FROM commentaire WHERE id_commentaire = '$_GET[id_commentaire]' ");
        $commentaireActuel = $tousCommentaire->fetch(PDO::FETCH_ASSOC);
    }

    $id_commentaire = (isset($commentaireActuel['id_commentaire'])) ? $commentaireActuel['id_commentaire'] : "";
    $commentaire = (isset($commentaireActuel['commentaire'])) ? $commentaireActuel['commentaire'] : "";


    // Requete pour effectuer une Supression
    if($_GET['action'] == 'delete'){
        $pdo->query(" DELETE FROM commentaire WHERE id_commentaire = '$_GET[id_commentaire]' ");
    }
}
require_once('includeAdmin/header.php');
?>


<h1 class="text-center my-5">
    <div class="badge badge-warning text-wrap p-3">Gestion des commentaires</div>
</h1>

<?= $erreur ?>
<?= $content ?>

<!-- <?= debug($_POST) ?> -->

<?php if (isset($_GET['action']) && isset($_GET['page'])) : ?>
<div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
    <p>Gérez ici votre base de données des commentaires</p>
    <p>Vous pouvez modifier leurs données, ajouter ou supprimer un commentaire</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if(isset($_GET['action'])): ?>
<h2 class="pt-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des commentaires</h2>

<form id="monForm" class="my-5" method="POST" action="" enctype="multipart/form-data">
    <!-- Important d'incorporer l'id_commentaire pour effectuer des modifications et de le cacher avec hidden  -->
    <input type="hidden" name="id_commentaire" value="<?= $id_commentaire ?>">



    <div class="row justify-content-around mt-5">
        <div class="col-md-6">
            <label class="form-label" for="commentaire">
                <div class="badge badge-dark text-wrap">Commentaire</div>
            </label>
            <textarea class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire" rows="5" ><?= $commentaire ?>"</textarea>
        </div>
    </div>


    <div class="col-md-1 mt-5">
        <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
    </div>

</form>
<?php endif; ?>

<?php $queryCommentaires = $pdo->query(" SELECT id_commentaire FROM commentaire "); ?>
<h2 class="py-5">Nombre de Commentaires en base de données: <?= $queryCommentaires->rowCount() ?></h2>

<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Ajouter une commentaire
        </button>
    </a>
</div>

<table class="table table-dark text-center table-responsive">
    <!-- Complété pour n'afficher que 10 prduits dans le tableau le OFFST détermine quel commentaire affichée dans la nouvelle page -->
    <?php $afficheCommentaires = $pdo->query("SELECT * FROM commentaire ") ?>
    <thead>
        <tr>
            <?php for ($i = 0; $i < $afficheCommentaires->columnCount(); $i++) :
                $colonne = $afficheCommentaires->getColumnMeta($i) ?>
                <th><?= $colonne['name'] ?></th>
            <?php endfor; ?>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($commentaire = $afficheCommentaires->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($commentaire as $key => $value) : ?>
                    <?php if ($key == 'prix') : ?>
                        <td><?= $value ?> €</td>
                    <?php elseif ($key == 'photo') : ?>
                        <td><img class="img-fluid" src="<?= URL . 'img/' . $value ?>" width="50" loading="lazy"></td>
                    <?php else : ?>
                        <td><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <!-- Crayon pour modifier (UPDATE) et pobelle pour supprimer (DELETE) -->
                <td><a href='?action=update&id_commentaire=<?= $commentaire['id_commentaire'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href="?action=delete&id_commentaire=<?= $commentaire['id_commentaire'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<!-- Debut de pagignation -->
<nav>
    <ul class="pagination justify-content-end">
        <!-- dans le cas ou nous sommes sur la page 1, il ne faudra pas pouvoir cliquer sur l'onglet précédent, sinon on sera expédiée à la page 0 !  Il faut donc dans ce cas (voir ternaire) si on est sur la page 1 , -->
        <li class="page-item <?= ($pageCourante == 1 ) ? 'disabled' : "" ?>">
        <!-- si on clique sur la fleche précédente, c'est pour aller à la page précédent, dans ce cas, on soustrait à page Courante, la valeur de 1 (si pageCourante = 4, on retournera à la page 3) -->
            <a class="page-link text-dark" href="?page=<?= $pageCourante -1?>" aria-label="Previous">
                <span aria-hidden="true">précédente</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <!-- AFFICHE LE NOMBRE DE PAGES pour cliquer celle que l'on veut -->
        <?php for($page = 1; $page <= $nombrePages; $page++): ?>
        <li class="mx-1 page-item ">
            <a class="btn btn-outline-dark <?= ($pageCourante == $page) ?'active' : "" ?>" href="?page=<?= $page ?>"><?= $page ?></a>
        </li>
        <?php endfor; ?>

        <!-- FIN NOMBRE DE PAGES -->
        <li class="page-item <?= ($pageCourante == $nombrePages)? 'disabled' : '' ?>">
            <a class="page-link text-dark" href="?page=<?= $pageCourante +1?>" aria-label="Next">
                <span aria-hidden="true">suivante</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>

<!-- <img class="img-fluid" src="" width="50"> -->

<!-- <td><a href=''><i class="bi bi-pen-fill text-warning"></i></a></td>-->
<!-- <td><a data-href="" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td> -->


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