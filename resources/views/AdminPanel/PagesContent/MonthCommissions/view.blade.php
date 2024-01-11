@extends('AdminPanel.layouts.main')
@section('content')
    <div class="card">
        <div class="card-body" style="width: 100%;max-width: 100%;overflow: scroll;">

            @if($commission)

                <table id="commissionsTable" style="width: 100%" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Commission ID</th>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Personal Order</th>
                        <th>Active Team Count</th>
                        <th>g1 new Count</th>
                        <th>g1 new Sales</th>
                        <th>Total g1 Sales</th>
                        <th>Total g2 Sales</th>
                        <th>Total Team Sales</th>
                        <th>Previous Level ID</th>
                        <th>Deserve Level ID</th>
                        <th>UpLevel</th>
                        <th>Earn g1 New Sales</th>
                        <th>Earn total g1 Sales</th>
                        <th>Earn total g2 Sales</th>
                        <th>Earn UpLevel Sales</th>
                        <th>Total Earnings</th>
                        <th>Create AT</th>
                        <th>Is Paid</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$commission['id']}}</td>
                        <td>{{$commission['user_id']}}</td>
                        <td>{{$commission['user_name']}}</td>
                        <td>{{$commission['personal_order']}}</td>
                        <td>{{$commission['active_team']}}</td>
                        <td>{{$commission['g1_new']}}</td>
                        <td>{{$commission['g1_new_sales']}}</td>
                        <td>{{$commission['total_g1_sales']}}</td>
                        <td>{{$commission['total_g2_sales']}}</td>
                        <td>{{$commission['total_sales_po_g1_g2']}}</td>
                        <td>{{$commission['previousSalesLeaderLevelId']}}</td>
                        <td>{{$commission['salesLeaderLevelId']}}</td>
                        <td>{{$commission['upLevel'] == '1' ? 'Yes' : 'no'}}</td>
                        <td>{{$commission['e_g1_new_sales']}}</td>
                        <td>{{$commission['e_total_g1_sales']}}</td>
                        <td>{{$commission['e_total_g2_sales']}}</td>
                        <td>{{$commission['e_upLevel']}}</td>
                        <td>{{$commission['total_earnings']}}</td>
                        <td>{{$commission['created_at']}}</td>
                        <td>{{$commission['is_paid']}}</td>
                    </tr>

                    </tbody>
                </table>
            @endif
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4 text-center">
                    You
                    <div style="border: 1px solid #888;padding: 5px;border-radius: 5px;">
                        <i class="fa-solid fa-circle-user fa-2x"></i> &nbsp; &nbsp;
                        <h6>{{$commission['user_name']}}</h6>
                        <h6>ID : {{$commission['user_id']}}</h6>
                        <h6>Personal orders : {{$commission['total_personal']}}</h6>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
            G1
            <div class="row">
                @if($commission['getMyTeamDataG1'] && count($commission['getMyTeamDataG1'])>0)
                    @for ($i = 0; $i < count($commission['getMyTeamDataG1']); $i++)
                        <div class="col-md-3 text-center"
                             style="border: 1px solid #888;padding: 5px;border-radius: 5px;">
                            @if(in_array($commission['getMyTeamDataG1'][$i]['user_id'],$commission['getMyNewMembers']))
                                <i class="fa-solid fa-circle-user fa-2x text-primary"></i>
                            @else
                                <i class="fa-solid fa-circle-user fa-2x"></i> &nbsp; &nbsp;
                            @endif
                            <h6>{{$commission['getMyTeamDataG1'][$i]['full_name']}}</h6>
                            <h6>ID : {{$commission['getMyTeamDataG1'][$i]['user_id']}}</h6>
                            <h6>Personal orders : {{$commission['getMyTeamDataG1'][$i]['total']}}</h6>
                            <h6>Orders Count : {{$commission['getMyTeamDataG1'][$i]['orders_count']}}</h6>
                        </div>
                    @endfor
                @endif
            </div>
            G2
            <div class="row">
                @if($commission['getMyTeamDataG2'] && count($commission['getMyTeamDataG1'])>0)
                    @for ($iii = 0; $iii < count($commission['getMyTeamDataG2']); $iii++)
                        <div class="col-md-3 text-center"
                             style="border: 1px solid #888;padding: 5px;border-radius: 5px;">
                            <i class="fa-solid fa-circle-user fa-2x"></i> &nbsp; &nbsp;
                            <h6>{{$commission['getMyTeamDataG2'][$iii]['full_name']}}</h6>
                            <h6>ID : {{$commission['getMyTeamDataG2'][$iii]['user_id']}}</h6>
                            <h6>Personal orders : {{$commission['getMyTeamDataG2'][$iii]['total']}}</h6>
                            <h6>Orders Count : {{$commission['getMyTeamDataG2'][$iii]['orders_count']}}</h6>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </div>
@endsection
