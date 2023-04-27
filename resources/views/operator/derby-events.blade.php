@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row col-md-12 justify-content-center">
    <div class="col-lg-6 m-auto p-3">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">NEW EVENT</h3>
        </div>
        <form class="form-horizontal">
          <div class="card-body">
            <div class="form-group row my-2">
              <label for="event-name" class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" id="event-name" placeholder="Event Name">
              </div>
            </div>
            <div class="form-group row my-2">
              <label for="inputPassword3" class="col-sm-2 col-form-label">Schedule</label>
              <div class="col-sm-10">
                <input type="date" class="form-control" id="sched-date">
              </div>
            </div>
            <div class="form-group row my-2">
              <label for="inputPassword3" class="col-sm-2 col-form-label">Time Start</label>
              <div class="col-sm-10">
                <input type="time" class="form-control" id="time-start">
              </div>
            </div>
           

          </div>
          <div class="card-footer">
            <button id="add-derby" class="btn btn-primary float-right mb-2">Add</button>
          </div>

        </form>
      </div>
    </div>
    <div class="card-header font-bold">DERBY EVENTS LIST</div>
      <table class="table table-striped w-100" id="events-table">
        <thead>
          <tr>
            <th>#</th>
            <th>EVENT NAME</th>
            <th>SCHED DATE</th>
            <th>START TIME</th>
            <th>STATUS</th>
            <th>ACTION</th>
          </tr>
        </thead>
        {{-- <tbody>
        </tbody> --}}
      </table>
    </div>
    <div class="card-body">
      @if (session('status'))
      <div class="alert alert-success" role="alert">
        {{ session('status') }}
      </div>
      @endif

    </div>
    
  </div>
</div>
@endsection

@section('additional-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="{{ asset('js/derby-events.js') }}" defer></script>
@endsection
