<?php

namespace App\WebSocketHandlers;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use App\CustomWebSocketsHanler;

class MyCustomWebSocketHandler implements MessageComponentInterface
{

    public function onOpen(ConnectionInterface $connection)
    {
       CustomWebSocketsHanler::onOpen($connection);
    }
    
    public function onClose(ConnectionInterface $connection)
    {
      CustomWebSocketsHanler::onClose($connection);
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
      CustomWebSocketsHanler::onError($connection,$msg);
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
      CustomWebSocketsHanler::onMessage($connection,$msg);
    }
}