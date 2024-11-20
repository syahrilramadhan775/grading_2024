<?php

namespace App\Console\Commands;

use App\Services\rabbitMQServices;
use Illuminate\Console\Command;

class UserCommandListening extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:listening';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Listening is wait async for AMQP Message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->consumeMessages('queue_syahril', function($message){
            $this->info("Consume: {$message->getBody()}");
        });
    }
}
