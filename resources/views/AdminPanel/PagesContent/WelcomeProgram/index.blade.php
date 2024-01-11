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
                        url: "{{url('admin/welcome_program')}}/" + id,
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
                        <li class="breadcrumb-item active"><a href="{{route('welcome_program.index')}}">Welcome
                                Program</a></li>
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
                <a class="btn btn-warning" href="{{route('welcome_program.create')}}">Create Program</a>
            </h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($programs) > 0)
                <table id="areasTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Month</th>
                        <th>image</th>
                        <th>Products</th>
                        <th>Total New Price</th>
                        <th>Total Old Price</th>
                        <th>status</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($programs as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{'Month ' .$row->month }}</td>
                            <td width="150">
                                @if($row->image)
                                    <img src="./../{{$row->image}}" width="150" height="100" alt="">
                                @endif
                            </td>
                            <td style="width: 500px">
                                @if(isset($row->product)&& count($row->product)>0)
                                    <ul>
                                        @foreach($row->product as $pro)
                                            @foreach($pro->product as $item)
                                                <li>
                                                  {{$item->name_en}}
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                @endif


                            </td>
                             <td width="150">{{$row->total_price }}</td>
                             <td width="150">{{$row->total_old_price }}</td>
                             <td width="150">{{$row->status }}</td>
                            <td>
                                <a class="btn btn-dark" href="{{route('welcome_program.edit',$row->id)}}">Edit</a>
                                <button class="btn btn-danger" onclick="deleteConfirmation({{$row->id}})">Delete
                                </button>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h1 class="text-center">NO DATA</h1>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
@endsection
