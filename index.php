<?php

	// include : inclut le contenu d'un autre fichier appelé, mais ne provoque pas d'erreur bloquante si le fichier appelé est indisponible (le reste du code est exécuté).
	// include_once : pour faire l'inclusion qu'une seule fois dans le fichier.
	include_once ('ressources_communes.php'); // fichier nécessaire qu'ici, à supprimer ?

	echo'
	<html>
		<body>

			<div id="gauche">
				<div id="celluleGauche">';
					include_once ('recherche_patient.php');
				echo' 
				</div>
			</div>

			<div id="droite">';
				include_once('resultat_recherche.php');

				include_once ('fiche_patient.php');
			echo'
			</div>

		</body>
	</html>';

	mysqli_close($connexion);

?>