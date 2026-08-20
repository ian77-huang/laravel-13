<?php

namespace App\Console\Commands;

use App\Events\MessageSent;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('websocket:send {channel} {message}')]
#[Description('Command description')]
class WebSocketSend extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $channel = $this->argument('channel');
        $message = $this->argument('message');

        broadcast(new MessageSent(
            channel: $channel,
            message: $message,
        ));

        $this->info("Message sent to [{$channel}]: {$message}");
    }
}
