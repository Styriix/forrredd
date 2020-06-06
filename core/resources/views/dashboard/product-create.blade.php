@extends('layouts.dashboard')
@section('style')

    <link href="{{ asset('assets/admin/css/bootstrap-toggle.min.css') }}" rel="stylesheet">


@endsection
@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="caption">
                        <div class="caption">
                            <strong class="uppercase"><i class="fa fa-plus"></i> {{ $page_title }}</strong>
                        </div>
                    </div>
                </div>

                <div class="panel-body" style="overflow: hidden">
                    {!! Form::open(['method'=>'post','class'=>'form-horizontal','files'=>true]) !!}
                    <div class="form-body">


                        <div class="row">

                            <div class="col-md-5">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Product Name</strong></label>
                                    <div class="col-sm-12">
                                        <input class="form-control input-lg bold" name="name" value="" required type="text" placeholder="Product Name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Product Price ({{ $basic->currency }})</strong></label>
                                    <div class="col-sm-12">
                                        <input class="form-control input-lg bold" name="price" value="" required type="text" placeholder="Product Price">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Select Category</strong></label>
                                    <div class="col-sm-12">
                                        <select class="form-control input-lg" name="category_id">
                                            <option value="" disabled selected>Select Product Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Product Image</strong></label>
                                    <div class="col-sm-12">
                                    <span class="btn green fileinput-button">
                                        <i class="fa fa-plus"></i>
                                        <span> Upload picture </span>
                                        <input class="form-control input-lg bold" name="image" value="" required type="file" >
                                    </span>
                                        <br> <br>
                                        {{--<div class="input-group mb15">--}}
                                        {{--<input class="form-control input-lg bold" name="image" value="" required type="file" >--}}
                                        {{--<span class="input-group-addon"><i class="fa fa-picture-o"></i></span>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>

                        </div>


                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;">Product
                                                    Description</strong></label>
                                            <div class="col-sm-12">
                                        <textarea name="description" rows="6"
                                                  class="form-control bold input-lg" required
                                                  placeholder="Description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12"><strong
                                                        style="text-transform: uppercase;">Status </strong></label>
                                            <div class="col-sm-12">
                                                <input data-toggle="toggle" checked data-onstyle="success"
                                                       data-offstyle="danger" data-width="100%" data-size="large"
                                                       type="checkbox" name="status">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> Add Product</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    @if (session('success'))

        <script type="text/javascript">

            $(document).ready(function(){

                swal("Success!", "{!! session('success') !!}", "success");

            });

        </script>

    @endif

    @if (session('alert'))

        <script type="text/javascript">

            $(document).ready(function(){

                swal("Sorry!", "{!! session('alert') !!}", "error");

            });

        </script>

    @endif
    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>

@endsection