@extends('AdminPanel.layouts.main')
@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('orderHeaders.index')}}">Purchase Invoices</a></li>
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
                        <div class="alert alert-danger" role="alert" style="display: none">
                          Please Choose Company
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{(isset($code))?route('orderHeaders.update',$code):route('orderHeaders.store')}}"
                              method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf



                            <div class="alert alert-primary" role="alert" id="infoMessage" style="display: none">

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <h3>Invoice <span style="color: #ee3535">(Please Choose One Company)</span></h3>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Image</th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">Stock Status</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="cartProductContainer">
                                                        </tbody>
                                                        <tr id="nodata">
                                                            <th scope="row" colspan="7" class="text-center">
                                                                No Data
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h3>Products</h3>
                                                <div class="input-group">
                                                    <div class="form-outline md-form w-100">
                                                        <input type="search" id="proname" class="form-control"
                                                               placeholder="Search Product Name En"/>
                                                        <br>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="md-form">
                                                    <label style="margin: 9px">Company</label>
                                                    <select id="company_id" class="form-control"
                                                            name="company_id" required>
                                                        <option value="">Chose Company</option>
                                                        @foreach($companies as $company)
                                                            <option value="{{$company->id}}">{{$company->name_en}}</option>
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

                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <img class="card-img-top cartimage"
                                                                 src="{{$product->image}}" alt="Card image cap">
                                                            <div class="card-body">
                                                                <h5 class="product-title">{{$product->name_en}}</h5>
                                                                <h6>Purchase Price &nbsp;
                                                                    <input type="number" min="1"
                                                                           value="1"
                                                                           class="border border-primary rounded text-center w-50"
                                                                           id="productPrice{{$product->id}}">
                                                                </h6>
{{--                                                                <h6>Selling Price &nbsp; &nbsp;--}}
{{--                                                                    <input type="number" min="1"--}}
{{--                                                                           value="1"--}}
{{--                                                                           class="border border-primary rounded text-center w-50"--}}
{{--                                                                           id="productSellingPrice{{$product->id}}">--}}
{{--                                                                </h6>--}}
{{--                                                                <h6>Discount Rate--}}
{{--                                                                    <input type="number" min="0"--}}
{{--                                                                           value="0"--}}
{{--                                                                           class="border border-primary rounded text-center w-50"--}}
{{--                                                                           id="productDiscountRate{{$product->id}}">--}}
{{--                                                                </h6>--}}
                                                                <h6>
                                                                    Quantity &nbsp;&nbsp; &nbsp; &nbsp;   &nbsp; &nbsp; &nbsp;  &nbsp; <input type="number" min="1"
                                                                                           value="1"
                                                                                           class="border border-primary rounded text-center w-50"
                                                                                           id="productQuantity{{$product->id}}">
                                                                </h6>
                                                                <h6>
                                                                    Stock Status
                                                                    <select id="stock_status{{$product->id}}" class="form-control">
                                                                        <option value="stock_confirm">Stock Confirm</option>
                                                                        <option value="feak_stock">Feak Stock</option>
                                                                    </select>
                                                                    <span style="color: #0c84ff;
    display: block;">(Feak Stock تعني هذه المنتجات يتم بيعها قبل شرائها )</span>
                                                                </h6>

                                                                <br>
                                                                <button type="button"
                                                                        class="btn btn-primary" onclick="addToCartFunction(this)"
                                                                        id="{{$product->id}}"
                                                                        product_name="{{$product->name_en}}"
                                                                        product_flag="{{$product->flag}}"
                                                                        product_image="{{$product->image}}">
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

                           &nbsp; &nbsp;  <button id="save_button" type="button" class="btn btn-primary" onclick="saveInvoiceButton()" disabled>
                                Save Invoice
                            </button>
                            <br>
                            <br>

                            <div class="row" id="invoice" style="    border: 1px solid #e7e7e7; margin-left: 3px; padding: 10px; border-radius: 5px; display: none" >
                                <div class="col-md-12">
                                    <h2 >Invoice</h2>
                                    <hr>
                                </div>



                                <div class="col-md-3">
                                    <h3 >Total Invoice Purchase Price: </h3>
                                </div>
                                <div class="col-md-3">
                                    <h3 id="totalProducts"></h3>
                                </div>
                                <div class="col-md-6">
                                </div>

                                <div class="col-md-3">
                                    <h3 >Total Products Count: </h3>
                                </div>
                                <div class="col-md-3">
                                    <h3 id="tProductsCount"></h3>
                                </div>
                                <div class="col-md-6">
                                </div>



                                <div class="col-md-3">
                                    <h3 >Invoice Id : </h3>
                                </div>
                                <div class="col-md-3">
                                    <h3 id="invoice_id"></h3>
                                </div>
                                <div class="col-md-6">
                                </div>
                                <br>
                                <br>
                                <br>

                            </div>

                            <br>
                            <br>



