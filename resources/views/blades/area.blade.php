@extends('layouts.main')

@section('content')

@if(isset($lists))
@include('inc.message')
    @if($lists->count() > 0) 
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>EACODE</th>
                            <th>HCN</th>
                            <th>SHSN</th>
                            <th>HOUSEHOLD HEAD</th>
                            <th>H LEVEL</th>
                            <th>I LEVEL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $list)
                        <tr>
                            <td>{{$list->eacode}}</td>
                            <td>{{$list->hcn}}</td>
                            <td>{{$list->shsn}}</td>
                            <td>{{$list->hhead}}</td>
                            <td>
                                <a href="{{ route('household', ['eacode'=>$list->eacode, 'hcn'=>$list->hcn, 'shsn'=>$list->shsn, 'hh'=>$list->hhead  ])}}">
                                <button>HH</button>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('individuals', ['eacode'=>$list->eacode, 'hcn'=>$list->hcn, 'shsn'=>$list->shsn  ])}}">
                                <button>INDV</button>
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