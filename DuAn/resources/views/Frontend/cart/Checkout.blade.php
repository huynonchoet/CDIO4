@extends('Frontend.layouts.app-cart')
@section('content')
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Check out</li>
            </ol>
        </div>
        <!--/breadcrums-->

        <div class="step-one">
            <h2 class="heading">Step1</h2>
        </div>
        <div class="checkout-options">
            <h3>New User</h3>
            <p>Checkout options</p>
            <ul class="nav">
                <li>
                    <label><input type="checkbox"> Register Account</label>
                </li>
                <li>
                    <label><input type="checkbox"> Guest Checkout</label>
                </li>
                <li>
                    <a href=""><i class="fa fa-times"></i>Cancel</a>
                </li>
            </ul>
        </div>
        <!--/checkout-options-->

        <div class="register-req">
            <p>Please use Register And Checkout to easily get access to your order history, or use Checkout as Guest</p>
        </div>
        <!--/register-req-->

        <div class="shopper-informations">
            <div class="row">
                <form class="form-horizontal form-material" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="col-md-12">Full Name</label>
                        <div class="col-md-12">
                            <input type="text" name="name" class="form-control form-control-line">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="example-email" class="col-md-12">Email</label>
                        <div class="col-md-12">
                            <input type="email" name="email" class="form-control form-control-line" id="example-email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Password</label>
                        <div class="col-md-12">
                            <input type="password" name="password" class="form-control form-control-line">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Phone No</label>
                        <div class="col-md-12">
                            <input type="text" name="phone" class="form-control form-control-line">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Avatar</label>
                        <div class="col-md-12">
                            <input type="file" name="avatar" class="form-control form-control-line">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Message</label>
                        <div class="col-md-12">
                            <textarea rows="5" class="form-control form-control-line"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12">Select Country</label>
                        <div class="col-sm-12">
                            <select class="form-control form-control-line" name="ct">
                                @foreach($data as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button class="btn btn-success">Register</button>
                        </div>
                    </div>
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <h4><i class="icon fa fa-check"></i>Thông báo!</h4>
                        {{session('success')}}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <h4><i class="icon fa fa-check"></i>Thông báo!</h4>
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </form> 

            </div>
        </div>
        <div class="review-payment">
            <h2>Review & Payment</h2>
        </div>

        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Item</td>
                        <td class="description"></td>
                        <td class="price">Price</td>
                        <td class="quantity">Quantity</td>
                        <td class="total">Total</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $cart)
                    <tr>
                        <td class="cart_product">
                            <a href=""><img style="width:80px"
                                    src="<?php $img = json_decode($cart['image'],true); echo asset("upload/product/{$cart['id_member']}/$img[0]") ?>"
                                    alt=""></a>
                        </td>
                        <td class="cart_description">
                            <h4><a>{{$cart['name']}}</a></h4>
                            <p id="{{$cart['Id']}}">{{$cart['Id']}}</p>
                        </td>
                        <td class="cart_price">
                            <p id="{{$cart['price']}}">${{$cart['price']}}</p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">
                                <a class="cart_quantity_up handle-cart" id='1'> + </a>
                                <input class="cart_quantity_input " type="text" name="quantity"
                                    value="{{$cart['amount']}}" autocomplete="off" size="2">
                                <a class="cart_quantity_down handle-cart" id='2'> - </a>
                            </div>
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">${{$cart['amount']*$cart['price']}}</p>
                        </td>
                        <td class="cart_delete">
                            <a class="cart_quantity_delete handle-cart" id="3"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    <td colspan="4">&nbsp;</td>
                    <td colspan="2">
                        <table class="table table-condensed total-result">
                            <tr>
                                <td>Cart Sub Total</td>
                                <td>$59</td>
                            </tr>
                            <tr>
                                <td>Exo Tax</td>
                                <td>$2</td>
                            </tr>
                            <tr class="shipping-cost">
                                <td>Shipping Cost</td>
                                <td>Free</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td><span>${{$total}}</span></td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="payment-options">
            <span>
                <label><input type="checkbox"> Direct Bank Transfer</label>
            </span>
            <span>
                <label><input type="checkbox"> Check Payment</label>
            </span>
            <span>
                <label><input type="checkbox"> Paypal</label>
            </span>
            <span>
                <button id="order" type="button" class="btn btn-primary" style="margin-left:600px;">ORDER</button>
            </span>
        </div>
    </div>
</section>
<!--/#cart_items-->
<script>
$('button#order').click(function() {
    var loggedIn = "{{Auth::check()}}";
    console.log(loggedIn);
    if (loggedIn == "") {
        alert('Please to login first');
        // window.location.href = "https://www.24h.com.vn/";
        window.location.href = "{{ url('/member/login') }}";
        return false;
        // 
        // window.location.href = "{{route('login')}}";
    } else
        window.location.href = "{{route('email')}}";
    return true;
})
</script>
@endsection