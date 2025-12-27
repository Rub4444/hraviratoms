@extends('layouts.invitation-base')

@section('title')
{{ $invitation->bride_name }} & {{ $invitation->groom_name }}
@endsection

@section('content')

<div class="bg-black/90 text-white">

    @include('invitation.blocks.header')

    @if(($features['program'] ?? false))
        @include('invitation.blocks.program')
    @endif

    @if(($features['gallery'] ?? false))
        @include('invitation.blocks.gallery')
    @endif

    @if(($features['rsvp'] ?? false))
        @include('invitation.blocks.rsvp')
    @endif

</div>

@endsection
