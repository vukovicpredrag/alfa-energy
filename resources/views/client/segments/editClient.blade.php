<!-- Edit modal body for asynchronous data editing -->

<div class="form-group">

    <label for="name"> Name </label>

    <input type="text" id="name" name="name" class="form-control" value="{{ $client -> name }}">

    <span id="name"></span>

</div>


<div class="form-group">
    <label for="city">City</label>
    <select name="city" id="city" class="form-control" >
        <option value="" class="form-control" disabled selected> Select city  </option>
        @foreach( \App\Models\City::all() as $city )

            <option @if( $client -> city_id == $city -> id) selected @endif  value="{{ $city -> id }}" class="form-control" > {{ $city -> name  }} </option>

        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="country"> Country </label>
    <select name="country" id="country" class="form-control" >
        <option value="" class="form-control" disabled selected> Select country </option>
        @foreach( \App\Models\Country::all() as $country )

            <option  @if( $client -> country_id == $country -> id) selected @endif value="{{ $country -> id }}" class="form-control" > {{ $country -> name  }} </option>

        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="industry"> Industry </label>
    <select name="industry" id="industry" class="form-control" >
        <option value="" class="form-control" disabled selected> Select industry </option>
        @foreach( \App\Models\Industry::all() as $industry )

            <option @if( $client -> industry_id == $industry -> id) selected @endif value="{{ $industry -> id }}" class="form-control" > {{ $industry -> name  }} </option>

        @endforeach
    </select>
</div>



















<input type="hidden" id="updateclientUrl" value="{{  route( 'clients.update', [$client -> id] )  }}">

{{ csrf_field() }}






