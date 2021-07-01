@extends('layouts.layout')

@section('title', 'Search')

@section('content')

<form method="GET" action="/admin/search">
  <div class="d-flex flex-row">
    <div class=" pr-2">
      <label>Area Code:</label>
      <select class="form-control" name="area_code" required>
        <option value="">--Area Code--</option>
        <option
          value="315"
          @if(isset($search_query['area_code']) && $search_query['area_code'] == 315) selected @endif
        >315</option>
        <option
          value="585"
          @if(isset($search_query['area_code']) && $search_query['area_code'] == 585) selected @endif
        >585</option>
        <option
          value="607"
          @if(isset($search_query['area_code']) && $search_query['area_code'] == 607) selected @endif
        >607</option>
        <option
          value="716"
          @if(isset($search_query['area_code']) && $search_query['area_code'] == 716) selected @endif
        >716</option>
      </select>
    </div>
    <div class="pr-2">
      <label>Vanity:</label>
      <input
        type="text"
        class="form-control @error('vanity') is-invalid @enderror"
        value="@if(isset($search_query['vanity'])){{ $search_query['vanity'] }}@endif"
        name="vanity"
        maxlength="7"
        required
      >
    </div>
    <div class="align-self-end">
      <input type="submit" class="btn btn-success" value="Search">
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

@if ($numbers)
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead class="thead-dark">
      <tr>
        <th>Phone</th>
        <th>Vanity</th>
        <th>Reserve & Forward To</th>
        <!--<th>Price</th>-->
      </tr>
    </thead>
    <tbody>
      @foreach ($numbers->items as $number)
        <tr>
          <td>
            @php
              echo substr_replace ( substr($number->phone, 3, 7), "-", 3, 0);
            @endphp
          </td>
          <td>
          @if(isset($number->vanity))
            @php
              $strlen = 7 - (strlen($vanity));
              echo substr_replace(  substr( strtoupper($number->vanity), 3, 7 ), "-", $strlen, 0);
            @endphp
          @else
            @php
              echo substr_replace ( substr($number->phone, 3, 7), "-", 3, 0);
            @endphp
          @endif
          </td>
          <td>
            <a class="btn btn-warning btn-sm" href="/admin/forward?phone={{$number->phone}}&vanity={{ $vanity }}">
              Reserve & Forward <i class="fas fa-forward ml-2"></i>
            </a>
          </td>
          <!--<td>${{ $number->price }}</td>-->
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif

@endsection