@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('getViewOracleProducts')}}">Oracle Products Availability</a></li>
                    </ol>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('AdminPanel.layouts.messages')


    <div class="card">

        <div class="row mb-1 mt-2 pl-4">
            <div class="col-sm-12 row" style="border: 2px solid #f00;border-radius: 10px; padding: 5px">
                <h3 class="col-md-12 text-red">Please Refresh Data On Click 4 Buttons</h3>
                <div class="col-sm-3">
                    1
                    <a class="btn btn-outline-primary" href="{{route('updateOracleInvoices')}}">Update
                        Oracle
                        Invoices</a>
                </div>
                <div class="col-sm-3">
                    2 <a class="btn btn-outline-success" href="{{route('refreshOracleInvoices')}}">Refresh
                        Invoices</a>
                </div>

                <div class="col-sm-3">
                    3 <a class="btn btn-outline-info" href="{{route('updateOracleProducts')}}">
                        Update Oracle Codes</a>
                </div>
                <div class="col-sm-3">
                    4 <a class="btn btn-outline-success" href="{{route('updateOracleProductsPrice')}}">
                        Update Products Price</a>
                </div>



            </div>
        </div>

        <div class="card-body">
            <form method="get" action="{{route('getViewOracleProducts')}}">
                <div class="row">
                    <div class="col-md-3">
                        <input class="form-control" type="text" placeholder="name" id="searchtext" name="name">
                    </div>

                    <div class="col-md-3">
                        <input class="form-control" type="text" placeholder="oracle code" id="searchtext" name="item_code">
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="text" placeholder="bar code" id="searchtext" name="barcode">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-info">Search</button>
                    </div>
                </div>
            </form>
            <h3 class="mt-1 mb-1">
                <button type="button"  onclick="ExportOracleProductsSheet()" class="btn btn-success mx-2">Export All</button>
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if(count($products) > 0)
                <table id="productsTable"  class="table table-bordered table-striped">
                    <thead>
                    <tr>
{{--                        <th> <input type="checkbox" id="select-all"></th>--}}
                        <th>Product_id</th>
                        <th>Name</th>
                        <th>Short code</th>
                        <th>oracle quantity </th>
                        <th>available quantity</th>
                        <th>Pending in Orders</th>
                        <th>subtraction (oracle - available)</th>
                        <th>bar Code</th>
                        <th>Date</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $row)
                        <tr>
{{--                            <td><input type="checkbox" name="checkbox[]" value="{{$row->id}}"/></td>--}}
                            <td >{{$row->product_id}}</td>
                            <td >{{$row->full_name}}</td>
                            <td >{{$row->oracle_short_code}}</td>
                            <td >{{$row->oracle_quantity}}</td>
                            <td >{{$row->available_quantity}}</td>
                            <td >{{$row->pending_query}}</td>
                            <td @if(($row->oracle_quantity-$row->available_quantity)>0) class="text-success" @else class="text-danger" @endif>{{$row->oracle_quantity-$row->available_quantity}}</td>
                            <td >{{$row->barcode}}</td>
                            <td >{{$row->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
{{--                    <tfoot>--}}
{{--                    <tr>--}}
{{--                        <th>#</th>--}}
{{--                        <th>Name EN</th>--}}
{{--                        <th>Name AR</th>--}}
{{--                        <th>price</th>--}}
{{--                        <th>stock Status</th>--}}
{{--                        <th>oracle Code</th>--}}
{{--                        <th>tax</th>--}}
{{--                        <th>discount Rate</th>--}}
{{--                        <th>price After Discount</th>--}}
{{--                        <th>Control</th>--}}
{{--                    </tr>--}}
{{--                    </tfoot>--}}
                </table>
                <div class="pagination justify-content-center mt-2">

                    @if (isset($products) && $products->lastPage() > 1)
                        <ul class="pagination align-items-center">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $products->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $products->currentPage() + $interval;
                            if($to > $products->lastPage()){
                              $to = $products->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($products->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->url(1)."&name=".app('request')->input('name')."&item_code".app('request')->input('item_code')."&barcode".app('request')->input('barcode')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->url($products->currentPage() - 1)."&name=".app('request')->input('name')."&item_code".app('request')->input('item_code')."&barcode".app('request')->input('barcode') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $products->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a class="page-link"
                                        href="
                                                {{
                                                    !$isCurrentPage ? $products->url($i)."&name=".app('request')->input('name')."&item_code".app('request')->input('item_code')."&barcode".app('request')->input('barcode') : ''
                                                }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($products->currentPage() < $products->lastPage())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->url($products->currentPage() + 1)."&name=".app('request')->input('name')."&item_code".app('request')->input('item_code')."&barcode".app('request')->input('barcode') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->url($products->lastpage())."&name=".app('request')->input('name')."&item_code".app('request')->input('item_code')."&barcode".app('request')->input('barcode') }}" aria-label="Last">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>

            @else
                <h1 class="text-center">NO DATA</h1>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
    @push('scripts')
        <script type="text/javascript">



            function ExportOracleProductsSheet()
            {
                window.location.href = "{{route('ExportOracleProductsSheet')}}";
            }
            $('#select-all').click(function() {
                var checked = this.checked;
                $('input[type="checkbox"]').each(function() {
                    this.checked = checked;
                });
            })

        </script>
    @endpush
@endsection
