<?php 
header( 'content-type: text/html; charset=utf-8' );

//include("header.php");
//include("rechercheMultiCrieteres.php");
//include("affichageDetails.php");
//include("affichageResultat.php"); 
//include("footer.php"); 
//
?>

<html>
	<head>
		<meta charset="utf-8" />
		<link href="css/index.css" rel="stylesheet"> <!-- Mettre en commentaire cette ligne pour enlever le CSS -->
		<title>Hopital</title>
	<head>

	<body>

<?php $bdd = new PDO('mysql:host=localhost;dbname=hopital_php;charset=utf8','user1','hcetylop');?>

<!-- Barre de recherche : nom et prénom -->

		<div id="gauche">
			<div id="cellule">
		
				<?php
				$patients = $bdd->query('SELECT nom, prenom FROM patient ORDER BY nom, prenom ASC');

				if(isset($_GET['q']) AND !empty($_GET['q'])) { // q est le nom de la requête query
						$q = htmlspecialchars($_GET['q']); // pour la sécurisation et éviter modifications
					$patients = $bdd->query('SELECT nom, prenom FROM patient WHERE nom LIKE "%'.$q.'%" or prenom LIKE "%'.$q.'%" ORDER BY nom, prenom ASC');
				}
				?>
			
				<h2> Rechercher un patient </h2>

				<form method="GET">
					<input type="search" name="q" placeholder="Rechercher..." />
					<input type="submit" value="Valider" />
				</form>

				<?php if($patients->rowCount() > 0) { ?>

				<ul>
					<?php while($a = $patients->fetch()) { ?>
						<li><?= strtoupper($a['nom']) ?> - <?= $a['prenom'] ?></li> <!-- la méthode strtoupper retourne une chaîne de caractère en majuscule -->
					<?php } ?>
				</ul>
				<?php } else { ?>
				<p>Aucun résultat pour : <?= $q ?></p>
				<?php } ?>

			</div>

	<!-- Liste déroulante motifs admission -->
	
			<div id="cellule">

				<label for=" ">Motifs d'admission : </label>
				</br></br>
				<select name=" "  id=" " required>
					<?php $reponse = $bdd->query('SELECT libelle FROM motif ORDER BY libelle ASC');?>
					<option select>Indifférent</option>
					<?php 
					while ($donnees = $reponse->fetch())
					{
						?>
						<option value="<?php echo $donnees['libelle']; ?>"> 
							<?php echo $donnees['libelle']; ?>
						</option>
					<?php } ?>
				</select>
				<br>
			</div>

	<!-- Liste déroulante nom pays -->

			<div id="cellule">
				<label for=" ">Noms de pays : </label>
				</br></br>
				<select name=" "  id=" " required>
					<?php $reponse = $bdd->query('SELECT libelle FROM pays ORDER BY libelle ASC');?>
					<option select>Indifférent</option>
					<?php 
					while ($donnees = $reponse->fetch())
					{
						?>
						<option value="<?php echo $donnees['libelle']; ?>"> 
							<?php echo $donnees['libelle']; ?>
						</option>
					<?php } ?>
				</select>
				<br>
			</div>
		</div>
	</body>
</html>
