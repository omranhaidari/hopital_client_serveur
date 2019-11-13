<?php 
	header( 'content-type: text/html; charset=utf-8' );
?>

<html>
	<head>
		<meta charset="utf-8" />
		<link href="css/index.css" rel="stylesheet"> <!-- Mettre en commentaire cette ligne pour enlever le CSS -->
		<title>Hopital</title>
	<head>

	<body>

		<?php $bdd = new PDO('mysql:host=localhost;dbname=hopital_php;charset=utf8','user1','hcetylop');?>

		<div id="gauche">

			<!-- Barre de recherche : nom et prénom -->

			<div id="cellule">

				<?php
					$patients = $bdd->query('SELECT * FROM patient ORDER BY nom, prenom ASC');

					if(isset($_GET['q']) AND !empty($_GET['q'])) { // q est le nom de la requête query
						$q = htmlspecialchars($_GET['q']); // pour la sécurisation et éviter modifications
						$patients = $bdd->query('SELECT * FROM patient WHERE nom LIKE "%'.$q.'%" or prenom LIKE "%'.$q.'%" ORDER BY nom, prenom ASC');
					}
				?>

				<h2> Informations du patient </h2>

				<form id="searchthis" method="GET">
					<input id="search" name="q" type="text" placeholder="Rechercher..." />
					<input id="search-btn" type="submit" value="Rechercher" />
				</form>

				<?php if($patients->rowCount() > 0) { ?>

					<ul>
						<?php while($a = $patients->fetch()) { ?>
							<li><?= strtoupper($a['nom']) ?></li> <!-- la méthode strtoupper retourne une chaîne de caractère en majuscule --> 
							<li><?= $a['prenom'] ?></li> 
							<li><?= $a['code'] ?></li> 
						<?php } ?>
					</ul>
				<?php } else { ?>
					<p>Aucun résultat pour : <?= $q ?></p>
				<?php } ?>

			</div>
		</div>
	</body>
</html>
