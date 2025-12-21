@extends('layouts.invitation-base')

@section('title')
{{ $invitation->bride_name }} & {{ $invitation->groom_name }}
@endsection

@section('styles')
<link rel="stylesheet" href="/css/templates/romantic.css">
@endsection

@section('content')
  <h1>{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</h1>
  <p>{{ $invitation->date }}</p>

  @include('partials.rsvp-form')
@endsection
