@extends('layouts.dashboard')

@section('style')
    <style>
        body.stop-scrolling {
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="caption">
                        <button class="btn green-meadow" id="btn_add_category">
                            <i class="fa fa-plus"></i> Add New Category
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover" id="table_categories">
                            {{--<colgroup>--}}
                                {{--<col class="col-xs-1">--}}
                                {{--<col class="col-xs-1">--}}
                                {{--<col class="col-xs-7">--}}
                            {{--</colgroup>--}}
                            <thead>
                                <tr>
                                    <th> Sl No. </th>
                                    <th> Category Title </th>
                                    <th> Actions </th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @foreach($product_categories as $product_category)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td> {{ $product_category->title }} </td>
                                    <td>
                                        <button id="btn_edit_category" class="btn btn-sm green-jungle bold uppercase" value="{{$product_category->id}}"><i class="fa fa-edit"></i> Edit</button>
                                        {{--<button id="btn_delete_category" class="btn btn-sm red bold uppercase" value="{{$product_category->id}}"><i class="fa fa-trash-o"></i> Delete</button>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title bold uppercase" id="category_modal_label"><i class="fa fa-th-list"></i> Manage Category</h4>
                </div>
                <div class="modal-body">
                    <form id="frmCategories" novalidate="" class="clearfix">
                        <div class="form-group error">
                            <label for="category_title" class="control-label bold uppercase">Category Title : </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                                <input type="text" class="form-control has-error bold " id="category_title" name="title" placeholder="Category Title" value="" required>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn green-meadow bold uppercase" id="btn_category_save" value="add"><i class="fa fa-send"></i> Save Category</button>
                    <input type="hidden" id="category_id" name="category_id" value="1">
                </div>
            </div>
        </div>
    </div>

    <meta name="_token" content="{!! csrf_token() !!}" />
    <!-- Modal for DELETE -->

@endsection
@section('scripts')
    @if (session('alert'))

        <script type="text/javascript">

            $(document).ready(function(){

                swal("Sorry!", "{!! session('alert') !!}", "error");

            });

        </script>

    @endif



    <script>
        $(document).ready(function () {

            //Add category
            $("#btn_add_category").on('click', function () {
                $("#category_modal_label").html("<i class='fa fa-th-list'></i> Add Category");
                $("#category_title").val("");
                $("#category_modal").modal('show');
            });

            // creates or updates category
            $("#btn_category_save").on('click', function () {
                var categoryTitle = $("#category_title").val();
                var url  = '{{ url('admin/product-category-create') }}';
                var type = 'post';

                if ($(this).val() == 'update') {
                    url  = '{{ url('admin/product-categories-edit').'/' }}' + $("#category_id").val();
                    type = 'put';
                }
                console.log(url);
                $.ajax({

                    type: type,
                     url: url,
                dataType: 'json',

                    data: {
                        'title' : categoryTitle,
                        '_token': $('meta[name="_token"]').attr('content')
                    },

                    success: function (result) {
                        $('#table_categories').load(location.href + ' #table_categories > *');
                        $("#category_modal").modal('hide');
                        swal({
                            title: "Success!",
                             text: "Done!",
                             type: "success"
                        });
                    },

                    error: function (error) {
                        console.log(error.responseText);
                        var message = JSON.parse(error.responseText);
                        swal("Sorry!", message.title, "error");
                    }

                });
            });

            $(document).on("click", "#btn_edit_category", function () {
                //setting value to determine whether update needed
                $("#category_id").val($(this).val());
                $("#btn_category_save").val("update");
                $("#category_modal_label").html("<i class='fa fa-edit'></i> Edit Category");
                $("#category_title").val($(this).parent().prev().text());
                $("#category_modal").modal('show');
            });
        });
    </script>

@endsection