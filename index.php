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
					$patients = $bdd->query('SELECT nom, prenom FROM patient ORDER BY nom, prenom ASC');

					if(isset($_GET['q']) AND !empty($_GET['q'])) { // q est le nom de la requête query
						$q = htmlspecialchars($_GET['q']); // pour la sécurisation et éviter modifications
						$patients = $bdd->query('SELECT nom, prenom FROM patient WHERE nom LIKE "%'.$q.'%" or prenom LIKE "%'.$q.'%" ORDER BY nom, prenom ASC');
					}
				?>

				<h2> Rechercher un patient </h2>

				<form id="searchthis" method="GET">
					<input id="search" name="q" type="text" placeholder="Rechercher..." />
					<input id="search-btn" type="submit" value="Rechercher" />
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
					<option select value="indifferent">Indifférent</option>
					<?php 
					while ($donnees = $reponse->fetch())
					{
						?>
						<option value="<?php echo $donnees['libelle']; ?>"> 
							<?php echo $donnees['libelle']; ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<br>

			<!-- Liste déroulante nom pays -->

			<div id="cellule">
				<label for=" ">Noms de pays : </label>
				</br></br>
				<select name=" "  id=" " required>
					<?php $reponse = $bdd->query('SELECT libelle FROM pays ORDER BY libelle ASC');?>
					<option select value="indifferent">Indifférent</option>
					<?php 
					while ($donnees = $reponse->fetch())
					{
						?>
						<option value="<?php echo $donnees['libelle']; ?>"> 
							<?php echo $donnees['libelle']; ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<br>

			<!-- Liste déroulante pour début intervalle date de naissance -->

			<div id="cellule">

				<label for=" ">Date de début : </label>
				</br></br>
				<?php 
					// Variable qui ajoutera l'attribut selected de la liste déroulante
					$selectedDateDebut = '';

					echo '<select name="annees">';
					echo "\t",'<option value="indifferent"', $selectedDateDebut ,'>Indifférent</option>',"\n"; 
					for($i=date('Y'); $i>=date('Y') - 119; $i--)
					{
						echo "\t",'<option value="', $i ,'"', $selectedDateDebut ,'>', $i ,'</option>',"\n";   
						$selectedDateDebut='';  
					}  
					echo '</select>',"\n";
				?>

				<br>

			<!-- Liste déroulante pour fin intervalle date de naissance -->

				<label for=" ">Date de fin : </label>
				</br></br>
				<?php 
					// Variable qui ajoutera l'attribut selected de la liste déroulante
					$selectedDateFin = '';

					echo '<select name="annees">';
					echo "\t",'<option value="indifferent"', $selectedDateFin ,'>Indifférent</option>',"\n"; 
					for($i=date('Y'); $i>=date('Y') - 119; $i--)
					{
						echo "\t",'<option value="', $i ,'"', $seleselectedDateFin ,'>', $i ,'</option>',"\n";   
						$selectedDateFin='';  
					}  
					echo '</select>',"\n";
				?>
			</div>

			<br>

			<div id="cellule">

				<?php
					$patients = $bdd->query('SELECT nom, prenom FROM patient WHERE date_naiss BETWEEN '.$selectedDateDebut.' AND '.$selectedDateDebut.' ORDER BY nom, prenom ASC');

						if(isset($_GET['q2']) AND !empty($_GET['q2'])) { // q est le nom de la requête query
						$q = htmlspecialchars($_GET['q2']); // pour la sécurisation et éviter modifications
						$patients = $bdd->query('SELECT nom, prenom FROM patient WHERE date_naiss BETWEEN '.$selectedDateDebut.' AND '.$selectedDateDebut.' ORDER BY nom, prenom ASC');
					}
				?>
				<form id="searchthis" method="GET">
					<input id="search-btn" type="submit" value="Rechercher" />
				</form>
			</div>
		</div>
	</body>
</html>
