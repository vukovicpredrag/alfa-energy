@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }} <br>

                        <h1> User Guid </h1>
                        <p> Enter values for each segment in navbar menu</p>
                        <ul>
                            <li>1. cites</li>
                            <li>2. countries</li>
                            <li>3. contact-type</li>
                            <li>4. industry-type</li>
                        </ul>
                        <p> Then you can use all these values dynamically in CLIENTS panel (for client fields values). </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
