<?php

namespace App;

use stdClass;
use Ratchet\ConnectionInterface;

class PrivateRoomChannel extends \BeyondCode\LaravelWebSockets\WebSockets\Channels\PresenceChannel // @@@ nado private
{

    public function unsubscribe(ConnectionInterface $connection)
    {

    	// dump($connection);
    	dump('@@@@@@@@22');
        // ChatHistory::chatDisconnect($this->channelName, $this);


    	parent::unsubscribe($connection);

    }
}
