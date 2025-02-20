@extends('layouts.app-sub')

@section('content')
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card col-md-12">
          <div class="card-header font-bold">{{ __('BET HISTORY') }}</div>
          <div class="card-body">
            <table class="table table-striped" style="width: 100%" id="bethistory-table">
              <thead>
                <tr>
                  <th>#</th>
                  {{-- <th>EVENT</th> --}}
                  <th>FIGHT #</th>
                  <th>BET SIDE</th>
                  <th>RESULT</th>
                  <th>TOTAL WIN</th>
                  <th>PERCENT %</th>
                  <th>BET</th>
                  <th>CURRENT POINTS</th>
                  <th>DATE</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('additional-scripts')
  <script src="{{ asset('js/bet-history.js') }}" defer></script>
@endsection
