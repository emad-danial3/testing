@extends('AdminPanel.layouts.main')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>

        function changeRowStatus(id,type) {
            swal({
                title: type,
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
                        url: "{{url('admin/reviews')}}/" + id,
                        data: {_token: CSRF_TOKEN,"type_operation":type},
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
                        <li class="breadcrumb-item active"><a href="{{route('reviews.index')}}">Reviews
                                </a></li>
                    </ol>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    @include('AdminPanel.layouts.messages')
    <div class="card">
{{--        <div class="card-body">--}}
{{--            <h3 class="card-title">--}}
{{--                <a class="btn btn-warning" href="{{route('reviews.create')}}">Create review</a>--}}
{{--            </h3>--}}
{{--        </div>--}}

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($reviews) > 0)
                <table id="areasTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>User Name</th>
                        <th>Rate</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reviews as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->product?$row->product->full_name:""}}</td>
                            <td width="150">{{$row->user?$row->user->full_name:""}}</td>
                            <td width="150">{{$row->rate}} &nbsp; <i class="fa-solid fa-star"></i></td>
                            <td width="150">{{$row->comment}}</td>
                            <td width="150">@if($row->status == '2') <span class="text-danger">Rejected</span>  @elseif($row->status == '1') <span class="text-success">Active</span> @else <span class="text-info">Pending</span> @endif</td>
                            <td>
{{--                                <a class="btn btn-dark" href="{{route('reviews.edit',$row->id)}}">Edit</a>--}}
                                <button class="btn btn-success" onclick="changeRowStatus({{$row->id}},'active')">Activate
                                </button>
                                <button class="btn btn-danger" onclick="changeRowStatus({{$row->id}},'reject')">Reject
                                </button>
{{--                                <button class="btn btn-danger" onclick="changeRowStatus({{$row->id}},'delete')">Delete--}}
{{--                                </button>--}}
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
