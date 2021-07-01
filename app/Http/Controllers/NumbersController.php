<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\OrderNumbers;
use App\Models\ForwardNumbers;
use App\Mail\ForwardEmail;
use Illuminate\Support\Facades\Mail;

class NumbersController extends Controller
{
    /**
     * Search api
     *
     * @return array
     */
    public function search(Request $request)
    {

        $search_query = $request->all();
        $response = [];
        $vanity = '';

        if($search_query) {
            $data = $request->validate([
                'area_code' => 'required|numeric',
                'vanity' => 'required|alpha_num|min:3'
            ]);

            $search_query['vanity'] = str_replace(" ", "", $search_query['vanity']);
            $vanity = $search_query['vanity'];
            $query_string = http_build_query($search_query);
            $url = env('API_URL') . '?' . $query_string;

            // Search request
            $response = json_decode(Http::withToken(env('API_TOKEN'))->get($url));
        }

        return view('search', [
            'numbers' => $response,
            'search_query' => $search_query,
            'vanity' => $vanity
        ]);
    }

    /**
     * Display Numbers.
     *
     * @return array
     */
    public function myNumbers(Request $request)
    {
        $search_query = $request->all();
        $all_numbers = false;
        $my_numbers = OrderNumbers::leftJoin('forward_numbers', 'forward_numbers.order_number_id', '=', 'order_numbers.id');

        if(Route::currentRouteName() =='all-numbers') {
            $my_numbers->leftJoin('users', 'users.id', '=', 'order_numbers.user_id')
            ->select('order_numbers.id', 'order_numbers.phone', 'order_numbers.vanity', 'order_numbers.area_code', 'order_numbers.price', 'order_numbers.created_at', 'forward_numbers.forward_to', 'users.name', 'users.email');
            $all_numbers = true;
        }
        else {
            $my_numbers->where('user_id', Auth::id())
            ->select('order_numbers.id', 'order_numbers.phone', 'order_numbers.vanity', 'order_numbers.area_code', 'order_numbers.price', 'order_numbers.created_at', 'forward_numbers.forward_to');
        }

        if($search_query) {
            if(!empty($search_query['phone'])) {
               $my_numbers->where('phone', 'LIKE', '%' . $search_query['phone']);
            }
            if(!empty($search_query['vanity'])) {
                $my_numbers->where('vanity', 'LIKE', '%' . $search_query['vanity']);
            }
            if(!empty($search_query['area_code'])) {
                $my_numbers->where('area_code', $search_query['area_code']);
            }
            if(!empty($search_query['forward_to'])) {
                $my_numbers->where('forward_to', $search_query['forward_to']);
            }
            if(!empty($search_query['start'])) {
                $my_numbers->where('order_numbers.created_at', '>', $search_query['start'] . ' 00:00:00');
            }
            if(!empty($search_query['end'])) {
                $my_numbers->where('order_numbers.created_at', '<', $search_query['end'] . ' 23:59:59');
            }
        }

        $search_results = $my_numbers->orderBy('order_numbers.created_at', 'desc')->paginate(25);

        return view('my-numbers', [
            'search_results' => $search_results,
            'search_query' => $search_query,
            'all_numbers' => $all_numbers
        ]);
    }

     /**
     * Forward Numbers.
     *
     * @return array
     */
    public function forwardNumbers(Request $request)
    {
        return view('forward-numbers', [
            'forward_from' => $request->get('phone'),
            'vanity' => $request->get('vanity')
        ]);
    }

    /**
     * Create Forwarding Numbers.
     *
     * @return array
     */
    public function forwardCreate(Request $request)
    {
        $forward_request = $request->all();
        $user_id = Auth::id();

        if($forward_request) {
            // Validate form
            $form_validate = $request->validate([
                'forward_to' => 'required|numeric|digits:10'
            ]);

            // Reserve number
            $reserve_results = $this->reserveNumbers($user_id, $forward_request['forward_from'], $forward_request['vanity']);

            if($reserve_results){
                // Send email notification
                $emails = explode(',', env('FORWARD_EMAILS'));
                Mail::to($emails)->send(new ForwardEmail($forward_request));

                // Get number record
                $number_data = OrderNumbers::firstWhere('phone', $forward_request['forward_from']);

                // Save forwarding info
                $forward = new ForwardNumbers;
                $forward->order_number_id = $number_data->id;
                $forward->forward_to = $forward_request['forward_to'];
                $forward->save();

                $message = "Success! $forward_request[forward_from] was reserved";
                return redirect()->route('my-numbers')->with('status-success', $message);
            }
            else {
                $message = "Error! $forward_request[forward_from] was unable to reserve";
                return redirect()->route('my-numbers')->with('status-error', $message);
            }
        }
    }

    /**
     * Edit Forwarding Numbers.
     *
     * @return array
     */
    public function forwardEdit($id)
    {
        $number_data = OrderNumbers::where('order_numbers.id', $id)
            ->leftJoin('forward_numbers', 'forward_numbers.order_number_id', '=', 'order_numbers.id')->first();

        return view('forward-numbers-edit', [
            'number_data' => $number_data
        ]);
    }

    /**
     * Update Forwarding Numbers.
     *
     * @return array
     */
    public function forwardUpdate(Request $request, $id)
    {
        $forward_request = $request->all();
        $number_data = OrderNumbers::find($id)->forwardNumber;

        // Validate form
        $form_validate = $request->validate([
            'forward_to' => 'required|numeric|digits:10'
        ]);

        if($form_validate) {
            // Send email notification
            $emails = explode(',', env('FORWARD_EMAILS'));
            Mail::to($emails)->send(new ForwardEmail($forward_request));

            // Update data
            $forward = forwardNumbers::find($number_data->id);
            $forward->forward_to = $request->forward_to;
            $forward->save();

            $message = 'Success! A notification hase been sent to update the forwarding';

            return redirect()->route('my-numbers')->with('status-success', $message);
        }

    }


    /**
     * Reserve Numbers.
     *
     * @return array
     */
    private function reserveNumbers($user_id, $phone_number, $vanity)
    {
        // Get number data
        $phone_url = env('API_URL') . '/' . $phone_number;
        $number_data = Http::withToken(env('API_TOKEN'))->get($phone_url);

        // Reserve request
        $order_url = env('API_URL') . '/' . $phone_number . '/order';
        $order_response = Http::withToken(env('API_TOKEN'))->post($order_url);

        // Successful reservation
        if($order_response->successful() && $number_data->successful()) {
            $number_data = json_decode($number_data);

            // Add number to account
            $order = new OrderNumbers;
            $order->user_id = $user_id;
            $order->phone = $phone_number;
            $order->vanity = $vanity;
            $order->area_code = $number_data->area_code;
            $order->state = $number_data->state;
            $order->price = $number_data->price;
            $order->save();

            return true;
        }
        // Failed reservation
        else {
            return false;
        }
    }
}
