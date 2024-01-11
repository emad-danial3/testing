@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('accountTypes.index')}}">accountTypes</a></li>
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
                        <form  action="{{(isset($type))?route('accountTypes.update',$type):route('accountTypes.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($type)?method_field('PUT'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name_en">Name EN</label>
                                    <input type="text" name="name_en" class="form-control"
                                           placeholder="Enter English Name" value="@if(old('name_en')){{old('name_en')}}@elseif(isset($type->name_en)){{$type->name_en}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="region_ar">Name Ar</label>
                                    <input type="text" name="name_ar" class="form-control"
                                           placeholder="Enter Arabic Name" value="@if(old('name_ar')){{old('name_ar')}}@elseif(isset($type->name_ar)){{$type->name_ar}}@endif" required>
                                </div>

                                <div class="form-group">
                                    <label for="governorate">Amount</label>
                                    <input type="number" name="amount" class="form-control"
                                           placeholder="Enter  Amount" value="@if(old('amount')){{old('amount')}}@elseif(isset($type->amount)){{$type->amount}}@endif" required>
                                </div>
                                <div class="form-group">
                                    <label for="governorate">Minimum Required</label>
                                    <input type="number" name="min_required" class="form-control"
                                           placeholder="Enter  min required" value="@if(old('min_required')){{old('min_required')}}@elseif(isset($type->min_required)){{$type->min_required}}@endif" required>
                                </div>
                                <div class="form-group">
                                    <label for="governorate">Delivery Fees</label>
                                    <input type="number" name="delivery_fees" class="form-control"
                                           placeholder="Enter  delivery fees" value="@if(old('delivery_fees')){{old('delivery_fees')}}@elseif(isset($type->delivery_fees)){{$type->delivery_fees}}@endif" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12 col-sm-12 col-xs-12" for="name">
                                        Available
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <select name="is_available" class="form-control col-md-12 col-xs-12">
                                                <option value="1"
                                                        @if(isset($type->is_available)&& $type->is_available==1)selected @endif>Available For Common User
                                                </option>
                                            <option value="0"
                                                    @if(isset($type->is_available)&& $type->is_available==0)selected @endif> Not Available For Common User
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="region_ar">Level 1 commission</label>
                                    <input type="number" name="level_one" class="form-control"
                                           placeholder="Enter Level 1 commission" value="@if(old('level_one')){{old('level_one')}}@elseif(isset($type->AccountCommissionLevels[0]->commission)){{$type->AccountCommissionLevels[0]->commission}}@endif" required>
                                </div>
                                <div class="form-group">
                                    <label for="region_ar">Level 2 commission</label>
                                    <input type="number" name="level_two" class="form-control"
                                           placeholder="Enter Level 2 commission" value="@if(old('level_two')){{old('level_two')}}@elseif(isset($type->AccountCommissionLevels[1]->commission)){{$type->AccountCommissionLevels[1]->commission}}@endif" required>
                                </div>
                                <div class="form-group">
                                    <label for="region_ar">Level 3 commission</label>
                                    <input type="number" name="level_three" class="form-control"
                                           placeholder="Enter Level 3 commission" value="@if(old('level_three')){{old('level_three')}}@elseif(isset($type->AccountCommissionLevels[2]->commission)){{$type->AccountCommissionLevels[2]->commission}}@endif" required>
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
