@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('banners.index')}}">Banners</a></li>
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

            <form method="get" action="{{route('banners.index')}}">
                <input type="text"  name="name">
                <button type="submit" class="btn btn-danger btn-2xs ">Search</button>
            </form>
            <h3 class="card-title float-right">
                <a class="btn btn-warning" href="{{route('banners.create')}}">Create New Banner</a>
            </h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($banners) > 0)
                <table id="areasTable"  class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name Ar</th>
                        <th>Image</th>
                        <th>Priority</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banners as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->title_en}}</td>
                            <td width="150">{{$row->title_ar}}</td>
                            <td><a href="{{url($row->url)}}" target="_blank">
                                    <img src="{{url($row->url)}}" width="350" height="200">
                                </a></td>
                            <td width="150">{{$row->priority}}</td>
                    <td>
                    <a class="btn btn-dark" href="{{route('banners.edit',$row)}}">Edit</a>
                    <form action="{{route("banners.destroy", $row)}}" method="post"
                          style="display:inline;">
                        @csrf
                        @method('delete')
                        <button type="button" id="btnDelete" class="btn btn-danger btn-delete">Delete
                        </button>
                    </form>
                    </td>
                    </tr>
                    @endforeach

                    </table>
                    <div class="pagination">

                    @if (isset($banners) && $banners->lastPage() > 1)
                    <ul class="pagination">
                    @php
                    $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                    $from = $banners->currentPage() - $interval;
                    if($from < 1){
                    $from = 1;
                    }

                    $to = $banners->currentPage() + $interval;
                    if($to > $banners->lastPage()){
                    $to = $banners->lastPage();
                    }
                    @endphp
                    <!-- first/previous -->
                    @if($banners->currentPage() > 1)
                    <li>
                        <a href="{{ $banners->url(1)."&name=".app('request')->input('name')}}" aria-label="First">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $banners->url($banners->currentPage() - 1)."&name=".app('request')->input('name') }}" aria-label="Previous">
                            <span aria-hidden="true">&lsaquo;</span>
                        </a>
                    </li>
                    @endif
                    <!-- links -->
                    @for($i = $from; $i <= $to; $i++)
                    @php
                        $isCurrentPage = $banners->currentPage() == $i;
                    @endphp
                    <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                        <a href="{{ !$isCurrentPage ? $banners->url($i)."&name=".app('request')->input('name') : '' }}">
                            {{ $i }}
                        </a>
                    </li>
                    @endfor
                    <!-- next/last -->
                    @if($banners->currentPage() < $banners->lastPage())
                    <li>
                        <a href="{{ $banners->url($banners->currentPage() + 1)."&name=".app('request')->input('name') }}" aria-label="Next">
                            <span aria-hidden="true">&rsaquo;</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $banners->url($banners->lastpage())."&name=".app('request')->input('name') }}" aria-label="Last">
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
