<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all Event attendees than event start soon.';

    /**
     * Execute the console command.php
     */
    public function handle()
    {
        // * 抓出開始時間24小時以內的事 件來做通知
        $events = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} {$eventLabel}.");
        $events->each(
            fn($event) => $event->attendees->each(
                fn($attendee) => $this->info("Notifying the user{$attendee->user->id}")
            )
        );

        $this->info('Reminder notifications sent successfully.');
    }
}
