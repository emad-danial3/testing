@extends('AdminPanel.layouts.main')
@section('content')
<style>
    #refresh {
        margin-top:30px;
    }
    .btn-group .btn{
        margin:0 3px;
    }
    .table-striped tbody tr:nth-of-type(odd).invalid {
    background-color: rgba(139, 0, 0,.09);
}
  .table-striped tbody tr:nth-of-type(even).invalid {
    background-color: rgba(139, 0 ,0,.07);
}
  .fa-info-circle
  {
    cursor: pointer;
    margin: 0 10px;
    
}
</style>
 <link rel="stylesheet" href="{{url('dashboard')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('oracleInvoices.all_view')}}">Invoices</a></li>
                    </ol>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('AdminPanel.layouts.messages')

    <div class="card">
       
        <div class="card-body pb-0">
            <div class="row">
                
           <div class="form-group col-3">
               <label class="form-label">From (order date)</label>
               <input class="form-control" id="fromInput" type="date" name="from" value="{{$from}}">
           </div>
           <div class="form-group col-3">
               <label class="form-label">To (order date) </label>
               <input class="form-control" id="toInput" type="date" name="to" value="{{$to}}">
           </div>
           <div class="form-group col-3">
               <label class="form-label">payment</label>
              <select class="form-control" id="wallet_status" name="wallet_status">
                <option value=''>all</option>
                  <option value="cash">cash</option>
                  <option value="only_fawry">fawry</option>
              </select>
           </div>
            <div class="form-group col-3">
               <label class="form-label">valid</label>
              <select  class="form-control" id="check_valid" name="check_valid">
                  <option value=''>all</option>
                  <option value="valid">valid</option>
                  <option value="notvalid">not valid</option>
              </select>
           </div>
            <div class="form-group col-2">
               <label class="form-label">Or by Order ID </label>
               <input class="form-control" id="order" type="text" name="order" >
           </div>
            <div class="form-group col-1" style="margin-top: 32px;">
               <input title ="connect with old invoice number" class="form-control" id="old_link" type="checkbox" name="old_link" >
           </div>

           <div class="col-2">
               
           <button class="btn btn-primary" id="refresh" type="button">Refresh</button>
           </div>
        </div>
        </div>

        <!-- /.card-header -->
        <div class="card-body" style="overflow-x:scroll">
           
                <table id="invoices_table" style="width: 100%" class="display table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>order id</th>
                        <th>oracle id</th>
                        <th>payment type</th>
                        <th>payment code</th>
                        <th>4u total order</th>
                        <th>oracle total order</th>
                        <th>4u total - oracle total EXCEL</th>
                        <th>4u total - oracle total</th>
                        <th>delivery status</th>
                        <th>created (oracle invoice)</th>
                        <th> system last updated date on oracle invoice</th>
                        <th>is valid</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="modal fade" id="stationsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="modal-title">Delivery Stations ( <span class="order_id"></span> )</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body" id="stationsContainer">
    <!-- Stations will be displayed here -->
    </div>
    </div>
    </div>
    </div>
    @push('scripts')
    <script src="{{url('dashboard')}}/plugins/datatables-buttons/js/dataTables.buttons.js"></script>
    <script src="{{url('dashboard')}}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{url('dashboard')}}/plugins/datatables-buttons/js/buttons.html5.min.js"></script> 
    <script>

    $(document).ready(function () {
        var dataTable ='' ;
     draw_table();
      $('#refresh').on('click', function () {
            draw_table();
        });
      function draw_table()
      {
       if(dataTable)  dataTable.destroy();
        var check_valid =  $('#check_valid').val() ;
        var wallet_status =  $('#wallet_status').val() ;
        var order =  $('#order').val() ;
        var from =  $('#fromInput').val() ;
        var to =  $('#toInput').val() ;
        var old_link =  $('#old_link').is(':checked') ;
        var url ="{{ route('oracleInvoices.all_data')}}?params" ;
        if(from)  url +='&from='+from ;
        if(to)  url +='&to='+to ;
        if(check_valid)  url +='&check_valid='+check_valid ;
        if(wallet_status)  url +='&wallet_status='+wallet_status ;
        if(order)  url +='&id='+order ;
        if(old_link)  url +='&invoice_link=1' ;
        
        
           dataTable =    $('#invoices_table').DataTable({
            responsive: true,
                dom: 'Bfrtip',
                lengthMenu: [
                [ 10, 50, 100, -1 ],
                [ '10', '50', '100', 'Show all' ]
                ],
                buttons: [
                    'pageLength',
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            modifier: {
                            page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'Export',
                        exportOptions: {
                            modifier: {
                            page: 'all'
                            },
                        columns: [0,1,2,3,4,5,6,8,9,10]
                        }
                    }
                ],
                processing: true,
                serverSide: false,
                ajax: {
                url: url,
                },
                columns: [
                    { data: 'web_order_number', name: 'web_order_number' },
                    { data: 'oracle_invoice_number', name: 'oracle_invoice_number' },
                    { data: 'order_header.wallet_status', name: 'wallet_status' },
                    { data: 'order_header.payment_code', name: 'order_header.payment_code',visible: false },
                    { data: 'order_header.total_order', name: 'order_header.total_order' ,visible: false},
                    { data: 'order_amount', name: 'order_amount' ,visible: false},
                    { data: 'differnce', name: 'differnce' ,visible: false},
                    { data: 'order_amount', name: 'order_amount' },
                    { data: 'order_header.delivery_status', name: 'delivery_status' },
                    { data: 'human_created_at', name: 'created_at' },
                    { data: 'human_updated_at', name: 'updated_at' },
                    { data: 'check_valid', name: 'check_valid' ,visible: false},
                ], createdRow: function (row, data, dataIndex) 
                {
                    if (data.check_valid !== 'valid') {
                    $(row).addClass('invalid');
                     $('td:eq(3)', row).html( '<span title="4u">' + data.order_header.total_order + '</span>  -  <span title="oracle">'+ data.order_amount +'</span> = ' + Math.round( data.order_header.total_order -  data.order_amount )  );
                    }
                    if (data.order_header.wallet_status !== 'cash') {
                       $('td:eq(2)', row).html('<span  class="badge badge-success">Fawry </span> '+ data.order_header.payment_code);
                    }
                     if (data.order_header.delivery_status) {

                       $('td:eq(4)', row).html( data.order_header.delivery_status +'<i title ="show detailes" onclick=show_delivery_stations("'+ data.order_header.id+'")  class="fas fa-info-circle"> </i> ');
                    }
                }
                
        });
      }
    });
    function show_delivery_stations(id)
    {
        $('#modal-title .order_id').html(id);
        var url = "{{ route('get_order_delivery_stations')}}" ;
        url+='?order_id=' + id ;
          $.ajax({
            url: url ,
            method: 'GET',
            success: function(data) {
                var stations  ;
                if(data)
                {
                     stations = JSON.parse(data);
                }
                displayStations(stations);
               
                $('#stationsModal').modal('show');
            },
            error: function(err) {
                console.error('Error fetching delivery stations:', err);
            }
        });
        
    }
    function displayStations(stations) {

        const stationsContainer = $('#stationsContainer');
        stationsContainer.empty(); // Clear previous content

        // Loop through stations and append to the modal body
        stations.forEach(function(station, index) {
            const stationElement = $('<p>').text('Station ' + (index + 1) + ': ' + station.status +'  '+station.status_time);
            stationsContainer.append(stationElement);
        });
    }
    </script>

    @endpush
@endsection
