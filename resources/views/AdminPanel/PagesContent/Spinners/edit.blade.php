@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('spinners.index')}}">Spinner</a></li>
                        <li class="breadcrumb-item active">{{isset($spinner)?'Edit / '.$spinner->title_en :'ADD'}}</li>
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
                        <form  action="{{(isset($spinner))?route('spinners.update',$spinner):route('spinners.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($spinner)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title_en">Name EN</label>
                                    <input type="text" name="title_en" class="form-control"
                                           placeholder="Enter English Name" value="@if(old('title_en')){{old('title_en')}}@elseif(isset($spinner->title_en)){{$spinner->title_en}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="title_ar">Name Ar</label>
                                    <input type="text" name="title_ar" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('title_ar')){{old('title_ar')}}@elseif(isset($spinner->title_ar)){{$spinner->title_ar}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="is_looked">Is Looked</label>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <select name="is_looked" class="form-control col-md-12 col-xs-12">

                                                <option value="1"
                                                        @if(isset($spinner->is_looked) && $spinner->is_looked == 1)selected @endif>
                                                        Looked
                                                </option>

                                            <option value="0"
                                                    @if(isset($spinner->is_looked) && $spinner->is_looked == 0)selected @endif>
                                                Open
                                            </option>

                                        </select>
                                    </div>

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
