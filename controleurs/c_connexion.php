<?php


if(!isset($_GET['action'])){
	$_GET['action'] = 'demandeConnexion';
}
$action = $_GET['action'];
switch($action){
	
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_POST['login'];
		
		$mdp = $_POST['mdp'];
		$connexionOk = $pdo->checkUser($login,$mdp);
		if(!$connexionOk){
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else { 
			$infosMedecin = $pdo->donneLeMedecinByMail($login);
			$id = $infosMedecin['id'];
			$_SESSION['ide']=$id;
			$_SESSION['mel']=$login;
			include("vues/v_sommaire.php");
			//$nom =  $infosMedecin['nom'];
			//$prenom = $infosMedecin['prenom'];
			//connecter($id,$nom,$prenom);
			
			/*$code=genererCode();
            $pdo->addCode($id,$code);
			envoimail($code,$login);
			$_POST['codeMessage']="Une code valable pendant 1mn a été envoyé à votre mail ".$login;
			include("vues/v_saisiecode.php");*/
			
		}
		
		break;	
		
	}
	case 'verifierCode':{//on prend toujours l'id pour avoir l'id
		$code=$_POST['code'];
		$log=$_SESSION['mel'];
		$infosMedecin = $pdo->donneLeMedecinByMail($log);
		$id = $infosMedecin['id'];
		$nom =  $infosMedecin['nom'];
		$prenom = $infosMedecin['prenom'];
		switch($pdo->verifCode($id,$code)){
			case 1:{//on connecte l'utilisateur
				connecter($id,$nom,$prenom);
				$pdo->addCode($id,"");
				include("vues/v_sommaire.php");
				break;
			}	
			case 0:{//temps dépasse
				$_POST['codeMessage']="Temps dépassé,code renvoyé! Vous avez 1mn";
				$code=genererCode();
				$pdo->addCode($id,$code);
				envoimail($code,$log);
				include("vues/v_saisiecode.php");
				
				break;
			}
			case 10:{//code faux
				$_POST['codeMessage']="Code faux,code renvoyé! Vous avez 1mn";
				$code=genererCode();
				$pdo->addCode($id,$code);
				envoimail($code,$log);
				include("vues/v_saisiecode.php");
				
				break;
			}
		}
		break;
	}
	case 'newCode':{
		
		$log=$_SESSION['mel'];
		$id = $_SESSION['ide'];
		$code=genererCode();
		$pdo->addCode($id,$code);
		envoimail($code,$log);
		$_POST['codeMessage']="Code renvoyé! Vous avez 1mn";
		include("vues/v_saisiecode.php");
		
	}
	
        
	
}
?>