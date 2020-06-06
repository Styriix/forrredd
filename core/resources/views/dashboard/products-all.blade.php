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
                    <table class="table table-striped table-bordered table-hover" id="sample_1">

                        <thead>
                        <tr>
                            <th>ID#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Current Trades</th>
                            <th>Status</th>
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
                                <td>{{ $product->price }} {{ $basic->currency }}</td>
                                <td>{{ $product->purchasesActive()->count() }}</td>
                                <td>
                                    @if($product->status == 1 )
                                        <span class="btn btn-xs green-meadow"><i class="fa fa-check"></i> Active</span>
                                    @elseif($product->status == 0)
                                        <span class="btn btn-xs red-sunglo"><i class="fa fa-times"></i> Deactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('product-edit',$product->id) }}" class="btn green-jungle btn-sm bold uppercase"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div><!-- ROW-->

@endsection
@section('scripts')

    <script>
        $(document).ready(function () {

            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                $(".abir_id").val(id);

            });

        });
        $(document).ready(function () {

            $(document).on("click", '.refund_button', function (e) {
                var id = $(this).data('id');
                $(".abir_id").val(id);

            });

        });
    </script>

@endsection
