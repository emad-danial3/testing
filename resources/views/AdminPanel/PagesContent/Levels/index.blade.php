@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('levels')}}">Levels</a></li>
                    </ol>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('AdminPanel.layouts.messages')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                        <form method="get" action="{{route('levels.ExportSheet')}}">
                            <button type="submit" class="btn btn-primary">Export Sheet</button>
                        </form>
                    <br>
                </div>
            </div>
            <form method="get" action="{{route('levels')}}">
                <div class="row">
                    <div class="form-group col-2">
                            <div class="input-group-prepend">
                                <input type="text" id="searchtext" placeholder="Enter Parent ID" name="name" class="form-control">
                            </div>
                    </div>
                    <div class="form-group col-3">
                            <div class="input-group-prepend">
                                <input type="email" id="searchtext" placeholder="Enter Parent Email" name="email" class="form-control">
                            </div>
                    </div>
                    <div class="form-group col-3">
                            <div class="input-group-prepend">
                                <input type="text" id="searchtext" placeholder="Enter Parent Name" name="user_name" class="form-control">
                            </div>
                    </div>
                    <div class="form-group col-2">
                            <div class="input-group-prepend">
                                <input type="text" id="searchtext" placeholder="Enter Child Name" name="child_name" class="form-control">
                            </div>
                    </div>
                    <div class="form-group col-2">
                        <div class="input-group">
                            <button type="submit" class="btn btn-info" class="form-control">Search</button>
                        </div>
                    </div>

                </div>

            </form>
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x:scroll">
            @if(count($levels) > 0)
                <table id="commissionsTable"  style="width: 100%" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th> <input type="checkbox" id="select-all"></th>
                        <th>Parent ID</th>
                        <th>Parent</th>
                        <th>Level</th>
                        <th>Child</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach($levels as $row)
                        <tr>
                            <td><input type="checkbox" name="checkbox[]" value="{{$row->id}}"/></td>
                            <td >{{$row->parent->id}}</td>
                            <td >{{($row->parent)?$row->parent->full_name:''}}</td>
                            <td >{{$row->level}}</td>
                            <td >{{($row->child)?$row->child->full_name:''}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th> <input type="checkbox" id="select-all"></th>
                        <th>Parent ID</th>
                        <th>Parent</th>
                        <th>Level</th>
                        <th>Child</th>
                    </tr>
                    </tfoot>
                </table>
            <br>
                <div class="pagination custom-pagination">

                    @if (isset($levels) && $levels->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $levels->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $levels->currentPage() + $interval;
                            if($to > $levels->lastPage()){
                              $to = $levels->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($levels->currentPage() > 1)
                                <li >
                                    <a href="{{ $levels->url(1)."&name=".app('request')->input('name')."&email=".app('request')->input('email')."&user_name=".app('request')->input('user_name')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li >
                                    <a href="{{ $levels->url($levels->currentPage() - 1)."&name=".app('request')->input('name')."&email=".app('request')->input('email')."&user_name=".app('request')->input('user_name') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $levels->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}">
                                    <a href="{{ !$isCurrentPage ? $levels->url($i)."&name=".app('request')->input('name')."&email=".app('request')->input('email')."&user_name=".app('request')->input('user_name') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($levels->currentPage() < $levels->lastPage())
                                <li >
                                    <a href="{{ $levels->url($levels->currentPage() + 1)."&name=".app('request')->input('name')."&email=".app('request')->input('email')."&user_name=".app('request')->input('user_name') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $levels->url($levels->lastpage())."&name=".app('request')->input('name')."&email=".app('request')->input('email')."&user_name=".app('request')->input('user_name') }}" aria-label="Last">
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


            $('#select-all').click(function() {
                var checked = this.checked;
                $('input[type="checkbox"]').each(function() {
                    this.checked = checked;
                });
            })
        </script>
    @endpush
@endsection
