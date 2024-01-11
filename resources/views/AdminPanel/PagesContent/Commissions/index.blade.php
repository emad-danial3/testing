@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('commissions.index')}}">Quarter&Annual
                                Commissions</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('AdminPanel.layouts.messages')

    <div class="card">
        <span class="alert alert-info">Import file should contain : &nbsp; id:commission id &nbsp; &nbsp;  is_redeemed : 0 for unpaid 1 for paid</span>

        <div class="card-header" style="float: right">
            <h3 class="card-title">
                <form method="post" action="{{route('commissions.importCommissionsSheet')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" required>
                    <button type="submit" class="btn btn-danger">Import Commission Sheet</button>
                </form>
            </h3>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <form method="post" action="{{route('commissions.ExportCommissionsSheet')}}" enctype="multipart/form-data" class="row">
                @csrf
                 <div class="form-group col-2">
                        <label>Type</label>
                        <select name="type" class="form-control mt-2" required>
                            <option value="">Select Type</option>
                            <option value="0" >
                                Pending Commissions
                            </option>
                            <option value="1" >
                                Redeem Commissions
                            </option>
                        </select>
                    </div>
                <div class="form-group col-md-4">
                    <label class="col-form-label" for="start_date">Start Date</label>
                    <input type="date" name="start_date" required class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label class="col-form-label" for="end_date">End Date</label>
                    <input type="date" name="end_date" required class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <button type="submit" class="btn btn-success">Exports Paid Commissions Sheet</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <form method="get" action="{{route('commissions.index')}}">

                <div class="row">
                    <div class="form-group col-3">
                        <label>Type</label>
                        <select name="type" class="form-control mt-2">
                            <option value="">Select Type</option>
                            <option value="0" @if(app('request')->input('type') == '0')selected @endif>
                                Pending Commissions
                            </option>
                            <option value="1" @if(app('request')->input('type') == '1')selected @endif>
                                Redeem Commissions
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-2">
                        <label class="col-form-label" for="name">User Phone</label>
                        <input type="text" name="name" class="form-control" id="name" @if(app('request')->input('name'))value="{{app('request')->input('name')}}" @endif placeholder="User Phone">
                    </div>
                    <div class="form-group col-2">
                        <label class="col-form-label" for="user">User Id</label>
                        <input type="text" name="user" class="form-control" id="user" @if(app('request')->input('user'))value="{{app('request')->input('user')}}" @endif placeholder=" User ID">
                    </div>
                     <div class="row col-3">
                        <div class="form-group col-6">
                            <label class="col-form-label" for="from_date">From Date</label>
                            <input type="date" name="from_date" id="from_date" @if(app('request')->input('from_date'))value="{{app('request')->input('from_date')}}" @endif class="form-control">
                        </div>
                        <div class="form-group col-6">
                            <label class="col-form-label"  for="to_date">To Date</label>
                            <input type="date" name="to_date"  @if(app('request')->input('to_date'))value="{{app('request')->input('to_date')}}" @endif id="to_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-2">
                        <label class="col-form-label"><i class="fa fa-search"></i></label>
                        <button type="submit" class="btn btn-info form-control">Search</button>
                    </div>
                </div>

            </form>
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x:scroll">
            @if(count($commissions) > 0)
                <table id="commissionsTable" style="width: 100%" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Nationality ID</th>
                        <th>User Name</th>
                        <th>Redeem Flag</th>
                        <th>Date Of Redeem</th>

                        <th>Commission Type</th>

                        <th>Commission</th>
                        <th>Front ID Image</th>
                        <th>Back ID Image</th>
                        <th>Create AT</th>
                        <th>PAID</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($commissions as $row)
                        <tr>
                            <td><input type="checkbox" name="checkbox[]" value="{{$row->id}}"/></td>
                            <td>{{$row->id}}</td>
                            <td>{{$row->user->id}}</td>
                            <td>{{$row->user->nationality_id}}</td>
                            <td>{{$row->user->full_name}}</td>
                            <td>{{$row->is_redeemed}}</td>
                            <td>{{$row->redeem_date}}</td>
                            <td>{{$row->commission_type}}</td>

                            <td>{{$row->commission}}</td>
                            <td>
                                <a href="{{url(($row->createdFor)?$row->createdFor->front_id_image:'')}}" target="_blank">Front
                                    ID Image</a></td>
                            <td>
                                <a href="{{url(($row->createdFor)?$row->createdFor->back_id_image:'')}}" target="_blank">Back
                                    ID Image</a></td>
                            <td>{{$row->created_at}}</td>
                            <td>{{$row->is_paid}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination">

                    @if (isset($commissions) && $commissions->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $commissions->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $commissions->currentPage() + $interval;
                            if($to > $commissions->lastPage()){
                              $to = $commissions->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($commissions->currentPage() > 1)
                                <li>
                                    <a href="{{ $commissions->url(1)."&name=".app('request')->input('name')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $commissions->url($commissions->currentPage() - 1)."&name=".app('request')->input('name') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $commissions->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a href="{{ !$isCurrentPage ? $commissions->url($i)."&name=".app('request')->input('name') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($commissions->currentPage() < $commissions->lastPage())
                                <li>
                                    <a href="{{ $commissions->url($commissions->currentPage() + 1)."&name=".app('request')->input('name') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $commissions->url($commissions->lastpage())."&name=".app('request')->input('name') }}" aria-label="Last">
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


            $('#select-all').click(function () {
                var checked = this.checked;
                $('input[type="checkbox"]').each(function () {
                    this.checked = checked;
                });
            })
        </script>
    @endpush
@endsection
