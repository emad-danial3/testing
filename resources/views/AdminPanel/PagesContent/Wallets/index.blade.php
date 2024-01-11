@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('wallets')}}">Wallets</a></li>
                    </ol>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('AdminPanel.layouts.messages')

    <div class="card">
    <div class="card-body">
        <div class="card-header" style="float: right">
            <h3 class="card-title">

                <form method="post" action="{{route('wallets.importWalletsSheet')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="file"  name="file" required>
                    <button type="submit" class="btn btn-danger">Import Wallet Sheet</button>
                </form>
            </h3>
        </div>
    </div>
    </div>

    <div class="card">
        <div class="card-body">

           <div class="row">
               <div class="col-md-3">
                   <form method="get" action="{{route('wallets')}}">
                       <input type="text" id="searchtext" placeholder="Enter user ID" name="user_id">
                       <button type="submit" class="btn btn-danger">Search</button>
                   </form>
               </div>

               <div class="col-md-6"></div>
               <div class="col-md-3">
                   <form method="get" action="{{route('wallets.ExportSheet')}}">
                       <button type="submit" class="btn btn-primary">Export Sheet</button>
                   </form>
               </div>
           </div>

        </div>


        <!-- /.card-header -->
        <div class="card-body" style="overflow-x:scroll">
            @if(count($wallets) > 0)
                <table id="commissionsTable"  style="width: 100%" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Total Wallet</th>
                        <th>Current Wallet</th>
                        <th>Used Wallet</th>
                        <th>Created At</th>
                        <th>Last Updated At</th>
                        <th>Added By </th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($wallets as $row)
                        <tr>
                            <td >{{$row->id}}</td>
                            <td >{{$row->user_id}}</td>
                            <td >{{$row->total_wallet}}</td>
                            <td >{{$row->current_wallet}}</td>
                            <td >{{$row->used_wallet}}</td>
                            <td >{{$row->created_at}}</td>
                            <td >{{$row->updated_at}}</td>
                            <td >{{$row->added_by}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination">

                    @if (isset($wallets) && $wallets->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $wallets->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $wallets->currentPage() + $interval;
                            if($to > $wallets->lastPage()){
                              $to = $wallets->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($wallets->currentPage() > 1)
                                <li>
                                    <a href="{{ $wallets->url(1)."&name=".app('request')->input('name')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $wallets->url($wallets->currentPage() - 1)."&name=".app('request')->input('name') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $wallets->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a href="{{ !$isCurrentPage ? $wallets->url($i)."&name=".app('request')->input('name') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($wallets->currentPage() < $wallets->lastPage())
                                <li>
                                    <a href="{{ $wallets->url($wallets->currentPage() + 1)."&name=".app('request')->input('name') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $wallets->url($wallets->lastpage())."&name=".app('request')->input('name') }}" aria-label="Last">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>

            @else
                <h1 class="text-center">NO DATA</h1>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
@endsection
