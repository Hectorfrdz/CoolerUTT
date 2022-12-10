<?php

namespace App\Jobs;

use App\Mail\Verificar_Telefono;
use App\Mail\Verification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class segundo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user,$Code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$Code)
    {
        $this->user=$user;
        $this->Code=$Code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::withBasicAuth('AC78410a00e0da71f3ebc678757cba36d1','01e731148f37e027e4c8785e43677f57')
        ->asForm()
        ->post('https://api.twilio.com/2010-04-01/Accounts/AC78410a00e0da71f3ebc678757cba36d1/Messages.json',[
        'To'=> "whatsapp:+52".$this->user->phone,
        'From'=>"whatsapp:+14155238886",
        'Body'=>"Tu codigo de verificacion es:".$this->Code
        ]);
    }
}