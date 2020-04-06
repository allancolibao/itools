@extends('layouts.main')

@section('content')
@include('inc.message')
<h5>Are you sure want to delete household on all tables?</h5>
    <h6>EACODE: {{$eacode}}</h6> 
    <h6>HCN: {{$hcn}}</h6> 
    <h6>SHSN: {{$shsn}}</h6> 
<form method="POST" action="{{ action('MainController@deleteAllTables', ['eacode'=>$eacode, 'hcn'=>$hcn, 'shsn'=>$shsn, 'hh'=>$hh]) }}">
    @csrf
    <input type="hidden" name="_method" value="DELETE" />
    <input id="password" type="password" class="form-control" placeholder="Please enter password" name="password" required autocomplete="off">
    <button type="submit" class="btn">Delete</button>
</form>
@endsection