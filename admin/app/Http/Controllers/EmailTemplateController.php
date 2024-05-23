<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Library\Clover;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $data = [
            "title" => 'Email Template list',
            "viewurl" => 'template.add',
            "editurl" => 'template.edit',
            'list' => EmailTemplate::orderBy('id', 'desc')->get(),
        ];
        return view('admin.email_template_list', $data);
    }

    public function addTemplate()
    {
        $data = [
            'url_action' => route('template.postadd'),
            'backtrack' => 'template.list',
            'title' => 'Add Email Template',
            "obj" => '',
        ];
        return view('admin.email_template', $data);
    }

    public function postAddTemplate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'group' => 'required',
            'content' => 'required',
        ], [
            'name.required' => 'The name title field is required.',
            'group.required' => 'The group title field is required.',
            'content.required' => 'The content title field is required.',
        ]);

        $template = new EmailTemplate;
        $template->name = $request->name;
        $template->group = $request->group;
        $template->content = $request->content;
        $template->status = $request->status ?? 'false';
        $template->save();
        return redirect()->back()->with('success', 'Template added successfully');
    }


    public function editTemplate($id)
    {
        $editdata = EmailTemplate::find($id);
        if ($editdata == null) {
            return 'no data';
        }
        $data = [
            'url_action' => route('template.update', ['id' => $id]),
            'backtrack' => 'template.list',
            'title' => 'Edit Email Template',
            "obj" => $editdata,
        ];
        return view('admin.email_template', $data);
    }

    public function updateTemplate(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'group' => 'required',
            'content' => 'required',
        ], [
            'name.required' => 'The name title field is required.',
            'group.required' => 'The group title field is required.',
            'content.required' => 'The content title field is required.',
        ]);

        $template = EmailTemplate::find($id);
        if ($template) {
            $template->name = $request->name;
            $template->group = $request->group;
            $template->content = $request->content;
            $template->status = $request->status ?? 'false';
            $template->save();
            return redirect()->back()->with('success', 'Template added successfully');
        } else {
            return redirect()->back()->with('error', 'Template Id \Invalid');
        }
    }

    public function distroy($id)
    {
        $id =  EmailTemplate::find($id);
        if ($id) {
            $id->delete();
            $output['res'] = 'success';
            $output['msg'] = 'Data Deleted';
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Template Id Required';
        }
        echo json_encode($output);
    }

    public function testGateway()
    {
    //    return view('payment_form');
       $clover = new Clover();
       $clover->tokenizeCard();
    }
}
