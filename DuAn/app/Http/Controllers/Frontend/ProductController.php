<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\products;
use App\brand;
use App\category;
use File;
use App\Http\Requests\AddProductRequest;
use Intervention\Image\ImageManagerStatic as Image;
use Mail;
use App\countries;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    
    public function index(){
        if(session()->has('id')){
            $id = session()->get('id');
        }
        $data = products::where('id_member','=',$id)->get();
        if(empty($data)){
            session()->put('product',0);
        } else {
            session()->put('product',1);
        }
        return view('Frontend.product.my-product',compact('data'));        
    }
    public function add(){
        $cate = category::all();
        $brand = brand::all();
        return view('Frontend.product.add-product',compact('cate','brand'));
    }
    public function store(AddProductRequest $request){
        $data = [];
        if($request->hasfile('files'))
        {

            foreach($request->file('files') as $image)
            {
                //dùng $this-> để gọi hàm viết bên dưới
                $data[] = $this->xuLyHinhAnh($image);
                
            }
        }
        $this->saveProduct($request->name,json_encode($data),$request->price,$request->input('category'),$request->input('brand'),$request->sale,$request->profile,$request->detail, Auth::id(),0);
        return back()->with('success', 'Your active has been successfully');
    }
    public function saveProduct($name,$image,$price,$category,$brand,$sale,$profile,$details,$id_member,$id) {
        if($id==0){
            $product= new products();
        }
        else {
            $product= products::find($id);
        }
        $product->name = $name;
        $product->image= $image;
        $product->price = $price;
        $product->category = $category;
        $product->brand = $brand;
        $product->sale= $sale;
        $product->profile = $profile;
        $product->details = $details;
        $product->id_member = $id_member;
        $product->save();
    }
    public function trangchu(){
        $cart = 0;
        $product = products::limit(2)->orderBy('created_at','desc')->get();
        if(session()->has('cart')){
            $cart = count(session()->get('cart'));
        }
        //return response()->json(['cart'=>$cart]);
        return view('Frontend.index',compact('product','cart'));
    }
    public function edit($id){
        $cate = category::all();
        $brand = brand::all();
        $product=products::find($id);
        return view('Frontend.product.edit-product',compact('cate','brand','product'));
    }
    public function delete($id){
        DB::table('products')->where('id',$id)->delete();
        return redirect()-> back();
    }
    public function update($id,Request $request){
        $data = [];
        $file = $request->file('files');
        $pro=products::find($id);
        $prod = json_decode($pro->image,true);
        $hinhanh = $request->hinhanh;
        foreach($prod as $prod){
            if(!empty($hinhanh)){
                if(!in_array($prod,$hinhanh))
                $data[] = $prod; 
            } else {
                $data[] = $prod; 
            }
        }
        //print_r($data);
        if($file)
        {
            print(count($file)+count($data));
            if(count($file)+count($data)>3){
                return redirect()->back()->withErrors(['Amount of images > 3']);
                
            } else {
                foreach($request->file('files') as $image)
                {
                    $data[] = $this->xuLyHinhAnh($image);
                }
            }
            
        }
        if($request->detail == null){
            $detail = $pro->details;
        } else {
            $detail = $request->detail;
        }
        $this->saveProduct($request->name,json_encode($data),$request->price,$request->input('category'),$request->input('brand'),$request->sale,$request->profile,$detail, Auth::id(),$id);

        return redirect()->route('my-product');
    }
    public function xuLyHinhAnh($image){
        //set ngày giờ về giờ VN
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        // chuyển ngày giờ thành chuỗi
        $time = strtotime(date('Y-m-d H:i:s'));
        $name = $time."_".$image->getClientOriginalName();
        $name_2 = "hinh50_".$time."_".$image->getClientOriginalName();
        $name_3 = "hinh200_".$time."_".$image->getClientOriginalName();
        if(!file_exists('upload/product/'.Auth::user()->id)){
            mkdir('upload/product/'.Auth::user()->id);    
        } 
        $path = public_path('upload/product/'.Auth::user()->id.'/' . $name);
        $path2 = public_path('upload/product/'.Auth::user()->id.'/' . $name_2);
        $path3 = public_path('upload/product/'.Auth::user()->id.'/' . $name_3);
        //resize để chỉnh kích thước ảnh
        Image::make($image->getRealPath())->save($path);
        Image::make($image->getRealPath())->resize(50, 70)->save($path2);
        Image::make($image->getRealPath())->resize(200, 300)->save($path3);
        return $name;
    }
    public function detail($id){
        $product = products::find($id);
        return view('Frontend/product/detail-product',compact('product'));
    }
    public function addCart(Request $request){
        $id = $request->id;
        $pro = products::find($id);
        $amount = 1;      
        $flag = 1;
        if (session()->has('cart')) {               
            $getSession = session()->get('cart'); 
            foreach ($getSession as $key => $value) {
                if ($id == $value['Id']) {
                    $getSession[$key]['amount'] += 1;
                    session()->put('cart',$getSession);
                    $flag = 0;      
                }
            } 
        } 
        $product = ['Id' => $pro->id , 'name' => $pro->name , 'image' => $pro->image , 'price' => $pro->price , 'amount' => $amount ,'id_member' => $pro->id_member];
        if ($flag == 1) {
            session()->push('cart',$product);
        }
        $tong = count(session()->get('cart'));
        return response()->json(['count'=>$tong]);
        
    }
    public function cart(){
        $total = 0 ;
        if(session()->has('cart')){
            $getSession = session()->get('cart');
            foreach($getSession as $key => $value) {
                $total += $getSession[$key]['amount'] * $getSession[$key]['price'];
            }
        }
        return view('Frontend.cart.cart',compact('total'));
    }
    public function handleCart(Request $request){
        $id = $request->id;
        $handle = $request->handle;
        $total = 0;
        if (session()->has('cart')) {               
            $getSession = session()->get('cart'); 
            foreach ($getSession as $key => $value) {
                if ($id == $value['Id']) {
                    if($handle == 1){
                        $getSession[$key]['amount'] += 1;
                        session()->put('cart',$getSession);  
                    }
                    if($handle == 2){
                        $getSession[$key]['amount'] -= 1;
                        if($getSession[$key]['amount'] == 0){
                            unset($getSession[$key]);
                        }
                        session()->put('cart',$getSession);
                    }
                    if($handle == 3){
                        unset($getSession[$key]);
                        session()->put('cart',$getSession);
                    }
                      
                }
                
            }
            foreach($getSession as $key => $value){
                $total += $getSession[$key]['amount'] * $getSession[$key]['price'];
            } 
        }
        if(count(session()->get('cart'))== null){
            session()->forget('cart');
            return response()->json(['total' => 0]);
        }
        return response()->json(['total' => $total ]); 
    }   
    public function checkout(){
        $data = countries::all();
        $total = 0;
        $cart= [];
        if(session()->has('cart')){
            $cart = session()->get('cart');
            foreach($cart as $key => $value){
                $total += $cart[$key]['amount'] * $cart[$key]['price'];
            } 
        }
        return view('Frontend.cart.checkout',compact('cart','total','data'));
    }  
    public function email(){
        $total = 0;
        $cart= [];
        if(session()->has('cart')){
            $cart = session()->get('cart');
            foreach($cart as $key => $value){
                $total += $cart[$key]['amount'] * $cart[$key]['price'];
            } 
        }
        $data = [
            'cart' => $cart,
            'total' => $total,
        ];
        Mail::send('Frontend.mail.mail',$data,function($message){
            $message->from('ngominhuyzz@gmail.com','Minh Huy');
            $message->to('ngominhuyzz@gmail.com','Customer');
            $message->subject('Checkout Mail');
        });
         return view('Frontend.mail.mail',compact('cart','total'));
    }
    public function search(Request $request){
        $cart = 0;
        $product = products::limit(2)->orderBy('created_at','desc')->get();
        if(session()->has('cart')){
            $cart = count(session()->get('cart'));
        }
        $product = DB::table('products')->where('name','like','%'.$request->search_box.'%')->get();
        session()->put('search',$product);
        return view('Frontend.index',compact('product','cart'));
        //return response()->json(['product'=>$product,'cart'=>$cart]); 
    }
}