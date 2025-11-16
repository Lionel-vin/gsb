<?php
require_once("include/class.pdogsb.inc.php");
require_once("include/fct.inc.php");

if(!isset($_GET['action'])){
	$_GET['action'] = 'demandePortabilite';
}
$action = $_GET['action'];
switch($action){
    case 'demandePortabilite':
        include('vues/v_droits.php');
        break;
    
    case 'telecharger':
        $lePdo = PdoGsb::getPdoGsb();
        $id=$_SESSION['id'];
        $donne=$lePdo->getport($id);
        $nomfile=ecrisData($donne,$id);
        $_SESSION['hash']=hash_file("sha256", __DIR__.'/../dossier/'.$nomfile);
        include('vues/v_droits.php');
        break;


}