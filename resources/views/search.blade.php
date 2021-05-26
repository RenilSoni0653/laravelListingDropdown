@extends('layouts.app')

@section('content')
@section('title','Search Details')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard
                    <form method="GET" action="{{ route('productList') }}">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" name="search" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default-sm" type="submit">
                                    <i class="fa fa-search">Search
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                @php $i=0; @endphp

                <div class="card-body">
                    <table width="100%" class="table table-bordered table-striped">
                    <tr>
                        <td>No</td>
                        <td>Name</a></td>
                        <td>Description</a></td>
                        <td>Country</td>
                        <td>State</a></td>
                        <td>City</a></td>
                        <td>File</td>
                        <td><a class="pl-2" href="/home">Add</a></td>
                    </tr>

                    @if($users->isNotEmpty())
                        @foreach($users as $data)
                            <tr>
                                <td>{{ $i = $i + 1 }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->description }}</td>
                                <td>{{ $data->country }}</td>
                                <td>{{ $data->state }}</td>
                                <td>{{ $data->city }}</td>
                                <td>
                                    @if($data->TextFile == NULL)
                                        <img src="/img/No_image.svg.png" width="50px" height="50px">
                                    @else
                                        <img src="/uploads/{{ $data->TextFile }}" width="50px" height="50px">
                                    @endif
                                </td>
                                <td>
                                <form method="POST" action="{{ url('list/'.$data->id.'/edit') }}">
                                @csrf
                                    <button class="btn btn-primary">Edit</button>
                                    <input type="hidden" name="name" value="{{ $data->name }}">
                                    <input type="hidden" name="description" value="{{ $data->description }}">
                                    <input type="hidden" name="country" value="{{ $data->country }}"> 
                                    <input type="hidden" name="country_id" value="{{ $countryId = DB::table('countries')->where('name','=',$data->country)->pluck('id') }}">                                                                       
                                    <input type="hidden" name="state" value="{{ $data->state }}">
                                    <input type="hidden" name="state_id" value="{{ $stateId = DB::table('states')->where('name','=',$data->state)->pluck('country_id') }}">
                                    <input type="hidden" name="city" value="{{ $data->city }}">
                                    <input type="hidden" name="city_id" value="{{ $cityId = DB::table('cities')->where('name','=',$data->city)->pluck('state_id') }}">
                                    <input type="hidden" name="file" value="{{ $data->file }}">
                                </form>
                            </td>
                            </tr>
                        @endforeach
                    @else
                        <div class="d-flex justify-content-center">
                            {{ 'Data Not Found' }}
                        </div>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection