<?php
namespace Memory\Controller;

use Memory\Model\Manager\GameManager;

class GameController 
{
    public function getListAction()
    {
        $db = new \PDO('mysql:host=localhost;dbname=memory;charset=utf8', 'memory', 'memory');
        $game_manager = new GameManager($db);
        $game_list = $game_manager->getList();
        require_once(__DIR__ . '/../view/lobby.php');
    }

    public function addAction(int $is_user, float $time, int $win)
    {
        $db = new \PDO('mysql:host=localhost;dbname=memory;charset=utf8', 'memory', 'memory');
        $game_manager = new GameManager($db);
        return $game_manager->add($is_user, $time, $win);
    }
}