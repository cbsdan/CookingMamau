<?php

namespace App\Http\Controllers;

use Mail;
use Exception;
use App\Models\Order;
use App\Models\OrderedGood;
use App\Models\Payment;
use App\Mail\MailNotify;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendOrderReceipt(Order $order)
    {
        // Retrieve the ordered goods associated with the order
        $orderedGoods = OrderedGood::where('id_order', $order->id)->get();

        // Retrieve the payment information associated with the order
        $payment = Payment::where('id_order', $order->id)->first();

        // Calculate the total price of goods ordered
        $totalPrice = 0;
        foreach ($orderedGoods as $orderedGood) {
            $totalPrice += $orderedGood->price_per_good * $orderedGood->qty;
        }

        // Calculate the discount amount
        $discountAmount = 0;
        if ($order->discount) {
            $discountAmount = $totalPrice * ($order->discount->percent / 100);
        }

        // Calculate the grand total price
        $grandTotal = $totalPrice - $discountAmount + $order->shipping_cost;

        // Prepare the email content
        $data = [
            "subject" => "Order Receipt",
            "body" => "<table border='1' style='width: 100%; border-collapse: collapse; border: 1px solid #ddd;'>" .
                    "<tr><td>Order ID</td><td>" . $order->id . "</td></tr>" .
                    "<tr><td>Order Status</td><td>" . $order->order_status . "</td></tr>" .
                    "<tr><td>Buyer Name</td><td>" . $order->buyer_name . "</td></tr>" .
                    "<tr><td>Email</td><td>" . $order->email_address . "</td></tr>" .
                    "<tr><td>Delivery Address</td><td>" . $order->delivery_address . "</td></tr>" .
                    "<tr><td>Ordered Goods</td><td><ul style='list-style:none; padding: 0; margin: 0'>" . $this->formatOrderedGoods($orderedGoods) . "</ul></td></tr>" .
                    "<tr><td>Total Price</td><td>₱" . $totalPrice . "</td></tr>" .
                    "<tr><td>Discount Code</td><td>" . ($order->discount ? $order->discount->discount_code : "N/A") . "</td></tr>" .
                    "<tr><td>Discount Percentage</td><td>" . ($order->discount ? $order->discount->percent . "%" : "N/A") . "</td></tr>" .
                    "<tr><td>Discount Amount</td><td>₱" . number_format($discountAmount, 2) . "</td></tr>" .
                    "<tr><td>Shipping Cost</td><td>₱" . $order->shipping_cost . "</td></tr>" .
                    "<tr><td>Grand Total</td><td>₱" . number_format($grandTotal, 2) . "</td></tr>" .
                    "<tr><td>Payment Mode</td><td>" . ($payment ? $payment->mode : "N/A") . "</td></tr>" .
                    "<tr><td>Amount Paid</td><td>₱" . ($payment ? $payment->amount : "N/A") . "</td></tr>" .
                    "</table>"
        ];

        try {
            // Attempt to send the email
            Mail::to($order->email_address)->send(new MailNotify($data));
            Mail::to("cookingmamau@gmail.com")->send(new MailNotify($data));
            
            // If sending is successful, return success response
            return redirect()->route('user.order.show', $order->id)->with('success', 'Order status is delivered. Email Sent!');
        } catch (Exception $e) {
            // If an exception occurs during sending, return error response with the exception message
            return redirect()->route('user.order.show', $order->id)->with('success', "Order status is delivered. Email failed to sent!. ". $e->getMessage());
        }
    }

    // Helper function to format ordered goods
    private function formatOrderedGoods($orderedGoods)
    {
        $formattedGoods = '';

        foreach ($orderedGoods as $orderedGood) {
            $formattedGoods .= "<li>" . $orderedGood->meal->name . ": ₱" . $orderedGood->price_per_good . " x " . $orderedGood->qty . "</li>";
        }

        return $formattedGoods;
    }

}
