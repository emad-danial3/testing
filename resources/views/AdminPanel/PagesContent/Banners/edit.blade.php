@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('banners.index')}}">Banner</a></li>
                        <li class="breadcrumb-item active">{{isset($banner)?'Edit / '.$banner->title_en :'ADD'}}</li>
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
                        <form  action="{{(isset($banner))?route('banners.update',$banner):route('banners.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($banner)?method_field('PUT'):''}}

                            <div class="card-body row">
                                <div class="form-group col-md-6">
                                    <label for="title_en">Title EN</label>
                                    <input type="text" name="title_en" class="form-control"
                                           placeholder="Enter English Name" value="@if(old('title_en')){{old('title_en')}}@elseif(isset($banner->title_en)){{$banner->title_en}}@endif" >
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="title_ar">Title Ar</label>
                                    <input type="text" name="title_ar" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('title_ar')){{old('title_ar')}}@elseif(isset($banner->title_ar)){{$banner->title_ar}}@endif" >
                                </div>


                                <div class="form-group col-md-6">
                                    <label for="description_en">Description en</label>
                                    <input type="text" name="description_en" class="form-control"
                                           placeholder="Enter Arabic description" value="@if(old('description_en')){{old('description_en')}}@elseif(isset($banner->description_en)){{$banner->description_en}}@endif" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description_ar">Description ar</label>
                                    <input type="text" name="description_ar" class="form-control"
                                           placeholder="Enter English description" value="@if(old('description_ar')){{old('description_ar')}}@elseif(isset($banner->description_ar)){{$banner->description_ar}}@endif" >
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="button_url">Button Url</label>
                                    <input type="text" name="button_url" class="form-control"
                                           placeholder="Enter Button Url" value="@if(old('button_url')){{old('button_url')}}@elseif(isset($banner->button_url)){{$banner->button_url}}@endif" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="button_en">Title On button en</label>
                                    <input type="text" name="button_en" class="form-control"
                                           placeholder="Enter On button En" value="@if(old('button_en')){{old('button_en')}}@elseif(isset($banner->button_en)){{$banner->button_en}}@endif" >
                                </div>
                               <div class="form-group col-md-6">
                                    <label for="button_ar">Title On button ar</label>
                                    <input type="text" name="button_ar" class="form-control"
                                           placeholder="Enter On button ar" value="@if(old('button_ar')){{old('button_ar')}}@elseif(isset($banner->button_ar)){{$banner->button_ar}}@endif" >
                                </div>


                                <div class="form-group col-md-6">
                                    <label for="priority">Priority</label>
                                    <input type="number" name="priority" class="form-control"
                                           placeholder="Enter Priority"  value="@if(old('priority')){{old('priority')}}@elseif(isset($banner->priority)){{$banner->priority}}@endif" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="url">Image</label>
                                    <input type="file" class="form-control" name="url" @if(!isset($banner))required @endif>
                                    <br>
                                    @if(isset($banner->url))
                                        <img src="{{url($banner->url)}}" width="250" height="250">
                                    @endif
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
