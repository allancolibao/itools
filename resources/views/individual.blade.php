@extends('index')

@section('content')
<div class="update-form">
    <h5>Are you sure you want to update?</h5>
      <h6>EACODE: {{$eacode}}</h6> 
      <h6>HCN: {{$hcn}}</h6> 
      <h6>SHSN: {{$shsn}}</h6> 
      <h6>MEMBER CODE: {{$memcode}} {{$surname}}, {{$givenname}}</h6>
      <h6>Please enter the new & correct EACODE, HCN, SHSN, MEMBER CODE!</h6>  
    <form method="POST" action="#">
        <input type="text" name="eacode" class="form-control" placeholder="Enter new EACODE" value={{$eacode}}>
        <input type="text" name="hcn" class="form-control" placeholder="Enter new HCN" value={{$hcn}}>
        <input type="text" name="shsn" class="form-control" placeholder="Enter new SHSN" value={{$shsn}}>
        <input type="text" name="MEMBER_CODE" class="form-control" placeholder="Enter new MEMBER CODE" value={{$memcode}}>
        <button class="btn">Update</button>
    </form>
</div>
@endsection