@extends('layouts.invitation-base')

@section('title')
{{ $invitation->bride_name }} & {{ $invitation->groom_name }}
@endsection

@section('content')

@include('invitation.blocks.header')

@if(($features['program'] ?? false))
    @include('invitation.blocks.program')
@endif

@if(($features['gallery'] ?? false))
    <div class="bg-pink-50/70">
        @include('invitation.blocks.gallery')
    </div>
@endif

@if(($features['rsvp'] ?? false))
    @include('invitation.blocks.rsvp')
@endif

@endsection
