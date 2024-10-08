<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaction;


class PaymentController extends Controller
{
     public function showForm()
     {
          return view('transaction.index');
     }

     public function processPayment(Request $request)
     {
          $request->validate([
               'amount' => 'required|numeric|min:0.01'
          ]);

          // Set Midtrans configuration
          Config::$serverKey = env('MIDTRANS_SERVER_KEY');
          Config::$isProduction = false;
          Config::$isSanitized = true;
          Config::$is3ds = true;

          // Prepare transaction details
          $transaction_details = [
               'order_id' => uniqid(),
               'gross_amount' => $request->amount,
          ];

          // Prepare customer details
          $customer_details = [
               'first_name' => 'Peter',
               'last_name' => 'Developer',
               'email' => 'peter@example.com',
               'phone' => '08123456789',
          ];

          // Create transaction
          $transaction = [
               'transaction_details' => $transaction_details,
               'customer_details' => $customer_details,
          ];

          try {
          $snapToken = Snap::getSnapToken($transaction);

          $data = new Transaction();
          $data->transaction_id = $transaction_details['order_id'];
          $data->amount = $request->amount;
          $data->status = 'pending';
          $data->save();

               return response()->json(['snap_token' => $snapToken]);
          } catch (\Exception $e) {
               return response()->json(['message' => 'Payment initiation failed!', 'error' => $e->getMessage()], 500);
          }
     }


}
