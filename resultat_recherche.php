<?php 

	if(isset($_POST['rechercher'])) {
		echo'
		<div id="celluleDroite">
		<h2>Résultat de la recherche</h2>

			<ul>';

				if(mysqli_num_rows($resultat) == 0) {        
					echo "Aucun résultat trouvé pour : $nomPatient !";
         		} else {
				// Pour chaque résultat récupéré par la requête, il y a l'affichage des noms et prénoms des patients correspondants à la recherche, sous forme de liste et de lien hypertexte (avec le code/identifiant du patient en paramètre, par l'URL).
					while($dataR3 = mysqli_fetch_array($resultat))
					{
						echo'<li><a href="index.php?param='.utf8_encode($dataR3["code"]).'">'.utf8_encode($dataR3["nom"]).' '.utf8_encode($dataR3["prenom"]).'</a></li>';
					}
				}

			echo'
			</ul>
		</div>';
	}

?>