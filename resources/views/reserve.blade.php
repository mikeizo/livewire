@extends('layouts.layout')

@section('title', 'Reserved Numbers')

@section('content')
  <div class="row">
    @if($numbers_success)
    <div class="col-sm-6">
      <h3 class="text-success">Success</h3>
      <ul>
        @foreach ($numbers_success as $success)
          <li class="text-success">{{ $success }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    @if($numbers_failed)
    <div class="col-sm-6">
      <h3 class="text-danger">Failed</h3>
      <ul>
        @foreach ($numbers_failed as $failed)
          <li class="text-danger">{{ $failed }}</li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>

@endsection