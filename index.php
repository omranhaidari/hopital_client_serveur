<?php 
//header( 'content-type: text/html; charset=utf-8' );

//include("header.php");
//include("rechercheMultiCrieteres.php");
//include("affichageDetails.php");
//include("affichageResultat.php"); 
//include("footer.php"); 
//?>


<meta charset="utf-8" />

<?php

$bdd = new PDO('mysql:host=localhost;dbname=hopital_php;charset=utf8','user1','hcetylop');
?>

<!-- Barre de recherche : nom et prénom -->

<?php
$patients = $bdd->query('SELECT nom, prenom FROM patient ORDER BY nom, prenom ASC');

	if(isset($_GET['q']) AND !empty($_GET['q'])) { // q est le nom de la requête query
		$q = htmlspecialchars($_GET['q']); // pour la sécurisation et éviter modifications
		$patients = $bdd->query('SELECT nom, prenom FROM patient WHERE nom LIKE "%'.$q.'%" or prenom LIKE "%'.$q.'%" ORDER BY nom, prenom ASC');
	}
	?>

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
		Aucun résultat pour : <?= $q ?>
	<?php } ?>



	<!-- Liste déroulante motifs admission -->

	<label for=" ">Nom du champ </label>
	<select name=" "  id=" " required>
		<?php $reponse = $bdd->query('SELECT nom FROM patient ORDER BY nom ASC');?>
		<option select>Indifférent</option>
		<?php 
		while ($donnees = $reponse->fetch())
		{
			?>
			<option value="<?php echo $donnees['nom']; ?>"> 
				<?php echo $donnees['nom']; ?>
			</option>
		<?php } ?>
	</select>

	<br>

	<!-- Liste déroulante nom pays -->

	<label for=" ">Nom du champ </label>
	<select name=" "  id=" " required>
		<?php $reponse = $bdd->query('SELECT nom FROM patient ORDER BY nom ASC');?>
		<option select>Indifférent</option>
		<?php 
		while ($donnees = $reponse->fetch())
		{
			?>
			<option value="<?php echo $donnees['nom']; ?>"> 
				<?php echo $donnees['nom']; ?>
			</option>
		<?php } ?>
	</select>

	<br>

