@extends('layouts.app')

@section('title', 'Cities')

@section('content_header')
    <h1>Cities</h1>
@stop

@section('content')

    <div class="container">

        <h1>cities panel</h1>

        <a href="#" class="btn btn-success btn-add-city"><i class="fa fa-plus"></i> Add City </a>

        <div class="row justify-content-center">

            <table id="cities" class="table table-striped table-bordered">

                <thead>

                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Manage</th>
                    </tr>

                </thead>

                <tbody> </tbody>

            </table>

            <!-- Add City Modal -->
            <div class="modal" id="add-city-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 id="products-modal-title" class="modal-title">Add City</h4>
                        </div>

                        <div class="modal-body">

                        <form id="add-city-form" action ="" method = "post" >

                                @csrf
                                <div class="form-group">
                                    <label for="name"> City  Name: </label>
                                    <input type="text" id="name" name="name" class="form-control">
                                    <span id="name"></span>
                                </div>

                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>

                                </div>

                                <div class="modal-footer">

                                    <button type="submit" class="btn btn-primary btn-add-city"><i class="fa fa-check"></i> Save City </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                </div>

                            </div>

                        </form>

                </div>
            </div>
            <!-- End add city modal -->

            <!-- Edit city modal -->
            <div class="modal fade" id="editCityModal" role="dialog">
                <div class="modal-dialog" role="document">

                    <!-- Modal content-->
                    <form action="" method="POST" id="editCityForm">
                        {{ csrf_field() }}

                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit City </h4>
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
            <!-- End edit city modal -->

        </div>

    </div>


    <script>

        // define CSRF token
        var csrfToken = "{{ csrf_token() }}"

        // cities table
        var cityTable = $('#cities').DataTable( {

            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "order": [[ 0, 'asc' ]],

            fixedColumns: true,
            "ajax":{
                "url": '{{ route( 'cities.table' ) }}',
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

        // city modal toggle
        $ (".btn-add-city" ).click(function () {

            $( "#add-city-modal" ).modal( 'toggle' );

        })

        // add city / submit form
        $("#add-city-form").submit( function( e ) {

            e.preventDefault();

            let url = "{{ route( 'cities.store' ) }}"
            let data = $(this).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "POST",
                success: function(response) {

                    let data = JSON.parse(response);

                    if (data.success  === true) {
                        cityTable.ajax.reload()
                        alert(data.message)
                    }

                    if ( data.success  === false) {
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display', 'block');
                        $("#add-city-modal").modal('toggle');

                        $.each( data.error, function ( key, value ) {
                            $( ".print-error-msg" ).find( "ul" ).append( '<li>' + value + '</li>' );

                        });
                    }

                }

            });

        });


        //  delete city
        $(document).on('click', '.btn-delete-city', function (e) {
            e.preventDefault();

            if (!confirm("Are you sure you want to delete city: "+$(this).parent().parent().children('td').eq(0).text()+"?")) return false;

            let url = $( this ).attr( 'href' );

            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function( result ) { cityTable.ajax.reload() }
            });

        })

        // edit city modal
        $(document).on('click', '.btn-edit-city', function (e) {
            e.preventDefault();

            $("#editCityModal" ).modal('show');

            let cityId = $(this).attr('data-city-id');

            $.ajax({
                url: "{{ route( 'cities.edit.modal' ) }}",
                type: 'POST',
                data: { _token: csrfToken, cityId: cityId },
                success: function( response ) { $( "#editCityModal .modal-body" ).html( response );}
            })

        })

        // edit city form submitting
        $("#editCityForm").submit( function( e ) {

            e.preventDefault();

            let url =  $('#updateCityUrl').val();
            let data = $( this ).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "PUT",
                success: function(response){

                    let data = JSON.parse( response );

                    if ( data.success  === true ) {

                        $("#editCityModal").modal('toggle');
                        cityTable.ajax.reload()

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



