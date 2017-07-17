<?php

namespace Model;

use Exception\MysqlException;
use PDO;

require_once (__DIR__ . '/../Exception/MysqlException.php');
require_once (__DIR__ . '/../Exception/MysqlNotFoundException.php');
require_once (__DIR__ . '/../Model/Player.php');

class PlayerMysql
{
    const DSN = 'mysql:dbname=pingpong;host=192.168.2.102';
    const USER = 'root';
    const PASSWORD = 'secret';
    const TABLE = 'players';
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(self::DSN, self::USER, self::PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    /**
     * @param $rfid
     * @return Player
     */
    public function getExistingOrNewFromRfid($rfid)
    {
        return $this->lookupByRfid($rfid) ?: new Player(null, $rfid);
    }

    /**
     * @param $rfid
     * @return Player
     * @throws MysqlException
     */
    public function lookupByRfid($rfid)
    {
        $statement = $this->pdo->query("SELECT * from " . self::TABLE . " WHERE rfid = $rfid");

        $playerData = $statement->fetchAll();

        if(empty($playerData[0])) {
            return null;
        }

        $playerData = $playerData[0];

        return(new Player(
            $playerData['id'], $playerData['rfid'], $playerData['name'], $playerData['image'], $playerData['gender']
        ));
    }

    /**
     * @param Player $player
     */
    public function save(Player $player)
    {
        if (self::lookupByRfid($player->getRfid())) {
            $query = "UPDATE " . self::TABLE . " SET name = :name, image = :image, gender = :gender WHERE rfid = :rfid;";
        } else {
            $query = "INSERT INTO " . self::TABLE . " (rfid, name, image, gender) VALUES (:rfid, :name, :image, :gender);";
        }

        $statement = $this->pdo->prepare($query);

        if (!$statement->execute([
            ':rfid' => $player->getRfid(),
            ':name' => $player->getName(),
            ':image' => $player->getImage(),
            ':gender' => $player->getGender()
        ])
        ) {
            throw new MysqlException('could not execute query: ' . $statement->queryString);
        }
    }
}