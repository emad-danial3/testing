@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('accountTypes.index')}}">Accepted Version & Setting </a></li>
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
            <h3 class="card-title float-right">
                <a class="btn btn-warning" href="{{route('AcceptedVersion.create')}}">Create New Type</a>
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if(count($types) > 0)
                <table id="areasTable"  class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>platform</th>
                        <th>version</th>

                        <th>  Upload Apple Version Status (Apple)</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($types as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->platform}}</td>
                            <td width="150">{{$row->version}}</td>
                            <td>
                                {{$row->upload_apple_version}}
                            </td>
                            <td>
                                <a class="btn btn-dark" href="{{route('AcceptedVersion.edit',$row)}}">Edit</a>
                                <a class="btn btn-danger" href="{{route('AcceptedVersion.show',$row)}}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <div class="pagination">
{{--                @links()--}}
                </div>

            @else
                <h1 class="text-center">NO DATA</h1>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
    <div class="card">

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($setting) > 0)
                <table id="areasTable"  class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>show wallet in App</th>
                        <th>show welcome programme in App</th>
                        <th>show fawry payemnt in App</th>
                    </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>{{$setting[0]->show_wallet}}</td>
                            <td>{{$setting[0]->show_welcome_programme}}</td>
                            <td>{{$setting[0]->show_fawry_payemnt}}</td>
                            <a class="btn btn-dark" href="{{route('AcceptedVersion.edit',10000)}}">Edit</a>
                        </tr>

                    </tbody>

                </table>
            @else
                <h1 class="text-center">NO DATA</h1>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
@endsection
