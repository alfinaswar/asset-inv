<?php
namespace App\Http\Controllers;
use App\Models\DataInventaris;
use App\Models\MasterRs;
use App\Models\MasterDepartemenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class DataInventarisController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:inventaris-create', ['only' => ['index','show']]);
        //  $this->middleware('permission:inventaris-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            if (auth()->user()->role == "admin") {
                $data = DataInventaris::latest();
            } else {
                $data = DataInventaris::where('nama_rs', auth()->user()->kodeRS)->latest();

            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $print = '<a href="' . route('inventaris.label', $row->id) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-print"></i></button></a>';
                    $edit = '<a href="' . route('inventaris.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-user-cog"></i></button></a>';
                    $btn = $print .' '. $edit; 
                    return $btn;
                })
                ->addColumn('nama_rs', function ($row) {

                    switch ($row->nama_rs) {
                        case 'K':
                            $realname = 'Awalbros Ayani';
                            break;
                        case 'I':
                            $realname = 'Awalbros Panam';
                            break;
                        case 'B':
                            $realname = 'Awalbros Batam';
                            break;
                        case 'A':
                            $realname = 'Awalbros Sudirman';
                            break;
                        case 'G':
                            $realname = 'Awalbros Ujung Batu';
                            break;
                        case 'S':
                            $realname = 'Awalbros Bagan Batu';
                            break;
                        case 'R':
                            $realname = 'Awalbros Botania';
                            break;
                        case 'D':
                            $realname = 'Awalbros Dumai';
                            break;
                        default:
                            $realname = 'Nama RS Kosong';
                            break;
                    }

                    $print = $realname;
                    return $print;
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('filter_pengguna') == 'umum' || $request->get('filter_pengguna') == 'Medis') {
                        $instance->where('pengguna', $request->get('filter_pengguna'));
                    }
    if ($request->get('filter_rs') && $request->get('filter_rs') !== '') {
                        $instance->where('nama_rs', $request->get('filter_rs'));
    }
    if ($request->get('filter_departemen') && $request->get('filter_departemen') !== '') {
                        $instance->where('departemen', $request->get('filter_departemen'));
    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('nama', 'LIKE', "%$search%")
                                ->orWhere('no_inventaris', 'LIKE', "%$search%")
                                ->orWhere('no_sn', 'LIKE', "%$search%");
                        });
                    }
})
                ->rawColumns(['action'])
                ->make(true);
        }
        $rs = MasterRs::all();
        $dept = MasterDepartemenModel::all();
        return view('data-inventaris.index', compact('rs','dept'));
    }

    public function create()
    {
        return view('data-inventaris.create');
    }

    public function label($id)
    {

        $query = DataInventaris::find($id);
        // $routes = route('masalah.history', $query->kode_item);
        $routes = '192.168.1.239/asset-inventaris/history/' . $query->kode_item;
        $qrcode = base64_encode(QrCode::format('svg')->size(40)->errorCorrection('H')->generate($routes));
        // dd($qrcode);
        $pdf = Pdf::loadView('data-inventaris.label', compact('qrcode', 'query'))->setPaper([0, 0, 161.57, 80.37], 'portrait');
        $pdfmya = $pdf->stream('Label.pdf');
        return $pdf->stream('Label.pdf');
    }

    public function getItem(Request $request)
    {

        if (auth()->check()) {
            $kodeRS = auth()->user()->kodeRS;
            switch ($kodeRS) {
                case 'K':
                    $selectdb = 'mysql2';
                    break;
                case 'I':
                    $selectdb = 'mysql3';
                    break;
                case 'B':
                    $selectdb = 'mysql4';
                    break;
                case 'A':
                    $selectdb = 'mysql5';
                    break;
                case 'G':
                    $selectdb = 'mysql6';
                    break;
                case 'S':
                    $selectdb = 'mysql7';
                    break;
                case 'R':
                    $selectdb = 'mysql8';
                    break;
                case 'D':
                    $selectdb = 'mysql9';
                    break;
                default:
                    $selectdb = 'Unknown';
                    break;
            }
        }
        $item = [];
        $kategori = $request->kategori;
        $dataItem = DB::connection($selectdb)->table('masteritem')->where('KategoriitemID', $kategori);
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem->where('Nama', 'LIKE', "%$search%")->limit(5)
                ->get(['ItemID', 'Nama']);
            $item = $dataItem->pluck('Nama', 'ItemID');
        } else {
            $item = $dataItem->limit(5)->get(['ItemID', 'Nama'])->pluck('Nama', 'ItemID');
        }
        return response()->json($item);
    }
    public function store(request $request)
    {
        $datanama = $request->nama;
        $result = explode(",", $datanama);
        $assetid = $result[0];
        $nama = $result[1];
        //dd($assetid);
        //ambil data terakhir
        $latest = DataInventaris::latest()->first()->id + 1;
        $kode_item = 'KD' . $latest . '';
        //dd($kode_item);
        //validate form
        $this->validate($request, [
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/gambar', $gambar->hashName());
        $dokumen = $request->file('dokumen');
        $dokumen->storeAs('public/dokumen', $dokumen->hashName());
        DataInventaris::create([
            'nama' => $nama,
            'real_name' => $request->real_name,
            'kode_item' => $kode_item,
            'assetID' => $assetid,
            'no_inventaris' => $request->no_inventaris,
            'no_sn' => $request->no_sn,
            'tanggal_beli' => $request->tanggal_beli,
            'keterangan' => $request->keterangan,
            'departemen' => $request->departemen,
            'pengguna' => $request->userPengguna,
            'gambar' => $gambar->hashName(),
            'tgl_kalibrasi' => $request->tgl_kalibrasi,
            'tgl_expire' => $request->tgl_expire,
            'dokumen' => $dokumen->hashName(),
            'nama_rs' => auth()->user()->kodeRS,
        ]);

        return redirect()->route('inventaris.index')->with('success', 'Data berhasil ditambahkan');
    }
    public function edit($id)
    {
        $datainv = DataInventaris::find($id);
        $dept = MasterDepartemenModel::all();
        return view('data-inventaris.edit', compact('datainv','dept'));
    }
    public function update(Request $request, $id)
    {
        $data['nama'] = $request->nama;
        $data['no_inventaris'] = $request->no_inventaris;
        $data['no_sn'] = $request->no_sn;
        $data['tanggal_beli'] = $request->tanggal_beli;
        $data['departemen'] = $request->departemen;
        $data['pengguna'] = $request->userPengguna;
        $query = DataInventaris::find($id);
        $query->update($data);
        return redirect()->route('inventaris.index')->with('success', 'Data berhasil di ubah');
    }
}