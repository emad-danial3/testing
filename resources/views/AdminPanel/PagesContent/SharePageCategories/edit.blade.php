@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('sharePageCategories.index')}}">Share Page</a></li>
                        <li class="breadcrumb-item active">{{isset($sharePageCategory)?'Edit / '.$sharePageCategory->name_en :'ADD'}}</li>
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
                        <form  action="{{(isset($sharePageCategory))?route('sharePageCategories.update',$sharePageCategory):route('sharePageCategories.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($sharePageCategory)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name_en">Name EN</label>
                                    <input type="text" name="name_en" class="form-control"
                                           placeholder="Enter English Name" value="@if(old('name_en')){{old('name_en')}}@elseif(isset($sharePageCategory->name_en)){{$sharePageCategory->name_en}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="name_ar">Name Ar</label>
                                    <input type="text" name="name_ar" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('name_ar')){{old('name_ar')}}@elseif(isset($sharePageCategory->name_ar)){{$sharePageCategory->name_ar}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-12 col-sm-12 col-xs-12" for="name">
                                        Paage Name
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <select name="page_category_source_id" class="form-control col-md-12 col-xs-12">

                                            <option value="1"
                                                    @if(isset($sharePageCategory->page_category_source_id) && $sharePageCategory->page_category_source_id == 1)selected @endif>
                                                Catalogue
                                            </option>
                                            <option value="2"
                                                    @if(isset($sharePageCategory->page_category_source_id) && $sharePageCategory->page_category_source_id == 2)selected @endif>
                                                Media
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
