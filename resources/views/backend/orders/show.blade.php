@extends('backend.layouts.app')

@section('content')
<style>
    .submt_adv_pay, .submt_shipment_cost{
        padding-top: 11px;
        padding-bottom: 11px;
        padding-right: 2px;
        padding-left: 2px;
        border-left-width: 0px;
        border-right-width: 0px;
        border-top-width: 0px;
        border-bottom-width: 0px;
        background-color: #6c50e1;
        border-radius: 5px;
        font-weight: bold;
        color: #ffffff;
    }
    
    
     
</style>
    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-18 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-header">
            <div class="flex-grow-1 row">
                <div class="col-md mb-3">
                    <div>
                        <div class="fs-15 fw-600 mb-2">{{ translate('Customer info') }}</div>
                        <div><span class="opacity-80 mr-2 ml-0">{{ translate('Name') }}:</span> {{ $order->user->name ?? '' }}</div>
                        <div><span class="opacity-80 mr-2 ml-0">{{ translate('Email') }}:</span> {{ $order->user->email ?? '' }}</div>
                        <div><span class="opacity-80 mr-2 ml-0">{{ translate('Phone') }}:</span> 
                            @if ($order->billing_address !== null  && !empty($order->billing_address))
                            @php
                            $user_info = json_decode($order->billing_address);
                            if(!empty($user_info->phone)){
                                echo $user_info->phone;
                            }else{
                                echo $order->user->phone;
                            }
                            @endphp
                            @else
                                {{ $order->user->phone}}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3 ml-auto mr-0 mb-3">
                    <p class="mb-0 pb-2">{{translate('Advance Payment')}}</p>
                    <div class="adv_pay_save_part">
                        <form action="">
                            <div class="d-flex flex-row ">
                                <div>
                                    <input type="hidden" id="order_id" value="{{$order->combined_order->code}}">
                                     <input type="number" id="advan_pay_val" value="" class="form-control">
                                </div>
                                 <div>
                                     <button class="submt_adv_pay" id="save_adv_pay">submit</button>
                                 </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3 ml-auto mr-0 mb-3">
                    <label>{{translate('Payment Status')}}</label>
                    <select class="form-control aiz-selectpicker" id="update_payment_status" data-minimum-results-for-search="Infinity" data-selected="{{ $order->payment_status }}">
                        <option value="paid">{{translate('Paid')}}</option>
                        <option value="unpaid">{{translate('Unpaid')}}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>{{translate('Delivery Status')}}</label>
                    <select class="form-control aiz-selectpicker" id="update_delivery_status" data-minimum-results-for-search="Infinity" data-selected="{{ $order->delivery_status }}">
                        <option value="confirmed">{{translate('Confirmed')}}</option>
                        <option value="processed">{{translate('Processed')}}</option>
                        <option value="shipped">{{translate('Shipped')}}</option>
                        <option value="delivered">{{translate('Delivered')}}</option>
                        <option value="cancelled">{{translate('Cancel')}}</option>
                    </select>
                </div>

                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Reason For Cancel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <form action="">
                                <textarea name="reason_cancel" class="form-control" required  id="order_cancel_val" cols="65" rows="10"></textarea>
                            </form>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save_order_cancel_cause">Submit</button>
                        </div>
                    </div>
                    </div>
                </div>
  
                <div class="col-md-3 ml-auto mr-0 mb-3">
                    <p class="mb-0 pb-2">{{translate('Shipment Cost')}}</p>
                    <div class="adv_pay_save_part">
                        <form action="">
                            <div class="d-flex flex-row ">
                                <div>
                                    <input type="hidden" id="combined_order_id" value="{{$order->combined_order->id}}">
                                     <input type="number" id="shipment_cost" value="" class="form-control">
                                </div>
                                 <div>
                                     <button class="submt_shipment_cost" id="save_shipment_cost">submit</button>
                                 </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>

        <div class="card-header">
            <div class="flex-grow-1 row align-items-start">
            <div class="col-md-auto w-md-250px">
                    @php
                        if($order->shipping_address !== null){
                            $shipping_address = json_decode($order->shipping_address);
                        } 
                    @endphp
                    <h5 class="fs-14 mb-3">{{ translate('Shipping address') }}</h5>
                    <address class="">
                    @if($order->shipping_address !== null)
                        {{ $shipping_address->phone }}<br>
                        {{ $shipping_address->address }}<br>
                        {{ $shipping_address->city }}, {{ $shipping_address->postal_code }}<br>
                        {{ $shipping_address->state }}, {{ $shipping_address->country }}
                    @else
                        @php if(!empty($user_info[0]->address_info)){echo $user_info[0]->address_info->address;} @endphp
                    @endif
                    </address>
                </div>
                <div class="col-md-auto w-md-250px">
                    @php
                    if($order->billing_address !== null){
                        $billing_address = json_decode($order->billing_address);
                    }
                    @endphp
                    <h5 class="fs-14 mb-3">{{ translate('Billing address') }}</h5>
                    <address class="">
                    @if($order->billing_address !== null)
                        {{ $billing_address->phone }}<br>
                        {{ $billing_address->address }}<br>
                        {{ $billing_address->city }}, {{ $billing_address->postal_code }}<br>
                        {{ $billing_address->state }}, {{ $billing_address->country }}
                    @else
                        @php if(!empty($user_info[0]->address_info)){echo $user_info[0]->address_info->address;} @endphp
                    @endif
                    </address>
                </div>
                <div class="col-md-4 col-xl-3 ml-auto mr-0">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td class="">{{translate('Order code')}}</td>
                            <td class="text-right text-info fw-700">{{ $order->combined_order->code }}</td>
                        </tr>
                        <tr>
                            <td class="">{{translate('Order Date')}}</td>
                            <td class="text-right fw-700">{{ $order->created_at->format('d.m.Y') }}</td>
                        </tr>
                        <tr>
                            <td class="">{{translate('Total Order')}}</td>
                            <td class="text-right fw-700">{{(!empty($total_user_order))? $total_user_order-1 : 0 }}</td>
                        </tr>
                        <tr>
                            <td class="">{{translate('Delivery type')}}</td>
                            <td class="text-right fw-700">
                                {{ ucfirst(str_replace('_', ' ', $order->delivery_type)) }}
                            </td>
                        </tr>
                        @if ($order->is_pos == 1)
                            @if (!empty($order->created_by))
                                <td class="">{{translate('Sold By')}}</td>
                                <td class="text-right text-info fw-700">{{ getUserSoldPersonName($order->created_by) }}</td>
                            @endif
                        @else
                            <td class="">{{translate('Order Type')}}</td>
                            <td class="text-right text-info fw-700">online</td>
                        @endif
                        <tr>
                            <td class="">{{translate('Payment method')}}</td>
                            <td class="text-right fw-700">{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
		</div>

    	<div class="card-body">
            <!-- <table class="aiz-table table-bordered">
                <thead>
                    <tr class="">
                        <th class="text-center" width="5%" data-breakpoints="lg">#</th>
                        <th width="40%">{{translate('Product')}}</th>
                        <th class="text-center" data-breakpoints="lg">{{translate('Qty')}}</th>
                        <th class="text-center" data-breakpoints="lg">{{translate('Unit Price')}}</th>
                        <th class="text-center" data-breakpoints="lg">{{translate('Unit Tax')}}</th>
                        <th class="text-center" data-breakpoints="lg">{{translate('Total')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                @if ($orderDetail->product != null)
                                <div class="media">
                                    <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}" class="size-60px mr-3">
                                    <div class="media-body">
                                        <h4 class="fs-14 fw-400">{{ $orderDetail->product->name }}</h4>
                                        @if($orderDetail->variation)
                                        <div>
                                            @foreach ($orderDetail->variation->combinations as $combination)
                                            <span class="mr-2">
                                                <span class="opacity-50">{{ $combination->attribute->name }}</span>:
                                                {{ $combination->attribute_value->name }}
                                            </span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @else
                                    <strong>{{ translate('Product Unavailable') }}</strong>
                                @endif
                            </td>
                            <td class="text-center">{{ $orderDetail->quantity }}</td>
                            <td class="text-center">{{ format_price($orderDetail->price) }}</td>
                            <td class="text-center">{{ format_price($orderDetail->tax) }}</td>
                            <td class="text-center">{{ format_price($orderDetail->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table> -->

<!-- Testing product table start -->
<table class="table table-bordered text-center ">
                    <thead class="table_head">
                        <tr >
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>SL</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px">Product Name</th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>QTY</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Price</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Discount Price</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>{{translate('Unit Tax')}}</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Total(BDT)</nobr></th>                       
                        </tr>
                    </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @if ($orderDetail->product != null)
                                    <tr>
                                        <td class="table_border" style="border-bottom:1px solid #DEDEDE;font-size:12px;padding-left:20px;padding-bottom:0px;padding-top:0px">{{ $key + 1 }}</td>
                                        <td class="table_border" style="border-bottom:1px solid #DEDEDE;padding-bottom:0px;padding-top:0px">
                                        <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}" class="size-60px mr-3">
                                            <span style="display: block; font-size:10px">{{ $orderDetail->product->name }}<?php echo '<br>' ?>{{$orderDetail->product->product_warranty != null ? '('.('Warranty:' .$orderDetail->product->product_warranty). ')':''}}</span>
                                            @if ($orderDetail->variation && $orderDetail->variation->combinations->count() > 0)
                                                @foreach ($orderDetail->variation->combinations as $combination)
                                                    <span style="margin-right:10px;font-size:10px">
                                                        <span class="">{{ $combination->attribute->getTranslation('name') }}</span>:
                                                        <span>{{ $combination->attribute_value->getTranslation('name') }}</span>
                                                    </span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center table_border" style=" border-bottom:1px solid #DEDEDE; font-size:14px;padding-bottom:0px;padding-top:0px">
                                            {{ $orderDetail->quantity }}</td>
                                        <td class="text-center table_border" style="border-bottom:1px solid #DEDEDE; font-size:14px;padding-bottom:0px;padding-top:0px">
                                            {{ (!empty($orderDetail->actual_price))? format_price($orderDetail->actual_price) : format_price($order->orderDetails[$key]->product->highest_price)}}</td>
                                        <td class="text-center table_border" style="border-bottom:1px solid #DEDEDE; font-size:14px;padding-bottom:0px;padding-top:0px">
                                                {{($order->orderDetails[$key]->product->highest_price > $orderDetail->price) ? format_price($orderDetail->price): ''}}</td>
                                        <td class="text-center table_border" style="border-bottom:1px solid #DEDEDE; font-size:14px;padding-bottom:0px;padding-top:0px">
                                        {{ format_price($orderDetail->tax) }}</td>
                                        <td class="text-center bold table_border" style="border-bottom:1px solid #DEDEDE;padding-right:20px; font-size:12px;padding-bottom:0px;padding-top:0px">
                                            {{ format_price($orderDetail->price * $orderDetail->quantity) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            @php
                            $subtotal_with_discount = 0;
                                foreach ($order->orderDetails as $key => $item) {
                                    $subtotal_with_discount += $item->price * $item->quantity;
                                } 
                            @endphp
                            <tr>
                                <td colspan="6" class="text-right bold table_border">With Discount Sub Total</td>
                                <td class="bold table_border">{{$subtotal_with_discount}}</td>
                            </tr>
                        </tbody>
                </table>
<!-- Testing product table end -->

    		<div class="row">

<!-- Calculation test start -->
<div style="margin-top:0px">
            <div class="col-xl-4 col-md-6 ml-auto mr-0">
                    @if ($order->payment_status == 'paid')
                        <div class="mt-5">
                            <img src="{{ static_asset('assets/img/paid_sticker.svg') }}">
                        </div>
                    @elseif($order->payment_type == 'cash_on_delivery')
                        <div class="mt-5">
                            <img src="{{ static_asset('assets/img/cod_sticker.svg') }}">
                        </div>
                    @endif
                </div>
            </div>
<!-- Calculation test start -->

                <div class="col-xl-4 col-md-6 ml-auto mr-0">
                    <table class="table">
                    <tbody>
                     
                     @php
                         $subtotal = 0;
                             foreach ($order->orderDetails as $key => $item) {
                                 if(empty($item->actual_price)){
                                     $subtotal += $order->orderDetails[$key]->product->highest_price * $item->quantity;
                                 }else{
                                     $subtotal += $item->actual_price * $item->quantity;
                                 }
                             } 

                         if($order->is_pos==1){
                             $offer_discount = $subtotal - ($order->grand_total-$order->orderDetails['0']->tax);
                         } else{
                             $offer_discount = ($subtotal - $order->grand_total);
                         }
                         
                         $total_discount =  100 - (($subtotal-$order->shipping_cost-$order->special_discount-$offer_discount) * 100) / $subtotal;  
                         
                     @endphp
                     <tr>
                         <td class="text-left" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                            {{ translate('Without Discount Sub Total') }}
                         </td>
                         <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ format_price($subtotal) }}
                         </td>
                     
                     </tr>
                     <tr class="">
                         <td class="text-left" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ translate('Total Tax') }}
                         </td>
                         <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ format_price( $order->orderDetails['0']->tax) }}
                         </td>
                     </tr>
                     <tr>
                         <td class="text-left" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ translate('Shipping Cost') }}
                         </td>
                         <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ format_price($order->shipping_cost) }}
                         </td>
                     </tr>
                     <tr>
                         <td class="text-left" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ translate('Discount') }}</td>
                         <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{($order->is_pos==1) ? format_price(($subtotal - ($order->grand_total-$order->orderDetails['0']->tax-$order->shipping_cost))) : format_price(($subtotal - $order->grand_total)+$order->shipping_cost)}}
                         </td>
                     </tr>
                     <tr class="">
                         <td class="text-left" style="border-bottom:1px solid #DEDEDE;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ translate('Coupon Discount') }}
                         </td>
                         <td class="bold" style="border-bottom:1px solid #DEDEDE;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ format_price($order->coupon_discount) }}
                         </td>
                     </tr>
                     <tr class="">
                         <td class="text-left" style="border-bottom:1px solid #DEDEDE;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ translate('Special Discount') }}
                         </td>
                         <td class="bold" style="border-bottom:1px solid #DEDEDE;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ format_price($order->special_discount) }}
                         </td>
                     </tr>
                     <tr class="">
                         <td class="text-left" style="border-bottom:1px solid #DEDEDE;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ translate('Advance Payment') }}
                         </td>
                         <td class="bold" style="border-bottom:1px solid #DEDEDE;font-size:14px;line-height: 1px; margin-bottom:0px">
                            {{$order->advance_payment == null? format_price(0): format_price($order->advance_payment)}}
                         </td>
                     </tr>
                     <tr>
                         <td class="text-left bold" style="border-bottom:1px solid #DEDEDE;font-size:14px;line-height: 1px; margin-bottom:0px">{{ translate('Cash To Collect') }}</td>
                         <td class="bold" style="border-bottom:1px solid #DEDEDE;font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ format_price(($order->grand_total - $order->special_discount) - ($order->advance_payment == null? 0: $order->advance_payment)) }}
                         </td>
                     </tr>
                     <tr>
                         <td class="text-left bold" style="font-size:14px;line-height: 1px; margin-bottom:0px">{{ translate('Discount%') }}</td>
                         <td class="bold" style="font-size:14px;line-height: 1px; margin-bottom:0px">
                             {{ round($total_discount,2)}}%
                         </td>
                     </tr>
                   </tbody>
                    </table>
                </div>
    		</div>
    	</div>



    </div>
