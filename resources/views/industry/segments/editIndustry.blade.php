<!-- Edit modal body for asynchronous data editing -->

<div class="form-group">

    <label for="name"> Name </label>

    <input type="text" id="name" name="name" class="form-control" value="{{ $industry -> name }}">

    <span id="name"></span>

</div>

<input type="hidden" id="updateindustryUrl" value="{{  route( 'industries.update', [$industry -> id] )  }}">

{{ csrf_field() }}


