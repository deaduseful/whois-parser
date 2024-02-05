@extends('layouts.app')
@section('title', __('Whois Parser'))
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">@yield('title')</div>

                    <div class="card-body">
                        <form method="POST"
                              action="{{ route(\Deaduseful\WhoisParser\ServiceProvider::NAMESPACE . '.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="user"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Query') }}</label>
                                <div class="col-md-6">
                                    <input id="query" type="text" class="form-control" name="query"
                                           value="{{ old('query') }}" placeholder="eg: example.com" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Parse') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (Session::has('response'))
        <pre id="response container">
            {{ Session::get('response') }}
        </pre>
    @endif
@endsection
