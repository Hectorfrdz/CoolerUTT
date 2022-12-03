<?php

namespace App\Jobs;

use App\Mail\Verificar_Correo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class primero implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user,$url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $url)
    {
        $this->user=$user;
        $this->url=$url;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    
    public function handle()
    {
        Mail::to($this->user)->send(new Verificar_Correo($this->user,$this->url));
    }
}
