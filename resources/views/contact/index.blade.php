@extends('layouts.app')

@section('title', 'contacts')

@section('content_header')
    <h1>contacts</h1>
@stop

@section('content')

    <div class="container">

        <h1>contacts panel</h1>

        <a href="#" class="btn btn-success btn-add-contact"><i class="fa fa-plus"></i> Add contact type </a>

        <div class="row justify-content-center">

            <table id="contacts" class="table table-striped table-bordered">

                <thead>

                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Manage</th>
                </tr>

                </thead>

                <tbody> </tbody>

            </table>

            <!-- Add contact Modal -->
            <div class="modal" id="add-contact-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 id="products-modal-title" class="modal-title">Add contact type</h4>
                        </div>

                        <div class="modal-body">

                            <form id="add-contact-form" action ="" method = "post" >

                                @csrf
                                <div class="form-group">
                                    <label for="name"> contact name: </label>
                                    <input type="text" id="name" name="name" class="form-control">
                                    <span id="name"></span>
                                </div>

                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>

                        </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary btn-add-contact"><i class="fa fa-check"></i> Save contact </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>

                    </div>

                    </form>

                </div>
            </div>
            <!-- End add contact modal -->

            <!-- Edit contact modal -->
            <div class="modal fade" id="editcontactModal" role="dialog">
                <div class="modal-dialog" role="document">

                    <!-- Modal content-->
                    <form action="" method="POST" id="editcontactForm">
                        {{ csrf_field() }}

                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit contact </h4>
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
            <!-- End edit contact modal -->

        </div>

    </div>


    <script>

        // define CSRF token
        var csrfToken = "{{ csrf_token() }}"

        // contacts table
        var contactTable = $('#contacts').DataTable( {

            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "order": [[ 0, 'asc' ]],

            fixedColumns: true,
            "ajax":{
                "url": '{{ route( 'contacts.table' ) }}',
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

        // contact modal toggle
        $ (".btn-add-contact" ).click(function () {

            $( "#add-contact-modal" ).modal( 'toggle' );

        })

        // add contact / submit form
        $("#add-contact-form").submit( function( e ) {

            e.preventDefault();

            let url = "{{ route( 'contacts.store' ) }}"
            let data = $(this).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "POST",
                success: function(response) {

                    let data = JSON.parse(response);

                    if (data.success  === true) {
                        contactTable.ajax.reload()
                        alert(data.message)
                    }

                    if ( data.success  === false) {
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display', 'block');
                        $("#add-contact-modal").modal('toggle');

                        $.each( data.error, function ( key, value ) {
                            $( ".print-error-msg" ).find( "ul" ).append( '<li>' + value + '</li>' );

                        });
                    }

                }

            });

        });


        //  delete contact
        $(document).on('click', '.btn-delete-contact', function (e) {
            e.preventDefault();

            if (!confirm("Are you sure you want to delete contact: "+$(this).parent().parent().children('td').eq(0).text()+"?")) return false;

            let url = $( this ).attr( 'href' );

            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function( result ) { contactTable.ajax.reload() }
            });

        })

        // edit contact modal
        $(document).on('click', '.btn-edit-contact', function (e) {
            e.preventDefault();

            $("#editcontactModal" ).modal('show');

            let contactId = $(this).attr('data-contact-id');

            $.ajax({
                url: "{{ route( 'contacts.edit.modal' ) }}",
                type: 'POST',
                data: { _token: csrfToken, contactId: contactId },
                success: function( response ) { $( "#editcontactModal .modal-body" ).html( response );}
            })

        })

        // edit contact form submitting
        $("#editcontactForm").submit( function( e ) {

            e.preventDefault();

            let url =  $('#updatecontactUrl').val();
            let data = $( this ).serialize()

            $.ajax( {
                url: url,
                data: data,
                method: "PUT",
                success: function(response){

                    let data = JSON.parse( response );

                    if ( data.success  === true ) {

                        $("#editcontactModal").modal('toggle');
                        contactTable.ajax.reload()

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



