<?php

namespace App\Http\Controllers;

use App\Models\MasterIPModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterIPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterIPModel::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '';
                    $btnlihat = '';
                    $btnupdate = '';

                    $btnEdite = '<button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-pen-alt"></i></button>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" ><i class="fa fa-folder-open"></i></button>';
                    $btnupdate = '<button type="button"  class="btn btn-outline-brand btn-icon" ><i class="fa fa-file-word"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat . '&nbsp;' . $btnupdate;
                    return $btn;
                })
                ->addColumn('status', function ($query) {
                    // $status = '';
                    if ($query->status == 0) {
                        return $status = '<span class="badge badge-primary">Kosong</span>';
                    } elseif ($query->status == 1) {
                        return $status = '<span class="badge badge-success">Digunakan</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('master-ip.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master-ip.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
