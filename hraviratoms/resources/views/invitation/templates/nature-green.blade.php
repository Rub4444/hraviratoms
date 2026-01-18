@extends('layouts.invitation-base')

@section('title')
{{ $invitation->bride_name }} & {{ $invitation->groom_name }}
@endsection
@section('content')

@include('invitation.blocks.header')

@if($features['program'] ?? false)
  <div class="card-soft">
    @include('invitation.blocks.program')
  </div>
@endif

@if($features['gallery'] ?? false)
  <div class="card-soft">
    @include('invitation.blocks.gallery')
  </div>
@endif

@if($features['rsvp'] ?? false)
  @include('invitation.blocks.rsvp')
@endif

@endsection
