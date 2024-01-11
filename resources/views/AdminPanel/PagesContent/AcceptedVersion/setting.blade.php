@extends('AdminPanel.layouts.main')
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('adminDashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('accountTypes.index')}}">Setting</a></li>
                        <li class="breadcrumb-item active">{{isset($setting)?'Edit / '.$setting->id :'ADD'}}</li>
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
                        <form  action="{{(isset($setting))?route('updateSetting'):route('updateSetting')}}" method="post" enctype="multipart/form-data">
                            @include('AdminPanel.layouts.messages')
                            @csrf
                            {{isset($setting)?method_field('POST'):''}}

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="show_wallet">platform</label>
                                    <select class="form-control" type="" name="show_wallet">
                                        <option @if(old('show_wallet') && old('show_wallet') == "0"){{"selected"}}@elseif(isset($setting->show_wallet)&& $setting->show_wallet == "0"){{"selected"}}@endif value="0">No</option>
                                        <option @if(old('show_wallet') && old('show_wallet') == "1"){{"selected"}}@elseif(isset($setting->show_wallet)&& $setting->show_wallet == "1"){{"selected"}}@endif value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="show_welcome_programme">platform</label>
                                    <select class="form-control" type="" name="show_welcome_programme">
                                        <option @if(old('show_welcome_programme') && old('show_welcome_programme') == "0"){{"selected"}}@elseif(isset($setting->show_welcome_programme)&& $setting->show_welcome_programme == "0"){{"selected"}}@endif value="0">No</option>
                                        <option @if(old('show_welcome_programme') && old('show_welcome_programme') == "1"){{"selected"}}@elseif(isset($setting->show_welcome_programme)&& $setting->show_welcome_programme == "1"){{"selected"}}@endif value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="show_fawry_payemnt">platform</label>
                                    <select class="form-control" type="" name="show_fawry_payemnt">
                                        <option @if(old('show_fawry_payemnt') && old('show_fawry_payemnt') == "0"){{"selected"}}@elseif(isset($setting->show_fawry_payemnt)&& $setting->show_fawry_payemnt == "0"){{"selected"}}@endif value="0">No</option>
                                        <option @if(old('show_fawry_payemnt') && old('show_fawry_payemnt') == "1"){{"selected"}}@elseif(isset($setting->show_fawry_payemnt)&& $setting->show_fawry_payemnt == "1"){{"selected"}}@endif value="1">Yes</option>
                                    </select>
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
        </div><!-- /.container-fluid -->
    </section>
@endsection
