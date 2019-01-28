<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/


Artisan::command('generateEventVkConversations  {--chatId=*} {--peerId=*} ', function () {
    $chatId = $this->option('chatId');
    $peerId = $this->option('peerId');
    App::call('App\Http\Controllers\Bot@generateEventAction',
        ['peerId' => intval(reset($peerId)), 'chatId' => intval(reset($chatId))]);
});


Artisan::command('inspire', function () {

    dump(PHP_EOL);die;
    // $chatId = $this->argument('competitionChatId') == null ? $this->argument('competitionChatId') : null;
    //  $competitionChatId = isset($this->argument('competitionChatId')) ? $this->argument('competitionChatId') : null;
    $chatId = $this->option('chatId');
    $competitionChatId = $this->option('competitionChatId');
    dump(reset($a));
    //  dump($this->argument('competitionChatId'));
    die;

    \Illuminate\Support\Facades\Log::debug(['asd']);
    die;
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('testShuffle', function () {
    //take users from VK chat
    $a = [0, 1, 2];
    //morerandom
    shuffle($a);
    dump($a);
    die;
    $test = [];
    $userIds = [];
    for ($i = 0; $i < 45; $i++) {
        $userIds[] = $i;
        $test[$i] = 0;
    }
    //$userIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    //$test = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0];
    $iterationsCount = rand(200, 300);
    //shuffling
    for ($i = 0; $i < $iterationsCount; $i++) {
        $shuffled = [];
        //for each element of ids array, we'll take random element to buffer array and then we'll unset it from stack.
        for ($j = count($userIds) - 1; $j >= 0; $j--) {
            $needle = rand(0, $j);
            $shuffled[] = $userIds[$needle];
            unset($userIds[$needle]);
            $userIds = array_values($userIds);
        }
        $userIds = $shuffled;
        //testing normal distribution
        $test[reset($userIds)]++;
        dump(reset($userIds));
    }

    dump(array_sort($test));
    die;
});