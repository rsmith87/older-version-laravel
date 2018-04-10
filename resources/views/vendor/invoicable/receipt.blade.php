
@extends('layouts.dashboard')

@section('content')

<div class="container dashboard invoice-single col-sm-10 col-xs-12 offset-sm-2">
    <nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-info" href="/dashboard/cases/case/{{ $case->id }}"><i class="fas fa-search"></i> View case</a>
    <a class="nav-item nav-link btn btn-info" href="/dashboard/cases"><i class="fas fa-briefcase"></i> My cases</a>      
    <a class="nav-item nav-link btn btn-info" href="/dashboard/invoices"><i class="fa fa-file-alt"></i> My invoices</a>      
  </nav>  
  


    <div class="invoice-box">
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
     </div>




