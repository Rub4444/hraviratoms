@extends('layouts.public')

@section('title', 'LoveLeaf · Demo Invitation')

@section('content')
<div id="demo-app"
     data-template="{{ $templateKey }}">
</div>
@endsection

@push('scripts')
@vite('resources/js/demo/main.js')
@endpush
