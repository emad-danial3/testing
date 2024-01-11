@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('welcome_program.index')}}">Welcome Program</a>
                        </li>
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
                        <form action="{{(isset($model))?route('welcome_program.update',$model->id):route('welcome_program.store')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($model)?method_field('PUT'):''}}

                            <div class="card-body">


                                <div class="row">
                                    <div class="col-md-12">
                                        <h1 style="color: red">If any data changes, please refer to Professor Bishoy</h1>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name_ar">Program name AR</label>
                                        <input type="text" name="name_ar" class="form-control"
                                               placeholder="Program name AR"
                                               value="@if(old('name_ar')){{old('name_ar')}}@elseif(isset($model->name_ar)){{$model->name_ar}}@endif"
                                               required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="name_en">Program name En</label>
                                        <input type="text" name="name_en" class="form-control"
                                               placeholder="Program name En"
                                               value="@if(old('name_en')){{old('name_en')}}@elseif(isset($model->name_en)){{$model->name_en}}@endif"
                                               required>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label for="month">Choose Month</label>
                                        <select name="month" id="m_chosen" class="form-select form-control" required >
                                            <option value="1" @if(isset($model->month)&&$model->month =='1') selected @endif>
                                                First month
                                            </option>
                                            <option value="2" @if(isset($model->month)&&$model->month =='2') selected @endif>
                                                Second month
                                            </option>
                                            <option value="3" @if(isset($model->month)&&$model->month =='3') selected @endif>
                                                Third month
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="status">Choose Status</label>
                                        <select name="status" class="form-select form-control" required>
                                            <option value="1" @if(isset($model->status)&&$model->status =='1') selected @endif>
                                               Active
                                            </option>
                                            <option value="2" @if(isset($model->status)&&$model->status =='0') selected @endif>
                                               Not Active
                                            </option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">

                                        <label for="image">Image</label>
                                        <input type="file" class="form-control" name="image" @if(!isset($model))required @endif>
                                        <br>
                                        @if(isset($model->image))
                                            <img src="{{url($model->image)}}" width="100" height="100">
                                        @endif
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="total_old_price">Total Old Price</label>
                                        <input type="number" min="0"  value="@if(old('total_old_price')){{old('total_old_price')}}@elseif(isset($model->total_old_price)){{$model->total_old_price}}@endif" class="form-control" name="total_old_price" required placeholder="Total Old Price">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="total_price">Total New Price</label>
                                        <input type="number" min="0"  value="49" disabled class="form-control" >
                                        <input type="hidden"  value="49" class="form-control" name="total_price" required >
                                    </div>
                                </div>


                                <div class="row" style="border: 1px solid #e7e7e7;border-radius: 5px;">
                                    <h3 class="col-md-12">Program Products</h3>
                                    <div class="col-md-12">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Discount</th>
                                                <th scope="col">Price After Discount</th>

                                            </tr>
                                            </thead>
                                            <tbody id="productForms">

                                            @if(isset($model->product)&& count($model->product)>0)

                                                @foreach($model->product as $pro)
                                                    <tr>
                                                        @foreach($pro->product as $item)
                                                            <td>{{$item->name_en}}</td>
                                                            <td>{{$pro->quantity}}</td>
                                                            <td>{{$pro->price}}</td>
                                                            <td>{{$pro->discount_rate}}</td>
                                                            <td>{{$pro->price_after_discount}}
                                                            </td> <td><a href="javascript:void(0)" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCancelOrderRequest" onclick="goToSpecificCancelOrder('{{$pro->id}}')">
                                       Delete
                                    </a>
                                                            </td>

                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="product_id">Choose Product</label>
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

                                                    <div class="form-group col-md-2">
                                                        <label>Catalog Price </label>
                                                        <input type="number" name="proPrice" id="proPrice"  class="form-control"
                                                               placeholder="Enter price"

                                                        >
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label>Enter New Discount</label>
                                                        <input type="number" name="proDiscount_rate" id="proDiscount_rate" disabled
                                                               class="form-control" step="0.01"
                                                               placeholder="Enter Discount"
                                                        >
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <label>Price After Discount</label>
                                                        <input type="number" name="proPrice_after_discount" id="proPrice_after_discount" disabled
                                                               class="form-control"
                                                               placeholder="Price After Discount"

                                                        >
                                                    </div>


                                                    <div class="form-group col-md-2">
                                                        <label>Choose Quantity</label>
                                                        <input type="number" placeholder="Quantity" id="proQuantity" min="1" value="1" class="form-control " >
                                                    </div>


                                                    <div class="form-group col-md-3">
                                                        <label>Add Product</label>
                                                        <a href="javascript:void(0)" onclick="addProducts()" class="btn btn-primary form-control">Add</a>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
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


    <!-- Modal Create Cancel Order Request -->
    <div class="modal fade" id="exampleModalCancelOrderRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalCancelOrderRequestTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">item delete </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('welcome_program.deleteProduct')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <h5 class="modal-title" id="exampleModalLongTitle">Do You want to delete Item </h5>
                            <br>
                            <br>
                            <input type="hidden" name="delete_product_id" id="delete_product_id" class="form-control">
                            <div class="form-group col-12">
                                <button type="submit" class="btn btn-success form-control" onclick="$('#exampleModalCancelOrderRequest').modal('hide');">
                                    Yes delete
                                </button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

        </div><!-- /.container-fluid -->
    </section>
    @push('scripts')
        <script>
            var ProgramProducts     = [];
            var pprogramproductname = ''
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
                            if (oldProductIdAssign == v['id']) {
                                $('#short_code').append("<option selected class='form-control col-md-12 col-xs-12'   value=" + v['id'] + " >" + v['full_name'] + " </option>");
                            }
                            else {
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
                            pprogramproductname = data.data['name_ar'];
                            $('#price').val(data.data['price']);
                            $('#proPrice').val(data.data['price']);
                            $('#discount_rate').val(data.data['discount_rate']);
                            //hhhhhh
                            var mo_chosen = $('#m_chosen').find(":selected").val();
                            var new_price =0;
                            if(mo_chosen=='1'){
                                $('#proDiscount_rate').val(70);
                                new_price = data.data['price'] - ((data.data['price'] * 70) / 100);
                            }else if(mo_chosen=='2'){
                                $('#proDiscount_rate').val(75);
                                new_price = data.data['price'] - ((data.data['price'] * 75) / 100);
                            }else {
                                $('#proDiscount_rate').val(80);
                                new_price = data.data['price'] - ((data.data['price'] * 80) / 100);
                            }

                            $('#proPrice_after_discount').val(new_price);
                            $('#price_after_discount').val(new_price);

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
                $('#proPrice').on('change', function () {
                   var ddisc= $('#proDiscount_rate').val();
                    var new_price2 = $('#proPrice').val() - (($('#proPrice').val() * ddisc) / 100)
                    $('#proPrice_after_discount').val(new_price2);
                });

            });

            function filterFunction() {

                var input,
                    itemSearchCode;
                input          = $("#myInput").val();
                itemSearchCode = $("#itemCode").val();

                $('#short_code option').remove();
                $.ajax({
                    type: "GET",
                    url: "{{route('getAllProductsToProgram')}}",
                    cache: false,
                    data: {name: input, item_code: itemSearchCode},
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

            function addProducts() {
                var optionSelectedproduct_id   = $('select[name="product_id"]').val();
                var pronewPrice                = $('#proPrice').val();
                var pronewDiscount_rate        = $('#proDiscount_rate').val();
                var pronewPrice_after_discount = $('#proPrice_after_discount').val();
                var pronewQuantity             = $('#proQuantity').val();
                var pronewname                 = pprogramproductname;

                if (optionSelectedproduct_id && optionSelectedproduct_id > 0 && pronewPrice && pronewPrice > 0) {
                    $("#productForms").append(
                        '<tr><td> ' +
                        '  <input type="hidden" name="product_ids[]" value="' + optionSelectedproduct_id + '"> <input type="hidden" name="product_quantitys[]" value="' + pronewQuantity + '"> <input type="hidden" name="product_discounts[]" value="' + pronewDiscount_rate + '"><input type="hidden" name="product_prices[]" value="' + pronewPrice + '"><input type="hidden" name="product_prices_after_discount[]" value="' + pronewPrice_after_discount + '">'
                        + pronewname + ' </td><td>' + pronewQuantity + '</td><td>' + pronewPrice + '</td><td>' + pronewDiscount_rate + '</td><td>' + pronewPrice_after_discount + '</td></tr>'
                    );
                }

                $('#proPrice').val('');
                $('#proDiscount_rate').val('');
                $('#proPrice_after_discount').val('');
                $('#proQuantity').val(1);
                pprogramproductname = '';

            }

              function goToSpecificCancelOrder(order_id) {
                console.log(order_id)
                $("#delete_product_id").val(order_id);
            }


        </script>
    @endpush
@endsection
