<?php

namespace App\Console\Commands;

use App\Comment;
use App\Discussion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class DataBaseUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:update {database=mysql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update mysql and redis database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('database update start!');
        $database = $this->argument('database');
        if($database == 'mysql'){
            $this->info('use mysql update redis!');
            $discussions = Discussion::all();
            foreach ($discussions as $discussion){
                Redis::command('HSET', ['warshipcommunity:discussion:viewcount',$discussion->id,$discussion->hot_discussion]);
                Redis::command('HSET', ['warshipcommunity:discussion:nicecount',$discussion->id,$discussion->nice_discussion]);
            }
            $comments = Comment::all();
            foreach ($comments as $comment){
                Redis::command('HSET', ['warshipcommunity:comment:nicecount',$comment->id,$comment->nice_comment]);
            }
        } else {
            $this->info('use redis update mysql!');
        }
        $this->info('database update finish!');
    }
}
