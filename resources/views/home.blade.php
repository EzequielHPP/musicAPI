@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <p>Welcome to this Music API interface.</p>
                        <p>We are generous and issue your unique token to allow access to our API</p>
                        <p>Normaly would be more complicated with an OAUTH 2, but for simplicity let's just give you a token :)</p>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Your personal API token</div>

                    <div class="panel-body">
                        <p>
                            Add the following token to your request header as follows:
                        </p>
                        <code>
                            Authorization: {{$token}}
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
