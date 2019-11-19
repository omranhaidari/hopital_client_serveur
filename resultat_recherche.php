<?php 
	
	if(isset($_POST['rechercher'])) {
		echo'
		<div id="celluleDroite">
		<h2>Résultats de la recherche</h2>

			<ul>';
		
     
         if(mysqli_num_rows($resultat)==0){        
             echo 'Aucun resulat trouve';
         }else{
	
                while($dataR3 = mysqli_fetch_array($resultat))
                        
      
                               
					echo'<li><a href="index.php?param='.utf8_encode($dataR3["code"]).'">'.utf8_encode($dataR3["nom"]).' '.utf8_encode($dataR3["prenom"]).'</a></li>';
        }
        
         }
                
			echo'
			</ul>
		</div>';
	

?>