<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class CityController extends Controller
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

        $cities = City::all();

        return view('city.index', ['cities' => $cities]);

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

            $city = City::create([

                'name' => $request->name,

            ]) ->latest()
               ->first();

            return json_encode(['success' => true,'message' => 'City successfully created!', 'id' => $city -> id,'city' => $city -> name]);

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
        $city = City::find($id);

        $validator = Validator::make( $request->all(), [

            'name' => 'required|string|max:255',

        ]);

        // data validation
        if ($validator -> passes()) {

            $city -> name = $request -> name;

            $city -> save();

            return json_encode([ 'success' => true, 'message' => 'City successfully updated!' ]);

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

        City::find($id) -> delete();

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
        $count = City::all() -> count();

        // get all cities
        $cities = City::offset( $start )
                -> limit( $limit )
                -> orderBy( $order, $direction );


        $citiesFiltered = City::whereNotNull('id') -> orderBy($order, $direction);

        if ($request -> input( 'search.value' )) {
            $search = $request -> input( 'search.value' );
            $cities -> where( 'name', 'LIKE', "%{$search}%" );
            $citiesFiltered -> where( 'name', 'LIKE', "%{$search}%" );
        }

        $cities = $cities -> get();
        $citiesFiltered = $citiesFiltered -> count();

        $data = [];

        // nested data
        foreach ( $cities as $city ) {

            $nestedData = [];
            $nestedData[ 'id' ] = $city -> id;
            $nestedData[ 'name' ] =  $city -> name;
            $nestedData[ 'manage' ] = '<a  href="'.route( 'cities.destroy',[$city->id]).'" class="btn btn-block btn-danger btn-sm btn-delete-city"><i class="fa fa-trash"></i> Delete</a>
                                       <a data-city-id='.$city->id.' class="btn btn-block btn-primary btn-sm btn-edit-city"><i class="fa fa-edit"></i> Edit</a>';
            $data[] = $nestedData;

        }

        // all data for table
        $return = [
            'draw' => intval( $request -> get( 'draw' ) ),
            'recordsTotal' => intval( $count ),
            'recordsFiltered' => intval( $citiesFiltered ),
            'data' => $data
        ];

        return json_encode( $return );

    }

    public function editModal( Request $request )
    {

        $city = City::find($request->cityId);

        return View::make( 'city.segments.editCity', ['city' => $city]);

    }


}
