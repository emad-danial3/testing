@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('spinners.index')}}">Spinner</a></li>
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
                        <form  action="{{route('spinners.update',$currentFreeProduct)}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($currentFreeProduct)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title_en">Name EN</label>
                                    <input type="text" name="title_en" class="form-control"
                                           placeholder="Enter English Name" value="@if(old('title_en')){{old('title_en')}}@elseif(isset($currentFreeProduct->title_en)){{$currentFreeProduct->title_en}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="title_ar">Name Ar</label>
                                    <input type="text" name="title_ar" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('title_ar')){{old('title_ar')}}@elseif(isset($currentFreeProduct->title_ar)){{$currentFreeProduct->title_ar}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="is_looked">Is Looked</label>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <select name="is_looked" class="form-control col-md-12 col-xs-12">

                                            <option value="1"
                                                    @if(isset($currentFreeProduct->is_looked) && $currentFreeProduct->is_looked == 1)selected @endif>
                                                Looked
                                            </option>

                                            <option value="0"
                                                    @if(isset($currentFreeProduct->is_looked) && $currentFreeProduct->is_looked == 0)selected @endif>
                                                Open
                                            </option>

                                        </select>
                                    </div>

                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-12 col-sm-12 col-xs-12" for="name">
                                        Country
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <select name="gift_id" class="form-control col-md-12 col-xs-12">
                                            @foreach($freeProducts as $product)
                                                <option value="{{$product->id}}"
                                                        @if(isset($currentFreeProduct->gift_id) && $currentFreeProduct->product->id == $product->id)selected @endif>{{$product->name_en}}
                                                </option>
                                            @endforeach
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
