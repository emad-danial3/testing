@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('sharePages.index')}}">Share Pages Category</a></li>
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
                <a class="btn btn-warning" href="{{route('sharePages.create')}}">Share Pages </a>
            </h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($sharePages) > 0)
                <table id="areasTable"  class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name Ar</th>
                        <th>Status</th>
                        <th>upload Link</th>
                        <th>Category</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sharePages as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->title_en}}</td>
                            <td width="150">{{$row->title_ar}}</td>
                            <td width="150">{{$row->status == '1' ? 'Active' :'No Active' }}</td>
                            <td width="150"><a href="{{$row->upload}}" TARGET="_blank">lINK</a></td>
                            <td width="150">{{$row->category->name_en}}</td>
                            <td>
                                <a class="btn btn-dark" href="{{route('sharePages.edit',$row)}}">Edit</a>
                                <form action="{{route("sharePages.destroy", $row)}}" method="post"
                                      style="display:inline;">
                                    @csrf
                                    @method('delete')
                                    <button type="button" id="btnDelete" class="btn btn-danger btn-delete">Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name AR</th>
                        <th>upload Link</th>
                        <th>Category</th>
                        <th>Control</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="pagination">

                    @if (isset($sharePages) && $sharePages->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $sharePages->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $sharePages->currentPage() + $interval;
                            if($to > $sharePages->lastPage()){
                              $to = $sharePages->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($sharePages->currentPage() > 1)
                                <li>
                                    <a href="{{ $sharePages->url(1)."&name=".app('request')->input('name')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $sharePages->url($sharePages->currentPage() - 1)."&name=".app('request')->input('name') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $sharePages->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a href="{{ !$isCurrentPage ? $sharePages->url($i)."&name=".app('request')->input('name') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($sharePages->currentPage() < $sharePages->lastPage())
                                <li>
                                    <a href="{{ $sharePages->url($sharePages->currentPage() + 1)."&name=".app('request')->input('name') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $sharePages->url($sharePages->lastpage())."&name=".app('request')->input('name') }}" aria-label="Last">
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
