<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ContactController extends Controller
{

    /**
     * Authentication
     */
    public function __construct()
    {

        $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $contacts = Contact::all();

        return view('contact.index', ['contacts' => $contacts]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

        ]);


        if ($validator -> passes()) {

            $contact = Contact::create([

                'name' => $request->name,

            ]) ->latest()
                ->first();

            return json_encode(['success' => true,'message' => 'contact successfully created!', 'id' => $contact -> id,'contact' => $contact -> name]);

        }

        return json_encode(['success' => false, 'error' => $validator->errors()->all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        $validator = Validator::make( $request->all(), [

            'name' => 'required|string|max:255',

        ]);

        // data validation
        if ($validator -> passes()) {

            $contact -> name = $request -> name;

            $contact -> save();

            return json_encode([ 'success' => true, 'message' => 'contact successfully updated!' ]);

        }


        return json_encode([ 'success' => false, 'error' => $validator -> errors() -> all () ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Contact::find($id) -> delete();

    }

    /**
     * Display contacts info table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function table(Request $request)
    {

        $columns = [ 'id', 'name',  'manage' ];

        $limit = $request -> get( 'length' );
        $start = $request -> get( 'start' );
        $order = $columns[ $request -> input( 'order.0.column' ) ];
        $direction = $request -> input( 'order.0.dir' );
        $count = Contact::all() -> count();

        // get all contacts
        $contacts = Contact::offset( $start )
            -> limit( $limit )
            -> orderBy( $order, $direction );


        $contactsFiltered = Contact::whereNotNull('id') -> orderBy($order, $direction);

        if ($request -> input( 'search.value' )) {
            $search = $request -> input( 'search.value' );
            $contacts -> where( 'name', 'LIKE', "%{$search}%" );
            $contactsFiltered -> where( 'name', 'LIKE', "%{$search}%" );
        }

        $contacts = $contacts -> get();
        $contactsFiltered = $contactsFiltered -> count();

        $data = [];

        // nested data
        foreach ( $contacts as $contact ) {

            $nestedData = [];
            $nestedData[ 'id' ] = $contact -> id;
            $nestedData[ 'name' ] =  $contact -> name;
            $nestedData[ 'manage' ] = '<a  href="'.route( 'contacts.destroy',[$contact->id]).'" class="btn btn-block btn-danger btn-sm btn-delete-contact"><i class="fa fa-trash"></i> Delete</a>
                                       <a data-contact-id='.$contact->id.' class="btn btn-block btn-primary btn-sm btn-edit-contact"><i class="fa fa-edit"></i> Edit</a>';
            $data[] = $nestedData;

        }

        // all data for table
        $return = [
            'draw' => intval( $request -> get( 'draw' ) ),
            'recordsTotal' => intval( $count ),
            'recordsFiltered' => intval( $contactsFiltered ),
            'data' => $data
        ];

        return json_encode( $return );

    }

    public function editModal( Request $request )
    {

        $contact = Contact::find($request->contactId);

        return View::make( 'contact.segments.editcontact', ['contact' => $contact]);

    }


}

