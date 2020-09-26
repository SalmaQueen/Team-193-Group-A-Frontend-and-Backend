@extends('layouts.sacco')
@section('content')
    @if(isset($payments))
        <table class="table">
            <tr>
                <th>MPESA RECEIPT</th>
                <th>REGISTRATION NUMBER</th>
                <th>PHONE NUMBER</th>
                <th>AMOUNT</th>
                <th>DATE</th>
            </tr>
            @foreach($payments as $payment)
                <tr>
                    <td>{{$payment->MpesaReceiptNumber}}</td>
                    <td>{{$payment->vehicle_registration_number}}</td>
                    <td>{{$payment->PhoneNumber}}</td>
                    <td>{{$payment->Amount}}</td>
                    <td>{{$payment->created_at->diffForHumans()}}</td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection
