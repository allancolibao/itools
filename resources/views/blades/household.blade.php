@extends('layouts.main')

@section('content')
@include('inc.message')
<div class="update-form">
    <h5>Are you sure you want to update?</h5>
      <h6>EACODE: {{$eacode}}</h6> 
      <h6>HCN: {{$hcn}}</h6> 
      <h6>SHSN: {{$shsn}}</h6>
      <h6>Please enter the new & correct EACODE, HCN, SHSN!</h6> 
    <form method="POST" action="{{ action('MainController@updateHousehold', ['eacode'=>$eacode, 'hcn'=>$hcn, 'shsn'=>$shsn, 'hh'=>$hh]) }}">
      @csrf
      <input type="hidden" name="_method" value="PATCH" />  
      <input type="text" name="eacode" class="form-control" placeholder="Enter new EACODE" value={{$eacode}}>
      <input type="text" name="hcn" class="form-control" placeholder="Enter new HCN" value={{$hcn}}>
      <input type="text" name="shsn" class="form-control" placeholder="Enter new SHSN" value={{$shsn}}>
      <button class="btn">Update</button>
    </form>
    <h5>Other features, Choose the right one!</h5>
    <a class="rep-link" href="{{route('replacement', ['eacode' => $eacode, 'hcn'=> $hcn, 'shsn' => $shsn,'hh'=>$hh ])}}"><h6>Replacement? Click here!</h6></a>
    <a class="rep-link" href="{{route('to-delete-listings', ['eacode' => $eacode, 'hcn'=> $hcn, 'shsn' => $shsn, 'hh'=>$hh ])}}"><h6>Delete on listings only? Click here!</h6></a>
    <a class="rep-link" href="{{route('to-delete-all-tables', ['eacode' => $eacode, 'hcn'=> $hcn, 'shsn' => $shsn, 'hh'=>$hh ])}}"><h6>Delete on all tables [except listings]? Click here!</h6></a>
</div>
@endsection