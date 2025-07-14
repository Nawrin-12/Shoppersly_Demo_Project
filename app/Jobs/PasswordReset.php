<?php

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordReset implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;
    protected $user;
    protected $Url;
    public function __construct(User $user, string $Url)
    {
        $this->user=$user;
        $this->Url=$Url;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
//        $token = $this->user->createToken('password-reset')->accessToken;
        $token = Str::random(20);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->user->email],
            [
                'token' => $token,
                'created_at'=>Carbon::now()
            ]
        );
        $resetUrl = $this->Url . '?' . http_build_query([
                'token' => $token,
                'email' => $this->user->email
            ]);

        Mail::to($this->user->email)->send(new ResetPasswordMail($resetUrl));
    }
}
