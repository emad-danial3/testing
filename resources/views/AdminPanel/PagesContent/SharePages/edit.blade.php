@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('sharePages.index')}}">Share Page</a></li>
                        <li class="breadcrumb-item active">{{isset($sharePage)?'Edit / '.$sharePage->title_en :'ADD'}}</li>
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
                        <form  action="{{(isset($sharePage))?route('sharePages.update',$sharePage):route('sharePages.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($sharePage)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title_en">Name EN</label>
                                    <input type="text" name="title_en" class="form-control"
                                           placeholder="Enter English Name" value="@if(old('title_en')){{old('title_en')}}@elseif(isset($sharePage->title_en)){{$sharePage->title_en}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="title_ar">Name Ar</label>
                                    <input type="text" name="title_ar" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('title_ar')){{old('title_ar')}}@elseif(isset($sharePage->title_ar)){{$sharePage->title_ar}}@endif" required>
                                </div>


                                <div class="form-group">
                                    <label for="upload">upload Link</label>
                                    <input type="text" name="upload" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('upload')){{old('upload')}}@elseif(isset($sharePage->upload)){{$sharePage->upload}}@endif" >
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-12 col-sm-12 col-xs-12" for="name">
                                        Category
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <select name="share_page_category_id" class="form-control col-md-12 col-xs-12">
                                            @foreach($sharePagesCategories as $sharePagesCategory)
                                                    <option value="{{$sharePagesCategory->id}}"
                                                            @if(isset($sharePage->category->id) && $sharePage->category->id == $sharePagesCategory->id)selected @endif>{{$sharePagesCategory->name_ar}}
                                                        <br> || {{$sharePagesCategory->name_en}}
                                                    </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url">Image</label>
                                    <input type="file" class="form-control" name="url" >
                                    <br>
                                    @if(isset($sharePage->url))
                                        <img src="{{url($sharePage->url)}}" width="250" height="250">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>

                                    <select name="status" class="form-control">
                                        <option {{old('status')=="1"? 'selected':''}} @if(isset($sharePage->status) && $sharePage->status == '1'){{'selected'}}@endif value="1">Active</option>
                                        <option {{old('status')=="0"? 'selected':''}} @if(isset($sharePage->status) && $sharePage->status == '0'){{'selected'}}@endif  value="0">No Active</option>

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
