<?php
require_once('include/init.php');

// $pageTitle = "Profil de " . $_SESSION['membre']['pseudo'];

// si le user n'est PAS connecté, alors on lui interdit l'accès à la page profil (redirection vers la page connexion ou autre selon reflexion)

if(!internauteConnecte()){
    header('location' . URL . 'connexion.php');
    exit();
}
if(isset($_GET['action']) && $_GET['action'] == 'validate') {

    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    Félicitations, vous etes connecté 😉 !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}


require_once('include/header.php');
?>

<?= $validate ?>

<!-- FORM PROFIL UTILISATEUR -->
<div class="container my-5">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h1 class="text-center text-wrap">Bonjour <?= (internauteConnecteAdmin()) ? $_SESSION['membre']['pseudo'] .' ! <br> ' . "Vous êtes admin du site" : $_SESSION['membre']['pseudo'] ?></h1>
					</div>
					<div class="card-body">
						<div class="row justify-content-around py-5">
							<div class="col-md-8">
								<ul class="list-group">
                                    <li class="btn btn-outline-dark text-dark my-3 shadow bg-white rounded"><?=  $_SESSION['membre']['prenom'] ?></li>
                                    <li class="btn btn-outline-dark text-dark my-3 shadow bg-white rounded"><?=  $_SESSION['membre']['nom'] ?></li>
                                    <li class="btn btn-outline-dark text-dark my-3 shadow bg-white rounded"><?=  $_SESSION['membre']['email'] ?></li>
                                </ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    </div>
    <div class="container my-5 ">
        <div class="row justify-content-center"><a href="<?= URL ?>deposer_annonce.php"><button class="shadow btn btn-dark btn-outline-success  px-4  ">Déposez votre annonce</button></a></div>
    </div>
    



<?php require_once('include/footer.php'); ?>