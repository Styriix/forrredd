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
                            <th>Product</th>
                            <th>Purchase Date</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Trade</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php $i = 1; @endphp
                        @foreach($purchase_histories as $purchase_history)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td><img src="{{ url('assets/images/products').'/'.$purchase_history->product->image }}" title=" {{ $purchase_history->product->name }} " alt="{{ $purchase_history->product->name }}" width="80px"></td>
                                <td>{{ date('d-F-Y h:i A',strtotime($purchase_history->created_at))  }}</td>
                                <td>{{ $purchase_history->transaction_id }}</td>
                                <td>{{ round($purchase_history->amount, $basic->deci) }} {{ $basic->currency }}</td>
                                <td>
                                    @if ($purchase_history->status == 1)
                                        <span class="btn btn-xs green-meadow"><i class="fa fa-check"></i> Open</span>
                                    @elseif($purchase_history->status == 0)
                                        <span class="btn btn-xs red-haze"><i class="fa fa-close"></i> Closed</span>
                                    @endif

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
