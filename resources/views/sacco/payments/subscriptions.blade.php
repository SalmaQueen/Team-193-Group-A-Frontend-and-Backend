@extends('layouts.sacco')
@section('content')
    @if(isset($subscribers))
        <table class="table">
            <tr>
                <th>PHONE NUMBER</th>
                <th>AMOUNT</th>
                <th>PERIOD</th>
                <th>DAILY SCANS</th>
                <th>START DATE</th>
            </tr>
            @foreach($subscribers as $subscriber)
                <tr>
                    <td>{{$subscriber->PhoneNumber}}</td>
                    <td>{{$subscriber->amount}}</td>
                    <td>{{$subscriber->period}} days</td>
                    <td>{{$subscriber->number_of_scans}}</td>
                    <td>{{$subscriber->created_at->diffForHumans()}}</td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection

