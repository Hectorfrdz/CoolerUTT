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
    protected $user,$url,$Code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $url, $Code)
    {
        $this->user=$user;
        $this->url=$url;
        $this->Code=$Code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::post('https://rest.nexmo.com/sms/json',[
            'from'=>"Vonage APIs",
            'text'=>"Codigo de verificacion: $this->Code",
            'to'=>52 .$this->user->phone,
            'api_key'=>"8b651de2",
            'api_secret'=>"umg1irRgyuvSGqGb"
        ]);

        Mail::to($this->user)->send(new Verificar_Telefono($this->user,$this->url));
    }
}
