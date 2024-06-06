<!-- component -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://rsms.me/inter/inter.css');

        .sf {
            font-family: 'Inter', sans-serif;
        }

        .sign {
            font-family: 'Homemade Apple', cursive;
        }


        @media print {
            section {
                page-break-before: always;
            }
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Homemade+Apple&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css"
        integrity="sha512-Cv93isQdFwaKBV+Z4X8kaVBYWHST58Xb/jVOcV9aRsGSArZsgAnFIhMpDoMDcFNoUtday1hdjn0nGp3+KZyyFw=="
        crossorigin="anonymous" />
</head>

<body>


    @forelse ($records as $data)
        <section
            class="bg-gray-200 print:bg-white md:flex lg:flex xl:flex print:flex md:justify-center lg:justify-center xl:justify-center print:justify-center sf">
            <div class="lg:w-1/12 xl:w-1/4"></div>
            <div
                class="w-full bg-white lg:w-full xl:w-2/3 lg:mt-20 lg:mb-20 lg:shadow-xl xl:mt-02 xl:mb-20 xl:shadow-xl print:transform print:scale-90">
                <header
                    class="flex flex-col items-center px-8 pt-20 text-lg text-center bg-white border-t-8 border-green-700 md:block lg:block xl:block print:block md:items-start lg:items-start xl:items-start print:items-start md:text-left lg:text-left xl:text-left print:text-left print:pt-8 print:px-2 md:relative lg:relative xl:relative print:relative">


                    <img style="width:300px !important" class="h-auto md:w-1/4 lg:ml-12 xl:ml-12 print:px-0 print:py-0"
                        src="{{ Storage::url($data['branch']['logo']) }}" />

                    <div
                        class="flex flex-row mt-12 mb-2 ml-0 text-2xl font-bold md:text-3xl lg:text-4xl xl:text-4xl print:text-2xl lg:ml-12 xl:ml-12">
                        Order ID :
                        <div class="text-green-700">
                            <span class="mr-4 text-sm"></span> #
                        </div>
                        <span id="invoice_id" class="text-gray-500">{{ $data['id'] }}</span>

                    </div>
                    <div
                        class="flex flex-row mt-12 mb-2 ml-0 text-2xl font-bold md:text-3xl lg:text-4xl xl:text-4xl print:text-2xl lg:ml-12 xl:ml-12">
                        WayBill ID :
                        <div class="text-green-700">
                            <span class="mr-4 text-sm"></span> #
                        </div>
                        <span id="invoice_id" class="text-gray-500">{{ $data['waybill_id'] }}</span>

                    </div>
                    <div class="mb-2 ml-0 text-2xl lg:ml-12 ">
                        <span>{{ now()->format('Y-m-d') }}</span>
                    </div>

                    <contract
                        class="flex flex-col m-12 text-center lg:m-12 md:flex-none md:text-left md:relative md:m-0 md:mt-16 lg:flex-none lg:text-left lg:relative xl:flex-none xl:text-left xl:relative print:flex-none print:text-left print:relative print:m-0 print:mt-6 print:text-sm">
                        <hr>
                        <br>
                        <span class="font-extrabold md:hidden lg:hidden xl:hidden print:hidden">FROM</span>
                        <from class="flex flex-col">
                            <span id="company-name" class="text-xl text-bold">Recipient Details</span>
                            <span id="person-name" class="text-xl">Name : {{ $data['receiver_name'] }}</span>
                            <span class="text-xl">District : {{ $data['district']['name_en'] }}</span>
                            <span class="text-xl">City : {{ $data['city']['name_en'] }}</span>
                            <div class="flex lg:flex">
                                <div id="c-city" class="text-xl">Address : </div>
                                <div id="c-postal" class="flex ml-2 text-xl">{!! $data['delivery_address'] !!}</div>
                            </div>
                            <span id="company-name" class="text-xl">Phone Number :
                                {{ $data['receiver_phone'] }}</span>

                            <br>

                        </from>
                    </contract>
                </header>
                <hr class="border-gray-300 md:mt-8 print:hidden">
                <br>
                <content>
                    <div id="content" class="flex justify-center md:p-8 lg:p-20 xl:p-20 print:p-2">
                        <table class="w-full text-left table-auto print:text-sm" id="table-items">
                            <thead>
                                <tr class="text-white bg-gray-700 print:bg-gray-300 print:text-black">
                                    <th class="px-4 py-2">Item</th>
                                    <th class="px-4 py-2 text-right">Size</th>
                                    <th class="px-4 py-2 text-right">Qty</th>
                                    <th class="px-4 py-2 text-right">Price</th>
                                    <th class="px-4 py-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($data['description'] as $item)
                                    @php
                                        $product = \App\Models\Product::find($item['product_id']);
                                    @endphp

                                    <tr>
                                        <td class="px-4 py-2 border">{{ $product->name }}</td>
                                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero">
                                            {{ $item['size'] }}</td>
                                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero">
                                            {{ $item['quantity'] }}</td>
                                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero">
                                            {{ $item['quantity'] . ' X ' . number_format($product->price) }}</td>
                                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero">
                                            {{ number_format($item['quantity'] * $product->price) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-4 py-2 border"></td>
                                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero"></td>
                                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero"></td>
                                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero"></td>
                                    </tr>
                                    <tr class="text-white bg-gray-700 print:bg-gray-300 print:text-black">
                                        <td class="invisible"></td>
                                        <td class="invisible"></td>
                                        <td class="px-4 py-2 font-extrabold text-right border">Total</td>
                                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero">Rs. 0</td>
                                    </tr>
                                @endforelse
                                <tr class="text-white bg-gray-700 print:bg-gray-300 print:text-black">
                                    <td class="invisible"></td>
                                    <td class="invisible"></td>
                                    <td class="invisible"></td>
                                    <td class="px-4 py-2 font-extrabold text-right border">Sub Total</td>
                                    <td class="px-4 py-2 text-right border tabular-nums slashed-zero">Rs.
                                        {{ number_format($data['cod'] - env('DELIVERY_CHARGES', 0)) }}</td>
                                </tr>
                                <tr class="text-white bg-gray-700 print:bg-gray-300 print:text-black">
                                    <td class="invisible"></td>
                                    <td class="invisible"></td>
                                    <td class="invisible"></td>
                                    <td class="px-4 py-2 font-extrabold text-right border">Delivery Charges

                                        <br> <span class="font-light">( All Island Delivery 🚚 )</span>

                                    </td>

                                    <td class="px-4 py-2 text-right border tabular-nums slashed-zero">Rs.
                                        {{ env('DELIVERY_CHARGES', 0) }}</td>
                                </tr>

                                <tr class="text-white bg-gray-700 print:bg-gray-300 print:text-black">
                                    <td class="invisible"></td>
                                    <td class="invisible"></td>
                                    <td class="invisible"></td>
                                    <td class="px-4 py-2 font-extrabold text-right border">Total</td>
                                    <td class="px-4 py-2 text-right border tabular-nums slashed-zero">Rs.
                                        {{ number_format($data['cod']) }}</td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                </content>
                <payment-history>
                    <div class="print:mb-2 print:mt-2">
                        <h2 class="text-xl font-semibold text-center print:text-2xl">COD : Rs.
                            {{ number_format($data['cod']) }}
                        </h2>
                        <div class="flex flex-col items-center mt-6 text-center print:text-sm">

                            @php
                                require_once base_path('vendor/autoload.php');

                                // This will output the barcode as HTML output to display in the browser
                                $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                                $barcodeHtml = $generator->getBarcode($data['waybill_id'], $generator::TYPE_CODE_128);
                            @endphp

                            {!! $barcodeHtml !!}
                            <p>{{ $data['waybill_id'] }}</p>

                        </div>

                    </div>
                    <hr>
                </payment-history>

            </div>

        </section>
    @empty

    @endforelse




</body>

<script type="text/javascript">
    // Print the page when it loads
    window.onload = function() {
        window.print();
    };
</script>

</html>
