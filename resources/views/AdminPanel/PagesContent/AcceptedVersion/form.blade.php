@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('accountTypes.index')}}">Accepted Version</a></li>
                        <li class="breadcrumb-item active">{{isset($type)?'Edit / '.$type->name_en :'ADD'}}</li>
                    </ol>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">

                        <!-- /.card-header -->
                        <!-- form start -->
                        <form  action="{{(isset($type))?route('AcceptedVersion.update',$type):route('AcceptedVersion.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($type)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name_en">platform</label>
                                    <select class="form-control" type="" name="platform">
                                        <option @if(old('platform') && old('platform') == "Ios"){{"selected"}}@elseif(isset($type->platform)&& $type->platform == "Ios"){{"selected"}}@endif value="Ios">Ios</option>
                                        <option @if(old('platform') && old('platform') == "Android"){{"selected"}}@elseif(isset($type->platform)&& $type->platform == "Android"){{"selected"}}@endif value="Android">Android</option>
                                    </select>

{{--                                    <input type="text" name="platform" class="form-control"--}}
{{--                                           placeholder="Enter Platform" value="@if(old('platform')){{old('platform')}}@elseif(isset($type->platform)){{$type->platform}}@endif" required>--}}
                                </div>

                                <div class="form-group">
                                    <label for="region_ar">version</label>
                                    <input type="text" name="version" class="form-control"
                                           placeholder="Enter version" value="@if(old('version')){{old('version')}}@elseif(isset($type->version)){{$type->version}}@endif" required>
                                </div>
                                <div class="form-group">
                                    <label>Upload Apple Version Status (Apple)</label>
                                    <select name="upload_apple_version" class="form-control">
                                        <option value="0" @if(old('upload_apple_version') == '0')selected @endif>
                                           No
                                        </option>
                                        <option value="1" @if(old('upload_apple_version') == '1')selected @endif>
                                          Yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Save Info</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">

                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
