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
                            <th>Product</th>
                            <th>Old price</th>
                            <th>New Price</th>
                            <th>Total Traded</th>
                            <th>Gain/Loss</th>
                            <th>Traded At</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php $i=1;@endphp
                        @foreach($trades as $trade)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td><img src="{{ url('assets/images/products').'/'.$trade->product->image }}" alt="{{ $trade->product->name }}" width="80px"></td>
                                <td>{{ $trade->old_price  }} {{ $basic->currency }}</td>
                                <td>{{ $trade->new_price  }} {{ $basic->currency }}</td>
                                <td>{{ $trade->userTrades()->count()  }}</td>
                                <td>{{ $trade->gain_loss < 0 ? "Loss" : "Earn"   }} : {{ abs($trade->gain_loss) }} {{ $basic->currency }}</td>
                                <td>{{ date('d-F-Y h:i A',strtotime($trade->created_at)) }}</td>
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
