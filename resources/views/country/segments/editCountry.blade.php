<!-- Edit modal body for asynchronous data editing -->

<div class="form-group">

    <label for="name"> Name </label>

    <input type="text" id="name" name="name" class="form-control" value="{{ $country -> name }}">

    <span id="name"></span>

</div>

<input type="hidden" id="updatecountryUrl" value="{{  route( 'countries.update', [$country -> id] )  }}">

{{ csrf_field() }}


