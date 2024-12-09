<?php

namespace App\Console\Commands;

use App\Events\SendMessageEvent;
use App\Events\UsersCollectionListener;
use App\Services\rabbitMQServices;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redis;

class usersCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Users Listening is wait async for AMQP Message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->consumeMessages('users_collection', function($message) {
            $this->info("Consume: {$message->getBody()}");
            Redis::set('users_collection', $message->getBody(), 'EX', 86400);
            $data = Redis::get('users_collection');

            event(new UsersCollectionListener(json_decode($data, true)));
        });
    }
}
