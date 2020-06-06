@extends('layouts.user-frontend.user-dashboard')

@section('style')
    <style>
        .product-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }
        .portlet.light > .portlet-title > .caption > .caption-subject {
            font-size: 20px;
        }
        .portlet.light > .portlet-title {
            min-height: 30px;
        }
        .portlet.light > .portlet-title > .caption {
            padding: 0;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h3 class="page_title">{!! $page_title  !!} </h3>
            <hr>
        </div>
    </div>

    <div class="row">
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
                        {{ $product->description }}
                    </div>

                    <div class="product-price">
                        <span>Price:    {{ $product->price }} {{ $basic->currency }}</span>
                    </div>
                    <br>
                    <a href="{{ route('buy-product') }}" class="btn btn-primary bold uppercase btn-block btn-icon icon-left">
                        <i class="fa fa-arrow-left"></i> Go Back To Previous Page
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="portlet light bordered product-item">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-blue-ebonyclay bold uppercase">Your Current Balance: {{ round(Auth::user()->balance, $basic->deci) }}</span>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <h4><strong>Note:</strong></h4>
                            <div class="well">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam commodi ducimus ipsum porro quod, ratione voluptatem. Aperiam deserunt eum impedit perferendis ratione. Accusantium dolorem enim, id impedit natus suscipit voluptatem.
                            </div>

                            <button {{ round(Auth::user()->balance, $basic->deci) < round($product->price, $basic->deci) ? 'disabled' : '' }} class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#BuyConfirmModal">Buy Product</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="BuyConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title bold uppercase" id="myModalLabel"> <i class='fa fa-exclamation-triangle'></i> Confirmation</h4>
                </div>

                <div class="modal-body">
                    <strong>Are you sure you want to buy this product.?</strong>
                </div>

                <div class="modal-footer">
                    <form method="post" action="{{ route('product-buy-submit') }}" class="form-inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="amount" class="abir_id" value="{{ $product->price }}">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <button type="button" class="btn btn-danger bold uppercase" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        <button type="submit" class="btn btn-success bold uppercase"><i class="fa fa-check"></i> Yes I'm Sure!</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('script')

@endsection

