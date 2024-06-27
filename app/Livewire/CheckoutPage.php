<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use App\Models\Order;
use App\Models\Product;
use App\Services\koombiyoApi;
use Livewire\Component;

class CheckoutPage extends Component
{
    public $cart = [];
    public $totalPrice = 0;

    public $name;
    public $address;
    public $district;
    public $city;
    public $primaryContact;
    public $secondaryContact;

    public $districtList, $cityList;

    public $branch;

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalPrice = 0;
        foreach ($this->cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $this->totalPrice += $product->price * $item['quantity'];
            }
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        session()->put('cart', $this->cart);
        $this->calculateTotal();
    }

    public function placeOrder()
    {

        $branch = Branch::where('slug', session()->get('slug'))->firstOrFail();

        // Validate input
        $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'primaryContact' => 'required|string',
        ]);

        // Logic to place order


        if (isset($branch->api_key) && $branch->api_enable == true) {


            $koombiyoApi = new koombiyoApi;

            $orderWaybillid = $koombiyoApi->getAllAllocatedBarcodes($branch->api_key);


            $koombiyoData = [
                'apikey' => $branch->api_key,
                'orderWaybillid' => $orderWaybillid['waybills'][0]['waybill_id'],
                'orderNo' => $orderWaybillid['waybills'][0]['waybill_id'],
                'receiverName' => $this->name,
                'receiverStreet' => $this->address,
                'receiverDistrict' => $this->district,
                'receiverCity' => $this->city,
                'receiverPhone' => $this->primaryContact,
                'description' => rtrim($this->productDataTrim(), ', '),
                'spclNote' => '',
                'getCod' => $this->totalPrice + env('DELIVERY_CHARGES', 0),
            ];


            Order::create([
                'branch_id' => $branch->id,
                'waybill_id' => $orderWaybillid['waybills'][0]['waybill_id'],
                'order_number' => $orderWaybillid['waybills'][0]['waybill_id'],
                'receiver_name' => $this->name,
                'delivery_address' => $this->address,
                'district_id' => $this->district,
                'city_id' => $this->city,
                'receiver_phone' => $this->primaryContact,
                'cod' => $this->totalPrice + env('DELIVERY_CHARGES', 0),
                'description' => $this->cart,
            ]);


            $koombiyoApi->addOrder($koombiyoData);
        } else {
            Order::create([
                'branch_id' => $branch->id,
                'waybill_id' => '',
                'order_number' => '',
                'receiver_name' => $this->name,
                'delivery_address' => $this->address,
                'district_id' => $this->district,
                'city_id' => $this->city,
                'receiver_phone' => $this->primaryContact,
                'cod' => $this->totalPrice + env('DELIVERY_CHARGES', 0),
                'description' => $this->cart,
            ]);
        }


        // Clear cart
        session()->forget('cart');
        $this->cart = [];
        $this->totalPrice = 0;

        // Notify user

        toastr()->success('Order placed successfully!');

        $this->redirect('/thank-you');
    }


    public function productDataTrim()
    {
        foreach ($this->cart as $key => $value) {
            $this->cart[$value['product_id']] = [
                'product_id' => $value['product_id'],
                'size' => $value['size'],
                'quantity' => $value['quantity'],
            ];

            $product = Product::find($value['product_id']);

            if ($product) {
                return $product->name . ' (' . $value['size'] . ') X ' . $value['quantity'] . ', ';
            }
        }
    }

    public function render()
    {

        $cart = session()->get('cart', []);

        if (session()->has('slug') == false) {

            $this->redirect('https://www.google.com/');
        } else if (empty($cart)) {
            if (session()->has('slug') == false) {
                $this->redirect('https://www.google.com/');
            }
            $this->redirect('/order-form' . '/' . session()->get('slug'));
        }


        $this->branch = Branch::where('slug', session()->get('slug'))->firstOrFail();

        if (isset($this->branch->api_key) && $this->branch->api_enable == true) {

            $koombiyoApi = new koombiyoApi;

            $this->districtList = $koombiyoApi->getAllDistrict($this->branch->api_key);

            $this->cityList = $koombiyoApi->getAllCities($this->branch->api_key, $this->district);
        } else {
            $this->districtList = District::all();

            $this->cityList = City::where('district_id', $this->district)->get();
        }



        return view('livewire.checkout-page');
    }
}
