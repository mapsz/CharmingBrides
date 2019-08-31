<?php

namespace App\WebSocketHandlers;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use BeyondCode\LaravelWebSockets\WebSockets\Messages\PusherMessageFactory;
use BeyondCode\LaravelWebSockets\Facades\StatisticsLogger;

class ClientSocketHandler implements MessageComponentInterface
{

    public $handle = "";

    public function onOpen(ConnectionInterface $connection)
    {
        // TODO: Implement onOpen() method.


      $this->handle = new WebSocketHandler(app(ChannelManager::class));

      $this->handle->onOpen($connection);

    }
    
    public function onClose(ConnectionInterface $connection)
    {
        // TODO: Implement onClose() method.
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        // TODO: Implement onError() method.
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        $this->handle->onMessage($connection,$msg);
    }

}