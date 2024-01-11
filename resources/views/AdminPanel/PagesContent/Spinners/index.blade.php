@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('spinners.index')}}">Spinners</a></li>
                    </ol>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    @include('AdminPanel.layouts.messages')
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            @if(count($spinners) > 0)
                <table id="areasTable"  class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name Ar</th>
                        <th>Name</th>
                        <th>IS Looked</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($spinners as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->title_en}}</td>
                            <td width="150">{{$row->title_ar}}</td>
                            @if($row->spinner_category_id == 1 || $row->spinner_category_id == 2)
                            <td >{{$row->product->name_ar}}</td>
                            @else
                                <td></td>
                            @endif
                            <td>{{$row->is_looked}}</td>
                            <td>
                                <a class="btn btn-dark" href="{{route('spinners.edit',$row)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name AR</th>
                        <th>Name</th>
                        <th>IS Looked</th>
                        <th>Control</th>
                    </tr>
                    </tfoot>
                </table>

            @else
                <h1 class="text-center">NO DATA</h1>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
@endsection
