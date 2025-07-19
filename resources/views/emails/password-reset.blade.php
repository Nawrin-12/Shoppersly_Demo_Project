@component('mail::message')
    # Password Reset Request

    You requested a password reset.

    Click the button below to reset your password:

    @component('mail::button', ['url' => $url])
        Reset Password
    @endcomponent

    If the button doesn't work, copy and paste this link into your browser:

    **{{ $url }}**

    ---

    **Token:** `{{ $token }}`
    **Email:** `{{ $email }}`

    If you didn’t request this, please ignore this email.
@endcomponent



{{--Here is you generated password reset token--}}
{{--<p>--}}
{{--    <a href="{{url('')}}"--}}
{{--</p>--}}
