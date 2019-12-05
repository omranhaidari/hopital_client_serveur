<?php

	if(isset($_GET['param'])) {
		// Récupération des données du patient sélectionné.
		$selectProfil = mysqli_query($connexion, "SELECT patient.nom, patient.prenom, patient.sexe, patient.date_naissance, patient.num_secu, pays.libelle AS libelle_pays, patient.date_prem_entree, motif.libelle AS libelle_motif FROM patient, motif, pays WHERE patient.code_pays = pays.code AND patient.code_motif = motif.code AND patient.code = ".$_GET['param']);

		// Récupération des documents du patient sélectionné.
		$selectDocuments = mysqli_query($connexion, "SELECT nom_fichier, type, format, date FROM patient, document WHERE patient.code = document.code_patient AND patient.code = ".$_GET['param']);

		echo' 
		<div id="celluleDroite">
			<button onclick="history.go(-2);" id="backButton">Retour</button>
			<h2>Fiche détaillée du patient</h2>
			
			<table>
			
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Sexe</th>
					<th>Date de naissance</th>
					<th>Numéro Sécu</th>
					<th>Pays</th>
					<th>Date première entrée</th>
					<th>Motif</th>
				</tr>';

				// Affichage de la fiche détaillée du patient sélectionné, dont l'identifiant a été passé en paramètre (par URL), sous forme d'un tableau.
				while($dataR4 = mysqli_fetch_array($selectProfil))
				{
					// Si le patient est un homme, on affiche la photo d'un homme ainsi que ses nom et prénom.
					if($dataR4["sexe"] == "M") {
						echo'
						<center>
							<img id=inconnu src="images/inconnu.jpeg">
							<br>
							<br>'
								.utf8_encode($dataR4["nom"]).' '.utf8_encode($dataR4["prenom"]).'
						</center>
						<br>';
					}

					// Si le patient est une femme, on affiche la photo d'une femme ainsi que ses nom et prénom.
					if($dataR4["sexe"] == "F") {
						echo'
						<center>
							<img id=inconnu src="images/inconnue.png">
							<br>
							<br>'
								.utf8_encode($dataR4["nom"]).' '.utf8_encode($dataR4["prenom"]).'
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
						<td>'.utf8_encode($dataR4["libelle_pays"]).'</td>
						<td>'.date("d/m/Y", strtotime(utf8_encode($dataR4["date_prem_entree"]))).'</td> <!-- changement du format de la date -->
						<td>'.utf8_encode($dataR4["libelle_motif"]).'</td>
					</tr>';
				}
				
			echo'
			</table>
		</div>';



		// Liste des documents du patient
		if(mysqli_num_rows($selectDocuments) > 0) { 
			echo'
			<div id="celluleDroite">';
				echo'
				<h2>Liste des documents</h2>
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

					// Retourne toutes les lignes de résultat pour les documents du patient sous la forme d'un tableau.
					while($dataR5 = mysqli_fetch_array($selectDocuments)) {
						echo'
						<tr>
							<td>'.utf8_encode($dataR5["nom_fichier"]).'</td>
							<td>'.utf8_encode($dataR5["type"]).'</td>
							<td>'.utf8_encode($dataR5["format"]).'</td>
							<td>'.date("d/m/Y", strtotime(utf8_encode($dataR5["date"]))).'</td>
							<td><a href="mesDocumentsUploades/'.utf8_encode($dataR5["nom_fichier"]).'" target="_blank"><img src="images/preview-file.png" title="Prévisualiser le document" alt="preview-file" height="30px" width="30px"/></a></td>
							<td><a href="mesDocumentsUploades/'.utf8_encode($dataR5["nom_fichier"]).'" download="'.utf8_encode($dataR5["nom_fichier"]).'"><img src="images/download-file.jpg" title="Télécharger le document" alt="download-file" height="25px" width="25px"/></a></td>
							<td><a href="./document/print.php?url='.utf8_encode($dataR5["nom_fichier"]).'" target="_blank"><img src="images/print-file.png" title="Imprimer le document" alt="print-file" height="30px" width="30px"/></a></td>
							<td><a href="./document/share.php?url='.utf8_encode($dataR5["nom_fichier"]).'" target="_blank"><img src="images/share-file.png" title="Partager le document par email" alt="share-file" height="30px" width="30px"/></a></td>
						</tr>';
					}

				echo'
				</table>
			</div>';
		}
		


		// Enregistrement des documents
	    echo'
	    <center>
	    	<div id="celluleUpload">
				<h2>Enregistrer un document</h2>';
				
				// Si il y a eu une demande d'enregistrement de document, on affiche le message d'erreur ou de succès
				if(isset($_GET['msg'])){
					if($_GET['msg'] == null){
						echo'<h3 style="color:green">Votre document a été enregistré avec succès !</h3>';
					}else{
						echo'<h3 style="color:red">'.$_GET['msg'].'</h3>';
					}
				}
				
				echo'
		        <form action="./document/upload.php" method="post" enctype="multipart/form-data" class="formulaire">
					<fieldset>
						</br>
				        <h4>Fichier :</h4>
				        <input type="file" name="file" id="file" required>

						<h4>Type de document :</h4>
						<select name="type_doc" id="type_doc" width="10p%" required>
							<option value="">Choisir un type de document</option>
							<option value="Bilan general">Bilan general</option>
							<option value="Bilan sanguin">Bilan sanguin</option>
							<option value="Compte rendu de consultation">Compte rendu de consultation</option>
							<option value="Compte rendu d\'intervention">Compte rendu d\'intervention</option>
							<option value="Compte rendu d\'hospitalisation">Compte rendu d\'hospitalisation</option>
							<option value="Feuille de surveillance">Feuille de surveillance</option>
							<option value="Ordonnance">Ordonnance</option>
							<option value="Radio">Radio</option>
							<option value="Resultats d\'examen">Resultats d\'examen</option>
						</select>
						
						<input id="param" name="param" type="hidden" value="'.$_GET['param'].'">

				        <input type="submit" name="submit" value="Enregistrer" id="submit">
				        <p><strong>Note:</strong> Seuls les formats .jpg, .jpeg, .gif, .png et .pdf sont autorisés jusqu\'à une taille maximale de 10 Mo.</p>
			        </fieldset>
		    	</form>
			</div>
		<center>';
	   
	}

?>