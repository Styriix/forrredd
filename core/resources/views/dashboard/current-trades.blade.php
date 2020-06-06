@extends('layouts.dashboard')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                    </div>
                    <div class="tools"> </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover ajax_reload" id="sample_1">
                        <thead>
                        <tr>
                            <th>ID#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Current Trades</th>
                            {{--<th>Status</th>--}}
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php $i=1;@endphp
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $product->name  }}</td>
                                <td><img src="{{ url('assets/images/products').'/'.$product->image }}" alt="{{ $product->name }}" width="80px"></td>
                                <td>{{ $product->category->title }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->purchasesActive()->count() }}</td>
                                {{--<td>--}}
                                    {{--@if($product->status == 0 )--}}
                                        {{--<span class="btn btn-xs red-sunglo"><i class="fa fa-times"></i> Deactive</span>--}}
                                    {{--@elseif($product->status == 1)--}}
                                        {{--<span class="btn btn-xs green-meadow"><i class="fa fa-check"></i> Active</span>--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                                <td>
                                    <input type="hidden" id="product_price" value="{{ $product->price }}">
                                    <button id="btn_edit_price" class="btn btn-sm green-jungle bold uppercase" value="{{ $product->id }}"><i class="fa fa-edit"></i> CHANGE PRICE</button>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div><!-- ROW-->


    <div class="modal fade" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title bold uppercase" id="category_modal_label"><i class="fa fa-th-list"></i> Change Price</h4>
                </div>
                <div class="modal-body">
                    <form id="frmPoducts" novalidate="" class="clearfix">
                        <div class="form-group error">
                            <label for="category_title" class="control-label bold uppercase">Product Price ({{ $basic->currency }}): </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                                <input type="text" class="form-control has-error bold " id="input_product_price" name="price"  value="" required>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn green-meadow bold uppercase" id="btn_save" value="add"><i class="fa fa-send"></i> Save</button>
                    <input type="hidden" id="product_id" name="product_id" value="1">
                </div>
            </div>
        </div>
    </div>
    <meta name="_token" content="{!! csrf_token() !!}" />

@endsection
@section('scripts')

    <script>
        $(document).ready(function () {
            //Add category
            $(document).on('click', "#btn_edit_price", function () {
                var product_price = $(this).prev('input').val();
                $("#product_id").val($(this).val());
                $("#input_product_price").val(product_price);
                $("#category_modal").modal('show');
            });

            $("#btn_save").on('click', function () {
                var productPrice = $("#input_product_price").val();
                var url  = '{{ url('admin/product-price/edit/').'/' }}' + $("#product_id").val();
                var type = 'put';
                console.log(url);
                $.ajax({

                    type: type,
                    url: url,
//                    dataType: 'json',

                    data: {
                        'price' : productPrice,
                        '_token': $('meta[name="_token"]').attr('content')
                    },

                    success: function (result) {
                        $('.ajax_reload').load(location.href + ' .ajax_reload > *');
                        $("#category_modal").modal('hide');
                        swal({
                            title: "Success!",
                            text: "Done!",
                            type: "success"
                        });
                    },

                    error: function (error) {
                        console.log(error.status);
                        var message = JSON.parse(error.responseText);
                        swal("Sorry!", message.title, "error");
                    }

                });
            });
        });
    </script>

@endsection
