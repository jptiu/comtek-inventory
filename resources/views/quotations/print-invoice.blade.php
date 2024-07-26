<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        {{ config('app.name') }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet"
        href="{{ asset('assets/invoice/fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
</head>

<body>
    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner-9" id="invoice_wrapper">
                        <div class="invoice-top" style="display:flex; justify-content: center; align-items: center; align-content: center;">
                            <div style="display: inline-flex;">
                                <img src="{{ asset('static/logo.png') }}" width="90" height="90" alt="Comtek">
                                <div style="margin-left: 15px;font-weight: 600">
                                    @php
                                        $user = auth()->user();
                                    @endphp
                                    {{ Str::title($user->store_name) }}<br>
                                    {{ $user->store_address }}<br>
                                    {{ $user->store_email }}<br>
                                    {{ $user->store_phone }}<br>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-info">
                            <div class="row">
                                <div class="col-sm-6 mb-15">
                                </div>
                                @php
                                    $user = auth()->user();
                                @endphp
                                <div class="col-sm-6 text-end mb-15">
                                    <div>
                                        <p class="new-invoice-ref" style="font-size: 14px !important">
                                            Quotation # <span>{{ $quotation->reference }}</span>
                                        </p>
                                        <p class="invo-addr-1" style="font-size: 14px !important">
                                            Date: {{ $quotation->date->format('d-m-Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-summary">
                            <div class="table-outer">
                                <table class="default-table invoice-table">
                                    <thead>
                                        <tr>
                                            <th class="align-middle" style="font-size: 14px !important; width: 20px;">Item</th>
                                            <th class="align-middle text-center" style="font-size: 14px !important; width: 20px;">QTY</th>
                                            <th class="align-middle text-center" style="font-size: 14px !important; width: 20px;">Unit</th>
                                            <th class="align-middle" style="font-size: 14px !important">Description</th>
                                            <th class="align-middle text-center" style="font-size: 14px !important">Price</th>
                                            <th class="align-middle text-center" style="font-size: 14px !important">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{--                                            @foreach ($quotationDetails as $item) --}}
                                        @foreach ($quotation->quotationDetails as $item)
                                            <tr>
                                                <td class="align-middle" style="font-size: 12px !important">
                                                    {{ $item->id }}
                                                </td>
                                                <td class="align-middle text-center" style="font-size: 12px !important">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="align-middle text-center" style="font-size: 12px !important">
                                                    {{ $item->product->unit->name }}
                                                </td>
                                                <td class="align-middle" style="font-size: 12px !important">
                                                    {{ $item->product->name }}
                                                </td>
                                                <td class="align-middle text-center" style="font-size: 12px !important">
                                                    {{ Number::currency($item->unit_price, 'PHP') }}
                                                </td>
                                                <td class="align-middle text-center" style="font-size: 12px !important">
                                                    {{ Number::currency($item->sub_total, 'PHP') }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>
                                                    Subtotal
                                                </strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ $quotation->sub_total }}
                                                </strong>
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            <td colspan="5" class="text-end" style="font-size: 14px !important">
                                                <strong>Discount</strong>
                                            </td>
                                            <td class="align-middle text-center" style="font-size: 12px !important">
                                                <strong>
                                                    {{ Number::currency($quotation->discount_amount, 'PHP') }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end" style="font-size: 14px !important">
                                                <strong>Tax</strong>
                                            </td>
                                            <td class="align-middle text-center" style="font-size: 12px !important">
                                                <strong>
                                                    {{ Number::currency($quotation->tax_amount, 'PHP') }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end" style="font-size: 14px !important">
                                                <strong>Total</strong>
                                            </td>
                                            <td class="align-middle text-center" style="font-size: 12px !important">
                                                <strong>
                                                    {{ Number::currency($quotation->total_amount, 'PHP') }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="invoice-informeshon-footer">
                                <ul>
                                    <li><a href="#">www.website.com</a></li>
                                    <li><a href="mailto:sales@hotelempire.com">info@example.com</a></li>
                                    <li><a href="tel:+088-01737-133959">+62 123 123 123</a></li>
                                </ul>
                            </div> --}}
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>
                            Print Quotation
                        </a>
                        <a id="invoice_download_btn" class="btn btn-lg btn-download">
                            <i class="fa fa-download"></i>
                            Download Quotation
                        </a>
                    </div>

                    {{-- back button --}}
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="{{ route('quotations.index') }}" class="btn btn-lg btn-print">
                            <i class="fa fa-arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/invoice/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/html2canvas.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/app.js') }}"></script>
</body>

</html>
