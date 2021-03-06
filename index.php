<?php
/**
 * Memory Game
 * 
 * Jeu du memory
 * Dev pour évaluation technique
 * Possible utilisation pour support de cours
 * 
 * @author Sulpice Rémi
 */

session_start();
require_once realpath("vendor/autoload.php");

use Memory\Controller\GameController;
use Memory\Controller\UserController;
use Memory\Controller\CardController;

if (empty($_GET) && empty($_POST)) {
    // Charge listing des games 
    $game_controller = new GameController();
    $game_controller->getListAction();
}
if (isset($_GET['action']) && $_GET['action'] == "connect") {
    // Auth simpliste par pseudo
    $input_nickname = $_GET['input_nickname'];
    $user_controller = new UserController();
    $user = $user_controller->existsAction($input_nickname);

    // Traitement AJAX : formulaire pseudo
    if (!$user) {
        //Si user n'existe pas : Creation User
        $user_controller->addAction($input_nickname);
        $user_created = $user_controller->getLastInsertedACtion();

        //Sauvegarde du User en session
        unset($_SESSION['user_id']);
        $_SESSION['user_id'] = (int)$user_created->id();
        unset($_SESSION['nickname']);
        $_SESSION['nickname'] = $user_created->nickname();

        // Reponse JSON
        echo json_encode(array(
            'user_id' => (int)$user_created->id(),
            'nickname' => $user_created->nickname()
        ));
    } else {
        //Si user existe : Recup des dernieres Games du User
        $game_controller = new GameController();
        $games = $game_controller->getListByUserAction($user->id());

        //Sauvegarde du User en session
        unset($_SESSION['user_id']);
        $_SESSION['user_id'] = (int)$user->id();
        unset($_SESSION['nickname']);
        $_SESSION['nickname'] = $user->nickname();
        
        // Reponse JSON
        echo json_encode(array('games' => $games));
    }
}
if (isset($_GET['action']) && $_GET['action'] == "start_game") {
    // Plateau Memory
    $card_controller = new CardController();
    $card_controller->getListAction();
}
if (isset($_POST['action']) && $_POST['action'] == "add_score") {
    // Enregistrement de la partie en DB
    $time = (float)$_POST['time'];
    $id_user = $_POST['id_user'];
    $win = $_POST['win'];
    
    $game_controller = new GameController();
    $game_controller->addAction($id_user, $time, $win);
    $output = "GG à toi ! 
    Pour reveni à la connexion c\'est par ici que ça se passe :) 
    Essaye de faire une nouvelle partie avec un noueau nom ;)";
    echo json_encode($output);
}