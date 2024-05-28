<?php

namespace App\Http\Controllers;

use App\Models\CenterStone;
use Illuminate\Http\Request;

class CenterStoneController extends Controller
{
    public function index()
    {
        $data = [
            "title" => 'Center stone list',
            "viewurl" => 'admin.centerstone.add',
            "editurl" => 'admin.centerstone.edit',
            'list' => CenterStone::orderBy('id', 'desc')->get(),
        ];
        return view('admin.centerstoneList', $data);
    }

    public function addCenterStone()
    {
        $data = [
            'url_action' => route('admin.centerstone.postadd'),
            'backtrack' => 'admin.centerstone.list',
            'title' => 'Add center stone',
            "obj" => '',
        ];
        return view('admin.centerstone', $data);
    }

    public function postAddCenterStone(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'value' => 'required',
        ], [
            'name.required' => 'The Name field is required.',
            'value.required' => 'The value field is required.',
        ]);
        $centerstone = new CenterStone;
        $centerstone->name = $request->name;
        $centerstone->value = $request->value;
        $centerstone->status = $request->status ?? 'false';
        $centerstone->save();
        return redirect()->back()->with('success', 'Center stone added successfully');
    }

    public function editCenterStone($id)
    {
        $editdata = CenterStone::find($id);
        if ($editdata == null) {
            return 'no data';
        }
        $data = [
            'url_action' => route('admin.centerstone.update', ['id' => $editdata['id']]),
            'backtrack' => 'admin.centerstone.list',
            'title' => 'Edit Center stone',
            'obj' => $editdata,
        ];
        return view('admin.centerstone', $data);
    }

    public function PostEditCenterStone(Request $request, $id)
    {
        $obj = CenterStone::find($id);
        $this->validate($request, [
            'name' => 'required',
            'value' => 'required',
        ], [
            'name.required' => 'The Name field is required.',
            'value.required' => 'The value field is required.',
        ]);
        $obj->name = $request->name;
        $obj->value = $request->value;
        $obj->status = $request->status ?? 'false';
        $obj->save();
        return redirect()->back()->with('success', 'Center stone updated successfully');
    }

    public function deleteCenterStone($id)
    {
        if ($id) {
            $carat = CenterStone::find($id);
            $carat->delete();
            $output['res'] = 'success';
            $output['msg'] = 'Data Deleted';
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Center stone Id Required';
        }
        echo json_encode($output);
    }
}
