@component('mail::message')
    # Password Reset Request

    Click the button below to reset your password:

    @component('mail::button', ['url' => $url])
        Reset Password
    @endcomponent

    If you didn't request a password reset, please ignore this email.

    Thanks,<br>
    {{ config('shoppersly') }}
@endcomponent
