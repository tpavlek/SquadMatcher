<?php

namespace App;

use CharlotteDunois\Yasmin\Models\Message;
use CharlotteDunois\Yasmin\Models\MessageReaction;
use React\Promise\Promise;

class MatchingMessage
{

    private $message;
    private $time;

    public function __construct(Message $message, $time = 10)
    {
        $this->message = $message;
        $this->time = $time;
    }

    public function countdown()
    {

        foreach (range($this->time, 0, -1) as $time) {
            $this->message->edit("OK matching in {$time}...")
                ->otherwise(function ($error) {
                    echo $error.PHP_EOL;
                })
                ->done(function(Message $message) use ($time) {
                    sleep(1);
                });
        }
    }

}
