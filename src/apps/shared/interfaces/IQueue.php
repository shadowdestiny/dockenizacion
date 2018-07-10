<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 28/06/18
 * Time: 15:28
 */

namespace EuroMillions\shared\interfaces;


interface IQueue
{

    public function messageProducer($message);

    public function receiveMessage();

    public function deleteMessage($message);

}