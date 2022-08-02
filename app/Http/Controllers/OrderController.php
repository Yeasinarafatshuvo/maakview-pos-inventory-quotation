<?php

namespace App\Http\Controllers;

use App\Models\CommissionHistory;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ReturnProduct;
use App\Models\CombinedOrder;
use CoreComponentRepository;
use App\Http\Controllers\CategoryController;
use DB;
class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_orders'])->only('index');
        $this->middleware(['permission:view_orders'])->only('show');
        $this->middleware(['permission:delete_orders'])->only('destroy');
    }

    public function index(Request $request)
    {
       
        CoreComponentRepository::instantiateShopRepository();
//  dd("ok");
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
       

        $admin = User::where('user_type','admin')->first();
        $orders = Order::with('combined_order','user')->where('shop_id',$admin->shop_id);
        

        if ($request->has('search') && $request->search != null ){
            $sort_search = $request->search;
            
            $explode_count = explode("-",$sort_search);
            // dd(count($explode_count));
            if(substr($sort_search, 0, 3) == "MVI" || count($explode_count)>1){
                $orders = $orders->whereHas('combined_order', function ($query) use ($sort_search) {
                    $query->where('code', 'like', '%'.$sort_search.'%');
                });
            }
            if(substr($sort_search, 0, 3) == "+88"){
                $orders = $orders->whereHas('user', function ($query) use ($sort_search) {
                    $query->where('phone', 'like', '%'.$sort_search.'%');
                });
            }
            if(substr($sort_search, 0, 2) == "01"){
                 $orders = $orders->whereJsonContains('shipping_address->phone', $sort_search);
            }
            if(substr($sort_search, 0, 3) !== "MVI" && count($explode_count)<2 && substr($sort_search, 0, 3) !== "+88" && substr($sort_search, 0, 2) !== "01"){
                $orders = $orders->whereHas('user', function ($query) use ($sort_search) {
                    $query->where('name', 'like', '%'.$sort_search.'%');
                });
            }

        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }

        if(!empty($start_date)) {
            $orders = $orders->where('created_at','>=', date("Y-m-d", strtotime($start_date)));
        }
        if(!empty($end_date)) {
            $orders = $orders->where('created_at','<=', date("Y-m-d", strtotime($end_date. ' +1 day')));
        }
       
        $orders = $orders->latest()->paginate(15);
        
        
        return view('backend.orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search','start_date','end_date'));
    }

    public function show($id)
    {
        $order = Order::with(['orderDetails.product','orderDetails.variation.combinations'])->findOrFail($id);
        //find total order of single user
        $user_id = Order::select('user_id')->find($id);
        $user_info = User::with('address_info')->find($user_id);
        $total_user_order = Order::where('user_id',$user_id->user_id)
                            ->where('delivery_status',"delivered")
                            ->count();

        return view('backend.orders.show', compact('order','total_user_order','user_info'));
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if($order != null){
            foreach($order->orderDetails as $key => $orderDetail){
                
                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        }
        else{
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function update_delivery_status(Request $request)
    {
       
        $order = Order::findOrFail($request->order_id);
        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == 'cancelled') {
            foreach($order->orderDetails as $orderDetail){
                try{
                    foreach($orderDetail->product->categories as $category){
                        $category->sales_amount -= $orderDetail->total;
                        $category->save();
                    }
        
                    $brand = $orderDetail->product->brand;
                    if($brand){
                        $brand->sales_amount -= $orderDetail->total;
                        $brand->save();
                    }
                }
                catch(\Exception $e){
                    
                }
            }

            if($order->payment_type == 'wallet'){
                $user = User::where('id', $order->user_id)->first();
                $user->balance += $order->grand_total;
                $user->save();
            }
            
            $order_product_serial_number = OrderDetail::select('prod_serial_num', 'product_id')->where('order_id', $request->order_id)->get();
           
            for($i = 0; $i < count($order_product_serial_number); $i++)
            {
                   
                    $order_product_serial_number_array_values = explode(",",$order_product_serial_number[$i]['prod_serial_num']);
        
                    $newArray = [];
                    foreach($order_product_serial_number_array_values as $item){
                        array_push($newArray, preg_replace(' /[^A-Za-z0-9]+/', '', $item));
                    }
    
                    $without_null_order_product_serial_number = array_filter($newArray, function($item){
                        return $item !== "" && $item !== "," ;
                    });
                   
                    //stock product serial num
                    $stock_product_serial_number = Product::where('id', $order_product_serial_number[$i]['product_id'])->pluck('serial_no');
                    $stock_pro_serial_array_values = explode(",",$stock_product_serial_number[0]);

                    $stock_newArray = [];
                    foreach($stock_pro_serial_array_values as $item){
                        array_push($stock_newArray, preg_replace(' /[^A-Za-z0-9]+/', '', $item));
                    }

                    $without_null_stock_serial = array_filter($stock_newArray, function($item){
                        return $item !== "" && $item !== "," ;
                    });
                    $add_stock_and_calcel_order_serial = array_merge($without_null_order_product_serial_number,$without_null_stock_serial);
                  
        
                    Product::where('id', $order_product_serial_number[$i]['product_id'])->update([
                        'serial_no' => array_values($add_stock_and_calcel_order_serial),
                        'stock_quantity' =>  count($add_stock_and_calcel_order_serial)
                    ]);
                    
            }
           

        }
        if ($request->status == 'confirmed')
        {
            $order_details_data = OrderDetail::where('order_id', $request->order_id)->get();

            // --------Sms Integration start----------

                $order_info = Order::find($request->order_id);

                if ($order_info->billing_address !== null  && !empty($order_info->billing_address)){
                    $user_info = json_decode($order->billing_address);
                    if(!empty($user_info->phone)){
                        $to = $user_info->phone;
                    }else{
                        $to = $order_info->user->phone;
                    }
                }else{
                        $to = $order_info->user->phone;
                }     
                
                $token = "7866132738dca110e68e8b7cbc10e238a12c992211";
                $message = "Maakview order ".$order_info->combined_order->code." Confirmed.Helpline:01888-012727";
                $url = "http://api.greenweb.com.bd/api.php";

                $to = "+8801722604027";
                $data= array(
                'to'=>"$to",
                'message'=>"$message",
                'token'=>"$token"
                ); 
                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_ENCODING, '');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $smsresult = curl_exec($ch);

                // echo $smsresult;
                // echo curl_error($ch);

            // --------Sms Integration end----------

            foreach ($order_details_data as $key => $value) {
               
                $confirm_product_serial_numbers = Product::find($value->product_id);
                $confirm_product_serial_numbers = $confirm_product_serial_numbers->serial_no;
                
                $confirm_stock_pro_serial_array_values = explode(",",$confirm_product_serial_numbers);
                $confirm_stock_newArray = [];
                foreach($confirm_stock_pro_serial_array_values as $item){
                    array_push($confirm_stock_newArray, preg_replace(' /[^A-Za-z0-9]+/', '', $item));
                }

                $confrim_without_null_stock_serial = array_filter($confirm_stock_newArray, function($item){
                    return $item !== "" && $item !== "," ;
                });
               
                $confirm_order_serial_number = [];
                // return $value->quantity;
                foreach ($confrim_without_null_stock_serial as $key => $confrim_without_null_stock_serial_item) {
                    array_push($confirm_order_serial_number,$confrim_without_null_stock_serial_item);
                    unset($confrim_without_null_stock_serial[$key]);
                    if(($key + 1) == $value->quantity){
                        break;
                    }
                }

                Product::where('id', $value->product_id)->update([
                    'serial_no' => array_values($confrim_without_null_stock_serial),
                    'stock_quantity' =>  count($confrim_without_null_stock_serial)
                ]);

                OrderDetail::where('order_id', $request->order_id)->where('product_id', $value->product_id)->update([
                    'prod_serial_num' => json_encode($confirm_order_serial_number),
                ]);
              

            }

        }

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status = $request->status;
        $order->save();

        if($request->status == 'paid'){
            calculate_seller_commision($order);
        }

        return 1;
    }

    //product return 
    public function return_products()
    {
        $invoice_numbers = CombinedOrder::select('code')->get();
        return view('backend.orders.return_products', compact('invoice_numbers'));
    }

    public function return_products_specefic_data(Request $request)
    {
        if($request->ajax()){
            $combine_order_id = CombinedOrder::select('id')->where('code',$request->invoice_number )->first();
            $order_id = Order::select('id')->where('combined_order_id', $combine_order_id->id)->first();
            $user_details = Order::where('combined_order_id', $combine_order_id->id)->with('user')->first();
            $order_details = OrderDetail::where('order_id', $order_id->id)->with('product')->get();

            return response()->json([
                'status' => 200,
                'order_details' => $order_details,
                'user_details' => $user_details,
            ]);
        }
    }

    public function store_return_product(Request $request)
    {
        if($request->ajax()){
            
            $product_qty = count($request->product_id);
            if($product_qty !== null){
                for ($i=0; $i <$product_qty ; $i++) { 
                    $return_product_instance = new ReturnProduct();

                    $specefic_product_serial_no = explode(",", $request->prod_serial_num[$i]);
                    $without_null_return_product_serial_no = array_filter($specefic_product_serial_no, function($item){
                        return $item !== "" && $item !== "," ;
                    });
                    //save return product table data
                    $return_product_instance->order_id = $request->order_id;
                    $return_product_instance->product_id = $request->product_id[$i];
                    $return_product_instance->product_return_qty = $request->return_product_qty[$i];
                    $return_product_instance->invoice_number = $request->invoice_number;
                    $return_product_instance->serial_number = json_encode($without_null_return_product_serial_no);
                    $save_return_product = $return_product_instance->save();
                  
                    if($save_return_product){
                        $previous_serial_no = Product::where('id', $request->product_id[$i])->pluck('serial_no');
                        $new_prev_data = explode(",",$previous_serial_no[0]);
    
                        $newArray = [];
                        foreach($new_prev_data as $item){
                            array_push($newArray, preg_replace(' /[^A-Za-z0-9]+/', '', $item));
                        }
    
                        $without_null_prev_values = array_filter($newArray, function($item){
                            return $item !== "" && $item !== "," ;
                        });
    
                        $merge_serial_no = array_merge_recursive($without_null_return_product_serial_no, $without_null_prev_values);

                        //check already save serial number and took unique serial number only
                        $get_unique_data = array_values(array_unique($merge_serial_no));
                        $previous_stock_qty = Product::where('id', $request->product_id[$i])->pluck('stock_quantity');

                        if($previous_stock_qty[0] !== ""){
                            $total_qty =   $previous_stock_qty[0] + (int) $request->return_product_qty[$i];
                            if( $total_qty ==  count($get_unique_data)){
                                $total_stock_qty = $total_qty;
                            }else{
                                $total_stock_qty = count($get_unique_data);
                            }
                        }else{
                            $total_stock_qty = (int) $request->product_qty[$i];
                        }

                        //update product table stock qty and serial number
                        Product::where('id', $request->product_id[$i])->update([
                            'serial_no' => $get_unique_data,
                            'stock_quantity' =>   $total_stock_qty,
                        ]);
                       
                    }
                    
                }
               
            }

            return response()->json([
                'status'=>200,
                'message'=> 'Product Return Done Successfully',
            ]); 
            
        }
    }

    public function return_product_list()
    {
        $return_product_invoices = ReturnProduct::select('invoice_number')->groupBy('invoice_number')->get();
        return view('backend.orders.return_product_list', compact('return_product_invoices'));
    }

    public function return_product_list_details(Request $request, $invoice)
    {
        $return_product_details = ReturnProduct::where('invoice_number', $invoice)->with('product')->get();
        $order_details = ReturnProduct::select('order_id')->where('invoice_number', $invoice)->first();
        $order = order::where('id', $order_details->order_id)->first();
        return view('backend.orders.return_product_details', compact('return_product_details','order'));
    }

    public function return_product_list_delete(Request $request)
    {
        $invoice_number =  $request->invoice_number;
        $return_product_list = ReturnProduct::select('product_id','serial_number')->where('invoice_number', $invoice_number)->get();
        $total_product_item =  count($return_product_list);
        for($i = 0; $i < $total_product_item; $i++)
        {
            $serial_number =  $return_product_list[$i]->serial_number;
            $category_instance = new CategoryController();
            $specefic_product_return_cancel_serial_number =  $category_instance->convert_json_serial_numbers_to_arrays($serial_number);
            $stock_product_id = $return_product_list[$i]->product_id;
            $specefic_stock_proudct_serial = Product::where('id',  $stock_product_id)->pluck('serial_no');
            $specefic_stock_proudct_serial_in_arrays = $category_instance->convert_json_serial_numbers_to_arrays($specefic_stock_proudct_serial);
            $stock_out_cancel_return_products =  $category_instance->unique_array_values_from_two_array($specefic_stock_proudct_serial_in_arrays,$specefic_product_return_cancel_serial_number);
            
            $update_product_stock = Product::where('id', $stock_product_id)->update([
                'serial_no' => array_values($stock_out_cancel_return_products),
                'stock_quantity' =>  count($stock_out_cancel_return_products)
            ]);

            if($update_product_stock)
            {
                ReturnProduct::where('product_id', $stock_product_id)->where('invoice_number',  $invoice_number)->delete();
            }   
          
        }

        return response()->json([
            'status' => 200,
            'message' => 'Product return successfully cancel'
        ]);

    }

    public function order_cancel_list()
    {
        $order_cancel_details = Order::orderBy('updated_at', 'DESC')->where('delivery_status', 'cancelled')->whereNotNull('cancel_reason')->with('combined_order','user')->latest()->get();
        return view('backend.orders.order_cancel_list', compact('order_cancel_details'));
    }

    public function order_cancel_search_by_date(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $order_cancel_details_by_date =  Order::orderBy('updated_at', 'DESC')->where(DB::raw('CAST(updated_at as date)'), '<=', $end_date)->where(DB::raw('CAST(updated_at as date)'), '>=', $start_date)->where('delivery_status', 'cancelled')->whereNotNull('cancel_reason')->with('combined_order','user')->get();
        return view('backend.orders.return_cancel_list_by_date', compact('order_cancel_details_by_date', 'start_date','end_date'));

    }






}
