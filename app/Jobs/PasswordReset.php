<?php

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordReset implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue, Dispatchable;
    public $user, $token,$resetUrl;
//    protected $Url;
    public function __construct($user, $token,$resetUrl)
    {
        $this->user=$user;
        $this->token=$token;
        $this->resetUrl=$resetUrl;
    }

    public function handle()
    {
//        $resetUrl = url('/reset-password/'.$this->token . '?email=' . urlencode($this->user->email));

        Mail::to($this->user->email)->send(new ResetPasswordMail($this->resetUrl,$this->token,$this->user->email));
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->user->email],
            [
                'token' => $token,
                'created_at'=>Carbon::now()
            ]
        );
        $resetUrl = $this->Url . '?' . http_build_query([
                'token' => $token,
                'email' > $this->user->email
            ]);

        Mail::to($this->user->email)->send(new ResetPasswordMail( $resetUrl, $this->user->email ));
    }
}
