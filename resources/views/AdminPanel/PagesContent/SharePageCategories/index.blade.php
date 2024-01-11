@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('sharePageCategories.index')}}">Share Pages Category</a></li>
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
            <h3 class="card-title">
                <a class="btn btn-warning" href="{{route('sharePageCategories.create')}}">Share Pages Category</a>
            </h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($sharePageCategories) > 0)
                <table id="areasTable"  class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name Ar</th>
                        <th>Page</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sharePageCategories as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->name_en}}</td>
                            <td width="150">{{$row->name_ar}}</td>
                            <td width="150">
                                @if($row->page_category_source_id == 1)Catalogue @else Media @endif
                            </td>
                            <td>
                                <a class="btn btn-dark" href="{{route('sharePageCategories.edit',$row)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name AR</th>
                        <th>Page</th>
                        <th>Control</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="pagination">

                    @if (isset($sharePageCategories) && $sharePageCategories->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $sharePageCategories->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $sharePageCategories->currentPage() + $interval;
                            if($to > $sharePageCategories->lastPage()){
                              $to = $sharePageCategories->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($sharePageCategories->currentPage() > 1)
                                <li>
                                    <a href="{{ $sharePageCategories->url(1)."&name=".app('request')->input('name')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $sharePageCategories->url($sharePageCategories->currentPage() - 1)."&name=".app('request')->input('name') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $sharePageCategories->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a href="{{ !$isCurrentPage ? $sharePageCategories->url($i)."&name=".app('request')->input('name') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($sharePageCategories->currentPage() < $sharePageCategories->lastPage())
                                <li>
                                    <a href="{{ $sharePageCategories->url($sharePageCategories->currentPage() + 1)."&name=".app('request')->input('name') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $sharePageCategories->url($sharePageCategories->lastpage())."&name=".app('request')->input('name') }}" aria-label="Last">
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
