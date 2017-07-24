<?php

require_once(__DIR__ . '/Model/Player.php');
require_once(__DIR__ . '/PlayerImage.php');
require_once(__DIR__ . '/FileTransfer/TransferInterface.php');
require_once(__DIR__ . '/../config/pingpong_server_config.php');

class PlayerImageWinning extends PlayerImage
{
    protected $saveToLocalPrefix = '/tmp/pingpongImgWinning';
    protected $saveToRemotePrefix = PINGPONG_INSTALLATION_PATH . '/ui/public/img/players/win/';
}