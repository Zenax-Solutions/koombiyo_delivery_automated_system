<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waybill</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .waybill {

            margin: 20px auto;
            padding: 20px;
            border: 1px solid #000;
        }


        @media print {
            .waybill {
                page-break-before: always;
            }

            .waybill-header h1,
            .waybill-header p,
            .waybill-content div,
            .waybill-content div label,
            .waybill-content div span,
            .cod,
            .note,
            .waybill-footer p {
                font-size: auto;
                /* Adjust font size for print */
            }

        }

        .box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 10px;
        }

        .waybill-header {
            text-align: left;
        }

        .waybill-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .waybill-header p {
            margin: 0;
            font-size: 16px;
        }

        .waybill-content {
            margin-bottom: 10px;
        }

        .waybill-content div {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .waybill-content div label {
            display: inline-block;
            width: 100px;
            font-weight: bold;
        }

        .waybill-content div span {
            display: inline-block;
        }

        .two-columns {
            display: flex;
            justify-content: space-between;
        }

        .two-columns .box {
            width: 48%;
        }

        .cod {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .note {
            font-size: 14px;
            font-style: italic;
        }

        .waybill-footer {
            text-align: center;
            margin-top: 20px;
        }

        .waybill-footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @forelse ($records as $data)
        <div class="waybill">
            <div class="box waybill-header two-columns">
                <div class="waybill-content">
                    <h1>{{ $data['branch']['name'] }}</h1>
                    <p>{!! $data['branch']['address'] !!}</p>
                    <p>{!! $data['branch']['contact_details'] !!}</p>
                </div>
                <div class="waybill-content">
                    <p>{{ now()->format('Y-m-d') }}</p>
                    <p>Order ID : #{{ $data['id'] }}</p>
                    <p>WayBill Id : #{{ $data['waybill_id'] }}</p>
                </div>
            </div>
            <div class="box waybill-content">
                <div>
                    <label>Name:</label>
                    <span>{{ $data['receiver_name'] }}</span>
                </div>
            </div>
            <div class="box waybill-content">
                <div>
                    <label>Address:</label>
                    <span>{{ $data['delivery_address'] }}</span>
                </div>
            </div>
            <div class="two-columns">
                <div class="box waybill-content">
                    <div>
                        <label>District:</label>
                        <span>{{ $data['district']['name_en'] }}</span>
                    </div>

                </div>
                <div class="box waybill-content">
                    <div>
                        <label>City:</label>
                        <span>{{ $data['city']['name_en'] }}</span>
                    </div>
                </div>
            </div>
            <div class="box waybill-content">
                <div>
                    <label>Phone No:</label>
                    <span>{{ $data['receiver_phone'] }}</span>
                </div>
            </div>
            <div class="box waybill-content cod">
                COD: {{ number_format($data['cod'], 2) }}
            </div>
            <div class="box waybill-content note">
                Note:
                @forelse($data['description'] as $item)
                    @php
                        $product = \App\Models\Product::find($item['product_id']);
                    @endphp
                    {{ $product->name }}
                    ({{ $item['size'] }})
                    X {{ $item['quantity'] }}{{ !$loop->last ? ',' : '' }}
                @empty
                @endforelse
            </div>
            <div class="box waybill-footer">
                <p>THANK YOU FOR SHOPPING WITH US!</p>
                <div
                    style="display: flex;
    justify-content: center;
    flex-wrap: nowrap;
    flex-direction: column;
    align-items: center;
    padding-top: 10px;">

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
        </div>
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
