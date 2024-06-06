<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Picqer\Barcode\BarcodeGeneratorHTML;

class FormController extends Controller
{
    public function view(Request $request)
    {
        if ($request->branch == null) {
            return back();
        } else {
            Branch::where('slug', $request->branch)->firstOrFail();

            Session::put('slug', $request->branch);
        }

        return view('pages.order-form');
    }


    public function checkout()
    {
        return view('pages.check-out');
    }


    public function waybill(Request $request)
    {
        if ($request->id) {

            $data = Order::find($request->id);

            if ($data == null) {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }

        return view('pages.waybill', compact('data', 'barcodeHtml'));
    }


    public function bulk_waybill(Request $request)
    {

        $records = Session::get('bulkwaybill_records');

        if ($records != null) {
            return view('pages.bulk_waybill', compact('records'));
        } else {
            return redirect()->back();
        }
    }


    public function thankyouPage()
    {
        if (session()->has('slug') == false) {

            $this->redirect('https://www.google.com/');
        }
        return view('pages.thank-you');
    }
}
