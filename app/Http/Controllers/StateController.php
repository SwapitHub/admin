<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;

class StateController extends Controller
{
    public function index()
    {
        $state =  State::query()
            ->select('state.*', 'country.name as country_name')
            ->join('country', 'country.id', '=', 'state.country_id')
            ->get();
        $data = [
            'title' => 'State list',
            'viewurl' => route('admin.addstate'),
            'editurl' => 'admin.editstate',
            'list' => $state
        ];
        return view('admin.stateList', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New State',
            'backtrack' => 'admin.state',
            'countries' => Country::where('status', 'true')->get(),
            'url_action' => route('admin.postaddstate'),
            "obj" => '',
        ];
        return view('admin.state', $data);
    }

    public function postCreate(Request $request)
    {
        $this->validate($request, [
            'country_id' => 'required',
            'name' => 'required|unique:state,name',
            'tax_percentage' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ], [
            'country_id.required' => 'The Country name field is required.',
            'name.required' => 'The State name field is required.',
            'name.unique' => 'The State name has already been taken.',
            'tax_percentage.required' => 'The Tax field is required.',
            'tax_percentage.regex' => 'The Tax field must be a valid number (integer or float).',
        ]);

        $state = new State;
        $state->country_id = $request->country_id;
        $state->name = trim(strtolower($request->name));
        $state->tax_percentage = $request->tax_percentage;
        $state->order_number = $request->order_number;
        $state->status = $request->status ?? 'false';
        $state->save();
        return redirect()->back()->with('success', 'State added successfully');
    }

    public function editState($id)
    {
        $editdata = State::find($id);
        if ($editdata == null) {
            return 'no data';
        }
        $data = [
            'title' => 'Edit State',
            'backtrack' => 'admin.state',
            'countries' => Country::where('status', 'true')->get(),
            'url_action' => route('admin.updatestate', ['id' => $editdata['id']]),
            'obj' => $editdata,
        ];
        return view('admin.state', $data);
    }

    public function postEdit(Request $request, $id)
    {
        $obj = State::find($id);
        $this->validate($request, [
            'name' => 'required',
        ], [
            'name.required' => 'The name field is required.',
        ]);
        $obj->country_id = $request->country_id;
        $obj->name = trim(strtolower($request->name));
        $obj->tax_percentage = $request->tax_percentage;
        $obj->order_number = $request->order_number;
        $obj->status = $request->status ?? 'false';
        $obj->save();
        return redirect()->back()->with('success', 'State updated successfully');
    }

    public function deleteCountry($id)
    {
        if ($id) {
            $country = Country::find($id);
            $country->delete();
            $output['res'] = 'success';
            $output['msg'] = 'Data Deleted';
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Country Id Required';
        }
        echo json_encode($output);
    }
}
