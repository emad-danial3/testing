@extends('AdminPanel.layouts.main')
@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <div class="loader">
        <img class="card-img-top cartimage"
             src="{{asset('test/img/Loading_icon.gif')}}" alt="Card image cap">
    </div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('orderHeaders.index')}}">Orders</a></li>

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
                        <form action="{{(isset($code))?route('orderHeaders.update',$code):route('orderHeaders.store')}}"
                              method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            <input type="hidden" id="min_required" value="{{$min_required}}"/>
                            <input type="hidden" id="admin_id" value="{{Auth::guard('admin')->user()->id}}"/>

                            <div class="alert alert-primary" role="alert" id="infoMessage" style="display: none">

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="md-form">


                                            <div class="row">


                                                <div class="col-md-5">
                                                    <h5>Cart (Total = <span id="totalHeaderAdminCart">0</span> LE)</h5>
                                                </div>
                                                <div class="col-md-5">
                                                    <div>
                                                        <button type="button" class="btn" data-toggle="modal" data-target="#exampleModalCurrentDiscount">
                                                            Current Discount ( <span id='currentDiscount'>0</span> % )
                                                            <i class="fa-solid fa-pen-to-square text-primary"></i>
                                                        </button>

                                                    </div>
                                                    <h5>After Discount ( <span id="totalHeaderAfterDiscount">0</span>
                                                        LE)</h5>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-danger" type="button" onclick="removeAllItems()">
                                                        <i class="fa-solid fa-trash-can"></i> All
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">P_ID</th>
                                                            <th scope="col">Image</th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="cartProductContainer">
                                                        <tr id="nodata">
                                                            <th scope="row" colspan="6" class="text-center">
                                                                No Data
                                                            </th>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3>Barcode</h3>
                                                <div class="input-group">
                                                    <div class="form-outline md-form w-100">
                                                        <input type="search" id="barcode" class="form-control"
                                                               placeholder="Search Product barcode" autofocus/>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h3>Product Name</h3>
                                                <div class="input-group">
                                                    <div class="form-outline md-form w-100">
                                                        <input type="search" id="proname" class="form-control"
                                                               placeholder="Search Product Name En"/>
                                                        <br>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <h3>Item Code</h3>
                                                <div class="input-group">
                                                    <div class="form-outline md-form w-100">
                                                        <input type="search" id="procode" class="form-control"
                                                               placeholder="Code"/>
                                                        <br>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="md-form">
                                                    <h3>Category</h3>
                                                    <select id="category_id" class="form-control">
                                                        <option value="">Chose Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{$category->id}}">{{$category->name_en}}</option>
                                                        @endforeach
                                                    </select>
                                                    <br>
                                                    <br>

                                                </div>
                                            </div>
                                        </div>

                                        @if(count($products) > 0)
                                            <div class="row" style="max-height: 500px;overflow-y: scroll"
                                                 id="productsSearchContainer">
                                                @foreach($products as $product)
                                                    <div class="col-md-4">
                                                        <div class="card">
                                                            <img class="card-img-top cartimage"
                                                                 src="{{url('/'.$product->image)}}" alt="Card image cap">
                                                            <div class="card-body">
                                                                <h5 class="product-title">{{$product->name_en}}</h5>
                                                                <h6> Price : {{$product->price}}</h6>
                                                                <h6> Price After Discount
                                                                    : {{$product->price_after_discount}} </h6>
                                                                <h6>
                                                                    Quantity &nbsp; <input type="number" min="1"
                                                                                           value="1"
                                                                                           class="border border-primary rounded text-center w-50"
                                                                                           id="product{{$product->id}}">
                                                                </h6>

                                                                <br>
                                                                <button type="button"
                                                                        class="btn btn-primary addToCartButton"
                                                                        id="{{$product->id}}"
                                                                        product_name="{{$product->name_en}}"
                                                                        product_flag="{{$product->flag}}"
                                                                        product_image="{{$product->image}}"
                                                                        product_price="{{$product->price}}"
                                                                         price_after_discount="{{$product->price_after_discount}}"
                                                                        excluder_flag="{{$product->excluder_flag}}">
                                                                    Add To Cart
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>

                                        @endif
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                            <!-- /.card-body -->

                            &nbsp; &nbsp;
                            <button id="save_button" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter" disabled>
                                Check Order
                            </button>

                        </form>
                        <br>
                        <br>

                        <div class="row" id="invoice" style="    border: 1px solid #e7e7e7; margin-left: 3px; padding: 10px; border-radius: 5px; display: none">
                            <div class="col-md-12">
                                <h2>Invoice</h2>
                                <hr>
                            </div>
                            <div class="col-md-3">
                                <h3>Total Products: </h3>
                            </div>
                            <div class="col-md-3">
                                <h3 id="totalProducts"></h3>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <h3>Discount Percentage : </h3>
                            </div>
                            <div class="col-md-3">
                                <h3 id="discountPercentage"></h3>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <h3>Shipping : </h3>
                            </div>
                            <div class="col-md-3">
                                <h3 id="shipping"></h3>
                            </div>
                            <div class="col-md-6">
                            </div>


                            <div class="col-md-3">
                                <h3>Total After Discount : </h3>
                            </div>
                            <div class="col-md-3">
                                <h3 id="totalProductsAfterDiscount"></h3>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <h3>Total Order : </h3>
                            </div>
                            <hr>
                            <div class="col-md-3">
                                <h3 id="totalOrder"></h3>
                            </div>
                            <div class="col-md-6">
                            </div>

                            <div class="col-md-3">
                                <h3>Order Id : </h3>
                            </div>
                            <div class="col-md-3">
                                <input type="number" disabled id="order_id"></input>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-4 mt-2 mb-4">
                                <button type="button" class="btn btn-primary" onclick="addNewOrder();">
                                    Add New Order
                                </button>
                            </div>
                        </div>


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


        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Save Order </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">

                                <div class="form-group">
                                    <label for="created_for_user_id">Created For User</label>
                                    <input class="form-control" type="hidden" value="1" id="created_for_user_id" disabled>
                                    <input list="browsers" name="browser" id="browser" class="form-control" placeholder="Choose User">
                                    <datalist id="browsers">
                                        @foreach($users as $user)
                                             <option value="{{$user->full_name}}" id="{{$user->id}}" onclick="takeMyId({{$user->id}})">{{$user->full_name}}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="new_discount">Discount</label>
                                    <input class="form-control" type="number" min="0" max="100" value="25" id="new_discount">
                                </div>
                            </div>
                            {{--                            <div class="col-md-4">--}}

                            {{--                                <div class="form-group">--}}
                            {{--                                    <label for="new_shipping">Shipping</label>--}}
                            {{--                                    <input class="checkbox_animated check-box w-50" type="checkbox" required--}}
                            {{--                                           id="new_shipping">--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                        </div>

                        <div class="form-group">
                            <label for="landmark" id="errorindatat" style="color: red ;display: none">Error In
                                Data</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveOrderButton()">Save Order</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCurrentDiscount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCurrentDiscountTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Current Discount</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="new_discount">Discount</label>
                                    <input class="form-control" type="number" min="0" max="100" value="30" id="edit_current_discount">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveCurrentDiscount()">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </section>

    @push('scripts')
        <script type="text/javascript">

            // users filter
            var allCurrentUsers     = <?php echo json_encode($users); ?>;
            var allCurrentUsersData = allCurrentUsers['data'];
            var total_cart          = 0;
            var base_url            = window.location.origin;
            var url_string          = (window.location).href;
            var url                 = new URL(url_string);
            var message             = url.searchParams.get("message");

            var cartProducts     = [];
            var allProductsArray = [];
            $(document).ready(function () {
                // $("select").select2();

                $('#currentDiscount').html($('#new_discount').val());


                if (message && message != '') {
                    $("#infoMessage").show('slow');
                    $("#infoMessage").html(message);
                    setTimeout(function () {
                        $("#infoMessage").hide('slow');
                    }, 5000);

                    if (message == 'Operation done successfully') {
                        cartProducts     = [];
                        const myJSONdone = JSON.stringify(cartProducts);
                        localStorage.setItem("admin_cart", myJSONdone);
                    }
                }

                var admin_cart = localStorage.getItem('admin_cart');
                let arrLength  = JSON.parse(admin_cart);
                if (!admin_cart || admin_cart == null || admin_cart == '' || admin_cart.length == 0 || arrLength.length == 0) {
                    $("#nodata").show();
                    $("#totalHeaderAdminCart").html(0);
                    $("#totalHeaderAfterDiscount").html(0);
                    total_cart = 0;
                }
                else {
                    $("#nodata").hide();
                    $('#save_button').removeAttr('disabled');
                    allProductsArray = JSON.parse(admin_cart);
                    cartProducts     = allProductsArray;
                    const cartLength = allProductsArray.length;

                    for (let iiii = 0; iiii < cartLength; iiii++) {
                        var proObjff = allProductsArray[iiii];
                        $("#cartProductContainer").append(
                            ' <tr id="productparent' + proObjff['id'] + '"> <th scope="row"> ' + proObjff['id'] + ' </th><th scope="row"><img class="card-img-top cartimage" src="' + proObjff['image'] + '" alt="Card image cap"></th><td> ' + proObjff['name'] + ' </td><td>' + proObjff['price'] + '</td><td><button class="increase-decrease" type="button" onclick="decreaseQuantity(' + proObjff['id'] + ')"> - </button><span id="proQuantity' + proObjff['id'] + '">' + proObjff['quantity'] + '</span><button class="increase-decrease" type="button" onclick="increaseQuantity(' + proObjff['id'] + ')"> + </button></td><td ><button  type="button" onclick="removeFromCart(' + proObjff['id'] + ')" style="border: 0px;color: red;">X</button></td></tr>'
                        );
                        total_cart = (Number(total_cart) + (Number(proObjff['price']) * Number(proObjff['quantity'])));
                    }
                    $("#totalHeaderAdminCart").html(total_cart);
                    var afdis = total_cart - (total_cart * $('#new_discount').val() / 100);
                    $("#totalHeaderAfterDiscount").html(afdis);
                }

                $(".addToCartButton").click(function () {

                    console.log("fdfdfd 11");
                    var productId          = $(this).attr('id');
                    var productName        = $(this).attr('product_name');
                    var productFlag        = $(this).attr('product_flag');
                    var productPrice       = $(this).attr('product_price');
                    var productImage       = $(this).attr('product_image');
                    var productQuantity    = $('#product' + productId).val();
                    var excluderFlag       = $(this).attr('excluder_flag');
                    var priceAfterDiscount = $(this).attr('price_after_discount');
                    if (productQuantity > 0) {


                        var el_exist_inarray = cartProducts.find((e) => e.id == productId);
                        if (el_exist_inarray) {
                            var mainobj = {
                                'id': productId,
                                'name': productName,
                                'image': productImage,
                                'price': productPrice,
                                'flag': productFlag,
                                'excluder_flag': excluderFlag,
                                'price_after_discount': priceAfterDiscount,
                                'quantity': parseInt(parseInt(el_exist_inarray['quantity']) + parseInt(productQuantity))
                            }
                            removeFromCart(productId)
                        }
                        else {
                            var mainobj = {
                                'id': productId,
                                'name': productName,
                                'image': productImage,
                                'price': productPrice,
                                'flag': productFlag,
                                'excluder_flag': excluderFlag,
                                'price_after_discount': priceAfterDiscount,
                                'quantity': productQuantity
                            }
                        }
                        cartProducts.push(mainobj);
                        const myJSON = JSON.stringify(cartProducts);
                        localStorage.setItem("admin_cart", myJSON);
                        var bex_total;
                        var ex_total;
                        if (excluderFlag == 'Y') {
                            bex_total  = (Number(mainobj['price']) * Number(mainobj['quantity']));
                            ex_total   = (Number(mainobj['price_after_discount']) * Number(mainobj['quantity']));
                            total_cart = (Number(total_cart) + bex_total);
                            $("#totalHeaderAdminCart").html(total_cart);
                            var afdis = $("#totalHeaderAfterDiscount").html();
                            var dd    = Number(Number(afdis) + ex_total);
                            $("#totalHeaderAfterDiscount").html(dd);
                        }
                        else {
                            total_cart = (Number(total_cart) + (Number(mainobj['price']) * Number(mainobj['quantity'])));
                            $("#totalHeaderAdminCart").html(total_cart);
                            var afdis = total_cart - (total_cart * $('#new_discount').val() / 100);
                            $("#totalHeaderAfterDiscount").html(afdis);
                        }
                        $("#nodata").hide();
                        $('#product' + productId).val(1);
                        $('#save_button').removeAttr('disabled');
                        $("#cartProductContainer").append(
                            ' <tr id="productparent' + productId + '"> <th scope="row"> ' + productId + ' </th><th scope="row"><img class="card-img-top cartimage" src="' + productImage + '" alt="Card image cap"></th><td> ' + productName + ' </td><td>' + productPrice + '</td><td><button class="increase-decrease" type="button" onclick="decreaseQuantity(' + productId + ')"> - </button><span id="proQuantity' + productId + '">' + mainobj['quantity'] + '</span><button class="increase-decrease" type="button" onclick="increaseQuantity(' + productId + ')"> +</button></td><td ><button type="button" onclick="removeFromCart(' + productId + ')" style="border: 0px;color: red;">X</button></td></tr>'
                        );
                        swal({
                            text: "{{trans('website.Add Product To Cart',[],session()->get('locale'))}}",
                            title: "Successful",
                            timer: 1500,
                            icon: "success",
                            buttons: false,
                        });
                    }
                });


                $("#city_id").change(function () {

                    var city_id  = $("#city_id").val();
                    var cityName = $('#city' + city_id).attr('class');
                    $("#city_final_name").val(cityName);

                    $("#area_container").html('');
                    let formData = new FormData();
                    formData.append('city_id', city_id);
                    let path = base_url + "/admin/orderHeaders/getAreasByCityID";
                    // console.log("path", path);
                    $.ajax({
                        url: path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        processData: false,
                        success: function (response) {
                            if (response.data) {
                                if (response.data.length > 0) {
                                    for (let iii = 0; iii < response.data.length; iii++) {
                                        let proObjff = response.data[iii];
                                        let option   = '<option value="' + proObjff['region_en'] + '">' + proObjff['region_en'] + '</option>';
                                        $('#area_container').append(option);
                                    }
                                }
                            }
                        },
                        error: function (response) {
                            console.log(response)
                            alert('error');
                        }
                    });
                });

                $("#browser").change(function () {
                    var username = $("#browser").val();
                    $('#created_for_user_id').val('');

                    let formData = new FormData();
                    formData.append('name', username);
                    let path = base_url + "/admin/orderHeaders/getUserByName";
                    $.ajax({
                        url: path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        processData: false,
                        success: function (response) {
                            if (response.data) {
                                if (response.data) {
                                    var id                  = response.data;
                                    var created_for_user_id = $('#created_for_user_id').val(id);
                                }
                            }
                        },
                        error: function (response) {
                            console.log(response)
                            alert('error');
                        }
                    });

                });
                $("#browser").keyup(function () {
                    var username = $("#browser").val();
                    var res      = searchfun(username, allCurrentUsersData);
                    if (res === false) {
                        $("#browsers").html('');
                        let formData = new FormData();
                        formData.append('name', username);
                        let path = base_url + "/admin/orderHeaders/getSearchUserByName";
                        $.ajax({
                            url: path,
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            processData: false,
                            success: function (response) {
                                if (response.data) {
                                    if (response.data) {
                                        if (response.data.data) {
                                            var newus = response.data.data;
                                            // console.log(newus)
                                            for (let iii = 0; iii < newus.length; iii++) {
                                                let proObjff = newus[iii];
                                                let option   = ' <option value="' + proObjff['full_name'] + '" id="' + proObjff['id'] + '"  onclick="takeMyId(' + proObjff['id'] + ')" >'+proObjff['phone']+'</option>';
                                                $('#browsers').append(option);
                                            }
                                        }
                                    }
                                }
                            },
                            error: function (response) {
                                console.log(response)
                                alert('error');
                            }
                        });
                    }


                });


                $("#proname").change(function () {
                    getpro();
                });
                $("#barcode").change(function () {
                    getpro();
                });
                $("#procode").change(function () {
                    getpro();
                });

                $("#category_id").change(function () {
                    getpro();
                });

                function getpro() {
                    var category_id = $("#category_id").val();
                    var proname     = $("#proname").val();
                    var procode     = $("#procode").val();
                    var barcode     = $("#barcode").val();
                    let formData    = new FormData();
                    formData.append('category_id', category_id);
                    formData.append('name', proname);
                    formData.append('barcode', barcode);
                    formData.append('code', procode);
                    let path = base_url + "/admin/orderHeaders/getAllproducts";
                    // console.log("path", path);
                    $.ajax({
                        url: path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        processData: false,
                        success: function (response) {
                            if (response.data) {

                                $("#productsSearchContainer").html('');
                                // console.log(response.data);
                                if (response.data.length > 0) {
                                    for (let ii = 0; ii < response.data.length; ii++) {
                                        let proObj = response.data[ii];
                                        $("#productsSearchContainer").append(
                                            '<div class="col-md-4"><div class="card"> <img class="card-img-top cartimage" src="' + base_url + '/' + proObj['image'] + '" " alt="Card image cap"> <div class="card-body"> <h5 class="product-title">' + proObj['name_en'] +
                                            '</h5><h6> Price : ' + proObj['price'] + '</h6>  <h6> Price After Discount : ' + proObj['price_after_discount'] + '</h6> <h6>' + 'Quantity &nbsp; <input type="number" min="1" value="1" class="border border-primary rounded text-center w-50" id="product' + proObj['id'] + '"> </h6>' +
                                            ' <br> <button type="button" class="btn btn-primary addToCartButton" onclick="addToCartFunction(this)" id="' + proObj['id'] + '" product_name="' + proObj['name_en'] + '" product_flag="' + proObj['flag'] + '"  price_after_discount="' + proObj['price_after_discount'] + '"  excluder_flag="' + proObj['excluder_flag'] + '" product_price="' + proObj['price'] + '" product_image="' + proObj['image'] + '" >' +
                                            'Add To Cart </button> </div> </div> </div>'
                                            + ' \n'
                                        );
                                    }

                                    if (barcode && barcode != '' && barcode > '') {

                                        let proObjBar        = response.data[0];
                                        var el_exist_inarray = cartProducts.find((e) => e.id == proObjBar['id']);
                                        if (el_exist_inarray) {
                                            var mainobj = {
                                                'id': proObjBar['id'],
                                                'name': proObjBar['name_en'],
                                                'image': proObjBar['image'],
                                                'price': proObjBar['price'],
                                                'flag': proObjBar['flag'],
                                                'quantity': parseInt(parseInt(el_exist_inarray['quantity']) + 1)
                                            }
                                            removeFromCart(proObjBar['id'])
                                        }
                                        else {
                                            var mainobj = {
                                                'id': proObjBar['id'],
                                                'name': proObjBar['name_en'],
                                                'image': proObjBar['image'],
                                                'price': proObjBar['price'],
                                                'flag': proObjBar['flag'],
                                                'quantity': 1
                                            }
                                        }
                                        cartProducts.push(mainobj);
                                        const myJSON = JSON.stringify(cartProducts);
                                        localStorage.setItem("admin_cart", myJSON);
                                        total_cart = (Number(total_cart) + (Number(mainobj['price']) * Number(mainobj['quantity'])));
                                        $("#totalHeaderAdminCart").html(total_cart);
                                        var afdis = total_cart - (total_cart * $('#new_discount').val() / 100);
                                        $("#totalHeaderAfterDiscount").html(afdis);
                                        $("#nodata").hide();
                                        $('#save_button').removeAttr('disabled');
                                        $("#cartProductContainer").append(
                                            ' <tr id="productparent' + proObjBar['id'] + '"> <th scope="row"> ' + proObjBar['id'] + ' </th><th scope="row"><img class="card-img-top cartimage" src="' + proObjBar['image'] + '" alt="Card image cap"></th><td> ' + proObjBar['name_en'] + ' </td><td>' + proObjBar['price'] + '</td><td><button class="increase-decrease" type="button" onclick="decreaseQuantity(' + proObjBar['id'] + ')"> - </button><span id="proQuantity' + proObjBar['id'] + '">' + mainobj['quantity'] + '</span><button class="increase-decrease" type="button" onclick="increaseQuantity(' + proObjBar['id'] + ')">+ </button></td><td > <button type="button" onclick="removeFromCart(' + proObjBar['id'] + ')" style="border: 0px;color: red;">X</button> </td></tr>'
                                        );
                                        $("#barcode").val('');

                                    }
                                }
                                else {
                                    $("#productsSearchContainer").html('');
                                    $('#productsSearchContainer').append('<div class="col-md-12"> <h3 class="text-center">No Data</h3></div>');

                                }

                            }
                            else {
                                $("#productsSearchContainer").html('');
                                $('#productsSearchContainer').append('<div class="col-md-12"> <h3 class="text-center">No Data</h3></div>');

                            }
                        },
                        error: function (response) {
                            console.log(response)
                            alert('error');
                        }
                    });
                }
            });

            function addToCartFunction(el) {
                console.log("fdfdfd 22");
                var productId       = $(el).attr('id');
                var productName     = $(el).attr('product_name');
                var productPrice    = $(el).attr('product_price');
                var productImage    = $(el).attr('product_image');
                var productFlag     = $(el).attr('product_flag');
                var productQuantity = $('#product' + productId).val();


                var priceAfterDiscount = $(el).attr('price_after_discount');
                var excluderFlag       = $(el).attr('excluder_flag');


                var el_exist_inarray = cartProducts.find((e) => e.id == productId);
                if (el_exist_inarray) {
                    var mainobj = {
                        'id': productId,
                        'name': productName,
                        'image': productImage,
                        'price': productPrice,
                        'flag': productFlag,
                        'excluder_flag': excluderFlag,
                        'price_after_discount': priceAfterDiscount,
                        'quantity': parseInt(parseInt(el_exist_inarray['quantity']) + parseInt(productQuantity))
                    }
                    removeFromCart(productId)
                }
                else {
                    var mainobj = {
                        'id': productId,
                        'name': productName,
                        'image': productImage,
                        'price': productPrice,
                        'flag': productFlag,
                        'excluder_flag': excluderFlag,
                        'price_after_discount': priceAfterDiscount,
                        'quantity': productQuantity
                    }
                }
                cartProducts.push(mainobj);
                const myJSON = JSON.stringify(cartProducts);
                localStorage.setItem("admin_cart", myJSON);
                var bex_total;
                var ex_total;
                if (excluderFlag == 'Y') {
                    bex_total  = (Number(mainobj['price']) * Number(mainobj['quantity']));
                    ex_total   = (Number(mainobj['price_after_discount']) * Number(mainobj['quantity']));
                    total_cart = (Number(total_cart) + bex_total);
                    $("#totalHeaderAdminCart").html(total_cart);
                    var afdis = $("#totalHeaderAfterDiscount").html();
                    var dd    = Number(Number(afdis) + ex_total);
                    $("#totalHeaderAfterDiscount").html(dd);
                }
                else {
                    total_cart = (Number(total_cart) + (Number(mainobj['price']) * Number(mainobj['quantity'])));
                    $("#totalHeaderAdminCart").html(total_cart);
                    var afdis = total_cart - (total_cart * $('#new_discount').val() / 100);
                    $("#totalHeaderAfterDiscount").html(afdis);
                }
                $("#nodata").hide();
                $('#save_button').removeAttr('disabled');
                $("#cartProductContainer").append(
                    ' <tr id="productparent' + productId + '"> <th scope="row"> ' + productId + ' </th><th scope="row"><img class="card-img-top cartimage" src="' + productImage + '" alt="Card image cap"></th><td> ' + productName + ' </td><td>' + productPrice + '</td><td><button class="increase-decrease" type="button" onclick="decreaseQuantity(' + productId + ')"> - </button><span id="proQuantity' + productId + '">' + mainobj['quantity'] + '</span><button class="increase-decrease" type="button" onclick="increaseQuantity(' + productId + ')">+ </button></td><td > <button type="button" onclick="removeFromCart(' + productId + ')" style="border: 0px;color: red;">X</button> </td></tr>'
                );
                swal({
                    text: "{{trans('website.Add Product To Cart',[],session()->get('locale'))}}",
                    title: "Successful",
                    timer: 1500,
                    icon: "success",
                    buttons: false,
                });
            }

            function removeFromCart(produt_id) {
                const indexOfObject = cartProducts.findIndex(object => {
                    return object.id == produt_id;
                });
                total_cart          = (Number(total_cart) - (Number(cartProducts[indexOfObject]['price']) * Number(cartProducts[indexOfObject]['quantity'])));
                $("#totalHeaderAdminCart").html(total_cart);
                var afdis = total_cart - (total_cart * $('#new_discount').val() / 100);
                $("#totalHeaderAfterDiscount").html(afdis);
                cartProducts.splice(indexOfObject, 1);
                const myJSON = JSON.stringify(cartProducts);
                localStorage.setItem("admin_cart", myJSON);
                $("#productparent" + produt_id).remove();
                if (cartProducts.length < 1) {
                    $("#nodata").show();
                    $("#totalHeaderAdminCart").html(0);

                    $("#totalHeaderAfterDiscount").html(0);
                    $('#save_button').prop('disabled', true);
                }
            }

            function removeAllItems() {
                $('#save_button').prop('disabled', true);
                $('#payOrderButtonFunction').prop('disabled', true);
                $('#payOrderFunctionmessage').show();
                cartProducts = [];
                const myJSON = JSON.stringify(cartProducts);
                localStorage.setItem("admin_cart", myJSON);
                $("#cartProductContainer").html('');
                $("#totalHeaderAdminCart").html(0);

                $("#totalHeaderAfterDiscount").html(0);
                $("#nodata").show();
                $("#cartProductContainer").append(
                    '<tr id="nodata"><th scope="row" colspan="6" class="text-center">No Data </th> </tr>'
                );
            }

            function increaseQuantity(produt_id) {
                const indexOfObject                     = cartProducts.findIndex(object => {
                    return object.id == produt_id;
                });
                total_cart                              = (Number(total_cart) + Number(cartProducts[indexOfObject]['price']));
                cartProducts[indexOfObject]['quantity'] = Number(cartProducts[indexOfObject]['quantity']) + 1;
                $("#totalHeaderAdminCart").html(total_cart);
                var afdis = total_cart - (total_cart * $('#new_discount').val() / 100);
                $("#totalHeaderAfterDiscount").html(afdis);
                $("#proQuantity" + produt_id).html(cartProducts[indexOfObject]['quantity']);
                const myJSON = JSON.stringify(cartProducts);
                localStorage.setItem("admin_cart", myJSON);
            }

            function decreaseQuantity(produt_id) {
                const indexOfObject                     = cartProducts.findIndex(object => {
                    return object.id == produt_id;
                });
                total_cart                              = (Number(total_cart) - Number(cartProducts[indexOfObject]['price']));
                cartProducts[indexOfObject]['quantity'] = Number(cartProducts[indexOfObject]['quantity']) - 1;
                $("#totalHeaderAdminCart").html(total_cart);
                var afdis = total_cart - (total_cart * $('#new_discount').val() / 100);
                $("#totalHeaderAfterDiscount").html(afdis);
                $("#proQuantity" + produt_id).html(cartProducts[indexOfObject]['quantity']);

                if (cartProducts[indexOfObject]['quantity'] < 1) {
                    $("#productparent" + produt_id).remove();
                    cartProducts.splice(indexOfObject, 1);
                }
                if (cartProducts.length < 1) {
                    $("#nodata").show();
                    $("#totalHeaderAdminCart").html(0);

                    $("#totalHeaderAfterDiscount").html(0);
                }
                const myJSON = JSON.stringify(cartProducts);
                localStorage.setItem("admin_cart", myJSON);
            }

            function saveOrderButton() {
                $("#exampleModalCenter").modal('hide');
                $('.loader').show();
                var created_for_user_id = $('#created_for_user_id').val();
                var new_discount        = $('#new_discount').val();
                $('#currentDiscount').html($('#new_discount').val());
                var min_required = $('#min_required').val();
                var admin_id     = $('#admin_id').val();

                var new_shipping = false;

                let path = base_url + "/admin/orderHeaders/CalculateProductsAndShipping";

                var ff = {
                    "user_id": created_for_user_id > 2 ? created_for_user_id : 2,
                    "created_for_user_id": created_for_user_id > 2 ? created_for_user_id : 2,
                    "new_discount": new_discount,
                    "new_shipping": new_shipping,
                    "admin_id": admin_id,
                    "type": "onLine",
                    "items": cartProducts
                }

                $.ajax({
                    url: path,
                    type: 'POST',
                    cache: false,
                    data: JSON.stringify(ff),
                    contentType: "application/json; charset=utf-8",
                    traditional: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    success: function (response) {
                        if (response.data) {
                            console.log(response.data);
                            $('.loader').hide();
                            $("#exampleModalCenter").modal('hide');
                            $('#save_button').prop('disabled', true);
                            $('#payOrderButtonFunction').prop('disabled', false);
                            $("#invoice").show();
                            $("#shipping").html('');
                            $("#totalProducts").html('');
                            $("#totalProductsAfterDiscount").html('');
                            $("#discountPercentage").html('');
                            $("#order_id").val(0);
                            $("#order_online_id").val(0);

                            $('#shipping').append(response.data.shipping);
                            $('#totalProducts').append(response.data.totalProducts);
                            $('#totalProductsAfterDiscount').append(response.data.totalProductsAfterDiscount);
                            $('#discountPercentage').append(response.data.discountPercentage);
                            $('#totalOrder').append(response.data.totalOrder);
                            $('#order_id').val(response.data.order_id);
                            $('#order_online_id').val(response.data.order_id);

                            window.scrollTo({left: 0, top: document.body.scrollHeight, behavior: 'smooth'})
                        }
                        else {
                            $('.loader').hide();
                        }
                    },
                    error: function (response) {
                        console.log(response)
                        alert('error');
                        $('.loader').hide();
                    }
                });
            }

            function saveCurrentDiscount() {
                var current_discount = $('#edit_current_discount').val();
                $('#new_discount').val(current_discount);
                $('#currentDiscount').html(current_discount);
                $("#exampleModalCurrentDiscount").modal('hide');
                var afdis = total_cart - (total_cart * current_discount / 100);
                $("#totalHeaderAfterDiscount").html(afdis);
            }

            function searchfun(nameKey, myArray) {
                for (let i = 0; i < myArray.length; i++) {
                    let text = myArray[i].full_name;
                    let phone = myArray[i].phone;

                    if (text.includes(nameKey) || phone.includes(nameKey)) {
                        return true;
                    }
                }
                return false;
            }

            function takeMyId(uid) {
                alert(uid);
            }

            function addNewOrder() {
                cartProducts = [];
                const myJSON = JSON.stringify(cartProducts);
                localStorage.setItem("admin_cart", myJSON);

                $("#nodata").show();
                $("#cartProductContainer").html('');
                location.reload();
            }
        </script>
    @endpush

@endsection

