<?php

/**
* Controleur Valider Frais
*
* PHP Version 7
*
* @category PPE
* @package GSB
* author Méora Pia Brami
* @author Beth Sefer
*/
$mois= getMois(date('d/m/Y'));
$moisPrecedent= getMoisPrecedent($mois);
$pdo->clotureFiche($moisPrecedent);
$action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
switch ($action){
  case 'selectionnerVisEtMois':
      $lesVisiteurs= $pdo ->getLesVisiteurs();
      $lesCles1= array_keys($lesVisiteurs);
      $leVisiteurASelectionner=$lesCles1[0];
      $lesMois = getListeMois($mois);
      $lesCles2= array_keys($lesMois);
      $moisASelectionner=$lesCles2[0];
      include 'vues/v_listeVisiteurs.php';
      break;
  case'afficheFrais':
       $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
       $lesVisiteurs=$pdo->getLesVisiteurs();
       $leVisiteurASelectionner=$idVisiteur;
       $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
       $lesMois = getListeMois($mois);
       $moisASelectionner = $leMois;
  
       $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
       $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
   

       $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
       $numAnnee = substr($leMois, 0, 4);
       $numMois = substr($leMois, 4, 2);
       $libEtat = $lesInfosFicheFrais['libEtat'];
       $montantValide = $lesInfosFicheFrais['montantValide'];
       $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
       $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
       
       if(!is_array($pdo->getLesInfosFicheFrais($idVisiteur, $leMois))){
           ajouterErreur('il n y a pas de fiche de frais pour ce visiteur et ce mois');
       include 'vues/v_erreurs.php';
       include 'vues/v_listeVisiteurs.php';
       }else{
           $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
           $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
           $nbJustificatifs=$pdo->getNbjustificatifs($idVisiteur, $leMois);
        //var_dump($lesFraisHorsForfait);
         include 'vues/v_afficheFrais.php';
        }
       
       break;
  case 'modifFraisForfait' :
      $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
      $lesVisiteurs=$pdo->getLesVisiteurs();
      $leVisiteurASelectionner=$idVisiteur;
      $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
      $lesMois = getListeMois($mois);
      $moisASelectionner = $leMois;
      $lesFrais= filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
      $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
      $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
      $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
       if (lesQteFraisValides($lesFrais)) {
        $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    include 'vues/v_afficheFrais.php';
    break;
    case 'modifFraisHorsForfait' :
      $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
      $lesVisiteurs=$pdo->getLesVisiteurs();
      $leVisiteurASelectionner=$idVisiteur;
      $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
      $lesMois = getListeMois($mois);
      $moisASelectionner = $leMois;
      $lesFrais= filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
      $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
      $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
      $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
      valideInfosFrais($date, $libelle, $montant);
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else {
       $pdo->majFraisHorsForfait($idVisiteur, $id, $leMois, $date, $libelle, $montant);
    }
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
    //var_dump ($lesFraisHorsForfait);
      include 'vues/v_afficheFrais.php';
      break;
}

