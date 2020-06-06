<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Compound;
use App\Deposit;
use App\Helpers\AppHelpers;
use App\PaymentMethod;
use App\Plan;
use App\Product;
use App\ProductCategory;
use App\Purchase;
use App\RepeatLog;
use App\Support;
use App\SupportMessage;
use App\Trade;
use App\TraitsFolder\MailTrait;
use App\User;
use App\UserLog;
use App\UserLogin;
use App\UserTradeLog;
use App\WithdrawLog;
use App\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class DashboardController extends Controller
{
    use MailTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function getDashboard()
    {
        $data['page_title'] = "Dashboard";

        $data['total_earn'] = UserLog::whereIn('amount_type', [1,2,5,9,10])->sum('amount') - UserLog::whereIn('amount_type', [4,3,6,8])->sum('amount');

        $data['total_pending_ticket'] = Support::whereIn('status', [1,3])->count();
        $data['total_ticket'] = Support::all()->count();
        $data['total_answer'] = Support::whereStatus(2)->count();
        $data['total_close'] = Support::whereStatus(9)->count();

        $data['user_balance'] = User::sum('balance');
        $data['total_trade'] = Purchase::sum('amount');

        $data['total_user'] = User::all()->count();
        $data['block_user'] = User::whereStatus(1)->count();
        $data['email_verify'] = User::whereEmail_verify(0)->count();
        $data['phone_verify'] = User::wherePhone_verify(0)->count();

        $data['total_deposit'] = Deposit::whereNotIn('status',[0])->sum('amount');
        $data['deposit_method'] = PaymentMethod::all()->count();
        $data['deposit_number'] = Deposit::whereNotIn('status',[0])->count();
        $data['deposit_pending'] = Deposit::whereStatus(0)->count();

        $data['total_plan'] = Plan::all()->count();
        $data['active_plan'] = Plan::whereStatus(1)->count();
        $data['deactive_plan'] = Plan::whereStatus(0)->count();


        $data['total_withdraw'] = WithdrawLog::whereStatus(2)->sum('amount');
        $data['withdraw_method'] = WithdrawMethod::all()->count();
        $data['withdraw_charge'] = WithdrawLog::whereStatus(2)->sum('charge');
        $data['withdraw_number'] = WithdrawLog::all()->count();
        $data['withdraw_success'] = WithdrawLog::whereStatus(2)->count();
        $data['withdraw_pending'] = WithdrawLog::whereStatus(1)->count();
        $data['withdraw_refund'] = WithdrawLog::whereStatus(3)->count();


        return view('dashboard.dashboard', $data);
    }
    public function manageCompound()
    {
        $data['page_title'] = "Manage Investment Compound";
        $data['compound'] = Compound::all();
        return view('dashboard.compound-manage', $data);
    }
    public function storeCompound(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'compound' => 'required',
        ]);
        $product = Compound::create($request->input());
        return response()->json($product);
    }
    public function editCompound($product_id)
    {
        $product = Compound::find($product_id);
        return response()->json($product);
    }
    public function updateCompound(Request $request,$product_id)
    {
        $product = Compound::find($product_id);
        $product->name = $request->name;
        $product->compound = $request->compound;
        $product->save();
        return response()->json($product);
    }
    public function createPlan()
    {
        $data['page_title'] = "New Investment Plan";
        $data['compound'] = Compound::all();
        return view('dashboard.plan-create', $data);
    }
    public function storePlan(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:plans,name',
            'minimum' => 'required|numeric|integer',
            'maximum' => 'required|numeric|integer',
            'time' => 'required|integer',
            'compound_id' => 'required',
            'percent' => 'required|numeric',
            'image' => 'required|mimes:jpg,png'
        ]);
        $plan = Input::except('_method','_token');
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $location = 'assets/images/' . $filename;
            Image::make($image)->resize(445,350)->save($location);
            $plan['image'] = $filename;
        }
        $plan['status'] = $request->status == 'on' ? '1' : '0';
        Plan::create($plan);
        session()->flash('message', 'Investment Plan Created Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }
    public function showPlan()
    {
        $data['page_title'] = "All Investment Plan";
        $data['plan'] = Plan::all();
        return view('dashboard.plan-show', $data);
    }
    public function editPlan($id)
    {
        $data['page_title'] = "All Investment Plan";
        $data['plan'] = Plan::findOrFail($id);
        $data['compound'] = Compound::all();
        return view('dashboard.plan-edit', $data);
    }
    public function updatePlan(Request $request,$id)
    {
        $p = Plan::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|unique:plans,name,'.$p->id,
            'minimum' => 'required|numeric|integer',
            'maximum' => 'required|numeric|integer',
            'time' => 'required|integer',
            'compound_id' => 'required',
            'percent' => 'required|numeric',
            'image' => 'mimes:jpg,png'
        ]);
        $plan = Input::except('_method','_token');
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $location = 'assets/images/' . $filename;
            Image::make($image)->resize(445,350)->save($location);
            $plan['image'] = $filename;
            $path = './assets/images/';
            $link = $path.$p->image;
            if (file_exists($link)) {
                unlink($link);
            }
        }
        $plan['status'] = $request->status == 'on' ? '1' : '0';
        $p->fill($plan)->save();
        session()->flash('message', 'Investment Plan Update Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }


    public function manageProductCategory()
    {
        $data['page_title'] = "Manage Product Category";
        $data['product_categories'] = ProductCategory::all();
        return view('dashboard.product-category-manage', $data);
    }

    public function storeProductCategory(Request $request)
    {
        $messages = array(
            'title.required'=>'Category title can\'t be empty!'
        );

        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $product_category = ProductCategory::create($request->all());

        return response()->json($product_category);
    }

    public function updateProductCategory(Request $request, $id)
    {
        $messages = array(
            'title.required'=>'Category title can\'t be empty!'
        );

        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $product_category = ProductCategory::find($id);
        $product_category->title = $request->title;
        $product_category->save();

        return response()->json($product_category);
    }

    public function products()
    {
        $data['page_title'] = "All Products";
        $data['products'] = Product::orderBy('id', 'DESC')->with('category')->get();
        return view('dashboard.products-all', $data);
    }

    public function createProduct()
    {
        $data['page_title'] = "Create Product";
        $data['categories'] = ProductCategory::all();
        return view('dashboard.product-create', $data);
    }

    public function storeProduct(Request $request)
    {
        $messages = array(
            'category_id.required'=>'You must select a category!'
        );
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            'category_id' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
        ], $messages);

        $product = Input::except('_token');
        $product['status'] = $request->status == 'on' ? '1' : '0';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'-product'.'.'.$image->getClientOriginalExtension();
            $destinationPath = 'assets/images/products/'.$imageName;
            Image::make($image)->resize(400,400)->save($destinationPath);
            $product['image'] = $imageName;
        }

        Product::create($product);
        session()->flash('success', 'Product Added Successfully.');

        return redirect()->back();
    }

    public function editProduct($id)
    {
        $data['page_title'] = "Edit Product";
        $data['categories'] = ProductCategory::all();
        $data['product'] = Product::findOrFail($id);
        return view('dashboard.product-edit', $data);
    }

    public function updateProduct(Request $request, $id)
    {
        $messages = array(
            'category_id.required'=>'You must select a category!'
        );
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
        ], $messages);

        $product = Product::find($id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->status = $request->status == 'on' ? '1' : '0';


        if ($request->hasFile('image')) {
            $image_path = 'assets/images/products/'. $product->image;
            if (file_exists($image_path)){
                unlink($image_path);
            }

            $image = $request->file('image');
            $imageName = time().'-product'.'.'.$image->getClientOriginalExtension();
            $destinationPath = 'assets/images/products/'.$imageName;
            Image::make($image)->resize(400,400)->save($destinationPath);
            $product->image = $imageName;
        }

        $product->save();
        session()->flash('success', 'Product Updated Successfully.');

        return redirect()->back();
    }

    public function updateProductPrice(Request $request, $id)
    {
        $this->validate($request, [
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::findorFail($id);

        if ($request->price > 0 && $request->price != $product->price) {
            $purchases = Purchase::where('product_id', $product->id)
                ->where('status', 1)
                ->get();

            //create trade for the product
            $trade_data['old_price'] = $product->price;
            $trade_data['new_price'] = $request->price;
            $trade_data['gain_loss'] = 0;
            $trade_data['product_id'] = $product->id;
            $trade = Trade::create($trade_data);
            $trade_gain_loss = 0;

            $basic_setting = BasicSetting::first();

            foreach ($purchases as $purchase) {
                $trade_gain_loss = $trade_gain_loss + ($purchase->amount - $request->price);
                //creates trade log for user
                $user = User::find($purchase->user_id);
                $gainOrLoss = $request->price - $purchase->amount;
                $user_trade_log['purchase_trx_id'] = $purchase->transaction_id;
                $user_trade_log['old_balance'] = $user->balance;
                $user_trade_log['new_balance'] = $user->balance + ($gainOrLoss);
                $user_trade_log['new_price'] = $request->price;
                $user_trade_log['gain_loss'] = $gainOrLoss;
                $user_trade_log['user_id'] = $purchase->user_id;
                $user_trade_log['trade_id'] = $trade->id;
                UserTradeLog::create($user_trade_log);

                $user->balance = $user->balance + ($gainOrLoss);
                $user->save();

                $gainOrLossText = AppHelpers::isNegative($gainOrLoss) ? "Lost" : "Earned";
                $user_log['user_id'] = $user->id;
                $user_log['amount'] = $gainOrLoss;
                $user_log['charge'] = null;
                // amount type 18 refers to user trade gain/loss
                $user_log['amount_type'] = 18;
                $user_log['post_bal'] = $user->balance - $request->amount;
                $user_log['description'] = abs($gainOrLoss)." ".$basic_setting->currency. " ". $gainOrLossText . " for the transaction ID ". $purchase->transaction_id;
                $user_log['transaction_id'] = $purchase['transaction_id'];
                UserLog::create($user_log);

            }

            $trade->gain_loss = $trade_gain_loss;
            $trade->save();

//            closing the trade for the purchases product
            Purchase::where('product_id', $product->id)
                ->where('status', 1)
                ->update(['status' => 0]);

            $product->trade_status = 0;
            $product->save();

        }

        $product->save();
        session()->flash('success', 'Trade Updated Successfully.');
        return response()->json(['success', 'Trade Updated successfully']);
    }

    public function currentTrades()
    {
        $data['page_title'] = "Current Trades";
        $data['products'] = Product::orderBy('id', 'DESC')->where('trade_status', 1)->get();
        return view('dashboard.current-trades', $data);
    }
    
    public function productTradesHistory()
    {
        $data['page_title'] = "Trade History";
        $data['trades'] = Trade::orderBy('id', 'DESC')->get();
        return view('dashboard.product-trades-history', $data);
    }

    public function depositMethod()
    {
        $data['page_title'] = 'Deposit Method';
        $data['gateways'] = PaymentMethod::where('id','<',800)->get();
        return view('dashboard.payment-method',$data);
    }
    
    public function updateDepositMethod(Request $request, $id)
    {
        $gateway = PaymentMethod::find($id);

        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'fix' => 'required',
            'percent' => 'required',
            'rate' => 'required',
            'val1' => 'nullable',
            'val2' => 'nullable',
            'status' => 'nullable'
        ]);

        if($request->hasFile('image'))
        {
            if (file_exists('assets/images/'.$gateway->image)) 
            {
                unlink('assets/images/'.$gateway->image);
            }
            $gateway['image'] = uniqid().'.'.$request->image->getClientOriginalExtension();
            $request->image->move('assets/images/',$gateway['image']);
        }

        $gateway['name'] = $request->name;
        $gateway['fix'] = $request->fix;
        $gateway['minamo'] = $request->minamo;
        $gateway['maxamo'] = $request->maxamo;
        $gateway['percent'] = $request->percent;
        $gateway['rate'] = $request->rate;
        $gateway['val1'] = $request->val1;
        $gateway['val2'] = $request->val2;
        $gateway['status'] = $request->status;

        $gateway->save();

        return back()->with('success','Gateway Information Updated successfully.');
    }
    public function bankDeposit()
    {
        $data['page_title'] = 'Add Manual Method';
        return view('dashboard.bank-create',$data);
    }

    public function showBitcoinManualDeposit()
    {
        $data['page_title'] = 'All Bitcoin Manual Method';
        $data['btc'] = PaymentMethod::where('id', 5)->first();
        return view('dashboard.manual-bitcoin-show',$data);
    }
    public function editBitcoinManualDeposit($id)
    {
        $data['page_title'] = 'Edit Manual Method';
        $data['btc'] = PaymentMethod::findOrFail($id);
        return view('dashboard.manual-bitcoin-edit',$data);
    }
    public function updateBitcoinManual(Request $request, $id)
    {
        $btc = PaymentMethod::findOrFail($id);
        $this->validate($request,[
            'name' => 'required',
            'image' => 'mimes:png,jpg,jpeg',
            'val1' => 'required',
            'fix' => 'required',
            'percent' => 'required',
        ]);
        $in = Input::except('_method','_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if($request->hasFile('image')) {
            $image3 = $request->file('image');
            $filename3 = time() . 'h7' . '.' . $image3->getClientOriginalExtension();
            $location = 'assets/images/' . $filename3;
            Image::make($image3)->resize(400, 400)->save($location);
            $in['image'] = $filename3;
            $path = './assets/images/';
            $link = $path.$btc->image;
            if (file_exists($link)){
                unlink($link);
            }
        }
        $btc->fill($in)->save();
        session()->flash('message', 'Manual Method Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }

    public function submitBankDeposit(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'val1' => 'required',
            'fix' => 'required|numeric',
            'percent' => 'required|numeric',
            'currency' => 'required',
            'rate' => 'required|numeric',
        ]);
        $in = Input::except('_method','_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        $inpid = PaymentMethod::where('id','>',800)->orderBy('id','desc')->first()->id;
        if(is_null($inpid))
        {
            $inpid = 800;
        }
        $in['id'] = $inpid+1;
        if($request->hasFile('image'))
        {
            $image3 = $request->file('image');
            $filename3 = time().'h7'.'.'.$image3->getClientOriginalExtension();
            $location = 'assets/images/' . $filename3;
            Image::make($image3)->resize(400,400)->save($location);
            $in['image'] = $filename3;
        }
        PaymentMethod::create($in);
        session()->flash('message', 'Manual Method Added Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }
    public function showBank()
    {
        $data['page_title'] = 'All Manual Method';

        $data['bank'] = PaymentMethod::where('id','>',800)->orderBy('id','asc')->get();
        return view('dashboard.bank-show',$data);
    }
    public function editBank($id)
    {
        $data['page_title'] = 'Edit Manual Method';
        $data['bank'] = PaymentMethod::findOrFail($id);
        return view('dashboard.bank-edit',$data);
    }
    public function updateBank(Request $request,$id)
    {
        $bank = PaymentMethod::findOrFail($id);
        $this->validate($request,[
            'name' => 'required',
            'image' => 'mimes:png,jpg,jpeg',
            'val1' => 'required',
            'fix' => 'required|numeric',
            'rate' => 'required|numeric',
            'percent' => 'required|numeric',
        ]);
        $in = Input::except('_method','_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if($request->hasFile('image')) {
            $image3 = $request->file('image');
            $filename3 = time() . 'h7' . '.' . $image3->getClientOriginalExtension();
            $location = 'assets/images/' . $filename3;
            Image::make($image3)->resize(400, 400)->save($location);
            $in['image'] = $filename3;
            $path = './assets/images/';
            $link = $path.$bank->image;
            if (file_exists($link)){
                unlink($link);
            }
        }
        $bank->fill($in)->save();
        session()->flash('message', 'Manual Method Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }
    public function withdrawMethod()
    {
        $data['page_title'] = 'Add Withdraw Method';
        return view('dashboard.withdraw-method',$data);
    }
    public function storeWithdrawMethod(Request $request)
    {

        $this->validate($request,[
            'name' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'fix' => 'required|numeric',
            'withdraw_min' => 'numeric',
            'withdraw_max' => 'numeric',
            'percent' => 'required|numeric',
        ]);
        $in = Input::except('_method','_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if($request->hasFile('image')) {
            $image3 = $request->file('image');
            $filename3 = time() . 'h8' . '.' . $image3->getClientOriginalExtension();
            $location = 'assets/images/' . $filename3;
            Image::make($image3)->resize(400, 400)->save($location);
            $in['image'] = $filename3;
        }
        WithdrawMethod::create($in);
        session()->flash('message','Withdraw Method Added Successfully.');
        session()->flash('type','success');
        session()->flash('title','Success');
        return redirect()->back();
    }
    public function showWithdrawMethod()
    {
        $data['page_title'] = 'All Withdraw Method';
        $data['bank'] = WithdrawMethod::orderBy('id','desc')->get();
        return view('dashboard.withdraw-show',$data);
    }
    public function editWithdrawMethod($id)
    {
        $data['page_title'] = 'Edit Withdraw Method';
        $data['bank'] = WithdrawMethod::findOrFail($id);
        return view('dashboard.withdraw-edit',$data);
    }
    public function updateWithdrawMethod(Request $request,$id)
    {
        $wit = WithdrawMethod::findOrFail($id);
        $this->validate($request,[
            'name' => 'required',
            'withdraw_min' => 'numeric',
            'withdraw_max' => 'numeric',
            'image' => 'mimes:png,jpg,jpeg',
            'fix' => 'required|numeric',
            'percent' => 'required|numeric',
        ]);
        $in = Input::except('_method','_token');
        $in['status'] = $request->status == 'on' ? '1' : '0';
        if($request->hasFile('image')) {
            $image3 = $request->file('image');
            $filename3 = time() . 'h8' . '.' . $image3->getClientOriginalExtension();
            $location = 'assets/images/' . $filename3;
            Image::make($image3)->resize(400, 400)->save($location);
            $in['image'] = $filename3;
            $path = './assets/images/';
            $link = $path.$wit->image;
            if (file_exists($link)){
                unlink($link);
            }
        }
        $wit->fill($in)->save();
        session()->flash('message','Withdraw Method Updated Successfully.');
        session()->flash('type','success');
        session()->flash('title','Success');
        return redirect()->back();
    }
    public function pendingDeposit()
    {
        $data['page_title'] = 'All Pending Manual Deposit Request';
        $data['deposit'] = Deposit::whereStatus(0)->orderBy('id','desc')->paginate(15);
        return view('dashboard.request-all',$data);
    }
    public function viewRequest($id)
    {
        $data['page_title'] = "Deposit Request View";
        $data['deposit'] = Deposit::findOrFail($id);
        return view('dashboard.request-view', $data);
    }
    public function approveManualRequest(Request $request)
    {

        $this->validate($request,[
            'id' => 'required'
        ]);

        $data = Deposit::findOrFail($request->id);
        $bank = $data->bank->name;
        $basic = BasicSetting::first();
        $mem = User::findOrFail($data->user_id);
        $data->status = 1;

        $ul['user_id'] = $mem->id;
        $ul['amount'] = $data->amount;
        $ul['charge'] = $data->charge;
        $ul['post_bal'] = $mem->balance + $data->amount;
        $ul['amount_type'] = 1;
        $ul['description'] = "Deposit ".$data->amount." ".$basic->currency." . By $bank.";
        $ul['transaction_id'] = $data->transaction_id;
        UserLog::create($ul);

//        if ($mem->under_reference != 0){
//            $refMem = User::findOrFail($mem->under_reference);
//            $refAmo = round(($data->amount * $basic->reference_percent) / 100,$basic->deci);
//            $ul['user_id'] = $refMem->id;
//            $ul['amount'] = $refAmo;
//            $ul['charge'] = null;
//            $ul['post_bal'] = $refMem->balance + $refAmo;
//            $ul['amount_type'] = 3;
//            $ul['description'] = "Reference Deposit Bonus ".$refAmo." ".$basic->currency." . From - $mem->username.";
//            $ul['transaction_id'] = $data->transaction_id;
//            UserLog::create($ul);
//
//            $refMem->balance = $refMem->balance + $refAmo;
//            $refMem->save();
//            if ($basic->email_notify == 1){
//                $text = $refAmo." - ". $basic->currency ." Reference Deposit Bonus From - $mem->username. <br> Transaction ID Is : <b>#".$data->custom."</b>";
//                $this->sendMail($refMem->email,$refMem->name,'Reference Deposit Bonus.',$text);
//            }
//            if ($basic->phone_notify == 1){
//                $text = $refAmo." - ".$basic->currency ." Reference Deposit Bonus From - $mem->username.. <br> Transaction ID Is : <b>#".$data->custom."</b>";
//                $this->sendSms($refMem->phone,$text);
//            }
//
//        }

        $mem->balance = $mem->balance + ($data->amount);
        $mem->save();

        $data->save();
        if ($basic->email_notify == 1){
            $text = $data->amount." - ". $basic->currency ." Payment Request Approve via $bank. <br> Transaction ID Is : <b>#".$data->transaction_id."</b>";
            $this->sendMail($mem->email,$mem->name,'Deposit Completed.',$text);
        }
        if ($basic->phone_notify == 1){
            $text = $data->amount." - ".$basic->currency ." Payment Request Approve via $bank. <br> Transaction ID Is : <b>#".$data->transaction_id."</b>";
            $this->sendSms($mem->phone,$text);
        }

        session()->flash('message', 'Payment Request Successfully Approved.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->route('request-deposit');
    }
    public function cancelManualRequest(Request $request)
    {
        $this->validate($request,[
            'id' => 'required'
        ]);
        $data = Deposit::findOrFail($request->id);
        $data->status = 2;
        $data->save();
        $basic = BasicSetting::first();
        if ($basic->email_notify == 1){
            $text = "$data->amount $basic->currency Payment Request Cancel. <br> Transaction ID Is : <b>#$data->transaction_id</b>";
            $this->sendMail($data->member->email,$data->member->name,'Payment Request Cancel.',$text);
        }
        if ($basic->phone_notify == 1){
            $text = "$data->amount $basic->currency Payment Request Cancel. <br> Transaction ID Is : <b>#$data->transaction_id</b>";
            $this->sendSms($data->member->phone,$text);
        }

        session()->flash('message', 'Payment Request Successfully Cancel.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->route('request-deposit');
    }
    public function requestDeposit()
    {
        $data['page_title'] = 'All Manual Deposit Request';
        $data['deposit'] = Deposit::whereNotIn('payment_type', [1,2,3,4])->orderBy('id','desc')->paginate(15);
        return view('dashboard.request-all',$data);
    }
    public function userDepositHistory()
    {
        $data['page_title'] = 'User Deposit History';
        $data['deposit'] = Deposit::orderBy('id','desc')->paginate(15);
        return view('dashboard.deposit-history',$data);
    }
    public function allWithdrawRequest()
    {
        $data['page_title'] = "All Withdraw Request";
        $data['log'] = WithdrawLog::whereNotIn('status',[0])->orderBy('id','desc')->get();
        return view('dashboard.withdraw-request-all', $data);
    }
    public function singleWithdrawView($id)
    {
        $data['page_title'] = 'Withdraw Details';
        $data['deposit'] = WithdrawLog::findOrFail($id);
        return view('dashboard.single-withdraw',$data);
    }
    public function confirmWithdraw(Request $request)
    {
        $basic = BasicSetting::first();
        $this->validate($request,[
            'id' => 'required'
        ]);
        $ee = WithdrawLog::findOrFail($request->id);
        $parent = User::findOrFail($ee->user_id);
        $ee->status = 2;
        $ee->save();

        if ($basic->email_notify == 1){
            $text = "$ee->amount $basic->currency Withdraw Request Approved. Withdraw Via ".$ee->method->name.". <br> Transaction ID Is : <b>#$ee->transaction_id</b>";
            $this->sendMail($parent->email,$parent->name,'Withdraw Approved.',$text);
        }
        if ($basic->phone_notify == 1){
            $text = "$ee->amount $basic->currency Withdraw Request Approved. Withdraw Via ".$ee->method->name.". <br> Transaction ID Is : <b>#$ee->transaction_id</b>";
            $this->sendSms($parent->phone,$text);
        }

        session()->flash('message','Withdraw Confirmed Successfully.');
        session()->flash('type','success');
        session()->flash('title','Success');
        return redirect()->back();
    }
    public function refundWithdraw(Request $request)
    {
        $this->validate($request,[
            'id' => 'required'
        ]);
        $ww = WithdrawLog::findOrFail($request->id);
        $ww->status = 3;


        $basic = BasicSetting::first();
        $parent = User::whereId($ww->user_id)->first();

        $bal36 = $parent;
        $ul['user_id'] = $parent->id;
        $ul['amount'] = $ww->amount;
        $ul['charge'] = null;
        $ul['post_bal'] = $bal36->balance + $ww->amount;
        $ul['amount_type'] = 6;
        $ul['description'] = $ww->amount." ".$basic->currency." Withdraw Refunded.";
        $ul['transaction_id'] = $ww->transaction_id;
        UserLog::create($ul);

        $ul['user_id'] = $parent->id;
        $ul['amount'] = $ww->charge;
        $ul['charge'] = null;
        $ul['post_bal'] = $bal36->balance + $ww->amount + $ww->charge;
        $ul['amount_type'] = 10;
        $ul['description'] = $ww->charge." ".$basic->currency." Withdraw Charge Refunded.";
        $ul['transaction_id'] = $ww->transaction_id;
        UserLog::create($ul);

        $parent->balance = $parent->balance + ($ww->net_amount);
        $parent->save();

        $ww->save();

        if ($basic->email_notify == 1){
            $text = "$ww->amount $basic->currency Withdraw Refunded. <br> Transaction ID Is : <b>#$ww->transaction_id</b>";
            $this->sendMail($parent->email,$parent->name,'Withdraw Refunded.',$text);
        }
        if ($basic->phone_notify == 1){
            $text = "$ww->amount $basic->currency Withdraw Refunded.  <br> Transaction ID Is : <b>#$ww->transaction_id</b>";
            $this->sendSms($parent->phone,$text);
        }


        session()->flash('message','Withdraw Refund Successfully.');
        session()->flash('type','success');
        session()->flash('title','Success');
        return redirect()->back();
    }
    public function withdrawConfirm()
    {
        $data['page_title'] = "Confirm Withdraw Request";
        $data['log'] = WithdrawLog::whereStatus(2)->orderBy('id','desc')->get();
        return view('dashboard.withdraw-request-all', $data);
    }
    public function withdrawPending()
    {
        $data['page_title'] = "Pending Withdraw Request";
        $data['log'] = WithdrawLog::whereStatus(1)->orderBy('id','desc')->get();
        return view('dashboard.withdraw-request-all', $data);
    }
    public function withdrawRefund()
    {
        $data['page_title'] = "Refund Withdraw Request";
        $data['log'] = WithdrawLog::whereStatus(3)->orderBy('id','desc')->get();
        return view('dashboard.withdraw-request-all', $data);
    }

//    public function repeatHistory()
//    {
//        $data['page_title'] = "Investment Repeat History";
//        $data['log'] = RepeatLog::orderBy('id','desc')->paginate(15);
//        return view('dashboard.repeat-history', $data);
//    }
    public function adminSupport()
    {
        $data['page_title'] = "All Support Ticket";
        $data['support'] = Support::orderBy('id','desc')->get();
        return view('dashboard.support-all', $data);
    }

    public function adminSupportPending()
    {
        $data['page_title'] = "Pending Support Ticket";
        $data['support'] = Support::whereIn('status', [1,3])->orderBy('id','desc')->get();
        return view('dashboard.support-pending', $data);
    }
    public function adminSupportMessage($id)
    {
        $data['page_title'] = "Support Message";
        $data['support'] = Support::whereTicket_number($id)->first();
        $data['message'] = SupportMessage::whereTicket_number($id)->orderBy('id','asc')->get();
        return view('dashboard.support-message', $data);
    }
    public function adminSupportMessageSubmit(Request $request)
    {
        $this->validate($request,[
            'message' => 'required',
            'support_id' => 'required'
        ]);
        $mm = Support::findOrFail($request->support_id);
        $mm->status = 2;
        $mm->save();
        $mess['support_id'] = $mm->id;
        $mess['ticket_number'] = $mm->ticket_number;
        $mess['message'] = $request->message;
        $mess['type'] = 2;
        SupportMessage::create($mess);
        session()->flash('message','Support Ticket Successfully Reply.');
        session()->flash('type','success');
        session()->flash('title','Success');
        return redirect()->back();
    }
    public function adminSupportClose(Request $request)
    {
        $this->validate($request,[
            'support_id' => 'required'
        ]);
        $su = Support::findOrFail($request->support_id);
        $su->status = 9;
        $su->save();
        session()->flash('message','Support Successfully Closed.');
        session()->flash('type','success');
        session()->flash('title','Success');
        return redirect()->back();
    }
    public function userDetails($id)
    {
        $data['page_title'] = 'User Details';
        $data['user'] = User::find($id);
        $user = $data['user'];
        $data['total_repeat'] = RepeatLog::whereUser_id($user->id)->count();
        $data['total_repeat_amount'] = RepeatLog::whereUser_id($user->id)->sum('amount');
        $data['total_deposit'] = Deposit::whereUser_id($user->id)->whereStatus(1)->count();
        $data['total_deposit_amount'] = Deposit::whereUser_id($user->id)->whereStatus(1)->sum('amount');
        $data['total_withdraw'] = WithdrawLog::whereUser_id($user->id)->whereIn('status',[3,2])->count();
        $data['total_withdraw_amount'] = WithdrawLog::whereUser_id($user->id)->whereIn('status',[2])->sum('amount');
        $data['total_login'] = UserLogin::whereUser_id($user->id)->count();
        $data['last_login'] = UserLogin::whereUser_id($user->id)->orderBy('id','desc')->first();
        return view('dashboard.user-details',$data);
    }
    public function userDetailsUpdate(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $uss = User::findOrFail($request->user_id);

        $in = Input::except('_method','_token','user_id');
        $in['email_verify'] = $request->email_verify == 'on' ? '1' : '0';
        $in['phone_verify'] = $request->phone_verify == 'on' ? '1' : '0';
        $in['status'] = $request->status == 'on' ? '0' : '1';
        $uss ->fill($in)->save();

        session()->flash('message', 'User Details Updated Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }
    public function userSendMail($id)
    {
        $data['page_title'] = 'User Details';
        $data['user'] = User::findOrFail($id);
        return view('dashboard.user-send-email',$data);
    }
    public function userSendMailSubmit(Request $request)
    {
        $this->validate($request,[
            'subject' => 'required',
            'message' => 'required',
            'user_id' => 'required'
        ]);
        $user = User::findOrFail($request->user_id);
        $this->sendMail($user->email,$user->name,$request->subject,$request->message);
        session()->flash('message', 'Email Send Successfully.');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }
    public function userMoney($id)
    {
        $data['page_title'] = 'User Balance Manage';
        $data['user'] = User::whereUsername($id)->first();
        return view('dashboard.user-money',$data);
    }
    public function userMoneySubmit(Request $request)
    {
        $this->validate($request,[
            'amount' => 'required',
            'reason' => 'required'
        ]);
        $basic = BasicSetting::first();
        $ac = $request->operation == 'on' ? '1' : '2';
        if ($ac == 1)
        {
            $user = User::findOrFail($request->user_id);
            $bal = $user;

            $cus = strtoupper(Str::random(20));
            $ul['user_id'] = $user->id;
            $ul['amount'] = $request->amount;
            $ul['charge'] = null;
            $ul['amount_type'] = 8;
            $ul['post_bal'] = $bal->balance + $request->amount;
            $ul['description'] = "Add $request->amount $basic->currency - For $request->reason";
            $ul['transaction_id'] = $cus;
            UserLog::create($ul);

            $bal->balance = $bal->balance + $request->amount;
            $bal->save();

            if ($basic->email_notify == 1){
                $text = "Add ".$request->amount." - ". $basic->currency ." For $request->reason. <br> Transaction ID Is : <b>#".$cus."</b>";
                $this->sendMail($bal->email,$bal->name,'Manual Add Balance.',$text);
            }
            if ($basic->phone_notify == 1){
                $text = "Add ".$request->amount." - ".$basic->currency ." For $request->reason. <br> Transaction ID Is : <b>#".$cus."</b>";
                $this->sendSms($bal->phone,$text);
            }

            session()->flash('message', 'User balance Added Successfully.');
            Session::flash('type', 'success');
            Session::flash('title', 'Success');
            return redirect()->back();
        }else{
            $user = User::findOrFail($request->user_id);
            $bal = $user;

            $cus = strtoupper(Str::random(20));
            $ul['user_id'] = $user->id;
            $ul['amount'] = $request->amount;
            $ul['charge'] = null;
            $ul['amount_type'] = 9;
            $ul['post_bal'] = $bal->balance - $request->amount;
            $ul['description'] = "Subtract $request->amount $basic->currency - For $request->reason";
            $ul['transaction_id'] = $cus;
            UserLog::create($ul);

            $bal->balance = $bal->balance - $request->amount;
            $bal->save();

            if ($basic->email_notify == 1){
                $text = "Subtract ".$request->amount." - ". $basic->currency ." For $request->reason. <br> Transaction ID Is : <b>#".$cus."</b>";
                $this->sendMail($bal->email,$bal->name,'Manual Subtract Balance.',$text);
            }
            if ($basic->phone_notify == 1){
                $text = "Subtract ".$request->amount." - ".$basic->currency ." For $request->reason. <br> Transaction ID Is : <b>#".$cus."</b>";
                $this->sendSms($bal->phone,$text);
            }

            session()->flash('message', 'User Balance Subtract Successfully.');
            Session::flash('type', 'success');
            Session::flash('title', 'Success');
            return redirect()->back();
        }

    }
    public function manageUser()
    {
        $data['page_title'] = "Manage User";
        $data['user'] = User::orderBy('id','desc')->paginate(15);
        return view('dashboard.user-manage',$data);
    }
    public function showBlockUser()
    {
        $data['page_title'] = 'All Blocked User';
        $data['user'] = User::whereStatus(1)->paginate(15);
        return view('dashboard.user-manage',$data);
    }
    public function allVerifyUser()
    {
        $data['page_title'] = 'All Verified User';
        $data['user'] = User::whereStatus(0)->whereEmail_verify(1)->wherePhone_verify(1)->orderBy('id','desc')->paginate(15);
        return view('dashboard.user-manage',$data);
    }
    public function phoneUnVerifyUser()
    {
        $data['page_title'] = 'Phone UnVerified User';
        $data['user'] = User::wherePhone_verify(0)->orderBy('id','desc')->paginate(15);
        return view('dashboard.user-manage',$data);
    }
    public function emailUnVerifyUser()
    {
        $data['page_title'] = 'Email UnVerified User';
        $data['user'] = User::whereEmail_verify(0)->orderBy('id','desc')->paginate(15);
        return view('dashboard.user-manage',$data);
    }
    public function blockUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->status = 1;
        $user->save();
        session()->flash('message', 'User Successfully Blocked');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }
    public function unblockUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->status = 0;
        $user->save();
        session()->flash('message', 'User Successfully Unblocked');
        Session::flash('type', 'success');
        Session::flash('title', 'Success');
        return redirect()->back();
    }
    public function userDepositAll($id)
    {
        $data['user'] = User::whereUsername($id)->first();
        $data['page_title'] = $data['user']->username.' - Deposit Log';
        $data['deposit'] = Deposit::whereUser_id($data['user']->id)->orderBy('id','desc')->get();
        return view('dashboard.user-deposit-log',$data);
    }
    public function userWithdrawAll($id)
    {
        $data['user'] = User::whereUsername($id)->first();
        $data['page_title'] = $data['user']->username.' - Withdraw Log';
        $data['log'] = WithdrawLog::whereUser_id($data['user']->id)->orderBy('id','desc')->get();
        return view('dashboard.user-withdraw-log',$data);
    }
    public function userLogInAll($id)
    {
        $data['user'] = User::whereUsername($id)->first();
        $data['page_title'] = $data['user']->username.' - Login Details';
        $data['log'] = UserLogin::whereUser_id($data['user']->id)->orderBy('id','desc')->get();
        return view('dashboard.user-login-log',$data);
    }

    public function userRepeatAll($id)
    {
        $data['user'] = User::whereUsername($id)->first();
        $data['page_title'] = $data['user']->username.' - All Repeat';
        $data['log'] = RepeatLog::whereUser_id($data['user']->id)->orderBy('id','desc')->paginate(15);
        return view('dashboard.repeat-history',$data);
    }
    public function adminActivity()
    {
        $data['page_title'] = 'Transaction Log';
        $data['log'] = UserLog::orderBy('id','desc')->paginate(15);
        return view('dashboard.admin-activity',$data);
    }

}
