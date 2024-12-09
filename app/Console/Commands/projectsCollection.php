<?php

namespace App\Console\Commands;

use App\Events\ProjectsCollectionListener;
use App\Services\rabbitMQServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class projectsCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Projects Listening is wait async for AMQP Message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rabbitMQServices = new rabbitMQServices();
        $rabbitMQServices->consumeMessages('projects_collection', function($message) {
            $this->info("Consume: {$message->getBody()}");
            Redis::set('projects_collection', $message->getBody(), 'EX', 86400);
            $data = Redis::get('projects_collection');

            event(new ProjectsCollectionListener(json_decode($data, true)));
        });
    }
}
