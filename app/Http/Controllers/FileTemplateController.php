<?php

namespace App\Http\Controllers;

use App\Models\FileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class FileTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FileModel::where('jenisDokumen', 'file template')->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnHapus = '';
                    if (auth()->user()->can('akses-admin')) {
                        $btnHapus = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="deleteFile(event,' . $row->id . ')" ><i class="fa fa-trash"></i></button>&nbsp;';
                    }
                    $btn = $btnHapus . '<a href="' . route('download-file-template', $row->id) . '"><button type=" button"
                            class="btn btn-outline-info btn-icon"><i
                                class="fa fa-file-download"></i></button></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('file-template.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::orderBy('name', 'asc')->get();
        return view('file-template.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'unit' => 'required',
            'files' => 'required',
            'files.*' => 'mimes:doc,docx|max:10248'
        ], [
            'unit.required' => 'Nama unit tidak boleh kosong',
            'files.required' => 'File tidak boleh kosong',
            'files.*.mimes' => 'Format dokumen doc,docx',
            'files.*.max' => 'Ukuran file maksimal 10MB'
        ]);
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();
                $insert[$key]['name'] = $name;
                $insert[$key]['path'] = $path;
                $insert[$key]['unit'] = $request->unit;
                $insert[$key]['jenisDokumen'] = 'file template';
                $insert[$key]['created_at'] = now();
                $insert[$key]['updated_at'] = now();
            }
        }
        FileModel::insert($insert);

        return redirect()->route('file-template.index')->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = FileModel::find($id);
        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
            $file->delete();
            return response()->json(['msg' => 'Data berhasil di hapus'], 200);
        } else {
            dd('File does not exists.');
        }
    }
    public function downloadFile($id)
    {
        $path = FileModel::where("id", $id)->first();
        return Storage::download($path->path, $path->name);
    }
}
