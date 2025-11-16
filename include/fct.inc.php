<?php
//on insère le fichier qui contient les fonctions


 /**
 * Teste si un quelconque visiteur est connecté
 * @return vrai ou faux 
 */
function estConnecte(){
  return isset($_SESSION['id']);
}
/**
 * Enregistre dans une variable session les infos d'un visiteur
 
 * @param $id 
 * @param $nom
 * @param $prenom
 */
function connecter($id,$nom,$prenom){
	$_SESSION['id']= $id; 
	$_SESSION['nom']= $nom;
	$_SESSION['prenom']= $prenom;
}


/* gestion des erreurs*/
/**
 * Indique si une valeur est un entier positif ou nul
 
 * @param $valeur
 * @return vrai ou faux
*/
function estEntierPositif($valeur) {
	return preg_match("/[^0-9]/", $valeur) == 0;
	
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 
 * @param $tabEntiers : le tableau
 * @return vrai ou faux
*/
function estTableauEntiers($tabEntiers) {
	$ok = true;
	if (isset($unEntier) ){
		foreach($tabEntiers as $unEntier){
			if(!estEntierPositif($unEntier)){
		 		$ok=false; 
			}
		}	
	}
	return $ok;
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs 
 
 * @param $msg : le libellé de l'erreur 
 */
function ajouterErreur($msg){
   if (! isset($_REQUEST['erreurs'])){
      $_REQUEST['erreurs']=array();
	} 
   $_REQUEST['erreurs'][]=$msg;
}
/**
 * Retoune le nombre de lignes du tableau des erreurs 
 
 * @return le nombre d'erreurs
 */
function nbErreurs(){
   if (!isset($_REQUEST['erreurs'])){
	   return 0;
	}
	else{
	   return count($_REQUEST['erreurs']);
	}
}


function input_data($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;

}

function generateCode(){
    // Generate a 6 digits hexadecimal number
	$numbytes = 3; // Because 6 digits hexadecimal = 3 bytes
	$bytes = openssl_random_pseudo_bytes($numbytes); 
        $hex   = bin2hex($bytes);
	return $hex;
        
}
function ecrisData($data,$id){
	$cheminfile=__DIR__."/../dossier/dossierDe_".$id.'.JSON';
	$nomfile='dossierDe_'.$id.'.JSON';
	$content=$data;
	file_put_contents($cheminfile,$data);
	return $nomfile;
}

function genererCode(){
	return random_int(100000,999999);
}

#envoi mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function envoimail($code,$mel){

			require __DIR__.'/../vendor/autoload.php';

			$mail = new PHPMailer(true);

			try {
				// Configuration SMTP
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'sanji6n6@gmail.com';
			$mail->Password = 'xetx irng aggc ziow';
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port = 587;
			
			// Destinataires
			$mail->setFrom('sanji6n6@gmail.com', 'GSBExtranet');
			$mail->addAddress($mel, 'Lionel');
			
			// Contenu
			$mail->isHTML(true);
			$mail->Subject = "Test d'envoi via PHPMailer";
			$mail->Body    = "
			<html>
			<head>
			<title>Code d'authentification</title>
			</head>
			<body>
			<p>Bonjour,</p>
			<p>Voici votre code d'authentification : <b>".$code."</b></p>
			<p>Il est valable 10 minutes.</p>
			</body>
			</html>
			";
			
			$mail->send();
			return " Email envoyé avec succès !";
		}
			catch(Exception $e) {
			return " Erreur : {$mail->ErrorInfo}";
			}
}




   
    
?>