@endsection

@section('script')
    <script type="text/javascript">
        // $('#update_delivery_status').on('change', function(){
        //     var order_id = {{ $order->id }};
        //     var status = $('#update_delivery_status').val();
        //     $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
        //         AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
        //     });
        // });

        


    $(document).ready(function () {
        var data = {
               'order_id': $('#order_id').val()
           }
          
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });

        $.ajax({
            type: "GET",
            url: "{{route('orders.advance_payment.check')}}",
            data: data,
            success: function (response) {
                if(response.check_data.advance_payment !== "null"){
                    $('#advan_pay_val').val(response.check_data.advance_payment);
                }
            }
        });
    });


    $(document).ready(function () {
        var data = {
            'combined_order_id' : $('#combined_order_id').val(),
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "GET",
            url: "{{route('orders.shiment_cost.get')}}",
            data: data,
            success: function (response) {
                if(response.shipping_cost !== "null"){
                    $('#shipment_cost').val(response.shipping_cost);
                }
            }
        });


    });

    // function start for user history

    $('#save_adv_pay').click(function (e) { 
            e.preventDefault();
            var data = {
               'advance_payment' : $('#advan_pay_val').val(),
               'order_id': $('#order_id').val()
           }
        
          
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            if(!data.advance_payment){
                
            }else{
                $.ajax({
                type: "POST",
                url: "{{route('user_history.orders.adv_payment')}}",
                data: data,
                success: function (response) {
                    if(response.status == 200)
                    {
                        //save advance payment after checking is it same value or not
                        $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                        });

                        if(!data.advance_payment){
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Please Add a value of Advance Payment!!!',
                                showConfirmButton: false,
                                timer: 1000
                            })
                        }else{
                            $.ajax({
                            type: "POST",
                            url: "{{route('orders.advance_payment')}}",
                            data: data,
                            success: function (response) {
                                Swal.fire({
                                        position: 'top',
                                        icon: 'success',
                                        title: response.message,
                                        showConfirmButton: false,
                                        timer: 1000
                                    })
                                }
                            });
                        }
                        
                    }else{
                        Swal.fire({
                            position: 'top',
                            icon: 'warning',
                            title: 'Already, Given! Please, change advance payament value',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }    
                }
            });
        }
           
    });

    $('#update_payment_status').change(function (e) { 
        e.preventDefault();
        var order_id = {{ $order->id }};
        var status = $('#update_payment_status').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{route('user_history.orders.payment_status')}}",
            data:{order_id:order_id,status:status},
            success: function (response) {
                if(response.status == 200)
                {
                    //after added user history add payment status in actual table
                    $.post('{{ route('orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                        AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
                        
                    });

                }
            }
        });
        
    });

    $('#update_delivery_status').change(function (e) { 
        e.preventDefault();
        var order_id = {{ $order->id }};
        var status = $('#update_delivery_status').val();

        if(status == 'cancelled')
        {
            
            $('#exampleModal').modal('show');
            $('#save_order_cancel_cause').click(function (e) { 
                e.preventDefault();
                var order_cancel_cause = $('#order_cancel_val').val();
                // save order cancel cause
                if(order_cancel_cause != '')
                {
                    $.ajax({
                        type: "POST",
                        url: "{{route('orders.update_delivery_status.order_cancel_cause')}}",
                        data: {cause_order_cancel:order_cancel_cause,order_id:order_id},
                        success: function (response) {

                            if(response.status == 200)
                            {
                                $('#exampleModal').modal('hide');
                                $('#order_cancel_val').val('');
                                //save user history
                                $.ajax({
                                    type: "POST",
                                    url: "{{route('user_history.orders.delivery_status')}}",
                                    data: {order_id:order_id,status:status},
                                    success: function (response) {
                                        if(response.status == 200)
                                        {
                                            //manage cancel status with stocking
                                            $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                                                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
                                            });
                                        }
                                    }
                                });
                                
                            }
                        }
                    });
                }
            });
        }
        else
        {
            $.ajax({
            type: "POST",
            url: "{{route('user_history.orders.delivery_status')}}",
            data: {order_id:order_id,status:status},
            success: function (response) {
                if(response.status == 200)
                {
                    $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                        AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
                    });
                }
            }
        });
    }
    });

    $('#save_shipment_cost').click(function (e) { 
        e.preventDefault();
        var data = {
            'combined_order_id' : $('#combined_order_id').val(),
            'shipment_cost_value' : $('#shipment_cost').val(),
        }

        $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });

        if(!data.shipment_cost_value){
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Please Add a value of Shpment Cost!!!',
                showConfirmButton: false,
                timer: 1000
            })
        }else{
            $.ajax({
                type: "POST",
                url: "{{route('user_history.orders.shipment_cost')}}",
                data: data,
                success: function (response) {
                    if(response.status == 200)
                    {
                        $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                        });

                        $.ajax({
                            type: "POST",
                            url: "{{route('orders.shiment_cost.update')}}",
                            data: data,
                            success: function (response) {
                                if(response.status == 200)
                                {
                                    Swal.fire({
                                        position: 'top',
                                        icon: 'success',
                                        title: response.message,
                                        showConfirmButton: false,
                                        timer: 1000
                                    })
                                }
                                
                            }
                        });
                    }else{
                        Swal.fire({
                            position: 'top',
                            icon: 'warning',
                            title: 'Already, Given! Please, change Shipment',
                            showConfirmButton: false,
                            timer: 1000
                        }) 
                    }
                }
            });
        }
        
    });



 //function end for user history

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
