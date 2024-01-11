@extends('AdminPanel.layouts.main')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('digital_brochure.index')}}">Digital Brochure</a>
                        </li>
                        <li class="breadcrumb-item active">{{isset($DigitalBrochure)?'Edit / '.$DigitalBrochure->name_en :'ADD'}}</li>
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
                        <form action="{{(isset($DigitalBrochure))?route('digital_brochure.update',$DigitalBrochure):route('digital_brochure.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($DigitalBrochure)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name_en">Title</label>
                                    <input type="text" name="title" class="form-control"
                                           placeholder="Enter Arabic Title" value="@if(old('title')){{old('title')}}@elseif(isset($DigitalBrochure->title)){{$DigitalBrochure->title}}@endif" required>
                                </div>
                                <div class="form-group">
                                    <label for="name_en">Title En</label>
                                    <input type="text" name="title_en" class="form-control"
                                           placeholder="Enter English Title" value="@if(old('title_en')){{old('title_en')}}@elseif(isset($DigitalBrochure->title_en)){{$DigitalBrochure->title_en}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="name_ar">Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*"
                                           placeholder="Enter Image"  @if(!isset($DigitalBrochure)) required @endif>
                                    @if(isset($DigitalBrochure->image))
                                        <img src="{{url('/'.$DigitalBrochure->image)}}" alt="000000" class="img-thumbnail"
                                             width="100px" height="100px">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name_ar">File PDF</label>
                                    <input type="file" name="file" class="form-control" accept=".pdf"
                                           placeholder="Enter File" @if(!isset($DigitalBrochure)) required @endif>

                                    @if(isset($DigitalBrochure->file))
                                        <embed src="{{url('/'.$DigitalBrochure->file)}}" style="width:200px; height:200px;" frameborder="0">
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
