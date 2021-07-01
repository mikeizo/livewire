@extends('layouts.layout')

@section('title', 'Reserve & Forward Number Edit')

@section('content')

<form method="POST" action="/admin/forward/{{ $number_data->id }}">
  @csrf
  @method('PUT')

  <div class="d-flex flex-row">
    <div class="pr-2">
      <label>Forward:</label>
      <input
        class="form-control"
        type="text"
        name="forward_from"
        value="{{ $number_data->phone }}"
        readonly
        required
      >
    </div>
    <div class="pr-2">
      <label>To:</label>
      <input
        class="form-control @error('forward_to') is-invalid @enderror"
        type="text"
        name="forward_to"
        value="{{ $number_data->forward_to }}"
        maxlength="10"
        required
      >
    </div>
    <div class="align-self-end">
      <input type="submit" class="btn btn-success" value="Submit" >
    </div>

    @if ($errors->any())
    <div class="alert alert-danger mb-0 ml-3 p-2 align-self-end" role="alert">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

  </div>
</form>
@endsection