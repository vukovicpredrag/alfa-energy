<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ClientController extends Controller
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

        $clients = Client::all();

        return view('client.index', ['clients' => $clients]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //TODO complete validation
        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

        ]);

        if ($validator -> passes()) {

            $client = Client::create([
                'name'        => $request -> name,
                'address'     => $request -> name,
                'city_id'     => $request -> city,
                'industry_id' => $request -> industry,
                'country_id'  => $request -> industry,
                'contact_id'  => $request -> contact_id,

            ])  -> latest()
                -> first();


            $contacts = array_combine($request->contact_type, $request->contact_value);

            foreach ($contacts as $key => $value){

                $client->contacts()->attach($client->id, ['contact_id' =>  $key, 'value' => $value] );

            }


            return json_encode(['success' => true,'message' => 'client successfully created!', 'id' => $client -> id,'client' => $client -> name]);

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
        $client = Client::find($id);

        //TODO complete validation

        $validator = Validator::make( $request->all(), [

            'name' => 'required|string|max:255',

        ]);

        // data validation
        if ($validator -> passes()) {

            $client->name        = $request-> name;
            $client->city_id     = $request-> city;
            $client->country_id  = $request-> country;
            $client->industry_id = $request-> industry;

            $client -> save();

            return json_encode([ 'success' => true, 'message' => 'client successfully updated!' ]);

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

        client::find($id) -> delete();

    }

    /**
     * Display countries info table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function table(Request $request)
    {

        $columns = [ 'id', 'name', 'address', 'city', 'country', 'industry', 'contacts', 'manage' ];

        $limit = $request -> get( 'length' );
        $start = $request -> get( 'start' );
        $order = $columns[$request -> input( 'order.0.column' )];
        $direction = $request -> input( 'order.0.dir' );
        $count = Client::all() -> count();

        // get all clients
        $clients = Client::offset( $start )
            -> limit( $limit )
            -> orderBy( $order, $direction );


        $clientsFiltered = Client::whereNotNull('id') -> orderBy($order, $direction);

        if ($request -> input( 'search.value' )) {
            $search = $request -> input( 'search.value' );
            $clients -> where( 'name', 'LIKE', "%{$search}%" );
            $clientsFiltered -> where( 'name', 'LIKE', "%{$search}%" );
        }

        $clients = $clients -> get();
        $clientsFiltered = $clientsFiltered -> count();

        $data = [];

        // nested data
        foreach ( $clients as $client ) {

            // Collect all contacts data into array
            // TODO make dynamicly columns and modal for contacts frontend
            $clientContacts = [];
            if( $client -> contacts ){
                foreach ($client -> contacts as $contact) {
                    $clientContacts[ $contact->name ] = $contact->pivot->value;
                }
            }

            $nestedData = [];
            $nestedData[ 'id' ] = $client -> id;
            $nestedData[ 'name' ] =  $client -> name;
            $nestedData[ 'address' ] = $client -> address;
            $nestedData[ 'city' ] =  $client -> city -> name;
            $nestedData[ 'country' ] =  $client -> country -> name;
            $nestedData[ 'industry' ] =  $client -> industry -> name;
            $nestedData[ 'contacts' ] =  json_encode($clientContacts);
            $nestedData[ 'manage' ] = '<a  href="'.route( 'clients.destroy',[$client->id]).'" class="btn btn-block btn-danger btn-sm btn-delete-client"><i class="fa fa-trash"></i> Delete</a>
                                       <a data-client-id='.$client->id.' class="btn btn-block btn-primary btn-sm btn-edit-client"><i class="fa fa-edit"></i> Edit</a>';
            $data[] = $nestedData;

        }

        // all data for table
        $return = [
            'draw' => intval( $request -> get( 'draw' ) ),
            'recordsTotal' => intval( $count ),
            'recordsFiltered' => intval( $clientsFiltered ),
            'data' => $data
        ];

        return json_encode( $return );

    }

    public function editModal( Request $request )
    {

        $client = Client::find($request->clientId);

        return View::make( 'client.segments.editclient', ['client' => $client]);

    }


}
