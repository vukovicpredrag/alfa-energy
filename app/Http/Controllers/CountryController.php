<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CountryController extends Controller
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

        $countries = Country::all();

        return view('country.index', ['countries' => $countries]);

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

            $country = Country::create([

                'name' => $request->name,

            ]) ->latest()
                ->first();

            return json_encode(['success' => true,'message' => 'Country successfully created!', 'id' => $country -> id,'country' => $country -> name]);

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
        $country = Country::find($id);

        $validator = Validator::make( $request->all(), [

            'name' => 'required|string|max:255',

        ]);

        // data validation
        if ($validator -> passes()) {

            $country -> name = $request -> name;

            $country -> save();

            return json_encode([ 'success' => true, 'message' => 'Country successfully updated!' ]);

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

        Country::find($id) -> delete();

    }

    /**
     * Display countries info table.
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
        $count = Country::all() -> count();

        // get all countries
        $countries = Country::offset( $start )
            -> limit( $limit )
            -> orderBy( $order, $direction );


        $countriesFiltered = country::whereNotNull('id') -> orderBy($order, $direction);

        if ($request -> input( 'search.value' )) {
            $search = $request -> input( 'search.value' );
            $countries -> where( 'name', 'LIKE', "%{$search}%" );
            $countriesFiltered -> where( 'name', 'LIKE', "%{$search}%" );
        }

        $countries = $countries -> get();
        $countriesFiltered = $countriesFiltered -> count();

        $data = [];

        // nested data
        foreach ( $countries as $country ) {

            $nestedData = [];
            $nestedData[ 'id' ] = $country -> id;
            $nestedData[ 'name' ] =  $country -> name;
            $nestedData[ 'manage' ] = '<a  href="'.route( 'countries.destroy',[$country->id]).'" class="btn btn-block btn-danger btn-sm btn-delete-country"><i class="fa fa-trash"></i> Delete</a>
                                       <a data-country-id='.$country->id.' class="btn btn-block btn-primary btn-sm btn-edit-country"><i class="fa fa-edit"></i> Edit</a>';
            $data[] = $nestedData;

        }

        // all data for table
        $return = [
            'draw' => intval( $request -> get( 'draw' ) ),
            'recordsTotal' => intval( $count ),
            'recordsFiltered' => intval( $countriesFiltered ),
            'data' => $data
        ];

        return json_encode( $return );

    }

    public function editModal( Request $request )
    {

        $country = Country::find($request->countryId);

        return View::make( 'country.segments.editCountry', ['country' => $country]);

    }


}
