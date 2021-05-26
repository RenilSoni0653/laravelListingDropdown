@extends('layouts.app')

@section('content')
@section('title','Enter Details')
<div class="container">
    <div class="panel panel-default">
      <div class="panel-body">
        <form method="POST" action="/allLists">
        @csrf
            <div class="form-group">
                <label for="name">Enter Name: </label>
                <input type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control" style="width:350px">
            </div>

            <div class="form-group">
                <label for="description">Enter Description: </label>
                <textarea class="description" name="description" id="description" style="width:350px"></textarea>
            </div>

            <div class="form-group">
                <label for="country">Select Country:</label>
                    <select id="country" name="country"  class="form-control" style="width:350px">
                    <option value="" selected disabled>Select Country</option>
                        @foreach($countries as $key => $country)
                            <option value="{{$key}}"> {{$country}}</option>
                        @endforeach
                    </select>
            </div>

            <div class="form-group">
                <label for="state">Select State:</label>
                <select name="state" id="state" class="form-control" style="width:350px">
                </select>
            </div>
         
            <div class="form-group">
                <label for="city">Select City:</label>
                <select name="city" id="city" class="form-control" style="width:350px">
                </select>
            </div>

            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group" style="width:350px">
                    <label for="document">Upload Photos: </label>
                    <div class="needsclick dropzone" id="document-dropzone">
                        
                    </div>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit">
                    <a class="pl-4" href="{{ url('/home/'.auth()->user()->id) }}">Show List</a>
                </div>
            </form>

        </form>
        </div>
    </div>
</div>
@endsection