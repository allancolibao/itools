@extends('layouts.main')

@section('content')
@include('inc.message')
@if(isset($indivs))
    @if($indivs->count() > 0) 
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>HCN</th>
                            <th>SHSN</th>
                            <th>MEMBER CODE</th>
                            <th>SURNAME</th>
                            <th>GIVENNAME</th>
                            <th>EDIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($indivs as $indiv)
                        <tr>
                            <td>{{$indiv->hcn}}</td>
                            <td>{{$indiv->shsn}}</td>
                            <td>{{$indiv->MEMBER_CODE}}</td>
                            <td>{{$indiv->SURNAME}}</td>
                            <td>{{$indiv->GIVENNAME}}</td>
                            <td>
                                <a href="{{ route('individual', ['eacode'=>$indiv->eacode, 'hcn'=>$indiv->hcn, 'shsn'=>$indiv->shsn, 'memcode'=>$indiv->MEMBER_CODE, 'surname'=>$indiv->SURNAME, 'givenname'=>$indiv->GIVENNAME  ])}}">
                                <button>EDIT</button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <h5>No record to display!</h5>
        @endif
    @endif
@endsection