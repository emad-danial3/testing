@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('staticPages.index')}}">Static Page</a></li>
                        <li class="breadcrumb-item active">{{isset($staticPage)?'Edit / '.$staticPage->name_en :'ADD'}}</li>
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
                        <form  action="{{(isset($staticPage))?route('staticPages.update',$staticPage):route('staticPages.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($staticPage)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name_en">Name EN</label>
                                    <input type="text" name="name_en" class="form-control"
                                           placeholder="Enter English Name" value="@if(old('name_en')){{old('name_en')}}@elseif(isset($staticPage->name_en)){{$staticPage->name_en}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="name_ar">Name Ar</label>
                                    <input type="text" name="name_ar" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('name_ar')){{old('name_ar')}}@elseif(isset($staticPage->name_ar)){{$staticPage->name_ar}}@endif" required>
                                </div>


                            </div>

                            <div class="card-body">
                                <label for="content_en">Content EN</label>
                              <textarea id="summernote2" name="content_en">
                                 @if(isset($staticPage))
                                {!!  $staticPage->content_en  !!}
                                  @endif
                              </textarea>
                            </div>

                            <div class="card-body">
                                <label for="content_ar">Content AR</label>
                              <textarea id="summernote" name="content_ar">
                                 @if(isset($staticPage))
                                      {!!  $staticPage->content_ar  !!}
                                  @endif
                              </textarea>
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
