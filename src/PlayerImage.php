<?php

require_once(__DIR__ . '/Model/Player.php');
require_once(__DIR__ . '/FileTransfer/TransferInterface.php');
require_once(__DIR__ . '/ImageConverter.php');
require_once(__DIR__ . '/Exception/TransferException.php');

use Exception\TransferException;
use FileTransfer\TransferInterface;

abstract class PlayerImage
{
    const IMAGE_FILENAME_PATTERN = 'rfid%d.png';

    protected $saveToLocalPrefix = null;
    protected $saveToRemotePrefix = null;

    /** @var TransferInterface $transfer */
    private $transfer;
    private $player;

    public function __construct(\Model\Player $player, TransferInterface $transfer)
    {
        $this->transfer = $transfer;
        $this->player = $player;
    }

    /**
     * @return string
     */
    public function download()
    {
        try {
            $this->transfer->init()->download($this->getSaveToRemotePath(), $this->getSaveToLocalPath());
            return file_get_contents($this->getSaveToLocalPath());
        } catch (TransferException $e) {
            return '';
        }
    }

    /**
     * @return string
     * @throws TransferException
     */
    public function getSaveToRemotePath()
    {
        if (empty($this->player->getName())) {
            throw new TransferException('player has no name');
        }
        return $this->saveToRemotePrefix . 'rfid' . $this->player->getRfid() . '.png';
    }

    /**
     * @return string
     * @throws TransferException
     */
    public function getSaveToLocalPath()
    {
        if (empty($this->player->getId())) {
            throw new TransferException('player has no name');
        }
        return $this->saveToLocalPrefix . sprintf(self::IMAGE_FILENAME_PATTERN, $this->player->getRfid());
    }

    /**
     * @param $tmpPath
     */
    public function upload($tmpPath)
    {
        $this->transfer->init()->upload((new ImageConverter($tmpPath))->toPng()->cutToDimensions()->getPath(), $this->getSaveToRemotePath());
    }
}