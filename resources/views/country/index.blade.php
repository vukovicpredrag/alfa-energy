@extends('layouts.app')

@section('title', 'countries')

@section('content_header')
    <h1>Countries</h1>
@stop

@section('content')

    <div class="container">

        <h1>countries panel</h1>

        <a href="#" class="btn btn-success btn-add-country"><i class="fa fa-plus"></i> Add country </a>

        <div class="row justify-content-center">

            <table id="countries" class="table table-striped table-bordered">

                <thead>

                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Manage</th>
                </tr>

                </thead>

                <tbody> </tbody>

            </table>

            <!-- Add country Modal -->
            <div class="modal" id="add-country-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 id="products-modal-title" class="modal-title">Add country</h4>
                        </div>

                        <div class="modal-body">

                            <form id="add-country-form" action ="" method = "post" >

                                @csrf
                                <div class="form-group">
                                    <label for="name"> Country name: </label>
                                    <input type="text" id="name" name="name" class="form-control">
                                    <span id="name"></span>
                                </div>

                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>

                        </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary btn-add-country"><i class="fa fa-check"></i> Save country </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>

                    </div>

                    </form>

                </div>
            </div>
            <!-- End add country modal -->

            <!-- Edit country modal -->
            <div class="modal fade" id="editcountryModal" role="dialog">
                <div class="modal-dialog" role="document">

                    <!-- Modal content-->
                    <form action="" method="POST" id="editcountryForm">
                        {{ csrf_field() }}

                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit country </h4>
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
            <!-- End edit country modal -->

        </div>

    </div>


    <script>

        // define CSRF token
        var csrfToken = "{{ csrf_token() }}"

        // countries table
        var countryTable = $('#countries').DataTable( {

            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "order": [[ 0, 'asc' ]],

            fixedColumns: true,
            "ajax":{
                "url": '{{ route( 'countries.table' ) }}',
                "data": function(d) { d._token = csrfToken; },
                "dataType": "json",
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "manage" }
            ]
        } );

        // country modal toggle
        $ (".btn-add-country" ).click(function () {

            $( "#add-country-modal" ).modal( 'toggle' );

        })

        // add country / submit form
        $("#add-country-form").submit( function( e ) {

            e.preventDefault();

            let url = "{{ route( 'countries.store' ) }}"
            let data = $(this).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "POST",
                success: function(response) {

                    let data = JSON.parse(response);

                    if (data.success  === true) {
                        countryTable.ajax.reload()
                        alert(data.message)
                    }

                    if ( data.success  === false) {
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display', 'block');
                        $("#add-country-modal").modal('toggle');

                        $.each( data.error, function ( key, value ) {
                            $( ".print-error-msg" ).find( "ul" ).append( '<li>' + value + '</li>' );

                        });
                    }

                }

            });

        });


        //  delete country
        $(document).on('click', '.btn-delete-country', function (e) {
            e.preventDefault();

            if (!confirm("Are you sure you want to delete country: "+$(this).parent().parent().children('td').eq(0).text()+"?")) return false;

            let url = $( this ).attr( 'href' );

            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function( result ) { countryTable.ajax.reload() }
            });

        })

        // edit country modal
        $(document).on('click', '.btn-edit-country', function (e) {
            e.preventDefault();

            $("#editcountryModal" ).modal('show');

            let countryId = $(this).attr('data-country-id');

            $.ajax({
                url: "{{ route( 'countries.edit.modal' ) }}",
                type: 'POST',
                data: { _token: csrfToken, countryId: countryId },
                success: function( response ) { $( "#editcountryModal .modal-body" ).html( response );}
            })

        })

        // edit country form submitting
        $("#editcountryForm").submit( function( e ) {

            e.preventDefault();

            let url =  $('#updatecountryUrl').val();
            let data = $( this ).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "PUT",
                success: function(response){

                    let data = JSON.parse( response );

                    if ( data.success  === true ) {

                        $("#editcountryModal").modal('toggle');
                        countryTable.ajax.reload()

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

    </script>
@endsection



