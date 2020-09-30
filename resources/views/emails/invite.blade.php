@component('mail::message')
# You have an invite!

Hi!

You were invited to join {{ $invite->company->name }} company at the {{ ucfirst($invite->role) }} position.
Link expires at {{ $invite->expires_at }}.

@component('mail::button', ['url' => route('register', ['invite_token' => $invite->code])])
Register
@endcomponent

    Enjoy it,
    {{ config('app.name') }} Team
@endcomponent