{{--                            <div class="card-footer">--}}
{{--                                <button type="submit" class="btn btn-success">Save Info</button>--}}
{{--                            </div>--}}
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
        <script type="text/javascript">

            // users filter
            var allCurrentUsers=[];
            var allCurrentUsersData=allCurrentUsers['data'];

            var base_url = window.location.origin;
            var url_string = (window.location).href;
            var url = new URL(url_string);
            var message = url.searchParams.get("message");

            var cartProducts = [];
            $(document).ready(function () {
                // $("select").select2();

                if(message && message !=''){
                    $("#infoMessage").show('slow');
                    $("#infoMessage").html(message);
                    setTimeout(function() {
                        $("#infoMessage").hide('slow');
                    }, 5000);
                }


                $("#city_id").change(function () {

                    var city_id = $("#city_id").val();
                    var cityName = $('#city'+city_id).attr('class');
                    $("#city_final_name").val(cityName);

                    $("#area_container").html('');
                    let formData = new FormData();
                    formData.append('city_id', city_id);
                    let path = base_url + "/admin/orderHeaders/getAreasByCityID";
                    console.log("path", path);
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
                                        let option='<option value="'+proObjff['region_en']+'">'+proObjff['region_en']+'</option>';
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


                $("#proname").change(function () {
                    getpro();
                });

                $("#company_id").change(function () {
                    getpro();
                    // var company_id = $("#company_id").val();
                    // if(company_id > 0){
                    //     $('#company_id').prop('disabled', true);
                    // }
                });

                function getpro() {
                    var company_id = $("#company_id").val();
                    var proname = $("#proname").val();
                    let formData = new FormData();
                    formData.append('company_id', company_id);
                    formData.append('name', proname);
                    let path = base_url + "/admin/purchaseInvoices/getAllproducts";
                    console.log("path", path);
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
                                console.log(response.data);
                                if (response.data.length > 0) {
                                    for (let ii = 0; ii < response.data.length; ii++) {
                                        let proObj = response.data[ii];

                                        $("#productsSearchContainer").append(
                                            '<div class="col-md-6"><div class="card"> <img class="card-img-top cartimage" src="'+proObj['image']+'" " alt="Card image cap"> <div class="card-body"> <h5 class="product-title">'+ proObj['name_en'] +
                                            '</h5>'+
                                            '<h6>Purchase Price &nbsp;<input type="number" min="1" value="1" class="border border-primary rounded text-center w-50" id="productPrice'+proObj['id']+'"></h6>'+
                                             '<h6> Quantity &nbsp;&nbsp; &nbsp; &nbsp;   &nbsp; &nbsp; &nbsp;  <input type="number" min="1" value="1" class="border border-primary rounded text-center w-50" id="productQuantity'+proObj['id']+'"></h6>'+
                                            ' <h6>'+'Stock Status    <select id="stock_status'+proObj['id']+'" class="form-control"> </h6>'+
                                            ' <option value="stock_confirm">Stock Confirm</option><option value="feak_stock">Feak Stock</option></select><span style="color: #0c84ff;display: block;">(Feak Stock تعني هذه المنتجات يتم بيعها قبل شرائها )</span></h6>'+
                                            ' <br> <button type="button" class="btn btn-primary" onclick="addToCartFunction(this)" id="'+proObj['id']+'" product_name="'+proObj['name_en']+'" product_flag="'+proObj['flag']+'" product_image="'+proObj['image']+'" >'+
                                            'Add To Cart </button> </div> </div> </div>'
                                            + ' \n'
                                        );
                                    }
                                } else {
                                    $("#productsSearchContainer").html('');
                                    $('#productsSearchContainer').append('<div class="col-md-12"> <h3 class="text-center">No Data</h3></div>');

                                }

                            } else {
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

                var productId = $(el).attr('id');
                var productName = $(el).attr('product_name');
                var productImage = $(el).attr('product_image');
                var productQuantity = $('#productQuantity' + productId).val();
                var productPayPrice = $('#productPrice' + productId).val();
                // var productSellingPrice = $('#productSellingPrice' + productId).val();
                // var productDiscountRate = $('#productDiscountRate' + productId).val();
                var productstock_status = $('#stock_status' + productId).val();
                var el_exist_inarray = cartProducts.find((e) => e.id == productId);

                if(el_exist_inarray){
                    console.log(parseInt(el_exist_inarray.quantity)+parseInt(productQuantity));
                    var mainobj = {
                        'id': productId,
                        'name': productName,
                        'purchase_price': productPayPrice,
                        // 'selling_price': productSellingPrice,
                        // 'discount_rate': productDiscountRate,
                        'quantity': parseInt(el_exist_inarray.quantity)+parseInt(productQuantity),
                        'stock_status': productstock_status
                    }
                    removeFromCart(productId)
                }else {
                    var mainobj = {
                        'id': productId,
                        'name': productName,
                        'purchase_price': productPayPrice,
                        // 'selling_price': productSellingPrice,
                        // 'discount_rate': productDiscountRate,
                        'quantity': productQuantity,
                        'stock_status': productstock_status
                    }
                }


                $("#nodata").hide();
                cartProducts.push(mainobj);

                $('#save_button').removeAttr('disabled');
                $("#cartProductContainer").append(
                    ' <tr id="productparent'+productId+'"> <th scope="row"> ' + cartProducts.length + ' </th><th scope="row"><img class="card-img-top cartimage" src="'+productImage+'" alt="Card image cap"></th><td> ' + productName + ' </td><td>' + productPayPrice + '</td><td>' + mainobj.quantity + '</td><td>' + productstock_status + '</td><td ><button type="button" onclick="removeFromCart('+productId+')" style="border: 0px;color: red;">X</button></td></tr>'
                );
                $('#productQuantity' + productId).val(1);
                $('#productPrice' + productId).val(1);
                // $('#productSellingPrice' + productId).val(1);
                // $('#productDiscountRate' + productId).val(0);

            }

            function removeFromCart(produt_id) {
                var indexOfObject = cartProducts.findIndex(object => {
                    return object.id == produt_id;
                });
                cartProducts.splice(indexOfObject, 1);
                $("#productparent"+produt_id).hide();
                if(cartProducts.length < 1){
                    $("#nodata").show();
                }

            }

            function saveInvoiceButton() {

                var company_id = $('#company_id').val();

                if(company_id < 1 ){
                    $(".alert-danger").show();
                    window.scrollTo({ left: 0, top: 0, behavior: 'smooth' })
                    return ;
                }else {
                    $(".alert-danger").hide();
                }

                let path = base_url + "/admin/purchaseInvoices/CreatePurchaseInvoices";


            var ff={
                    "company_id":company_id ,
                    "items":cartProducts
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
                            $('#save_button').prop('disabled', true);
                            $("#invoice").show();
                            $("#invoice_id").html('');
                            $('#invoice_id').append( response.data.id);
                            $('#totalProducts').append( response.data.total_price);
                            $('#tProductsCount').append( response.data.items_count);
                            cartProducts=[];

                            $("#cartProductContainer").html('');
                            $("#nodata").show();
                            window.scrollTo({ left: 0, top: document.body.scrollHeight, behavior: 'smooth' })
                        }
                    },
                    error: function (response) {
                        console.log(response)
                        alert('error');
                    }
                });
            }
            function payOrderFunction() {

                let path = base_url +'/fawry'
                window.open(path,'_blank')
            }


            function searchfun(nameKey, myArray){
                for (let i=0; i < myArray.length; i++) {
                    let text=myArray[i].full_name
                    if (text.includes(nameKey)) {
                        return true;
                    }
                }
                return false;
            }


        </script>
    @endpush

@endsection

