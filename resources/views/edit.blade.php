@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
      <div class="panel-body">
        <form method="POST" action="{{ url('/list/'.$list->id) }}">
        @csrf
        @method('PUT')
            <div class="form-group">
                <label for="name">Enter Name: </label>
                <input type="text" name="name" id="name" value="{{ $list->name }}" placeholder="Enter Your Name" class="form-control" style="width:350px">
            </div>

            <div class="form-group">
                <label for="description">Enter Description: </label>
                <textarea class="description" name="description" id="description" style="width:350px" value="{{ $list->description }}">{{ $list->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="country">Select Country:</label>
                    <select id="country-edit" name="country" value="{{ $list->country }}" class="form-control" style="width:350px">
                    <option value="" selected disabled>{{ $list->country }}</option>
                        @foreach($countries as $key => $country)
                            <option value="{{$key}}"> {{$country}}</option>
                        @endforeach
                    </select>
            </div>

            <div class="form-group">
                <label for="state">Select State:</label>
                <select name="state" id="state-edit" class="form-control" style="width:350px">
                <option value="" selected disabled>{{ $list->state }}</option>
                @for($j = 0; $j < $states->count(); $j++)
                    <option value="{{$state_key}}"> {{$states[$j]}}</option>
                @endfor
                </select>
            </div>
         
            <div class="form-group">
                <label for="city">Select City:</label>
                <select name="city" id="city-edit" class="form-control" style="width:350px">
                <option value="" selected disabled>{{ $list->city }}</option>
                    @for($j = 0; $j < $cities->count(); $j++)
                        <option value="{{$city_key}}"> {{$cities[$j]}}</option>
                    @endfor
                </select>
            </div>

            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group" style="width:350px">
                    <label for="document">Upload Files: </label>
                    <div class="needsclick dropzone" id="document-dropzone">
                    
                    </div>
                </div>

                <div>
                    <button class="btn btn-primary">Update</button>
                    <a class="pl-4" href="{{ url('/home/'.auth()->user()->id) }}">Show List</a>
                </div>
            </form>

        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
        $('#country-edit').change(function(){
            var countryID = $(this).val();
            if(countryID){
                $.ajax({
                type:"GET",
                url:"{{url('api/get-state-list')}}?country_id="+countryID,
                success:function(res){
                    if(res){
                        $("#state-edit").empty();
                        $("#state-edit").append('<option>Select State</option>');
                        $.each(res,function(key,value){
                        $("#state-edit").append('<option value="'+key+'">'+value+'</option>');
                    });
                    $('#city-edit').html('<option value=""></option>');
                    }else{
                        $("#state-edit").empty();
                    }
                }
                });
            }else{
                $("#state-edit").empty();
                $("#city-edit").empty();
            }
        
        $('#state-edit').on('change',function(){
            var stateID = countryID;
            if(stateID){
                $.ajax({
                    type:"GET",
                    url:"{{url('api/get-city-list')}}?state_id="+stateID,
                    success:function(res){               
                        if(res){
                            $("#city-edit").empty();
                            $("#city-edit").append('<option>Select City</option>');
                            $.each(res,function(key,value){
                                $("#city-edit").append('<option value="'+key+'">'+value+'</option>');
                            });
                        }else{
                            $("#city-edit").empty();
                        }
                    }
                });
            }else{
                $("#city-edit").empty();
            }
        });
    });
</script>
@endsection