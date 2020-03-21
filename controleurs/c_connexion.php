<?php
/**
 * Gestion de la connexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Bet Sefer
 * @author    Méora Pia Brami
 */

//filter = voir ce qu'il y a écrit dans la variable
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$action) {
    $action = 'demandeconnexion';
}

switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.php';
    break;
case 'valideConnexion':
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    
    //on va vers getInfosVisiteur / getInfosComptable pour verifier si login et mot de passe valide
    $visiteur = $pdo->getInfosVisiteur($login, $mdp);
    $comptable = $pdo->getInfosComptable($login, $mdp);
    
    if (!is_array($visiteur)&&!is_array($comptable))  {
       //!is_array veut dire n'est pas dans le tableau
       ajouterErreur('Login ou mot de passe incorrect');
       include 'vues/v_erreurs.php';//affiche
       include 'vues/v_connexion.php';
   } else {
       if (is_array($visiteur)){
       $id = $visiteur['id'];
       $nom = $visiteur['nom'];
       $prenom = $visiteur['prenom'];
       $statut='visiteur';}
       
       else{
           if (is_array($comptable)){
 
           $id = $comptable['id'];
           $nom = $comptable['nom'];
           $prenom = $comptable['prenom'];
           $statut='comptable';
       }
       }
           connecter($id, $nom, $prenom,$statut);
           header('Location: index.php');
       }
   break;
default:
   include 'vues/v_connexion.php';
   break;
}
