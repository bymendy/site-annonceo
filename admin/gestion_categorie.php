<?php
require_once('../include/init.php');

if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'connexion.php');
    exit();
}

// ************ CONTRAINTE ************

if (isset($_GET['action'])) {

    if ($_POST) {

// Les contraintes pour chaque champs        
        if (!isset($_POST['titre']) || iconv_strlen($_POST['titre']) < 3 || iconv_strlen($_POST['titre']) > 20) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
        }

        if (!isset($_POST['motscles']) || iconv_strlen($_POST['motscles']) < 3 || iconv_strlen($_POST['motscles']) > 20) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format motscles !</div>';
        }

        // Condition si user à bien renseigner les champs et ne s'est pas tromper
        if (empty($erreur)) {
            // si dans l'URL action == update, on on modifie
            if ($_GET['action'] == 'update') {
                $modifCategorie = $pdo->prepare(" UPDATE categorie SET id_categorie = :id_categorie , titre = :titre, motscles = :motscles WHERE id_categorie = :id_categorie ");
                $modifCategorie->bindValue(':id_categorie', $_POST['id_categorie'], PDO::PARAM_INT);
                $modifCategorie->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $modifCategorie->bindValue(':motscles', $_POST['motscles'], PDO::PARAM_STR);
                $modifCategorie->execute();

                // Requete pour afficher un message personnaliser lorsque la modification à bien été réussie
                $queryCategorie = $pdo->query(" SELECT categorie FROM titre WHERE id_categorie = '$_GET[id_categorie]' ");
                $Categorie = $queryCategorie->fetch(PDO::FETCH_ASSOC);

                $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Modification de l`\'utilisateur '. $Categorie['categorie'] .' réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                // si on récupère autre chose que update (et donc add) on entame une procédure d'insertion en BDD
                $inscrireCategorie = $pdo->prepare(" INSERT INTO categorie (id_categorie, titre, motscles, categorie,date_enregistrement) VALUES (:id_categorie, :titre, :motscles) ");
                
                $inscrireCategorie->bindValue(':id_categorie', $_POST['id_categorie'], PDO::PARAM_STR);
                $inscrireCategorie->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $inscrireCategorie->bindValue(':motscles', $_POST['motscles'], PDO::PARAM_STR);
                $inscrireCategorie->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_INT);
                $inscrireCategorie->execute();
            }
        }
    }

    // procédure de récupération des infos en BDD pour les afficher dans le formulaire lorsque on fait un update (plus pratique et plus sur)
    if ($_GET['action'] == 'update') {
        $tousCategories = $pdo->query("SELECT * FROM categorie WHERE titre = '$_GET[titre]' ");
        $categorieActuel = $tousCategories->fetch(PDO::FETCH_ASSOC);
    }

    $titre = (isset($categorieActuel['titre'])) ? $categorieActuel['titre'] : "";
    $motscles = (isset($categorieActuel['mostscles'])) ? $categorieActuel['mostscles'] : "";
   
    
    // syntaxe de condition classique équivalente à la ternaire juste au dessus
    /*if(isset($categorieActuel['pseudo'])){
            $pseudo = $categorieActuel['pseudo'];
        }else{
            $pseudo = "";
        }*/

    if($_GET['action'] == 'delete'){
        // requete de suppression d'une entrée (pas besoin de stocker une valeur dans une variable que l'on declare, on travaille directement avec l'objet $pdo qui pointe sur la méthode query pour faire un DELETE)
        $pdo->query(" DELETE FROM categorie WHERE id_categorie = '$_GET[id_categorie]' ");
    }
}

require_once('includeAdmin/header.php');
?>


<h1 class="text-center my-5">
    <div class="badge badge-warning text-wrap p-3">Gestion des categories</div>
</h1>

<?= $erreur ?>
<?= $content ?>

<?php if (isset($_GET['action'])) : ?>
    <h2 class="my-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?> des categories</h2>

<!-- FORMULAIRE -->

    <form class="my-5" method="POST" action="">
        <input type="hidden" name="titre" value="<?= $titre ?>">

        <div class="row">
            <div class="col-md-4 mt-5">
                <label class="form-label" for="titre">
                    <div class="badge badge-dark text-wrap">Titre</div>
                </label>
                <input class="form-control" type="text" name="titre" id="titre" placeholder="titre" value="<?= $titre ?>">
            </div>
            <div class="col-md-4 mt-5">
                <label class="form-label" for="titre">
                    <div class="badge badge-dark text-wrap">Titre</div>
                </label>
                <input class="form-control" type="text" name="motscles" id="motscles" placeholder="mots clés" value="<?= $motscles ?>">
            </div>


      
           
        </div>

        <div class="col-md-1 mt-5">
            <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
        </div>

    </form>
<?php endif; ?>

<!-- requete SQL pour récupérer le nb d'categories inscrits en BDD, nb que je pourrais afficher grace à rowCount deux lignes en dessous -->
<?php $nbCategories = $pdo->query("SELECT id_categorie FROM categorie"); ?>
<h2 class="py-5">Nombre de categories en base de données: <?= $nbCategories->rowCount() ?></h2>

<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded">
            <i class="bi bi-plus-circle-fill"></i> Ajouter une categorie
        </button>
    </a>
</div>

<table class="table table-dark text-center">
    <?php $affichecategorie = $pdo->query("SELECT * FROM categorie ORDER BY titre ASC "); ?>
    <thead>
        <tr>
            <?php for ($i = 0; $i < $affichecategorie->columnCount(); $i++) :
                $colonne = $affichecategorie->getColumnMeta(($i)) ?>
                <?php if ($colonne['name'] != 'mdp') : ?>
                    <th><?= $colonne['name'] ?></th>
                <?php endif; ?>
            <?php endfor; ?>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($Categorie = $affichecategorie->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($Categorie as $key => $value) : ?>
                    <?php if ($key != 'mdp') : ?>
                        <td><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td><a href='?action=update&id_categorie=<?= $Categorie['id_categorie'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
                <td><a data-href="?action=delete&id_categorie=<?= $Categorie['id_categorie'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination justify-content-end">
        <li class="page-item ">
            <a class="page-link text-dark" href="" aria-label="Previous">
                <span aria-hidden="true">précédente</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>

        <li class="mx-1 page-item">
            <a class="btn btn-outline-dark " href=""></a>
        </li>

        <li class="page-item ">
            <a class="page-link text-dark" href="" aria-label="Next">
                <span aria-hidden="true">suivante</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>

<!-- <td><a href=''><i class="bi bi-pen-fill text-warning"></i></a></td>-->
<!-- <td><a data-href="" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td> -->

<!-- modal suppression codepen https://codepen.io/lowpez/pen/rvXbJq -->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer categorie
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer cette categorie de votre base de données ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- modal -->

<!-- pour empecher la modale de s'ouvrir à chaque rafraichissement de page, le temps de terminer de coder cette page -->
<?php if (!isset($_GET['action']) && !isset($_GET['page'])) : ?>
    <!-- modal infos -->
    <div class="modal fade" id="myModalCategories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Gestion des categories</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Gérez ici votre base de données des categories</p>
                    <p>Vous pouvez modifier leurs données, ajouter ou supprimer un categorie</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-warning text-dark" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
<?php endif; ?>

<?php require_once('includeAdmin/footer.php'); ?>