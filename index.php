<?php
/**
 * Index du projet GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Bet Sefer
 * @author    Méora Pia Brami
 */

//besoin préliminaire et qu'on utilise 1 seule fois sur la page
require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';

//session = grosse variable qui contient plusieurs variables = variable super globale
session_start();

//on appelle la fonction getPdoGsb
$pdo = PdoGsb::getPdoGsb();

//on appelle la fonction estConnecte
$estConnecte = estConnecte();


//execute la vue v_entete
require 'vues/v_entete.php';
//filter= on regarde quel est le contenu de la variable
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
//if qui permet d'affecter à la variable 'uc' la valeur 'connexion' s'il n'y a pas d'utilisateur connecté 
//et la valeur 'accueil' sinon
if ($uc && !$estConnecte) {
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil';
}

//switch qui nous permet de savoir quoi faire selon la valeur de la variable 'uc'
switch ($uc) {
case 'connexion':
    include 'controleurs/c_connexion.php';
    break;
case 'accueil':
    include 'controleurs/c_accueil.php';
    break;
case 'gererFrais':
    include 'controleurs/c_gererFrais.php';
    break;
case 'etatFrais':
    include 'controleurs/c_etatFrais.php';
    break;
case 'validerFrais' :
    include 'controleurs/c_validerFrais.php';
    break;
case 'deconnexion':
    include 'controleurs/c_deconnexion.php';
    break;
}
require 'vues/v_pied.php';
