@extends('AdminPanel.layouts.main')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('registerLinks.index')}}">Generate Links</a></li>
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

            <form action="{{route('registerLinks.generateLinks')}}" method="post">
                @csrf
                <div class="form-group">
                    <input type="number" placeholder="Number for Free Account" name="number">
                </div>
                <div class="form-group">
                    <select name="user_id">
                        @foreach($users as  $userid)
                            <option  class="form-control" value="{{$userid->id}}">{{$userid->full_name}}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-success">Generate</button>
            </form>
        </div>
    </div>

    <div class="card">

        <!-- /.card-header -->
        <div class="card-body">
            @if(count($registerLinks) > 0)
                <table id="areasTable"  class="table table-bordered table-striped" >
                    <thead>
                    <tr>
                        <th> <input type="checkbox" id="select-all"> </th>
                        <th>Link</th>
                        <th>Name</th>
                        <th>Copy</th>
                    </tr>
                    </thead>
                    <tbody>
                    <button type="button" onclick="deleteSeleted()"  class="btn btn-danger mb-4" style="margin-right: 20px">Delete Selected
                    </button>

                    <button type="button"  onclick="exportSeleted()"  class="btn btn-dark mb-4 ">Export Selected
                    </button>
                    @foreach($registerLinks as $row)
                        <tr>
                            <td><input type="checkbox" name="checkbox[]" value="{{$row->id}}"/></td>
                            <td width="150"><a href="{{url($row->link)}}"  target="_blank">{{url($row->link)}}</a></td>
                            <td>{{$row->full_name}}</td>
                            <td>
                                <button class="btn btn-success" type="button" id="myTooltip" onclick="myFunction('{{url($row->link)}}')">
                                  Copy
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Link</th>
                        <th>Name</th>
                        <th>Copy</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="pagination">

                    @if (isset($registerLinks) && $registerLinks->lastPage() > 1)
                        <ul class="pagination">
                        @php
                            $interval = isset($interval) ? abs(intval($interval)) : 3 ;
                            $from = $registerLinks->currentPage() - $interval;
                            if($from < 1){
                              $from = 1;
                            }

                            $to = $registerLinks->currentPage() + $interval;
                            if($to > $registerLinks->lastPage()){
                              $to = $registerLinks->lastPage();
                            }
                        @endphp
                        <!-- first/previous -->
                            @if($registerLinks->currentPage() > 1)
                                <li>
                                    <a href="{{ $registerLinks->url(1)."&name=".app('request')->input('name')}}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $registerLinks->url($registerLinks->currentPage() - 1)."&name=".app('request')->input('name') }}" aria-label="Previous">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            @endif
                        <!-- links -->
                            @for($i = $from; $i <= $to; $i++)
                                @php
                                    $isCurrentPage = $registerLinks->currentPage() == $i;
                                @endphp
                                <li class="{{ $isCurrentPage ? 'active' : '' }}" style="padding: 5px">
                                    <a href="{{ !$isCurrentPage ? $registerLinks->url($i)."&name=".app('request')->input('name') : '' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        <!-- next/last -->
                            @if($registerLinks->currentPage() < $registerLinks->lastPage())
                                <li>
                                    <a href="{{ $registerLinks->url($registerLinks->currentPage() + 1)."&name=".app('request')->input('name') }}" aria-label="Next">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $registerLinks->url($registerLinks->lastpage())."&name=".app('request')->input('name') }}" aria-label="Last">
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

        function myFunction(link) {
            navigator.clipboard.writeText(link);
        }

        function deleteSeleted()
        {
            var ids =[];
            $('input[type="checkbox"]').each(function() {
                if (this.checked)
                {
                    ids.push(this.value);
                }
            });

              window.location.href = "{{route('registerLinks.deleteLinks')}}?links_ids="+ids;
        }

        function exportSeleted()
        {
            var ids =[];
            $('input[type="checkbox"]').each(function() {
                if (this.checked)
                {
                    ids.push(this.value);
                }
            });

            window.location.href = "{{route('registerLinks.exportLinksSheet')}}?links_ids="+ids;
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
