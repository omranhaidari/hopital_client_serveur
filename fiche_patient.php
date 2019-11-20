<?php

	if(isset($_GET['param'])) {
		// Récupération des données du patient sélectionné.
		$selectProfil = mysqli_query($connexion, "SELECT patient.nom, patient.prenom, patient.sexe, patient.date_naissance, patient.num_secu, patient.code_pays, patient.date_prem_entree, motif.libelle FROM patient, motif WHERE patient.code_motif = motif.code AND patient.code = ".$_GET['param']);

		echo' 
		<div id="celluleDroite">
			<h2>Fiche détaillée du patient</h2>
			
			<table>
			
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Sexe</th>
					<th>Date de naissance</th>
					<th>Numéro Sécu</th>
					<th>Code pays</th>
					<th>Date première entrée</th>
					<th>Motif</th>
				</tr>';

				// Possibilité d'affichage en liste : Nom : x, ... ?
				// Affichage de la fiche détaillée du patient sélectionné, dont l'identifiant a été passé en paramètre (par URL), sous forme d'un tableau.
				while($dataR4 = mysqli_fetch_array($selectProfil))
				{
					if($dataR4["sexe"] == "M") {
						echo'
						<center>
							<img id=inconnu src="images/inconnu.jpeg">
							<br>
							<br>'
							.$dataR4["nom"].' '.$dataR4["prenom"].'
						</center>
						<br>';
					}

					if($dataR4["sexe"] == "F") {
						echo'
						<center>
							<img id=inconnu src="images/inconnue.png">
							<br>
							<br>'
							.$dataR4["nom"].' '.$dataR4["prenom"].'
						</center>
						<br>';
					}


					echo'
					<tr>
						<td>'.strtoupper(utf8_encode($dataR4["nom"])).'</td> <!-- pour être sûr que le nom est en majuscule -->
						<td>'.utf8_encode($dataR4["prenom"]).'</td>
						<td>'.utf8_encode($dataR4["sexe"]).'</td>
						<td>'.date("d/m/Y", strtotime(utf8_encode($dataR4["date_naissance"]))).'</td> <!-- changement du format de la date -->
						<td>'.utf8_encode($dataR4["num_secu"]).'</td>
						<td>'.utf8_encode($dataR4["code_pays"]).'</td>
						<td>'.date("d/m/Y", strtotime(utf8_encode($dataR4["date_prem_entree"]))).'</td> <!-- changement du format de la date -->
						<td>'.utf8_encode($dataR4["libelle"]).'</td>
					</tr>';
				}
				
			echo'
			</table>
		</div>';
	}

?>