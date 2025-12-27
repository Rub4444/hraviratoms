@extends('layouts.invitation-base')

@section('title')
{{ $invitation->bride_name }} & {{ $invitation->groom_name }}
@endsection

@section('content')

@include('invitation.blocks.header')

@if(($features['program'] ?? false))
    @include('invitation.blocks.program')
@endif

@if($features['gallery'])
    @include('invitation.blocks.gallery')
    @include('invitation.blocks.lightbox')
@endif

@if(($features['rsvp'] ?? false))
    @include('invitation.blocks.rsvp')
@endif

@endsection
