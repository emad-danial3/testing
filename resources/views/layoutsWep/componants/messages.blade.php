
@if((isset ($errors) && count($errors) > 0) || Session::get('success', false)  || Session::get('message', false))
    <!-- Banner Section Start -->
    <section class="banner-section ratio_60 wow fadeInUp">
        <div class="container-fluid">
            <div class="banner-slider">
                @if(isset ($errors) && count($errors) > 0)
                    <div class="alert alert-danger text-center" role="alert">
                        <ul class="list-unstyled mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(Session::get('success', false))
                    <?php $data = Session::get('success'); ?>
                    @if (is_array($data))
                        @foreach ($data as $msg)
                            <div class="alert alert-success text-center" role="alert">
                                <i class="fa fa-check"></i>
                                {{ $msg }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closebtn">
                                                <span aria-hidden="true">&times;</span>
                                 </button>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-success text-center" role="alert">
                            <i class="fa fa-check"></i>
                            {{ $data }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closebtn">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                @endif
                @if(Session::get('message', false) && !(Session::get('success', false)))
                    <?php $data = Session::get('message'); ?>
                    @if (is_array($data))
                        @foreach ($data as $msg)
                            <div class="alert alert-info text-center" role="alert">
                                <i class="fa fa-check"></i>
                                {{ $msg }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closebtn">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fa fa-check"></i>
                            {{ $data }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closebtn">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>
    <!-- Banner Section End -->
@endif
