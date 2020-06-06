@extends('layouts.user-frontend.user-dashboard')
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
                            <th>Traded Date</th>
                            <th>Transaction ID</th>
                            <th>Product Old Price</th>
                            <th>Product New Price</th>
                            <th>Gain/Loss</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php $i=1;@endphp
                        @foreach($trade_histories as $trade_history)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ date('d-F-Y h:i A',strtotime($trade_history->created_at))  }}</td>
                                <td>{{ $trade_history->purchase_trx_id }}</td>
                                <td>{{ $trade_history->trade->old_price }}</td>
                                <td>{{ $trade_history->trade->new_price }}</td>
                                <td>{{ round($trade_history->gain_loss, $basic->deci) }} {{ $basic->currency }}</td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div><!-- ROW-->



@endsection
