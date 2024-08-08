<?php

namespace App\Console\Commands;

use App\Enum\StatusEnum;
use App\Mail\Notification;
use App\Repository\BorrowingRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BorrowDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:borrow-duration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    public function handle(BorrowingRepository $borrowingRepository) 
    {
        $dates = $borrowingRepository->getByStatus(StatusEnum::BORROWED);
        foreach ($dates as $date) {
            #$date = Carbon::parse($date);  
            if(Carbon::now()->equalTo($date)){
                $this->notReturned($borrowingRepository,$date);
            }
        }
    }

    public function notReturned(BorrowingRepository $borrowingRepository, $date)
    {
        $borrowings = $borrowingRepository->getByDate($date);
        foreach ($borrowings as $borrowing) {
            $borrowing->status === StatusEnum::OVERDUE;
            $borrowing->save();

            Mail::to($borrowing->user->email)->send(new Notification($borrowing->name));
            $this->info('notification sent to: ' . $borrowing->user->email);
        }
    }
}
