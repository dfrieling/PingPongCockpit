<?php

require_once('Model/Player.php');
require_once('PlayerImage.php');
require_once('FileTransfer/TransferInterface.php');

class PlayerImageWinning extends PlayerImage
{
    protected $saveToLocalPrefix = '/tmp/pingpongImgWinningId';
    protected $saveToRemotePrefix = '/storage/temp/Ping-Pong/ui/public/img/players/win/';
}