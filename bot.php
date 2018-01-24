<?php

require_once(__DIR__.'/vendor/autoload.php');
$loop = \React\EventLoop\Factory::create();
$client = new \CharlotteDunois\Yasmin\Client(array(
    'ws.disabledEvents' => array(
        /* We disable the TYPING_START event to save CPU cycles, we don't need it here in this example. */
        'TYPING_START'
    )
), $loop);


$client->on('message', function (\CharlotteDunois\Yasmin\Models\Message $message) use ($client, &$sentMessage) {
    try {
        if($message->mentions->users->has($client->user->id)) {
            $args = explode(' ', $message->content);
            if(mb_strtolower($args[1]) === 'match') {
                /** @var \CharlotteDunois\Yasmin\Models\Message $sentMessage */
                $sentMessage = null;
                $message->channel
                    ->send("Ok matching in 10...")
                    ->otherwise(function ($error) {
                        echo $error;
                    })
                    ->done(function (\CharlotteDunois\Yasmin\Models\Message $message) use (&$sentMessage) {
                        $message->react('🤚')
                            ->otherwise(function ($error) {
                                echo $error.PHP_EOL;
                            })
                            ->done(function (\CharlotteDunois\Yasmin\Models\MessageReaction $reaction) use (&$sentMessage){
                                $sentMessage = $reaction->message;

                                echo 'Done';
                                (new \App\MatchingMessage($sentMessage))->countdown();

                                $reaction->fetchUsers()->done(function (\CharlotteDunois\Yasmin\Utils\Collection $users) use ($sentMessage) {
                                    $matcher = (new \App\SquadMatcher());
                                    $users->filter(function (\CharlotteDunois\Yasmin\Models\User $user) {
                                        return $user->username != "SquadMatcher";
                                    })
                                        ->each(function (\CharlotteDunois\Yasmin\Models\User $user) use ($matcher) {
                                            $matcher->addPlayer($user->username);
                                        });

                                    $msg = $matcher->computeSquads()->map(function(\App\Squad $squad) {
                                        return $squad->__toString();
                                    })
                                        ->implode(PHP_EOL);

                                    $sentMessage->channel->send($msg);
                                });
                            });
                    });




            }
        }
    } catch(\Exception $error) {
        echo "WOW ." . $error->getMessage();
    }
});


$client->login('NDA1NDk0NDE4ODk2ODQ2ODYw.DUlNsg.5m7x_wMlh_cJhuWgVkb6fcPW8mM');
$loop->run();
