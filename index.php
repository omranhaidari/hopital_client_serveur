<?php
	
	header('content-type: text/html; charset=utf-8');

	echo'
	<head>
		<meta charset="utf-8"/>
		<link href="css/style.css" rel="stylesheet">
		<title>Hôpital Didier LEFEBVRE</title>
		<a href="index.php"><h1>Hôpital Didier LEFEBVRE</h1></a>
	<head>';

	// include : inclut le contenu d'un autre fichier appelé, mais ne provoque pas d'erreur bloquante si le fichier appelé est indisponible (le reste du code est exécuté).
	// include_once : pour faire l'inclusion qu'une seule fois dans le fichier.
	
	echo'
	<html>
		<body>

			<div id="gauche">';
					include_once('recherche/recherche_patient.php');

					include_once('recherche/recherche_document.php');
					
					include_once('document/plan_urgence.php');
				echo'
			</div>

			<div id="droite">';
				include_once('recherche/resultat_recherche.php');

				include_once('patient/fiche_patient.php');
			echo'
			</div>

		</body>
	</html>';

?>