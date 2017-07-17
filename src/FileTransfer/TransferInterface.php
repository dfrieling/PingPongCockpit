<?php
/**
 * Created by PhpStorm.
 * User: johannes
 * Date: 18.07.2017
 * Time: 16:10
 */

namespace FileTransfer;


interface TransferInterface
{
    function download($from, $to);

    function upload($from, $to);

    /** @return TransferInterface */
    function init();
}