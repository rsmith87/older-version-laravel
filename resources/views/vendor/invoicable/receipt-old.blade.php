<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Receipt</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #fff;
            background-image: none;
            font-size: 12px;
        }
        address{
            margin-top:15px;
        }
        h2 {
            font-size:28px;
            color:#cccccc;
        }
        .container {
            padding-top:30px;
        }
        .invoice-head td {
            padding: 0 8px;
        }
        .invoice-body{
            background-color:transparent;
        }
        .logo {
            padding-bottom: 10px;
        }
        .table th {
            vertical-align: bottom;
            font-weight: bold;
            padding: 8px;
            line-height: 20px;
            text-align: left;
        }
        .table td {
            padding: 8px;
            line-height: 20px;
            text-align: left;
            vertical-align: top;
            border-top: 1px solid #dddddd;
        }
        .well {
            margin-top: 15px;
        }
    </style>
</head>

<body>

        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="https://www.sparksuite.com/images/logo.png" style="width:100%; max-width:300px;">
                            </td>
                            
                            <td>
                                Invoice #: {{ $invoice->id }}<br>
                                Created:  {{ \Carbon\Carbon::parse($invoice->created_at)->format('F d, Y') }}<br>
                                Due: {{ \Carbon\Carbon::parse($invoice->created_at)->format('F d, Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                              {{ $client->first_name }} {{ $client->last_name }}<br />
                              {{ $client->address_1 }} {{ $client->address_2}}<br />
                              {{ $client->city  }}, {{ $client->state }} {{ $client->zip }}
                            </td>
                            
                            <td>
                               {{ $client->name }}<br />
                               {{ $client->company_title }} | {{ $client->company }}<br />
                               {{ $client->phone }}<br />
                               {{ $client->email }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
           
            
  
            
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td>
                    Price
                </td>
            </tr>
            
          @foreach($invoice->InvoiceLines as $invoice_line)
            <tr class="item">
                <td>
                   {{ $case->name }}
                </td>
                
                <td>
                    ${{ $invoice_line->amount }}
                </td>
            </tr>
          @endforeach     
         
            
            <tr class="total">
                <td></td>
                
                <td>
                   Total: ${{ $invoice_line->amount }}
                </td>
            </tr>
          
          <tr><td></td><td><a class="btn btn-primary">Pay</a></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td></tr>         
          <tr>
            <td>
              Thank you for your continued support!
            </td>
          </tr>
          
         
        </table>
    </div>

