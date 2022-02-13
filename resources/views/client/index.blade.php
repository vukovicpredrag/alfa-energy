@extends('layouts.app')

@section('title', 'clients')

@section('content_header')
    <h1>clients</h1>
@stop

@section('content')

    <div class="container">

        <h1>clients panel</h1>

        <a href="#" class="btn btn-success btn-add-client"><i class="fa fa-plus"></i> Add client </a>

        <div class="row justify-content-center">

            <table id="clients" class="table table-striped table-bordered">

                <thead>

                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Industry</th>
                    <th>Contacts</th>
                    <th>Manage</th>
                </tr>

                </thead>

                <tbody> </tbody>

            </table>

            <!-- Add client Modal -->
            <div class="modal" id="add-client-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 id="products-modal-title" class="modal-title">Add client type</h4>
                        </div>

                        <div class="modal-body">

                            <form id="add-client-form" action ="" method = "post" >

                                @csrf
                                <div class="form-group">

                                    <label for="name"> client name: </label>
                                    <input type="text" id="name" name="name" class="form-control">

                                    <label for="address"> address: </label>
                                    <input type="text" id="address" name="address" class="form-control">

                                </div>

                                <div class="form-group">
                                    <label for="city">City</label>
                                    <select name="city" id="city" class="form-control" >
                                        <option value="" class="form-control" disabled selected> Select city  </option>
                                        @foreach( \App\Models\City::all() as $city )

                                            <option value="{{ $city -> id }}" class="form-control" > {{ $city -> name  }} </option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="country"> Country </label>
                                    <select name="country" id="country" class="form-control" >
                                        <option value="" class="form-control" disabled selected> Select country </option>
                                        @foreach( \App\Models\Country::all() as $country )

                                            <option value="{{ $country -> id }}" class="form-control" > {{ $country -> name  }} </option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="industry"> Industry </label>
                                    <select name="industry" id="industry" class="form-control" >
                                        <option value="" class="form-control" disabled selected> Select industry </option>
                                        @foreach( \App\Models\Industry::all() as $industry )

                                            <option value="{{ $industry -> id }}" class="form-control" > {{ $industry -> name  }} </option>

                                        @endforeach
                                    </select>
                                </div><br>

                                <div class="form-group">
                                    <label for="contact_type"> Contact </label>
                                    <select name="contact_type[]" id="contact_type" class="form-control" >
                                        <option value="" class="form-control" disabled selected> Select contact type </option>
                                        @foreach( \App\Models\Contact::all() as $contact )

                                            <option value="{{ $contact -> id }}" class="form-control" > {{ $contact -> name  }} </option>

                                        @endforeach
                                    </select>
                                    <input type="text" id="contact_value" name="contact_value[]" class="form-control" placeholder="Enter contact value">

                                </div> <br>

                                <div id="contactEditModal">
                                        <div class="input-group control-group after-add-more">

                                            <div class="input-group-btn">
                                                <button class="btn btn-success add-more" type="button"> Add an additional contact </button>
                                            </div>
                                </div><br>

                                </div>
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-add-client"><i class="fa fa-check"></i> Save client </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </div>

                    </form>

                </div>
            </div>
            <!-- End add client modal -->

            <!-- Edit client modal -->
            <div class="modal fade" id="editclientModal" role="dialog">
                <div class="modal-dialog" role="document">

                    <!-- Modal content-->
                    <form action="" method="POST" id="editclientForm">
                        {{ csrf_field() }}

                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit client </h4>
                            </div>

                            <div class="modal-body">

                            </div>

                            <div class="modal-footer">

                                <button type="submit" class="btn btn-primary">Update<i class="fa fa-check"></i></button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            </div>

                        </div>

                    </form>
                </div>

            </div>
            <!-- End edit client modal -->

        </div>
        <!--Add page input hidden copy-->
        <div class="form-group pages hidden" id="contents">

            <label for="contact_type"> Contact </label>
            <select name="contact_type[]" id="contact_type" class="form-control" >
                <option value="" class="form-control" disabled selected> Select contact type </option>
                @foreach( \App\Models\Contact::all() as $contact )

                    <option value="{{ $contact -> id }}" class="form-control" > {{ $contact -> name  }} </option>

                @endforeach
            </select>
            <input type="text" id="contact_value" name="contact_value[]" class="form-control" placeholder="Enter contact value">

        </div>
    </div>


    <script>

        // define CSRF token
        var csrfToken = "{{ csrf_token() }}"

        // clients table
        var clientTable = $('#clients').DataTable( {

            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "order": [[ 0, 'asc' ]],

            fixedColumns: true,
            "ajax":{
                "url": '{{ route( 'clients.table' ) }}',
                "data": function(d) { d._token = csrfToken; },
                "dataType": "json",
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "address" },
                { "data": "city" },
                { "data": "country" },
                { "data": "industry" },
                { "data": "contacts" },
                { "data": "manage" },
            ]
        } );

        // client modal toggle
        $ (".btn-add-client" ).click(function () {

            $( "#add-client-modal" ).modal( 'toggle' );

        })

        // add client / submit form
        $("#add-client-form").submit( function( e ) {

            e.preventDefault();

            let url = "{{ route( 'clients.store' ) }}"
            let data = $(this).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "POST",
                success: function(response) {

                    let data = JSON.parse(response);

                    if (data.success  === true) {
                        clientTable.ajax.reload()
                        alert(data.message)
                    }

                    if ( data.success  === false) {
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display', 'block');
                        $("#add-client-modal").modal('toggle');

                        $.each( data.error, function ( key, value ) {
                            $( ".print-error-msg" ).find( "ul" ).append( '<li>' + value + '</li>' );

                        });
                    }

                }

            });

        });


        //  delete client
        $(document).on('click', '.btn-delete-client', function (e) {
            e.preventDefault();

            if (!confirm("Are you sure you want to delete client: "+$(this).parent().parent().children('td').eq(0).text()+"?")) return false;

            let url = $( this ).attr( 'href' );

            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function( result ) { clientTable.ajax.reload() }
            });

        })

        // edit client modal
        $(document).on('click', '.btn-edit-client', function (e) {
            e.preventDefault();

            $("#editclientModal" ).modal('show');

            let clientId = $(this).attr('data-client-id');

            $.ajax({
                url: "{{ route( 'clients.edit.modal' ) }}",
                type: 'POST',
                data: { _token: csrfToken, clientId: clientId },
                success: function( response ) { $( "#editclientModal .modal-body" ).html( response );}
            })

        })

        // edit client form submitting
        $("#editclientForm").submit( function( e ) {

            e.preventDefault();

            let url =  $('#updateclientUrl').val();
            let data = $( this ).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "PUT",
                success: function(response){

                    let data = JSON.parse( response );

                    if ( data.success  === true ) {

                        $("#editclientModal").modal('toggle');
                        clientTable.ajax.reload()

                    }

                    if ( data.success  === false ) {

                        $( ".print-error-msg" ).find( "ul" ).html( '' );
                        $( ".print-error-msg" ).css( 'display', 'block' );

                        $.each( data.error, function ( key, value ) {

                            $( ".print-error-msg" ).find( "ul" ).append( '<li>' + value + '</li>' );

                        });
                    }
                }

            });

        });

        $("body").on('click','.add-more', function( e ){

            $("#contactEditModal").append(
                // clone the row and insert it in the DOM
                $("#contents")

                    .clone()
                    .removeClass("hidden")
            );

            $( "#additionalInput" ).css( 'display', 'block' )

        });
    </script>
@endsection



