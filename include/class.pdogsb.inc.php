<?php

/** 
 * Classe d'accÃ¨s aux donnÃ©es. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsbextranet';   		
      	//private static $user='gsbextranetAdmin';    		
      	private static $user='login5529';    		
      	//private static $mdp='Solfa-55!';	
      	private static $mdp='IxTSlPVJuMAQUrb';	
	private static $monPdo;
	private static $monPdoGsb=null;
		
/**
 * Constructeur privÃ©, crÃ©e l'instance de PDO qui sera sollicitÃ©e
 * pour toutes les mÃ©thodes de la classe
 */				
	private function __construct(){
          
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crÃ©e l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * vÃ©rifie si le login et le mot de passe sont corrects
 * renvoie true si les 2 sont corrects
 * @param type $lePDO
 * @param type $login
 * @param type $pwd
 * @return bool
 * @throws Exception
 */
function checkUser($login, $pwd):bool {
    //AJOUTER TEST SUR TOKEN POUR ACTIVATION DU COMPTE
    $user=false;
    $pdo = PdoGsb::$monPdo;
    $monObjPdoStatement=$pdo->prepare("SELECT motDePasse FROM medecin WHERE mail= :login AND token IS NULL");
    $bvc1=$monObjPdoStatement->bindValue(':login',$login,PDO::PARAM_STR);
    if ($monObjPdoStatement->execute()) {
        $unUser=$monObjPdoStatement->fetch();
        if (is_array($unUser)){
           if (password_verify($pwd,$unUser['motDePasse']))
                $user=true;
        }
    }
    else
        throw new Exception("erreur dans la requÃªte");
return $user;   
}


	

function donneLeMedecinByMail($login) {
    
    $pdo = PdoGsb::$monPdo;
    $monObjPdoStatement=$pdo->prepare("SELECT id, nom, prenom,mail FROM medecin WHERE mail= :login");
    $bvc1=$monObjPdoStatement->bindValue(':login',$login,PDO::PARAM_STR);
    if ($monObjPdoStatement->execute()) {
        $unUser=$monObjPdoStatement->fetch();
       
    }
    else
        throw new Exception("erreur dans la requÃªte");
return $unUser;   
}


public function tailleChampsMail(){
    

    
     $pdoStatement = PdoGsb::$monPdo->prepare("SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_name = 'medecin' AND COLUMN_NAME = 'mail'");
    $execution = $pdoStatement->execute();
$leResultat = $pdoStatement->fetch();
      
      return $leResultat[0];
    
       
       
}


public function creeMedecin($nom, $prenom,$email, $mdp)
{
   //insertion des medecins
    $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO medecin(id,nom, prenom, mail, motDePasse,dateCreation,dateConsentement) "
            . "VALUES (null,:lenom,:leprenom,:leMail, :leMdp, now(),now())");
    $bv1 = $pdoStatement->bindValue(':lenom', $nom,PDO::PARAM_STR);
    $bv1 = $pdoStatement->bindValue(':leprenom', $prenom,PDO::PARAM_STR);
    $bv1 = $pdoStatement->bindValue(':leMail', $email,PDO::PARAM_STR);
    
    $bv2 = $pdoStatement->bindValue(':leMdp',password_hash($mdp, PASSWORD_DEFAULT) );
    $execution = $pdoStatement->execute();
    
    //prendre l'id de ce medecin
   /* $pdoStatement = PdoGsb::$monPdo->prepare("select id from medecin where mail=:mail");
    $bv1 = $pdoStatement->bindValue(':mail', $email,PDO::PARAM_STR);
    $id=$pdoStatement->execute();
    //prendre la version du consentement en ce moment
    $pdoStatement = PdoGsb::$monPdo->prepare("select version from version where date_fin is null");
    $version=$pdoStatement->execute();
    
    
    //insertion dans consentement
    $pdoStatement = PdoGsb::$monPdo->prepare("insert into consentement(medecin, version, date) values(:id,:version,now()");
    $bv1 = $pdoStatement->bindValue(':id', $id,PDO::PARAM_STR);
    $bv1 = $pdoStatement->bindValue(':version', $version,PDO::PARAM_STR);
    $pdoStatement->execute();*/

    return $execution;
    
}


