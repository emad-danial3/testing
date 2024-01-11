@extends('AdminPanel.layouts.main')
@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('users.usersNotInOracle')}}">Users</a></li>
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
{{--            <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">--}}

        @if(count($users) > 0)
                <table id="usersTable"   class="display"  class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>FULL_NAME</th>
                        <th>MOBILE</th>
                        <th>ACCOUNT_ID</th>
                        <th>NATIONALITY_ID</th>
                        <th>ADDRESS</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $row)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td width="150">{{$row->FULL_NAME}}</td>
                            <td>{{$row->MOBILE}}</td>
                            <td>{{$row->ACCOUNT_ID}}</td>
                            <td>{{$row->NATIONALITY_ID}}</td>
                            <td>{{$row->ADDRESS}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <script>
                    $(document).ready( function () {
                        $('#usersTable').DataTable();
                    } );
                </script>


            @else
                <h1 class="text-center">NO DATA</h1>
            @endif
        </div>
        <!-- /.card-body -->
    </div>

@endsection
 @push('scripts')
        <script type="text/javascript">

        </script>
    @endpush
