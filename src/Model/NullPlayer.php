<?php
namespace Model;

require_once (__DIR__ . '/Player.php');

class NullPlayer extends Player
{
    public function __construct()
    {
        parent::__construct(null, null, null, null, null);
    }
}