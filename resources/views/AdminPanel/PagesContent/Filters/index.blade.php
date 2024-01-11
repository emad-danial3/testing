@extends('AdminPanel.layouts.main')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>

        function deleteConfirmation(id) {

            swal({
                title: "Delete",
                text: "Please ensure and then confirm!",
                icon: "warning",
                buttons: {
                    cancel: true,
                    confirm: "Confirm",
                },

                reverseButtons: !0
            }).then(function (e) {

                if (e === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'delete',
                        url: "{{url('admin/filters')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal({
                                    title: "Done",
                                    text: "delete done",
                                    icon: "success",
                                });
                                window.location.reload();
                            }
                            else {
                                swal("delete error", results.message, "error");
                            }
                        }
                    });

                }
                else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }
    </script>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('filters.index')}}">Filters</a></li>
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
                <a class="btn btn-warning" href="{{route('filters.create')}}">Create Filter</a>
            </h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($filters) > 0)
                <table id="areasTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name Ar</th>

                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($filters as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->name_en}}</td>
                            <td width="150">{{$row->name_ar}}</td>
                            <td>
                                <a class="btn btn-dark" href="{{route('filters.edit',$row)}}">Edit</a>
                                <button class="btn btn-danger" onclick="deleteConfirmation({{$row->id}})">Delete
                                </button>
                                {{--                                 <form method="delete" enctype="multipart/form-data">--}}
                                {{--                                    <input type="hidden" name="id" value="{{$row->id}}">--}}
                                {{--                                    <input type="submit" value="Delete">--}}
                                {{--                                </form>--}}

                                {{--                                <a class="btn btn-danger" href="{{route('filters.destroy',$row)}}">Delete</a>--}}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name AR</th>
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
