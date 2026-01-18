@extends('layouts.public')

@section('title', 'Create invitation · LoveLeaf')

@section('content')
  <div
    id="invitation-app"
    data-template-key="{{ $templateKey }}"
  ></div>

  <script>
    window.__DEMO_INVITATION__ = @json($demoInvitation);
  </script>
@endsection

@push('scripts')
  @vite('resources/js/invitation/app.js')
@endpush
