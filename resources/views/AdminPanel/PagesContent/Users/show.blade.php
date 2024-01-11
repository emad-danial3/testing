@extends('AdminPanel.layouts.main')
@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3><i class="fa fa-hospital-o"></i> <a href="{{route('adminDashboard')}}">Home</a> / View

            </h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    @include('AdminPanel.layouts.messages')
                    <table class="table table-hover table-striped">
                        <tbody>
                        <tr>
                            <th>Full Name</th>
                            <td>{{$user->full_name}}</td>
                        </tr>
                    @if(isset($user->walletHistory))
                        <tr>
                            <th>Total Orders Amount</th>
                            <td>{{$user->walletHistory->total_orders_amount}}</td>
                        </tr>
                        <tr>
                            <th>Total Member Count</th>
                            <td>{{$user->walletHistory->total_members_count}}</td>
                        </tr>

                        <tr>
                            <th>Total Wallet</th>
                            <td>{{$user->walletHistory->total_wallet}}</td>
                        </tr>
                        <tr>
                            <th>Current Wallet</th>
                            <td>{{$user->walletHistory->current_wallet}}</td>
                        </tr>
                    @else
                        <tr>
                            <th>Wallet</th>
                            <td>0</td>
                        </tr>
                    @endif
                        <tr>
                            <th>My reOrders</th>
                            <td>
                                @if(isset($user->ordersHistory))
                                    @foreach($user->ordersHistory as $ordersHeader)
                                        <a href="{{route('orderHeaders.show',$ordersHeader)}}" target="_blank">Order: {{$ordersHeader->id}}</a>
                                        <p>{{$ordersHeader->total_order +$ordersHeader->shipping_amount}}</p>
                                        <br>
                                        <hr>
                                    @endforeach
                                @else
                                    <h1>No Orders</h1>
                                @endIf
                            </td>
                        </tr>



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
