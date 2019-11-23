<?php

namespace App\Http\Controllers;

use App\Pay;
use App\Membership;
use Illuminate\Http\Request;
use App\Order;
use Auth;

use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\WebProfile;
use PayPal\Api\InputFields;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;


class PayController extends Controller
{


  //Order
  public function order(request $request){

    if (!$request->cat || !$request->id) {
      return view('pages.home');
    }

    //Membership
    if($request->cat == 'membership'){
      $order = Membership::where('id',$request->id)->first();
    }
    if(!$order) return view('pages.home');



    return view('pages.order')->with('order',$order)->with('cat',$request->cat);

  }


  //PayPal
  protected $clientID       = "ARrbqkpBMp1i-tZ3Mca6ljr0KfOrYCGGRHimwEtb-TqS3Q_axb06MucF_XjHkEXp9v4KnhBfvBpAzUxu";
  protected $clientSecret   = "EFfv8xmlXMGdg-5ZhpzYrq8LizcybYX2tbC_EXThIrMUhgzJtPz1kywaZl783EtePKVtw9UGWjRN8ocZ";

  public function paypalCreatePayment(request $request)
  {
    if(
      !$request->userid   ||
      !$request->cat      ||
      !$request->id       ||
      !$request->method   
    ){
      return false;
    }
 
    if($request->cat == "membership"){
      $product = Membership::where('id',$request->id)->first();
    }
    if(!$product) return false;

    //Make order
    $order = new Order();

    $order->user_id = $request->userid;
    $order->name = $product->name;
    $order->category = $request->cat;
    $order->product_id = $product->id;
    $order->method = $request->method;
    $order->value = $product->price;

    if(!$order->save())return false;

    $order_id = $order->id;

    if(!$order_id) return false;



    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            $this->clientID,     // ClientID
            $this->clientSecret      // ClientSecret
        )
    );

    $apiContext->setConfig(
      array(
        'mode' => 'live',
      )
    );  

    $payer = new Payer();
    $payer->setPaymentMethod("paypal");


    //Items
    $item1 = new Item();
    $item1->setName($product->name)
        ->setCurrency('EUR')
        ->setQuantity(1)
        ->setSku($order_id) // Similar to `item_number` in Classic API
        ->setPrice($product->price);
    $item2 = new Item();

    $itemList = new ItemList();
    $itemList->setItems(array($item1));

    $details = new Details();
    $details->setShipping(0)
        ->setTax(0)
        ->setSubtotal($product->price);

    $amount = new Amount();
    $amount->setCurrency("EUR")
        ->setTotal($product->price)
        ->setDetails($details);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
      ->setItemList($itemList)
      ->setDescription("Charming Brides ".$product->name)
      ->setInvoiceNumber(uniqid());

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl("http://charmingbrides.com")
        ->setCancelUrl("http://charmingbrides.com");

    // Add NO SHIPPING OPTION
    $inputFields = new InputFields();
    $inputFields->setNoShipping(1);
    $webProfile = new WebProfile();
    $webProfile->setName('Charming Brides' . uniqid())->setInputFields($inputFields);
    $webProfileId = $webProfile->create($apiContext)->getId();

    $payment = new Payment();
    $payment->setExperienceProfileId($webProfileId); // no shipping
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

    try {
        $payment->create($apiContext);
    } catch (Exception $ex) {
        echo $ex;
        exit(1);
    }

    return $payment;
  }

  public function paypalExecutePayment(Request $request)
  {

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            $this->clientID,     // ClientID
            $this->clientSecret  // ClientSecret
        )
    );

    $apiContext->setConfig(
      array(
        'mode' => 'live',
      )
    );  
    
    $paymentId = $request->paymentID;
    $payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    $execution->setPayerId($request->payerID);

    try {
        $result = $payment->execute($execution, $apiContext);
    } catch (Exception $ex) {
        echo $ex;
        exit(1);
    }

    if($result->payer->status == "VERIFIED"){
      //Update order
      $order = Order::find($result->transactions[0]->item_list->items[0]->sku);

      //get data
      $userId = $order->user_id;
      $membership = $order->product_id;


      //update data
      $order->status_id = 1;
      $order->transaction = $result->id;

      $order->save();                    

      //Attach membership
      Membership::attachMembership($userId, $membership);

    }



    return $result;
  }

}
