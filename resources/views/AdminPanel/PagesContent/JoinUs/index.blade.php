@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('nettingJoin.index')}}">JoinUS</a></li>
                    </ol>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    @include('AdminPanel.layouts.messages')
    <div class="card">
        <div class="card-body">

            <form method="get" action="{{route('nettingJoin.index')}}">
                <input type="text"  name="name">
                <button type="submit" class="btn btn-danger btn-2xs ">Search</button>
            </form>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($nettingJoin) > 0)
                <table id="areasTable"  class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($nettingJoin as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->phone}}</td>
                            <td width="150">{{$row->message}}</td>
                            <td width="150">{{$row->name}}</td>
                            <td width="150">{{$row->email}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="pagination">

                    @if (isset($nettingJoin) && $nettingJoin->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $nettingJoin->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $nettingJoin->currentPage() + $interval;
                            if($to > $nettingJoin->lastPage()){
                              $to = $nettingJoin->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($nettingJoin->currentPage() > 1)
                                <li>
                                    <a href="{{ $nettingJoin->url(1)."&name=".app('request')->input('name')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $nettingJoin->url($nettingJoin->currentPage() - 1)."&name=".app('request')->input('name') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $nettingJoin->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a href="{{ !$isCurrentPage ? $nettingJoin->url($i)."&name=".app('request')->input('name') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($nettingJoin->currentPage() < $nettingJoin->lastPage())
                                <li>
                                    <a href="{{ $nettingJoin->url($nettingJoin->currentPage() + 1)."&name=".app('request')->input('name') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $nettingJoin->url($nettingJoin->lastpage())."&name=".app('request')->input('name') }}" aria-label="Last">
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
