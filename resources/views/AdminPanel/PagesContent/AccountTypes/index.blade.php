@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('accountTypes.index')}}">Account Types</a></li>
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
            <h3 class="card-title float-right">
                <a class="btn btn-warning" href="{{route('accountTypes.create')}}">Create New Type</a>
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if(count($types) > 0)
                <table id="areasTable"  class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name EN</th>
                        <th>Name Ar</th>
                        <th>Amount</th>
                        <th>Is Available</th>
                        <th>Minimum Required</th>
                        <th>Delivery Fees</th>
                        <th>Commissions Level</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($types as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td width="150">{{$row->name_en}}</td>
                            <td width="150">{{$row->name_ar}}</td>
                            <td width="150">{{$row->amount}} </td>
                            <td width="150">{{$row->is_available}}</td>
                            <td width="150">{{$row->min_required}}</td>
                            <td width="150">{{$row->delivery_fees}}</td>
                            <td>
                                <table id="areasTable"  class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Level</th>
                                        <th>Commission</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($row->AccountCommissionLevels as $element)
                                        <tr>
                                            <td>{{$element->level}}</td>
                                            <td>{{$element->commission}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <a class="btn btn-dark" href="{{route('accountTypes.edit',$row)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <div class="pagination">
{{--                @links()--}}
                </div>

            @else
                <h1 class="text-center">NO DATA</h1>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
@endsection
