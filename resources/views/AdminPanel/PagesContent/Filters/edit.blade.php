@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('filters.index')}}">Filters</a></li>
                        <li class="breadcrumb-item active">{{isset($filter)?'Edit / '.$filter->name_en :'ADD'}}</li>
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
                        <form  action="{{(isset($filter))?route('filters.update',$filter):route('filters.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($filter)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name_en">Name EN</label>
                                    <input type="text" name="name_en" class="form-control"
                                           placeholder="Enter English Name" value="@if(old('name_en')){{old('name_en')}}@elseif(isset($filter->name_en)){{$filter->name_en}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="name_ar">Name Ar</label>
                                    <input type="text" name="name_ar" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('name_ar')){{old('name_ar')}}@elseif(isset($filter->name_ar)){{$filter->name_ar}}@endif" required>
                                </div>

                                 <div class="form-group">
                                     <label for="image">Filter Image <span style="color: red;font-size: 22px"> * Please select image ( 300 * 175 )</span></label>
                                    <input type="file" class="form-control" name="image"
                                           @if(!isset($filter))required @endif>
                                    <br>
                                    @if(isset($filter->image))
                                        <img src="{{url($filter->image)}}" width="150" height="150">
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
    @push('scripts')
        <script>
            $(function () {
                // Summernote
                $('#summernote').summernote()
                $('#summernote2').summernote()
            })
        </script>
    @endpush
@endsection
