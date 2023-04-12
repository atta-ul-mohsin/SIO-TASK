
@extends('layouts.app')
@include('notifications.error')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-title">
                        <a class="btn btn-primary" href="{{ url('timelogs/report/weekly') }}"> Weekly Report</a>
                        <a class="btn btn-primary" href="{{ url('timelogs/report/monthly') }}"> Monthly Report</a>
                        <div class="row">
                            <div class="col-lg-12 margin-tb">
                                    <h2 class="text-center">{{ __('Time Logs') }}</h2>
                                    <a class="btn btn-primary" href="{{ route('timelogs.create') }}"> {{ __('Add Time Log') }}</a>
                            </div>
                        </div>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Log Date</th>
                    <th>Hours</th>
                    <th>Minutes</th>
                    <th>Seconds</th>
                    <th width="280px">Action</th>
                </tr>
                @foreach ($timelogs as $timelog)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $timelog->log_date }}</td>
                        <td>{{ $timelog->timeData['hours'] }}</td>
                        <td>{{ $timelog->timeData['minutes'] }}</td>
                        <td>{{ $timelog->timeData['seconds'] }}</td>
                        <td>
                            <form action="{{ route('timelogs.destroy',$timelog->id) }}" method="POST">

                                <a class="btn btn-info" href="{{ route('timelogs.show',$timelog->id) }}">Show</a>

                                <a class="btn btn-primary" href="{{ route('timelogs.edit',$timelog->id) }}">Edit</a>

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
                    </div>
                </div>
            </div>

            {!! $timelogs->links() !!}
        </div>
    </div>
@endsection

