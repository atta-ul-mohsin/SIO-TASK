@extends('layouts.app')
@section('content')
    @include('notifications.error')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-title">
                        <a class="btn btn-primary" href="{{ route('timelogs.index') }}"> Back</a>
                        <h2 class="text-center">{{ __('Edit Time Log') }}</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('timelogs.update', $timelog->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="hours" class="col-md-4 col-form-label text-md-end">{{ __('Log Date') }}</label>

                                <div class="col-md-6">
                                    <input id="log_date" type="date"  value="{{ date('Y-m-d', strtotime($timelog->log_date)) }}" class="form-control @error('log_date') is-invalid @enderror" name="log_date" value="{{ old('log_date') }}" required autocomplete="hours" autofocus>

                                    @error('log_date')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="hours" class="col-md-4 col-form-label text-md-end">{{ __('Hours') }}</label>

                                <div class="col-md-6">
                                    <input id="hours" min="0" max="24" type="number"  value="{{ $timelog->timeData['hours'] }}" class="form-control @error('hours') is-invalid @enderror" name="hours" value="{{ old('hours') }}" required autocomplete="hours" autofocus>

                                    @error('hours')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="minutes" class="col-md-4 col-form-label text-md-end">{{ __('Minutes') }}</label>
                                <div class="col-md-6">
                                    <input id="minutes" min="0" max="1440" type="number"  value="{{ $timelog->timeData['minutes']  }}" class="form-control @error('minutes') is-invalid @enderror" name="minutes" value="{{ old('minutes') }}" required autocomplete="minutes" autofocus>
                                    @error('hours')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="seconds" class="col-md-4 col-form-label text-md-end">{{ __('Seconds') }}</label>
                                <div class="col-md-6">
                                    <input id="seconds" min="0" max="86400" type="number"  value="{{ $timelog->timeData['seconds']  }}" class="form-control @error('seconds') is-invalid @enderror" name="seconds" value="{{ old('seconds') }}" required autocomplete="seconds" autofocus>

                                    @error('seconds')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

