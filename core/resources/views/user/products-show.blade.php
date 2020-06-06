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
                <div class="col-md-3">
                    <div class="sidebar_area">
                        <div class="list-group">
                            <a href="{{ route('buy-product') }}" class="list-group-item {{ request()->path() == 'user/buy-product' ? "active" : "" }}">All</a>
                            @foreach($product_categories as $product_category)
                                    <a href="{{ route('product-by-category', $product_category->id) }}" class="list-group-item {{ $current_category == $product_category->id ? 'active' : ''}}">{{ $product_category->title }}</a>
                                @endforeach
                            </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="products_area">
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-md-4">
                                    <div class="portlet light bordered product-item">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="caption-subject font-blue-ebonyclay bold uppercase">{{ $product->name }}</span>
                                            </div>
                                        </div>

                                        <div class="portlet-body">
                                            <img src="{{ url('assets/images/products'. '/'. $product->image) }}" alt="" class="img-responsive">
                                            <br>
                                            <div class="description">
                                                {{ the_excerpt($product->description, 5) }} <a href="{{ route('product-details', $product->id) }}" class="btn btn-xs blue">Read More</a>
                                            </div>

                                            <div class="product-price">
                                                <span>{{ $basic->currency }}: {{ $product->price }}</span>
                                            </div>

                                            <form method="POST" action="{{ route('product-buy-details') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button class="btn green-meadow btn-block btn-buy" type="submit">Buy Item</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

