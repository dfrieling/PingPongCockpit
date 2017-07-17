<?php

require_once('Model/Player.php');
require_once('PlayerImage.php');
require_once('FileTransfer/TransferInterface.php');

class PlayerImagePlaying extends PlayerImage
{
    protected $saveToLocalPrefix = '/tmp/pingpongImgPlayingId';
    protected $saveToRemotePrefix = '/storage/temp/Ping-Pong/ui/public/img/players/';
}