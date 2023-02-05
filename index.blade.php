@extends('AdminPanel.layouts.main')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('orderHeaders.index')}}">Orders</a></li>
                    </ol>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('AdminPanel.layouts.messages')

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{route('orderHeaders.ExportOrderHeadersSheet')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-3">
                        <label class="col-form-label" for="start_date">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group col-3">
                        <label class="col-form-label " for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="form-group col-3">
                        <label class="col-form-label" for="with"> With</label>
                        <select name="with"  class="form-control">
                            <option value="user">User</option>
                            <option value="products">Products</option>
                        </select>
                    </div>

                    <div class="form-group col-3">
                        <input type="hidden" name="payment_status" id="payment_status">
                        <label class="col-form-label" > <i class="fa fa-file"></i> </label>
                        <button type="submit" class="btn btn-success form-control">Exports Orders Sheet</button>
                    </div>
                </div>
            </form>
            <form method="get" action="{{route('orderHeaders.index')}}" >

                <div class="row">
                    <div class="form-group col-2">

                        <label class="col-form-label" for="name">Invoice Number</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder=" Invoice Number" >
                    </div>
                    <div class="form-group col-2">
                        <label class="col-form-label" for="user_serial_number">User Serial Number</label>
                        <input type="text" name="user_serial_number" class="form-control" id="user_serial_number" placeholder="Serial Number" >
                    </div>

                    <div class="form-group col-3">
                        <label class="col-form-label" for="phone">User Phone</label>
                        <input type="text" name="phone" class="form-control" id="phone" placeholder="User phone" >
                    </div>
                    <div class="form-group col-3">
                        <label class="col-form-label" for="user_name">User name</label>
                        <input type="text" name="user_name" class="form-control" id="user_name" placeholder="User Name" >
                    </div>
                    <div class="form-group col-2">
                        <label class="col-form-label"><i class="fa fa-search"></i></label>
                        <button type="submit" class="btn btn-info form-control">Search</button>
                    </div>
                </div>

            </form>
        </div>


           <div class="form-group">
               <label>Payment Status</label>
               <select name="type" class="form-control" id="orderType">
                   <option value="">Select Type</option>
                   <option value=""  @if(app('request')->input('type') == '')selected @endif>All</option>
                   <option value="PENDING" @if(app('request')->input('type') == 'PENDING')selected @endif>PENDING</option>
                   <option value="PAID" @if(app('request')->input('type') == 'PAID')selected @endif>PAID</option>
                   <option value="EXPIRED" @if(app('request')->input('type') == 'EXPIRED')selected @endif>EXPIRED</option>
                   <option value="DELETED" @if(app('request')->input('type') == 'DELETED')selected @endif>DELETED</option>
                   <option value="CANCELED" @if(app('request')->input('type') == 'CANCELED')selected @endif>CANCELED</option>
               </select>
           </div>

        <!-- /.card-header -->
        <div class="card-body" style="overflow-x:scroll">
            @if(count($orderHeaders) > 0)
                <table id="orderHeadersTable" style="width: 100%" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th> <input type="checkbox" id="select-all"></th>
                        <th>Invoice Number</th>
                        <th>payment_code</th>
                        <th>total_order</th>
                        <th>User Name</th>
                        <th>User Type</th>
                        <th>User Serial Number</th>
                        <th>User phone</th>
                        <th>User landline number</th>
                        <th>User email</th>
                        <th>User Street</th>
                        <th>User City</th>
                        <th>User Area</th>
                        <th>User Building Number</th>
                        <th>User Floor Number</th>
                        <th>User Apartment Number</th>
                        <th>User Landmark</th>
                        <th>User NationalID</th>
                        <th>order_type</th>
                        <th>shipping_amount</th>
                        <th>payment_status</th>
                        <th>order_status</th>
                        <th>shipping_date</th>
                        <th>delivery_date</th>
                        <th>wallet_status</th>
                        <th>wallet_used_amount</th>
                        <th>gift_category_id</th>
                        <th>Date</th>
                        <th>Control</th>
                        <th>Charge</th>
                        <th>Update Status</th>
                        @if(Auth::guard('admin')->user()->id == 13)
                        <th>Refund</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orderHeaders as $row)
                        <tr>
                            <td><input type="checkbox" name="checkbox[]" value="{{$row->id}}"/></td>
                            <td>{{$row->id}}</td>
                            <td>{{$row->payment_code}}</td>
                            <td>{{$row->total_order}}</td>
                            <td>{{($row->createdFor)?$row->createdFor->full_name:''}}</td>
                            <td>{{($row->createdFor)?$row->createdFor->userType->name_en:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->createdFor->account_id:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->createdFor->phone:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->createdFor->telephone:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->createdFor->email:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->address:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->city:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->area:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->building_number:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->floor_number:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->apartment_number:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->landmark:''}}</td>
                            <td>{{(isset($row->createdFor))?$row->createdFor->nationality_id :''}}</td>
                            <td>{{$row->order_type}}</td>
                            <td>{{$row->shipping_amount}}</td>
                            <td>{{$row->payment_status}}</td>
                            <td>{{$row->order_status}}</td>
                            <td>{{$row->shipping_date}}</td>
                            <td>{{$row->delivery_date}}</td>
                            <td>{{$row->wallet_status}}</td>
                            <td>{{$row->wallet_used_amount}}</td>
                            <td>{{(isset($row->giftCategory->name_en))? $row->giftCategory->name_en : 'NO Gift'}}</td>
                            <td>{{$row->created_at}}</td>
                            <td>
                                <a class="btn btn-success" href="{{route('orderHeaders.show',$row)}}" target="_blank">View Invoice</a>
                            </td>
                             <td>
                                @if($row->payment_status== 'PAID')
                                    <form method="post" action="{{route('orderHeaders.ExportOrderCharge')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="order_id" class="form-control" value="{{$row->id}}">
                                            <input type="hidden" name="user_name" class="form-control" value="{{($row->createdFor)?$row->createdFor->full_name:''}}">
                                            <input type="hidden" name="user_phone" class="form-control" value="{{(isset($row->createdFor))?$row->createdFor->phone:''}}">
                                            <input type="hidden" name="user_city" class="form-control" value="{{(isset($row->createdFor))?$row->city:''}}">
                                            <input type="hidden" name="user_address" class="form-control" value="{{(isset($row->createdFor))?$row->address:''}}">
                                            <input type="hidden" name="user_area" class="form-control" value="{{(isset($row->createdFor))?$row->area:''}}">
                                            <div class="form-group col-12">
                                                <button type="submit" class="btn btn-success form-control">شحن</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if($row->payment_status== 'PAID')
                                    <form method="post" action="{{route('orderHeaders.changeOrderChargeStatus')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="order_id" class="form-control" value="{{$row->id}}">
                                            <div class="form-group col-12">
                                                <button type="submit" class="btn btn-success form-control">تحديث</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </td>
                            @if(Auth::guard('admin')->user()->id == 13)
                            <td>
                                <a class="btn btn-success" href="{{route('orderHeaders.edit',$row)}}" target="_blank">Refund Invoice</a>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
{{--                    <tfoot>--}}
{{--                    <tr>--}}
{{--                        <th> <input type="checkbox" id="select-all"></th>--}}
{{--                        <th>Invoice Number</th>--}}
{{--                        <th>payment_code</th>--}}
{{--                        <th>total_order</th>--}}
{{--                        <th>User Name</th>--}}
{{--                        <th>User Serial Number</th>--}}
{{--                        <th>order_type</th>--}}
{{--                        <th>shipping_amount</th>--}}
{{--                        <th>payment_status</th>--}}
{{--                        <th>order_status</th>--}}
{{--                        <th>shipping_date</th>--}}
{{--                        <th>delivery_date</th>--}}
{{--                        <th>wallet_status</th>--}}
{{--                        <th>wallet_used_amount</th>--}}
{{--                        <th>gift_category_id</th>--}}
{{--                        <th>Date</th>--}}
{{--                        <th>Control</th>--}}
{{--                    </tr>--}}
{{--                    </tfoot>--}}
                </table>
                <div class="pagination">

                    @if (isset($orderHeaders) && $orderHeaders->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $orderHeaders->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $orderHeaders->currentPage() + $interval;
                            if($to > $orderHeaders->lastPage()){
                              $to = $orderHeaders->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($orderHeaders->currentPage() > 1)
                                <li>
                                    <a href="{{ $orderHeaders->url(1)."&type=".app('request')->input('type')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $orderHeaders->url($orderHeaders->currentPage() - 1)."&type=".app('request')->input('type') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $orderHeaders->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a href="{{ !$isCurrentPage ? $orderHeaders->url($i)."&type=".app('request')->input('type') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($orderHeaders->currentPage() < $orderHeaders->lastPage())
                                <li>
                                    <a href="{{ $orderHeaders->url($orderHeaders->currentPage() + 1)."&type=".app('request')->input('type') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $orderHeaders->url($orderHeaders->lastpage())."&type=".app('request')->input('type') }}" aria-label="Last">
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

            function urlParamfun(name){
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                if (results==null){
                    return null;
                }
                else{
                    return results[1] || 0;
                }
            }
            $( "#orderType" ).change(function() {
                if ($(this).val() == '')
                {
                    window.location.href = "{{route('orderHeaders.index')}}"
                }
                window.location.href = "{{route('orderHeaders.index')}}?type="+$(this).val();
            });

            $('#select-all').click(function() {
                var checked = this.checked;
                $('input[type="checkbox"]').each(function() {
                    this.checked = checked;
                });
            })

            $(document).ready(function () {
                var type = urlParamfun('type');
                console.log(type);
                $("#payment_status").val(type);
            });


        </script>
    @endpush
@endsection
