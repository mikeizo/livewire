@extends('layouts.layout')

@if($all_numbers)
  @section('title', 'All Numbers')
@else
  @section('title', 'My Numbers')
@endif

@section('content')

@if (session('status-success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('status-success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@elseif (session('status-error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('status-error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

<form method="GET" action="">
  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead class="thead-dark">
        <tr>
          <th>Phone</th>
          <th>Vanity</th>
          <th>Area Code</th>
          <th>Forward To #</th>
          <!--<th>Price</th>-->
          <th class="text-center">Reserved Date</th>
          @if($all_numbers)
            <th> Reserved By</th>
          @else
            <th class="text-center">Edit</th>
          @endif
        </tr>
        <tr>
          <th>
            <input type="text" class="form-control form-control-sm" value="@if(isset($search_query['phone'])){{ $search_query['phone'] }}@endif" name="phone" placeholder="phone number" maxlength="10">
          </th>
          <th>
            <input type="text" class="form-control form-control-sm" value="@if(isset($search_query['vanity'])){{ $search_query['vanity'] }}@endif" name="vanity" placeholder="vanity">
          </th>
          <th>
            <input type="text" class="form-control form-control-sm" value="@if(isset($search_query['area_code'])){{ $search_query['area_code'] }}@endif" name="area_code" placeholder="area code" maxlength="3">
          </th>
          <th>
            <input type="text" class="form-control form-control-sm" value="@if(isset($search_query['forward_to'])){{ $search_query['forward_to'] }}@endif" name="forward_to" placeholder="Forward To" maxlength="10">
          </th>
          <!--<th></th>-->
          <th>
            <div class="input-group" id="daterange">
              <input class="form-control form-control-sm" type="text" name="start" placeholder="{{ date('Y-m-d') }}" value="@if(isset($search_query['start'])){{ $search_query['start'] }}@endif">
              <span class="px-1">to</span>
              <input class="form-control form-control-sm" type="text" name="end" placeholder="{{ date('Y-m-d') }}" value="@if(isset($search_query['end'])){{ $search_query['end'] }}@endif">
            </div>
          </th>
          <th class="text-right">
            <input type="submit" class="btn btn-success btn-sm" value="Search">
            <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm">Reset</a>
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($search_results as $number)
        <tr>
          <td>
            @php
              echo substr($number->phone, 0, 3) . '-' . substr($number->phone, 3, 3) . '-' . substr($number->phone, 6, 4);
            @endphp
          </td>
          <td>{{ strtoupper($number->vanity) }}</td>
          <td>{{ $number->area_code }}</td>
          <td>
            @if($number->forward_to)
              @php
                echo substr($number->forward_to, 0, 3) . '-' . substr($number->forward_to, 3, 3) . '-' . substr($number->forward_to, 6, 4);
              @endphp
            @endif
          </td>
          <!--<td>${{ $number->price }}</td>-->
          <td  class="text-center">{{ $number->created_at }}</td>
          @if($all_numbers)
            <td>{{ $number->name }} - {{ $number->email }}</td>
          @else
            <td class="text-center">
              <a href="/admin/forward/{{$number->id}}">
                <i class="fas fa-edit"></i>
              </a>
            </td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>

    <!--Pagination-->
    {{ $search_results->withQueryString()->links() }}

  </div>
</form>
@endsection