@extends('layouts.app')

@section('title', 'industries')

@section('content_header')
    <h1>industries</h1>
@stop

@section('content')

    <div class="container">

        <h1>industries panel</h1>

        <a href="#" class="btn btn-success btn-add-industry"><i class="fa fa-plus"></i> Add industry </a>

        <div class="row justify-content-center">

            <table id="industries" class="table table-striped table-bordered">

                <thead>

                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Manage</th>
                </tr>

                </thead>

                <tbody> </tbody>

            </table>

            <!-- Add industry Modal -->
            <div class="modal" id="add-industry-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 id="products-modal-title" class="modal-title">Add industry</h4>
                        </div>

                        <div class="modal-body">

                            <form id="add-industry-form" action ="" method = "post" >

                                @csrf
                                <div class="form-group">
                                    <label for="name"> industry name: </label>
                                    <input type="text" id="name" name="name" class="form-control">
                                    <span id="name"></span>
                                </div>

                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>

                        </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary btn-add-industry"><i class="fa fa-check"></i> Save industry </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>

                    </div>

                    </form>

                </div>
            </div>
            <!-- End add industry modal -->

            <!-- Edit industry modal -->
            <div class="modal fade" id="editindustryModal" role="dialog">
                <div class="modal-dialog" role="document">

                    <!-- Modal content-->
                    <form action="" method="POST" id="editindustryForm">
                        {{ csrf_field() }}

                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit industry </h4>
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
            <!-- End edit industry modal -->

        </div>

    </div>


    <script>

        // define CSRF token
        var csrfToken = "{{ csrf_token() }}"

        // industries table
        var industryTable = $('#industries').DataTable( {

            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "order": [[ 0, 'asc' ]],

            fixedColumns: true,
            "ajax":{
                "url": '{{ route( 'industries.table' ) }}',
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

        // industry modal toggle
        $ (".btn-add-industry" ).click(function () {

            $( "#add-industry-modal" ).modal( 'toggle' );

        })

        // add industry / submit form
        $("#add-industry-form").submit( function( e ) {

            e.preventDefault();

            let url = "{{ route( 'industries.store' ) }}"
            let data = $(this).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "POST",
                success: function(response) {

                    let data = JSON.parse(response);

                    if (data.success  === true) {
                        industryTable.ajax.reload()
                        alert(data.message)
                    }

                    if ( data.success  === false) {
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display', 'block');
                        $("#add-industry-modal").modal('toggle');

                        $.each( data.error, function ( key, value ) {
                            $( ".print-error-msg" ).find( "ul" ).append( '<li>' + value + '</li>' );

                        });
                    }

                }

            });

        });


        //  delete industry
        $(document).on('click', '.btn-delete-industry', function (e) {
            e.preventDefault();

            if (!confirm("Are you sure you want to delete industry: "+$(this).parent().parent().children('td').eq(0).text()+"?")) return false;

            let url = $( this ).attr( 'href' );

            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function( result ) { industryTable.ajax.reload() }
            });

        })

        // edit industry modal
        $(document).on('click', '.btn-edit-industry', function (e) {
            e.preventDefault();

            $("#editindustryModal" ).modal('show');

            let industryId = $(this).attr('data-industry-id');

            $.ajax({
                url: "{{ route( 'industries.edit.modal' ) }}",
                type: 'POST',
                data: { _token: csrfToken, industryId: industryId },
                success: function( response ) { $( "#editindustryModal .modal-body" ).html( response );}
            })

        })

        // edit industry form submitting
        $("#editindustryForm").submit( function( e ) {

            e.preventDefault();

            let url =  $('#updateindustryUrl').val();
            let data = $( this ).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "PUT",
                success: function(response){

                    let data = JSON.parse( response );

                    if ( data.success  === true ) {

                        $("#editindustryModal").modal('toggle');
                        industryTable.ajax.reload()

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



