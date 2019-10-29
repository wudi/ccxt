<?php

namespace ccxt;


interface WebSocketClientInterface {
    public function isConnected();

    public function connect();

    public function close();

    public function send($data);
}
