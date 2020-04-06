@extends('layouts.main')

@section('content')
@include('inc.message')
<div class="update-form">
    <h5>Are you sure you want to update?</h5>
      <h6>EACODE: {{$eacode}}</h6> 
      <h6>HCN: {{$hcn}}</h6> 
      <h6>SHSN: {{$shsn}}</h6> 
      <h6>MEMBER CODE: {{$memcode}} {{$surname}}, {{$givenname}}</h6>
      <h6>Please enter the new & correct EACODE, HCN, SHSN, MEMBER CODE!</h6>  
    <form method="POST" action="{{ action('MainController@updateIndividuals', ['eacode'=>$eacode, 'hcn'=>$hcn, 'shsn'=>$shsn, 'memcode'=>$memcode,  'surname'=>$surname, 'givenname'=>$givenname]) }}">
        @csrf
        <input type="hidden" name="_method" value="PATCH" />  
        <input type="text" name="eacode" class="form-control" placeholder="Enter new EACODE" value={{$eacode}}>
        <input type="text" name="hcn" class="form-control" placeholder="Enter new HCN" value={{$hcn}}>
        <input type="text" name="shsn" class="form-control" placeholder="Enter new SHSN" value={{$shsn}}>
        <input type="text" name="MEMBER_CODE" class="form-control" placeholder="Enter new MEMBER CODE" value={{$memcode}}>
        <button class="btn">Update</button>
    </form>
    <h5>Other features, Choose the right one!</h5>
    <a class="rep-link" href="{{route('to-delete-individual', ['eacode'=>$eacode, 'hcn'=>$hcn, 'shsn'=>$shsn, 'memcode'=>$memcode, 'surname'=>$surname, 'givenname'=>$givenname  ])}}"><h6>Delete this member? Click here!</h6></a>
</div>
@endsection