function testMail($email){
    $pdo = PdoGsb::$monPdo;
    $pdoStatement = $pdo->prepare("SELECT count(*) as nbMail FROM medecin WHERE mail = :leMail");
    $bv1 = $pdoStatement->bindValue(':leMail', $email);
    $execution = $pdoStatement->execute();
    $resultatRequete = $pdoStatement->fetch();
    if ($resultatRequete['nbMail']==0)
        $mailTrouve = false;
    else
        $mailTrouve=true;
    
    return $mailTrouve;
}




function connexionInitiale($mail){
     $pdo = PdoGsb::$monPdo;
    $medecin= $this->donneLeMedecinByMail($mail);
    $id = $medecin['id'];
    $this->ajouteConnexionInitiale($id);
    
}

function ajouteConnexionInitiale($id){
    $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO historiqueconnexion "
            . "VALUES (:leMedecin, now(), now())");
    $bv1 = $pdoStatement->bindValue(':leMedecin', $id);
    $execution = $pdoStatement->execute();
    return $execution;
    
}
function donneinfosmedecin($id){
  
       $pdo = PdoGsb::$monPdo;
           $monObjPdoStatement=$pdo->prepare("SELECT id,nom,prenom FROM medecin WHERE id= :lId");
    $bvc1=$monObjPdoStatement->bindValue(':lId',$id,PDO::PARAM_INT);
    if ($monObjPdoStatement->execute()) {
        $unUser=$monObjPdoStatement->fetch();
   
    }
    else
        throw new Exception("erreur");
           
    
}
function getport($id){//Portabilité
    $pdo = PdoGsb::$monPdo;
    $monObjPdoStatement=$pdo->prepare("SELECT  nom, prenom,mail,dateNaissance, dateCreation,dateDiplome,dateConsentement FROM medecin WHERE id= :id");
    $bvc1=$monObjPdoStatement->bindValue(':id',$id,PDO::PARAM_STR);
    if ($monObjPdoStatement->execute()) {
        $unUser=$monObjPdoStatement->fetch(PDO::FETCH_ASSOC);
        $unUser=[
            'nom'=>$unUser['nom'],
            'prenom'=>$unUser['prenom'],
            'mail'=>$unUser['mail'],
            'dateCreation'=>$unUser['dateCreation'],
            'dateDiplome'=>$unUser['dateDiplome'],
            'dateConsentement'=>$unUser['dateConsentement']
        ];
    }
    else
    throw new Exception("erreur dans la requÃªte");
    return json_encode($unUser) ; 
}
function addCode($idmedecin,$code){//Ajoute le code envoyé ou le supprime dans la base de données
    $pdo = PdoGsb::$monPdo;
    if ($code==""){//cas où on supprime le code
        $p=$pdo->prepare("UPDATE medecin set code=null,timeCode=null where id=:id");
        $p->bindValue(':id',$idmedecin,PDO::PARAM_STR);
    }
    else{
        $p=$pdo->prepare("UPDATE medecin set code=:c,timeCode=now() where id=:id");
        $p->bindValue(':id',$idmedecin,PDO::PARAM_STR);
        $p->bindValue(':c',$code,PDO::PARAM_STR);
        
    }
    $result=$p->execute();
    return $result;
}

function verifCode($id,$code){
    date_default_timezone_set('Europe/Paris');

    $pdo = PdoGsb::$monPdo;
    $p=$pdo->prepare("select code,timeCode from medecin where id=:id");
    $p->bindValue(':id',$id,PDO::PARAM_STR);
    $p->execute();
    $result=$p->fetch(PDO::FETCH_ASSOC);

        $timenow=new dateTime('now');
        $lasttime=new dateTime($result['timeCode']);
        $lasttime->add(new DateInterval('PT60S'));//ajoute 50s au temps de création du code
     
     //   echo 'lasttime('.$lasttime->format('Y-m-d H:i:s').')>=timenow('.$timenow->format('Y-m-d H:i:s').')='.($lasttime>=$timenow?'true':'false');
     //   echo '<br>';   
        if($result['code']==$code && $lasttime>=$timenow){
            return 1;
        }
        else{
            return $lasttime>=$timenow?10:0;//10 si code faux et 0 si temps depassé
        }
        

    }

}
?>