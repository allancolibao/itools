@extends('layouts.main')

@section('content')
@include('inc.message')
<h5>Are you sure this is replacement?</h5>
      <h6>EACODE: {{$eacode}}</h6> 
      <h6>HCN: {{$hcn}}</h6> 
      <h6>SHSN: {{$shsn}}</h6>
    <form method="POST" action="{{ action('MainController@saveReplacement', ['eacode'=>$eacode, 'hcn'=>$hcn, 'shsn'=>$shsn, 'hh'=>$hh]) }}">
      @csrf
      <input type="hidden" name="_method" value="PATCH" />
      <div class="input-group">
        <input type="checkbox" name="REF_DATE" placeholder="Enter new EACODE" value="1" {{ $replacement->REF_DATE == '1'  ? 'checked' : ''}}>
        <label class="rep-label" for="REF_DATE">Replacement? Please check!</label>  
      </div>
        <input type="text" name="DATE_EDIT" class="form-control" placeholder="HCN/SHSN ORIGINAL HH" value="{{$replacement->DATE_EDIT}}" >
        <button class="btn">Save</button>
    </form>

@endsection