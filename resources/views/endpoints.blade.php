@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Endpoints of the API</div>

                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>
                                    Methood
                                </th>
                                <th>
                                    Endpoint
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Returns
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($routes as $index => $route)
                                <tr>
                                    <td>
                                        {{strtoupper($route['methood'])}}
                                    </td>
                                    <td>
                                        <a href="javascript:$('#route{{$index}}').toggleClass('hidden');">{{$route['url']}}</a>
                                    </td>
                                    <td>
                                        {{$route["description"]}}
                                    </td>
                                    <td>
                                        {{$route["returns"]}}
                                    </td>
                                </tr>
                                <tr id="route{{$index}}" class="hidden">
                                    <td colspan="4" class="bg-info">

                                        <h3>Request Parameters:</h3>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <td>
                                                    Parameter
                                                </td>
                                                <td>
                                                    Description
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($route["urlelements"] as $element)
                                                <tr>
                                                    <td>
                                                        {{$element[0]}}
                                                    </td>
                                                    <td>
                                                        {{$element[1]}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        <h3>Request Body data:</h3>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <td>
                                                    Parameter key
                                                </td>
                                                <td>
                                                    Type
                                                </td>
                                                <td>
                                                    Description
                                                </td>
                                                <td>
                                                    Example Value
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($route["bodyData"] as $element)
                                                <tr>
                                                    <td>
                                                        {{$element[0]}}
                                                    </td>
                                                    <td>
                                                        {{$element[1]}}
                                                    </td>
                                                    <td>
                                                        {{$element[2]}}
                                                    </td>
                                                    <td>
                                                        <pre>{!! var_dump(json_decode($element[3])) !!}</pre>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        @if($route['replySuccessCode'] != '')
                                            <h3>Reply code:</h3>
                                            <pre>{{$route['replySuccessCode']}}</pre>
                                        @endif
                                        @if($route['exampleReply'] != '')
                                            <h3>Example reply:</h3>
                                            <pre>{!! var_dump(json_decode($route['exampleReply'])) !!}</pre>
                                        @endif

                                        @if($route['replyFailCode'] != '')
                                            <h3>Reply Unsuccessful code:</h3>
                                            <pre>{{$route['replyFailCode']}}</pre>
                                        @endif
                                        @if($route['exampleBadReply'] != '')
                                            <h3>Example Unsuccessful reply:</h3>
                                            <pre>{!! var_dump(json_decode($route['exampleBadReply'])) !!}</pre>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
