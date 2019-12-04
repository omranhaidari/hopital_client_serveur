<?php 

	// Recherche de patient
	if(isset($_POST['rechercher_patient'])) {
		echo'
		<div id="celluleDroite">
			<button onclick="history.go(-1);" id="backButton">Retour</button>
			<h2>Résultat de la recherche</h2>

			<ul>';

				if(mysqli_num_rows($resultat_patient) == 0) {  
					if(!empty($nomPatient))
						echo "Aucun résultat trouvé pour : $nomPatient !";
					else
						echo "Aucun résultat trouvé !";
         		} else {
					// Pour chaque résultat récupéré par la requête, il y a l'affichage des noms et prénoms des patients correspondants à la recherche, sous forme de liste et de lien hypertexte (avec le code/identifiant du patient en paramètre, par l'URL).
					while($dataR3 = mysqli_fetch_array($resultat_patient))
					{
						echo'<li><a href="index.php?param='.utf8_encode($dataR3["code"]).'">'.utf8_encode($dataR3["nom"]).' '.utf8_encode($dataR3["prenom"]).'</a></li>';
					}
				}

			echo'
			</ul>
		</div>';
	}

	// Recherche de document
	if(isset($_POST['rechercher_document'])) {
		echo'
		<div id="celluleDroite">
			<button onclick="history.go(-1);" id="backButton">Retour</button>
			<h2>Résultat de la recherche</h2>';

			if(mysqli_num_rows($resultat_document) == 0) {  
				echo "Aucun document trouvé !";
     		} else {

     			echo'
				<table>
					<tr>
						<th>Nom fichier</th>
						<th>Type</th>
						<th>Format</th>
						<th>Date</th>
						<th>Visualiser</th>
						<th>Télécharger</th>
						<th>Imprimer</th>
						<th>Partager</th>
					</tr>';

					while($dataR4 = mysqli_fetch_array($resultat_document)) {
						echo'
						<tr>
							<td>'.utf8_encode($dataR4["nom_fichier"]).'</td>
							<td>'.utf8_encode($dataR4["type"]).'</td>
							<td>'.utf8_encode($dataR4["format"]).'</td>
							<td>'.date("d/m/Y", strtotime(utf8_encode($dataR4["date"]))).'</td>
							<td><a href="mesDocumentsUploades/'.utf8_encode($dataR4["nom_fichier"]).'" target="_blank"><img src="images/preview-file.png" title="Prévisualiser le document" alt="preview-file" height="30px" width="30px"/></a></td>
							<td><a href="mesDocumentsUploades/'.utf8_encode($dataR4["nom_fichier"]).'" download="'.utf8_encode($dataR4["nom_fichier"]).'"><img src="images/download-file.jpg" title="Télécharger le document" alt="download-file" height="25px" width="25px"/></a></td>
							<td><a href="print.php?url='.utf8_encode($dataR4["nom_fichier"]).'" target="_blank"><img src="images/print-file.png" title="Imprimer le document" alt="print-file" height="30px" width="30px"/></a></td>
							<td><a href="share.php?url='.utf8_encode($dataR4["nom_fichier"]).'" target="_blank"><img src="images/share-file.png" title="Partager le document par email" alt="share-file" height="30px" width="30px"/></a></td>
						</tr>';
					}
				echo'
				</table>';

			}
			
			echo'
		</div>';
	}

?>