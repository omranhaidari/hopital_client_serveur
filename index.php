<?php
header( 'content-type: text/html; charset=utf-8' );
	
$connexion = mysqli_connect("localhost", "user1", "hcetylop","hopital_php");
if (mysqli_connect_errno()){
	echo 'Failed to connect to MySQL: ' . mysqli_connect_error();
}

$SelectAllMotifs = mysqli_query($connexion, "SELECT libelle FROM motif ORDER BY libelle ASC");
$SelectAllPays = mysqli_query($connexion, "SELECT libelle FROM pays ORDER BY libelle ASC");
$requete = "SELECT DISTINCT patient.code, patient.nom, patient.prenom FROM patient, pays, motif WHERE patient.code_pays = pays.code AND patient.code_motif = motif.code ";

if(isset($_POST['rechercher'])) {

	$nomPatient = utf8_decode($_POST['nomPatient']);
	$nomPatient = htmlspecialchars($nomPatient); // pour sécuriser le formulaire contre les intrusions html
	$nomPatient = trim($nomPatient); // pour supprimer les espaces dans la requête de l'internaute
	$nomPatient = strip_tags($nomPatient); // pour supprimer les balises html dans la requête

	if(isset($nomPatient)  && !empty($nomPatient)){
		$requete = $requete."AND patient.nom LIKE '%".$nomPatient."%'";
	}
	
	$motifAdmission = utf8_decode($_POST['motifAdmission']);
	if($motifAdmission != "vide"){
		$requete = $requete."AND motif.libelle = '".$motifAdmission."' ";
	}
	
	$nomPays = utf8_decode($_POST['nomPays']);
	if($nomPays != "vide"){
		$requete = $requete."AND pays.libelle = '".$nomPays."' ";
	}
	
	$dateDebut = utf8_decode($_POST['dateDebut']);
	$dateFin = utf8_decode($_POST['dateFin']);
	if($dateDebut != "vide" OR $dateFin != "vide"){
		$requete = $requete."AND patient.date_naissance BETWEEN '".$dateDebut."' AND '".$dateFin."' ";
	}
	
	$resultat = mysqli_query($connexion,$requete."ORDER BY patient.nom, patient.prenom;");
}

if(isset($_GET['param'])) {
	$SelectProfil = mysqli_query($connexion, "SELECT patient.nom, patient.prenom, patient.sexe, patient.date_naissance, patient.num_secu, patient.code_pays, patient.date_prem_entree, motif.libelle FROM patient, motif WHERE patient.code_motif = motif.code AND patient.code = ".$_GET['param']);
}

echo'
<html>
	<head>
		<meta charset="utf-8" />
		<link href="css/index.css" rel="stylesheet">
		<title>Hopital</title>
	<head>

	<body>
		<div id="gauche">
			<div id="celluleGauche">
				<h2> Rechercher un patient </h2>
					
				<form action="index.php" method="post" class="formulaire">
					<fieldset>
					
						<legend>Formulaire</legend></br>
						
						<h4>Nom du patient :</h4>
						
						<input type="text" name="nomPatient" id="nomPatient" placeholder="ex : DUPONT"/></br>
						
						<h4>Motif d&apos;admission :</h4>
					
						<select name="motifAdmission" id="motifAdmission">
							<option selected="selected" value="vide">Indifférent</option>';
							while($dataR1 = mysqli_fetch_array($SelectAllMotifs))
							{
								echo'<option value="'.utf8_encode($dataR1["libelle"]).'">'.utf8_encode($dataR1["libelle"]).'</option>';
							}
						echo'
						</select>
						
						<h4>Pays d&apos;origine :</h4>
						
						<select name="nomPays" id="nomPays">
							<option selected="selected" value="vide">Indifférent</option>';
							while($dataR2 = mysqli_fetch_array($SelectAllPays))
							{
								echo'<option value="'.utf8_encode($dataR2["libelle"]).'">'.utf8_encode($dataR2["libelle"]).'</option>';
							}
						echo'
						</select>
						
						<h4>Intervalle des dates de naissance :</h4>
						
						<div id="gauche">
							<select name="dateDebut" id="dateDebut">
								<option selected="selected" value="vide">Indifférent</option>';
								for($i = date('Y'); $i >= date('Y') - (date('Y') - 1900); $i--) // pour être sûr que c'est jusqu'à l'an 1900
								{
									echo'<option value="'.$i.'">'.$i.'</option>';
								}
							echo'
							</select>
						</div>
						
						<div id="droite">
							<select name="dateFin" id="dateFin">
								<option selected="selected" value="vide">Indifférent</option>';
								for($i = date('Y'); $i >= date('Y') - (date('Y') - 1900); $i--) // pour être sûr que c'est jusqu'à l'an 1900
								{
									echo'<option value="'.$i.'">'.$i.'</option>';
								}
							echo'
							</select>
						</div>
						
						<input type="submit" name="rechercher" value="Rechercher" id="submit">
						
					</fieldset>
				</form>
			</div>
		</div>
		
		<div id="droite">
			<div id="celluleDroite">
				<h2>Résultats de recherche</h2>';
				
					if(isset($_POST['rechercher'])){
						echo'<ul>';
						while($dataR3 = mysqli_fetch_array($resultat))
						{
							echo'<li><a href="index.php?param='.utf8_encode($dataR3["code"]).'">'.utf8_encode($dataR3["nom"]).' '.utf8_encode($dataR3["prenom"]).'</a></li>';
						}
					
						echo'</ul>';
					}
			echo'
			</div>';
			
			if(isset($_GET['param'])) {
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
					while($dataR4 = mysqli_fetch_array($SelectProfil))
					{
						echo'
						<tr>
							<td>'.strtoupper(utf8_encode($dataR4["nom"])).'</td> <!-- pour être sûr que le nom est en majuscule -->
							<td>'.utf8_encode($dataR4["prenom"]).'</td>
							<td>'.utf8_encode($dataR4["sexe"]).'</td>
							<td>'.date("d/m/y", strtotime(utf8_encode($dataR4["date_naissance"]))).'</td> <!-- changementant du format de la date -->
							<td>'.utf8_encode($dataR4["num_secu"]).'</td>
							<td>'.utf8_encode($dataR4["code_pays"]).'</td>
							<td>'.date("d/m/y", strtotime(utf8_encode($dataR4["date_prem_entree"]))).'</td> <!-- changementant du format de la date -->
							<td>'.utf8_encode($dataR4["libelle"]).'</td>
						</tr>';
					}
					
					echo'
				</table>
			</div>';
			}
			echo'
		</div>
	</body>
</html>';

mysqli_close($connexion);

?>