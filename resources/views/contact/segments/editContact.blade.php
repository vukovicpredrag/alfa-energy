<!-- Edit modal body for asynchronous data editing -->

<div class="form-group">

    <label for="name"> Name </label>

    <input type="text" id="name" name="name" class="form-control" value="{{ $contact -> name }}">

    <span id="name"></span>

</div>

<input type="hidden" id="updatecontactUrl" value="{{  route( 'contacts.update', [$contact -> id] )  }}">

{{ csrf_field() }}


