<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class IndustryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $industries = Industry::all();

        return view('industry.index', ['industries' => $industries]);

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

            $industry = Industry::create([

                'name' => $request->name,

            ]) ->latest()
                ->first();

            return json_encode(['success' => true,'message' => 'industry successfully created!', 'id' => $industry -> id,'industry' => $industry -> name]);

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
        $industry = Industry::find($id);

        $validator = Validator::make( $request->all(), [

            'name' => 'required|string|max:255',

        ]);

        // data validation
        if ($validator -> passes()) {

            $industry -> name = $request -> name;

            $industry -> save();

            return json_encode([ 'success' => true, 'message' => 'industry successfully updated!' ]);

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

        Industry::find($id) -> delete();

    }

    /**
     * Display industries info table.
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
        $count = Industry::all() -> count();

        // get all industries
        $industries = Industry::offset( $start )
            -> limit( $limit )
            -> orderBy( $order, $direction );


        $industriesFiltered = Industry::whereNotNull('id') -> orderBy($order, $direction);

        if ($request -> input( 'search.value' )) {
            $search = $request -> input( 'search.value' );
            $industries -> where( 'name', 'LIKE', "%{$search}%" );
            $industriesFiltered -> where( 'name', 'LIKE', "%{$search}%" );
        }

        $industries = $industries -> get();
        $industriesFiltered = $industriesFiltered -> count();

        $data = [];

        // nested data
        foreach ( $industries as $industry ) {

            $nestedData = [];
            $nestedData[ 'id' ] = $industry -> id;
            $nestedData[ 'name' ] =  $industry -> name;
            $nestedData[ 'manage' ] = '<a  href="'.route( 'industries.destroy',[$industry->id]).'" class="btn btn-block btn-danger btn-sm btn-delete-industry"><i class="fa fa-trash"></i> Delete</a>
                                       <a data-industry-id='.$industry->id.' class="btn btn-block btn-primary btn-sm btn-edit-industry"><i class="fa fa-edit"></i> Edit</a>';
            $data[] = $nestedData;

        }

        // all data for table
        $return = [
            'draw' => intval( $request -> get( 'draw' ) ),
            'recordsTotal' => intval( $count ),
            'recordsFiltered' => intval( $industriesFiltered ),
            'data' => $data
        ];

        return json_encode( $return );

    }

    public function editModal( Request $request )
    {

        $industry = Industry::find($request -> industryId);

        return View::make( 'industry.segments.editindustry', ['industry' => $industry]);

    }


}
