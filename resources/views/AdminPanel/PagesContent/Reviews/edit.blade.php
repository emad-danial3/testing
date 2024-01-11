@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('welcome_program.index')}}">Welcome Program</a></li>
                        <li class="breadcrumb-item active">{{isset($model)?'Edit / '.$model->id :'ADD'}}</li>
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
                        @if(isset($model))
                            <input type="hidden" value="{{$model->product_id}}" id="oldProductId">
                        @endif
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form  action="{{(isset($model))?route('welcome_program.update',$model->id):route('welcome_program.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($model)?method_field('PUT'):''}}

                            <div class="card-body">


                                    <div class="form-group">
                                        <label for="name">Choose Product</label>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                 <select id="short_code" name="product_id" class="form-select form-control" required>
                                                 </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="text" placeholder="Search By Product Name" id="myInput"
                                                   onkeyup="filterFunction()" class="form-control ">
                                            </div>
                                            <div class="form-group col-md-4">
                                                 <input type="text" placeholder="Search By Product Item Code" id="itemCode"
                                                   onkeyup="filterFunction()" class="form-control">
                                            </div>

                                        </div>

                                    </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="name">price</label>
                                        <input type="number" name="price" id="price" class="form-control" step="any"
                                               placeholder="Enter price"
                                               value="@if(old('price')){{old('price')}}@elseif(isset($model->price)){{$model->price}}@endif"
                                               required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="name">Discount Rate</label>
                                        <input type="number" name="discount_rate" id="discount_rate"
                                               class="form-control" step="0.01"
                                               placeholder="Enter Discount"
                                               value="@if(old('discount_rate')){{old('discount_rate')}}@elseif(isset($model->discount_rate)){{$model->discount_rate}}@endif"
                                               required>
                                    </div>
                                     <div class="form-group col-md-4">
                                          <label for="name">Price After Discount</label>
                                    <input type="number" name="price_after_discount" id="price_after_discount"
                                           class="form-control"
                                           placeholder="Price After Discount"
                                           value="@if(old('price_after_discount')){{old('price_after_discount')}}@elseif(isset($model->price_after_discount)){{$model->price_after_discount}}@endif"
                                           >
                                </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                       <label for="name">Choose Month</label>
                                       <select  name="month" class="form-select form-control" required>
                                           <option  value="1"  @if(isset($model->month)&&$model->month =='1') selected @endif>First month</option>
                                           <option  value="2"  @if(isset($model->month)&&$model->month =='2') selected @endif>Second month</option>
                                           <option  value="3"  @if(isset($model->month)&&$model->month =='3') selected @endif>Third month</option>
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
    @push('scripts')
        <script>
            $(function () {
                var oldProductIdAssign = $("#oldProductId").val();

                var item_code = $(this).val();
                $('#short_code option').remove();
                $.ajax({
                    type: "GET",
                    url: "{{route('getAllProductsToProgram')}}",
                    cache: false,
                    data: {name: item_code},
                    dataType: "json",
                    success: function (data) {

                        $.each(data.data.data, function (k, v) {
                            if(oldProductIdAssign == v['id']){
                                $('#short_code').append("<option selected class='form-control col-md-12 col-xs-12'   value=" + v['id'] + " >" + v['full_name'] + " </option>");
                            }else {
                                $('#short_code').append("<option class='form-control col-md-12 col-xs-12'   value=" + v['id'] + " >" + v['full_name'] + " </option>");
                            }

                        });
                    },
                    fail: function (Error) {
                        console.log(Error)
                    }
                });

                $('#short_code').on('change', function (e) {
                    var optionSelected = $("option:selected", this);
                    var valueSelected  = optionSelected.val();
                    $.ajax({
                        type: "GET",
                        url: "{{route('getOneProductToProgram')}}",
                        cache: false,
                        data: {id: valueSelected},
                        dataType: "json",
                        success: function (data) {

                            $('#price').val(data.data['price']);
                            $('#discount_rate').val(data.data['discount_rate']);
                            $('#price_after_discount').val(data.data['price_after_discount']);
                        },
                        fail: function (Error) {
                            console.log(Error)
                        }
                    });
                });

                  $('#price').on('change', function () {
                        var new_price = this.value - ((this.value * $('#discount_rate').val()) / 100)
                        $('#price_after_discount').val(new_price);
                    });

            $('#discount_rate').on('change', function () {
                var new_price = $('#price').val() - (($('#price').val() * this.value) / 100)
                $('#price_after_discount').val(new_price);
            });

            });

            function filterFunction() {

                var input,itemSearchCode;
                input  =  $("#myInput").val();
                itemSearchCode  =  $("#itemCode").val();

                $('#short_code option').remove();
                $.ajax({
                    type: "GET",
                    url: "{{route('getAllProductsToProgram')}}",
                    cache: false,
                    data: {name: input,item_code: itemSearchCode},
                    dataType: "json",
                    success: function (data) {
                        $.each(data.data.data, function (k, v) {
                            $('#short_code').append("<option class='form-control col-md-12 col-xs-12'   value=" + v['id'] + " >" + v['full_name'] + " </option>");
                        });
                    },
                    fail: function (Error) {
                        console.log(Error)
                    }
                });
            }

        </script>
    @endpush
@endsection
