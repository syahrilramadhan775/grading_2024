<?php

namespace App\Console\Commands;

use App\Events\TaskCollection;
use App\Services\rabbitMQServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class tasksCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tasks Listening is wait async for AMQP Message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->consumeMessages('tasks_collection', function($message) {
            $this->info("Consume: {$message->getBody()}");
            Redis::set('tasks_collection', $message->getBody(), 'EX', 86400);
            $data = Redis::get('tasks_collection');

            event(new TaskCollection(json_decode($data, true)));
        });
    }
}
