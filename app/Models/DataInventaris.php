<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataInventaris extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * prote
     *
     * @var array
     *
     */
    protected $table = 'data_inventaris';
     protected $fillable = ['RO2ID','ROID','nama','real_name','kode_item','assetID', 'no_inventaris', 'no_sn','tanggal_beli', 'nama_rs', 'departemen', 'unit', 'pengguna','gambar','tgl_kalibrasi','tgl_expire','dokumen','manualbook'];

    public function DataMaintenance()
    {
        return $this->hasMany(Maintanance::class, 'kode_item', 'kode_item');
    }
}
