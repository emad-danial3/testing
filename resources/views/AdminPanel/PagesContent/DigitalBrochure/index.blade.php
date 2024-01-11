@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('digital_brochure.index')}}">Digital
                                Brochure</a></li>
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

            <form method="get" action="{{route('digital_brochure.index')}}">
                <input type="text" name="name">
                <button type="submit" class="btn btn-danger btn-2xs ">Search</button>
            </form>
            <h3 class="card-title float-right">
                <a class="btn btn-warning" href="{{route('digital_brochure.create')}}">Create New Digital Brochure</a>
            </h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($digital_brochures) > 0)
                <table id="areasTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title EN</th>
                        <th>Title Ar</th>
                        <th>Image</th>
                        <th>Control</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($digital_brochures as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->title_en}}</td>
                            <td width="150">{{$row->title}}</td>
                            <td width="150">
                                @if(isset($row->image))
                                    <a href="{{url('/'.$row->image)}}" target="_blank">
                                        <img src="{{url('/'.$row->image)}}" alt="000000" class="img-thumbnail"
                                             width="50px" height="50px">
                                    </a>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-dark" href="{{route('digital_brochure.edit',$row)}}">Edit</a>
                            </td>
                            <td>
                                <form action="{{ route('digital_brochure.destroy', $row->id) }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                   <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination">

                    @if (isset($digital_brochures) && $digital_brochures->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $digital_brochures->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $digital_brochures->currentPage() + $interval;
                            if($to > $digital_brochures->lastPage()){
                              $to = $digital_brochures->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($digital_brochures->currentPage() > 1)
                                <li>
                                    <a href="{{ $digital_brochures->url(1)."&name=".app('request')->input('name')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $digital_brochures->url($digital_brochures->currentPage() - 1)."&name=".app('request')->input('name') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $digital_brochures->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a href="{{ !$isCurrentPage ? $digital_brochures->url($i)."&name=".app('request')->input('name') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($digital_brochures->currentPage() < $digital_brochures->lastPage())
                                <li>
                                    <a href="{{ $digital_brochures->url($digital_brochures->currentPage() + 1)."&name=".app('request')->input('name') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $digital_brochures->url($digital_brochures->lastpage())."&name=".app('request')->input('name') }}" aria-label="Last">
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
