<?php

namespace ccxt;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception as Exception; // a common import

class bitfinex extends Exchange {

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'has' => array (
                'fetchWsTicker' => true,
                'fetchWsOrderBook' => true,
            ),
            'urls' => array (
                'logo' => 'https://user-images.githubusercontent.com/1294454/27766244-e328a50c-5ed2-11e7-947b-041416579bb3.jpg',
                'api' => array (
                    'wss' => 'wss://api-pub.bitfinex.com/ws/2',
                ),
            ),
        ));
    }

    public function get_ws_message_hash ($client, $response) {
        if (gettype ($response) === 'array' && count (array_filter (array_keys ($response), 'is_string')) == 0 && gettype ($response[1]) === 'array' && count (array_filter (array_keys ($response[1]), 'is_string')) == 0) {
            return $this->options['channels'][$response[0]];
        }
    }

    public function fetch_ws_ticker ($symbol, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $marketId = $market['id'];
        $url = $this->urls['api']['websocket']['public'];
        return $this->WsTickerMessage ($url, 'ticker/' . $marketId, array (
            'event' => 'subscribe',
            'channel' => 'ticker',
            'symbol' => $marketId,
        ));
    }

    public function handle_ws_ticker ($ticker) {
        $data = $ticker[1];
        $symbol = $this->parse_symbol ($ticker);
        return array (
            'symbol' => $symbol,
            'bid' => floatval ($data[1]),
            'ask' => floatval ($data[2]),
            'change' => floatval ($data[4]),
            'percent' => floatval ($data[5]),
            'last' => floatval ($data[6]),
            'volume' => floatval ($data[7]),
            'high' => floatval ($data[8]),
            'low' => floatval ($data[9]),
            'info' => $data,
        );
    }

    public function fetch_ws_order_book ($symbol, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $marketId = $market['id'];
        $url = $this->urls['api']['websocket']['public'];
        return $this->WsOrderBookMessage ($url, 'book/' . $marketId, array (
            'event' => 'subscribe',
            'channel' => 'book',
            'symbol' => $marketId,
        ));
    }

    public function handle_ws_order_book ($orderBook) {
        $data = $orderBook[1];
        $deltas = gettype ($data[0]) === 'array' && $count (array_filter (array_keys ($data[0]), 'is_string')) == 0 ? $data : array ( $data );
        $bids = array();
        $asks = array();
        for ($i = 0; $i < $count ($deltas); $i++) {
            $delta = $deltas[$i];
            $price = floatval ($delta[0]);
            $amount = floatval ($delta[2]);
            $count = intval ($delta[1]);
            if ($amount < 0) {
                $amount = -$amount;
                $asks[] = array ( $price, $amount, $count );
            } else {
                $bids[] = array ( $price, $amount, $count );
            }
        }
        $symbol = $this->parse_symbol ($orderBook);
        if (!(is_array($this->orderBooks) && array_key_exists($symbol, $this->orderBooks))) {
            $this->orderBooks[$symbol] = new IncrementalOrderBook ();
        }
        $nonce = null;
        $timestamp = null;
        return $this->orderBooks[$symbol].update ($nonce, $timestamp, $bids, $asks);
    }

    public function parse_delta ($delta) {
        $price = floatval ($delta[0]);
        $amount = floatval ($delta[2]);
        $count = intval ($delta[1]);
        $side = null;
        if ($amount < 0) {
            $side = 'asks';
            $amount = -$amount;
        } else {
            $side = 'bids';
        }
        $operation = 'add';
        if ($count === 0) {
            $operation = 'delete';
        }
        return [null, $operation, $side, $price, $amount];
    }

    public function handle_ws_dropped ($client, $response, $messageHash) {
        if ($this->safe_string($response, 'event') === 'subscribed') {
            $channel = $response['channel'];
            $marketId = $response['pair'];
            $channelId = $response['chanId'];
            $this->options['channels'][$channelId] = $channel . '/' . $marketId;
            return;
        }
        if ($messageHash !== null && $messageHash->startsWith ('book')) {
            $this->handle_ws_order_book ($response);
        }
    }

    public function parse_symbol ($response) {
        $channelId = $response[0];
        $marketId = explode('/', $this->options['channels'][$channelId])[1];
        return $this->marketsById[$marketId]['symbol'];
    }
}
