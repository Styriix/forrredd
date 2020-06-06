@extends('layouts.fontEnd')
@section('style')

    <link rel="stylesheet" href="{{ asset('assets/css/ion.rangeSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ranger-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ion.rangeSlider.skinFlat.css') }}">
    <style>
        .price-table {
            margin-bottom: 40px;
        }
        .our-plan.section-padding {
            padding: 0 0 100px 0;
        }
    </style>
@endsection
@section('content')
<section class="header-section ">
    <div class="head-slider">


        @foreach($slider as $s)
            <div class="single-header slider header-bg" style="background-image: url('{{ asset('assets/images/slider') }}/{{ $s->image }}')">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="header-slider-wrapper">
                                <h1>{{ $s->title }}</h1>
                                <p>{{ $s->subtitle }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</section><!--Header section end-->

<!--About community Section Start-->
<section class="section-padding about-community">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2>about - {{ $site_title }}</h2>
                    <p>{!! $page->about_subtitle !!}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p class="about-community-text text-right">
                    {!! $page->about_leftText !!}
                </p>
            </div>
            <div class="col-md-6">
                <p class="about-community-text">
                    {!! $page->about_rightText !!}
                </p>
            </div>
        </div>
    </div>
</section><!--About community Section end-->

<!--service section start-->
<section class="section-padding service-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center section-padding padding-bottom-0">
                    <h2>Services - {{ $site_title }}</h2>
                    <p>{!! $page->service_subtitle !!}</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($service as $s)
            <div class="col-md-3 col-sm-6">
                <div class="service-wrapper text-center">
                    <div class="service-icon ">
                        {!! $s->code !!}
                    </div>
                    <div class="service-title">
                        <p>{{ $s->title }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section><!--service section end-->


<!--Our Plan section start-->
<section class="section-padding our-plan">
    <div class="container">
        <div class="row section-padding padding-bottom-0">
            <div class="col-md-6 col-sm-6">
                <div class="contact-info">
                    <div class="contact-title">
                        <h4>Have a question <span>we are here to help!</span></h4>
                    </div>
                    <div class="contact-details">
                        <p><i class="fa fa-phone"></i> {{ $basic->phone }}</p>
                        <p><i class="fa fa-envelope"></i> {{ $basic->email }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="discunt-text">
                    <h3>{{ $basic->reference_percent }}<i class="fa fa-percent"></i> <br /> trade <br /> commission </h3>
                </div>
            </div>
        </div>
    </div>
</section><!--Our Plan section end-->

<!--Project Done so far start-->
<section class="completed-projcets section-padding">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-sm-6">
                <div class="happy-clients-box">
                    <div class="happy-clients-icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="happy-clients-text">
                        <h4 class="counter" data-count="{{ $total_user }}">0 </h4>
                        <p>Total User</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="happy-clients-box">
                    <div class="happy-clients-icon">
                        <i class="fa fa-suitcase"></i>
                    </div>
                    <div class="happy-clients-text">
                        <h4 class="counter" data-count="{{ $total_trade }}" >0</h4>
                        <p>Total Trade</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="happy-clients-box">
                    <div class="happy-clients-icon">
                        <i class="fa fa-cloud-download"></i>
                    </div>
                    <div class="happy-clients-text">
                        <h4 class="counter" data-count="{{ $total_deposit }}">0 </h4>
                        <p>Total Deposit</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="happy-clients-box">
                    <div class="happy-clients-icon">
                        <i class="fa fa-cloud-upload"></i>
                    </div>
                    <div class="happy-clients-text">
                        <h4 class="counter" data-count="{{ $total_withdraw }}" >0 </h4>
                        <p>Total Withdraw</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!--Project Done so far end-->

<!--Our Top Investor Section Start-->
<section class="section-padding top-investor">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2 class="color-text">Top Trades</h2>
                    <p>{{ $page->investor_subtitle }}</p>
                </div>
            </div>
        </div>
        <div class="row text-center">
            @foreach($top_trades as $key => $val)
            <div class="col-md-3 col-sm-6">
                <div class="single-investor-wrapper @if($key % 2 != 0) color-onvestor @endif ">
                    <h4>{{ \App\User::findOrFail($val->user_id)->name }}</h4>
                    <p>Trade: {{ $val->total_trade }}</p>
                </div>
            </div>

            {{--<div class="col-md-3 col-sm-6">
                <div class="single-investor-wrapper ">
                    <h4>Rifayet Islam</h4>
                    <p>$62554</p>
                </div>
            </div>--}}
            @endforeach
        </div>
    </div>
</section><!--Our Top Investor Section Start-->

<!--testimonial section start-->
<section class="section-padding  testimonial-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2 class="color-text">What People Say</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="slider-activation">
                    @foreach($testimonial as $tes)
                    <div class="testimonial-carousel">
                        <div class="single-testimonial-wrapper">
                            <div class="single-testimonial-top">
                                <div class="testimoanial-top-text">
                                    <div class="profile-pic">
                                        <img src="{{ asset('assets/images') }}/{{ $tes->image }}" class="img-circle img-responsive" alt="Client's Profile Pic">
                                    </div>
                                    <h4>{{ $tes->name }}<span>{{ $tes->position }}</span></h4>
                                </div>
                                <div class="testimonial-bottom">
                                    <p>{!! $tes->message !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section><!--testimonial section end-->

<!--Deopsit and Payouts section start-->
<section class="section-padding">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-6">
                <div class="deposit-table">
                    <div class="deposit-title">
                        <h4>Latest Deposits</h4>
                    </div>
                    <div class="deposit-body">
                        <table class="table main-table">

                            <tbody>
                            <tr class="head">
                                <th>Name</th>
                                <th>Date</th>
                                <th>Currency</th>
                                <th>Amount</th>
                            </tr>
                            @foreach($latest_deposit as $ld)
                            <tr>
                                <td>{{ $ld->member->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($ld->created_at)->format('M d,Y') }}</td>
                                <td><strong>{{ $basic->currency }}</strong></td>
                                <td><strong>{{ $basic->symbol }}{{ $ld->amount }}</strong></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="deposit-table">
                    <div class="deposit-title">
                        <h4>Latest Withdraw</h4>
                    </div>
                    <div class="deposit-body">
                        <table class="table main-table">

                            <tbody>
                            <tr class="head">
                                <th>Name</th>
                                <th>Date</th>
                                <th>Currency</th>
                                <th>Amount</th>
                            </tr>
                            @foreach($latest_withdraw as $ld)
                                <tr>
                                    <td>{{ $ld->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ld->created_at)->format('M d,Y') }}</td>
                                    <td><strong>{{ $basic->currency }}</strong></td>
                                    <td><strong>{{ $basic->symbol }}{{ $ld->amount }}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!--Deopsit and Payouts Section End-->


@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('assets/js/ion.rangeSlider.js') }}"></script>
    <script>
        $.each($('.slider-input'), function() {
            var $t = $(this),

                    from = $t.data('from'),
                    to = $t.data('to'),

                    $dailyProfit = $($t.data('dailyprofit')),
                    $totalProfit = $($t.data('totalprofit')),

                    $val = $($t.data('valuetag')),

                    perDay = $t.data('perday'),
                    perYear = $t.data('peryear');


            $t.ionRangeSlider({
                input_values_separator: ";",
                prefix: '{{ $basic->symbol }} ',
                hide_min_max: true,
                force_edges: true,
                onChange: function(val) {
                    $val.val( '{{ $basic->symbol }} ' + val.from);

                    var profit = (val.from * perDay / 100).toFixed(1);
                    profit  = '{{ $basic->symbol }} ' + profit.replace('.', '.') ;
                    $dailyProfit.text(profit) ;

                    profit = ( (val.from * perDay / 100)* perYear ).toFixed(1);
                    profit  =  '{{ $basic->symbol }} ' + profit.replace('.', '.');
                    $totalProfit.text(profit);

                }
            });
        });
        $('.invest-type__profit--val').on('change', function(e) {

            var slider = $($(this).data('slider')).data("ionRangeSlider");

            slider.update({
                from: $(this).val().replace('{{ $basic->symbol }} ', "")
            });
        })
    </script>
@endsection