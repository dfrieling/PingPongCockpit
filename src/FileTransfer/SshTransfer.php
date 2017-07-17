<?php

namespace FileTransfer;

use Exception\TransferException;
use Exception\TransferInitException;

require_once(__DIR__ . '/../Exception/TransferException.php');
require_once(__DIR__ . '/../Exception/TransferInitException.php');
require_once(__DIR__ . '/../../config/ssh_config.php');

class SshTransfer implements TransferInterface
{
    private $session;

    public function __construct()
    {
    }

    /**
     * @return TransferInterface
     * @throws TransferException
     */
    public function init()
    {
        $this->session = ssh2_connect(HOST, PORT);

        if (!ssh2_auth_password($this->session, USER, PASSWORD)) {
            throw new TransferInitException('could not auth with given creds');
        }

        register_shutdown_function([$this, 'closeConnection']);

        return $this;
    }

    /**
     * @param $from
     * @return void
     * @throws TransferException
     */
    public function download($from, $to)
    {
        if (!$result = ssh2_scp_recv($this->session, $from, $to)) {
            throw new TransferException("could not download $from to $to");
        }
    }

    /**
     * @param $from
     * @param $to
     * @throws TransferException
     */
    public function upload($from, $to)
    {
        if (!ssh2_scp_send($this->session, $from, $to)) {
            throw new TransferException("could not upload $from to $to");
        }

        move_uploaded_file($from, '/tmp/TrangLocal.png');
    }

    /**
     *
     */
    public function closeConnection()
    {
        ssh2_exec($this->session, 'exit');
    }
}