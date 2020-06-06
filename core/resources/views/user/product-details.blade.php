@extends('layouts.user-frontend.user-dashboard')

@section('style')
    <style>
        .btn-buy {
            margin-top: 15px;
        }
        .product-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        .portlet.light > .portlet-title > .caption > .caption-subject {
            font-size: 15px;
        }
        .portlet.light > .portlet-title {
            min-height: 30px;
        }
        .portlet.light > .portlet-title > .caption {
            padding: 0;
        }
        .product-item .description{
            max-height: 50px;
            min-height: 50px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page_title">{!! $page_title  !!} </h3>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="products_area">
                        <div class="portlet light bordered product-item">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject font-blue-ebonyclay bold uppercase">{{ $product->name }}</span>
                                </div>
                            </div>

                            <div class="portlet-body">
                                <img src="{{ url('assets/images/products'. '/'. $product->image) }}" alt="{{ $product->name }}" class="img-responsive center-block">
                                <br>
                                <div class="description">
                                    {{ $product->description }}
                                </div>

                                <div class="product-price">
                                    <span>{{ $basic->currency }}: {{ $product->price }}</span>
                                </div>

                                <form method="POST" action="{{ route('product-buy-details') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!---ROW-->

@endsection
@section('script')

    @if (session('success'))
        <script type="text/javascript">
            $(document).ready(function(){

                swal("Success!", "{{ session('success') }}", "success");

            });
        </script>

    @endif



    @if (session('alert'))

        <script type="text/javascript">
            $(document).ready(function(){
                swal("Sorry!", "{{ session('alert') }}", "error");
            });

        </script>

    @endif

@endsection

