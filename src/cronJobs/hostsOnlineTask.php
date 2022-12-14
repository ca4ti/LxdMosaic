<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/hostsOnline.php');
$task
    ->cron("* * * * * ")
    ->description('Checking hosts are online');

return $schedule;
