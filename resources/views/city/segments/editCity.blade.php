<!-- Edit modal body for asynchronous data editing -->

<div class="form-group">

    <label for="name"> Name </label>

    <input type="text" id="name" name="name" class="form-control" value="{{ $city -> name }}">

    <span id="name"></span>

</div>

<input type="hidden" id="updateCityUrl" value="{{  route( 'cities.update', [$city -> id] )  }}">

{{ csrf_field() }}


