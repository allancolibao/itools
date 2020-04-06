@extends('layouts.main')

@section('content')
@include('inc.message')
<h5>Are you sure you want to delete this member?</h5>
    <h6>EACODE: {{$eacode}}</h6> 
    <h6>HCN: {{$hcn}}</h6> 
    <h6>SHSN: {{$shsn}}</h6> 
    <h6>MEMBER CODE: {{$memcode}} {{$surname}}, {{$givenname}}</h6>
<form method="POST" action="{{ action('MainController@deleteIndividual', ['eacode'=>$eacode, 'hcn'=>$hcn, 'shsn'=>$shsn, 'memcode'=>$memcode, 'surname'=>$surname, 'givenname'=>$givenname]) }}">
    @csrf
    <input type="hidden" name="_method" value="DELETE" />
    <input id="password" type="password" class="form-control" placeholder="Please enter password" name="password" required autocomplete="off">
    <button type="submit" class="btn">Delete</button>
</form>
@endsection