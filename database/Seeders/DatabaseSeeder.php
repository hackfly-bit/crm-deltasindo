<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $perusahaan = array(
            array('id' => '1', 'name' => 'Pemerintah'),
            array('id' => '2', 'name' => 'Swasta')
        );

        $segmentasi = array(
            array('id' => '1', 'name' => 'AGRICULTURE & VETERINARY'),
            array('id' => '2', 'name' => 'CHEMICAL'),
            array('id' => '3', 'name' => 'COSMETIC, FLAVOUR & FRAGRANCE'),
            array('id' => '4', 'name' => 'CEDUCATION'),
            array('id' => '5', 'name' => 'ENERGY & MINNING'),
            array('id' => '6', 'name' => 'ENVIRONMENT'),
            array('id' => '7', 'name' => 'FOOD & BAVERAGE'),
            array('id' => '8', 'name' => 'HEALTH'),
            array('id' => '9', 'name' => 'LAB SERVICE'),
            array('id' => '10', 'name' => 'LAB RESEARCH'),
            array('id' => '11', 'name' => 'LUBRICANT'),
            array('id' => '12', 'name' => 'OIL & GAS'),
            array('id' => '13', 'name' => 'PHARMACY'),
            array('id' => '14', 'name' => 'POWER PLAN'),
            array('id' => '15', 'name' => 'LAINNYA')
        );

        // $kegiatan = array(
        //     array('id' => '1', 'name' => 'CALL'),
        //     array('id' => '2', 'name' => 'PRESENTASI'),
        //     array('id' => '3', 'name' => 'BUAT SPH'),
        //     array('id' => '4', 'name' => 'VISIT')

        // );

        $principal = array(

            array('id' => '1', 'name' => 'GBC'),
            array('id' => '2', 'name' => 'SH SCIENTIFIC'),
            array('id' => '3', 'name' => 'WASSERLAB'),
            array('id' => '4', 'name' => 'K-LAB'),
            array('id' => '5', 'name' => 'MAGMATHERM'),
            array('id' => '6', 'name' => 'ODLAB'),
            array('id' => '7', 'name' => 'WIGGENS'),
            array('id' => '8', 'name' => 'CMSI (LOKAL)'),
            array('id' => '9', 'name' => 'PHOENIX'),
            array('id' => '10', 'name' => 'SONNEN'),
            array('id' => '11', 'name' => 'ATPIO'),
            array('id' => '12', 'name' => 'PHOTRON')

        );

        $pertemuan = array(

            array('id' => '1', 'name' => '1'),
            array('id' => '2', 'name' => '2'),
            array('id' => '4', 'name' => '4'),
            array('id' => '3', 'name' => '3'),
            array('id' => '5', 'name' => '5'),
            array('id' => '6', 'name' => '6'),
            array('id' => '7', 'name' => '7'),
            array('id' => '8', 'name' => '8'),
            array('id' => '9', 'name' => '9'),
            array('id' => '10', 'name' => '10'),
            array('id' => '11', 'name' => '11'),
            array('id' => '12', 'name' => '12')

        );

        $status = array(
            array('id' => '1', 'name' => 'Done'),
            array('id' => '2', 'name' => 'Lost'),
            array('id' => '3', 'name' => 'Hold'),
            array('id' => '4', 'name' => 'On Progress'),
            array('id' => '5', 'name' => 'Win'),
        );

        $sumber_anggaran = array(
            array('id' => '1', 'name' => 'DAK'),
            array('id' => '2', 'name' => 'APBD'),
            array('id' => '3', 'name' => 'APBN'),
            array('id' => '4', 'name' => 'APBNP'),
            array('id' => '5', 'name' => 'PIHAK KETIGA'),
            array('id' => '6', 'name' => 'ANGGARAN KANTOR')
        );


        $metode_pembelian = array(
            array('id' => '1', 'name' => 'Direct Purchase'),
            array('id' => '2', 'name' => 'E-CATALOG'),
            array('id' => '4', 'name' => 'TANDER'),
        );

        $time_line = array(
            array('id' => '1', 'name' => 'Q1'),
            array('id' => '2', 'name' => 'Q2'),
            array('id' => '3', 'name' => 'Q3'),
            array('id' => '4', 'name' => 'Q4'),
            array('id' => '5', 'name' => 'Q5')
        );

        $nama_customer = array(
            array("id" => "1", "user_id" => "1", "nama_instansi" => "Four Times Chewy", "nama_customer" => "Cruz Daniels", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "2", "user_id" => "1", "nama_instansi" => "Bring Me the Pants", "nama_customer" => "Khalid Gates", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "3", "user_id" => "1", "nama_instansi" => "Ample Saturday", "nama_customer" => "Justin Poole", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "4", "user_id" => "1", "nama_instansi" => "Chewy Chewy", "nama_customer" => "Marianne Bailey", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "5", "user_id" => "1", "nama_instansi" => "McTeddy", "nama_customer" => "Muhammed Villegas", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "6", "user_id" => "1", "nama_instansi" => "Lynette Eats the Teddy", "nama_customer" => "Jana Collier", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "7", "user_id" => "1", "nama_instansi" => "Lynette Ettenyl", "nama_customer" => "Arjun Hampton", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "8", "user_id" => "1", "nama_instansi" => "One Girl, Four Pants", "nama_customer" => "Kenny Manning", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "9", "user_id" => "1", "nama_instansi" => "Chewy Dream and a Pinch of Teddy", "nama_customer" => "Nicolas Lane", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "10", "user_id" => "1", "nama_instansi" => "No Teddy", "nama_customer" => "Tina Vaughn", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "11", "user_id" => "1", "nama_instansi" => "The Ample Pants", "nama_customer" => "Annika Barry", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "12", "user_id" => "1", "nama_instansi" => "Of Men and Foxes", "nama_customer" => "Ayah Banks", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "13", "user_id" => "1", "nama_instansi" => "Flight of the Purple Foxes", "nama_customer" => "Ben Odom", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "14", "user_id" => "1", "nama_instansi" => "C.H.E.W.Y.", "nama_customer" => "Milton Sheppard", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "15", "user_id" => "1", "nama_instansi" => "Purely Purple", "nama_customer" => "Eesa Lambert", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "16", "user_id" => "1", "nama_instansi" => "Prancing Twins", "nama_customer" => "Frederic Barrera", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "17", "user_id" => "1", "nama_instansi" => "The Ramsay Street Strippers", "nama_customer" => "Lilli Odonnell", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "18", "user_id" => "1", "nama_instansi" => "Ramsay Street Thunder Ears", "nama_customer" => "Laurence Duran", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "19", "user_id" => "1", "nama_instansi" => "Teddyback", "nama_customer" => "Dominik Brooks", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
            array("id" => "20", "user_id" => "1", "nama_instansi" => "The Chewy Lynette Project", "nama_customer" => "Erik York", "jabatan" => "manager", "nomer_hp" => "0853673848883", "jenis_perusahaan" => "swasta", "segmentasi" => "Chamical", "alamat" => "ulujami , Jakarta , Selatan "),
        );



        // array(
        //     array('id' => '1', 'user_id' =>  '1', 'nama_instansi' => 'PT. Indonesia Bumi Harta', 'nama_customer' => 'Yayan Wahyu Setion', 'jabatan' => 'manager', 'jenis_perusahaan' => 'swasta', 'segmentasi' => 'Chamical', 'alamat' => 'ulujami , Jakarta , Selatan', 'nomer_hp' => '0853673848883')

        // );

        $kegiatan_visit = array(
            array("id" => "1", "user_id" => "1", "customer_id" => "1", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "3",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "2", "user_id" => "1", "customer_id" => "2", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "4",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "3", "user_id" => "1", "customer_id" => "3", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "5",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "4", "user_id" => "1", "customer_id" => "4", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "6",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "5", "user_id" => "1", "customer_id" => "5", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "7",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "6", "user_id" => "1", "customer_id" => "6", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "8",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "7", "user_id" => "1", "customer_id" => "7", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "9",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "8", "user_id" => "1", "customer_id" => "8", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "10",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "9", "user_id" => "1", "customer_id" => "9", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "11", "note" => "note Singakat Untuk Kamu "),
            array("id" => "10", "user_id" => "1", "customer_id" => "10", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "12",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "11", "user_id" => "1", "customer_id" => "11", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "13",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "12", "user_id" => "1", "customer_id" => "12", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "14",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "13", "user_id" => "1", "customer_id" => "13", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "15",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "14", "user_id" => "1", "customer_id" => "14", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "16", "note" => "note Singakat Untuk Kamu "),
            array("id" => "15", "user_id" => "1", "customer_id" => "15", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "17", "note" => "note Singakat Untuk Kamu "),
            array("id" => "16", "user_id" => "1", "customer_id" => "16", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "18", "note" => "note Singakat Untuk Kamu "),
            array("id" => "17", "user_id" => "1", "customer_id" => "17", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "19", "note" => "note Singakat Untuk Kamu "),
            array("id" => "18", "user_id" => "1", "customer_id" => "18", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "20",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "19", "user_id" => "1", "customer_id" => "19", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "21",  "note" => "note Singakat Untuk Kamu "),
            array("id" => "20", "user_id" => "1", "customer_id" => "20", "kegiatan" => "Visit", "tanggal" => Carbon::now(), "produk" => "Apa Aja", "brand" => "Apa Aja", "pertemuan" => "22",  "note" => "note Singakat Untuk Kamu ")
        );
        // array(
        //     array('id' => '1', 'user_id' =>  '1', 'customer_id' => '1', 'jenis_kegiatan' => 'visit', 'tanggal_visit'  => Carbon::now(), 'produk' => 'Apa Aja',  'principal' => 'apa aja', 'pertemuan_ke' => '3', ,  'deskripsi' => 'deskripsi singakat untuk kamu')
        // );

        $kegiatan_other = array(
            array('id' => '1', 'user_id' =>  '1', 'customer_id' => '1', 'nama_kegiatan' => 'kunjungan', 'tanggal_other' => Carbon::now(), 'deskripsi' => 'apa aja boleh')
        );

        $sph = array(
              array("id" => "1", "user_id" => "1", "customer_id" => "1", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "200000000", "metode_pembelian" => "cash" ),
              array("id" => "2", "user_id" => "1", "customer_id" => "2", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "300000000", "metode_pembelian" => "cash" ),
              array("id" => "3", "user_id" => "1", "customer_id" => "3", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "400000000", "metode_pembelian" => "cash" ),
              array("id" => "4", "user_id" => "1", "customer_id" => "4", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "500000000", "metode_pembelian" => "cash" ),
              array("id" => "5", "user_id" => "1", "customer_id" => "5", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "600000000", "metode_pembelian" => "cash" ),
              array("id" => "6", "user_id" => "1", "customer_id" => "6", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "700000000", "metode_pembelian" => "cash" ),
              array("id" => "7", "user_id" => "1", "customer_id" => "7", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "800000000", "metode_pembelian" => "cash" ),
              array("id" => "8", "user_id" => "1", "customer_id" => "8", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "900000000", "metode_pembelian" => "cash" ),
              array("id" => "9", "user_id" => "1", "customer_id" => "9", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "100000000", "metode_pembelian" => "cash" ),
            array("id" => "10", "user_id" => "1", "customer_id" => "10", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "110000000", "metode_pembelian" => "cash" ),
            array("id" => "11", "user_id" => "1", "customer_id" => "11", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "120000000", "metode_pembelian" => "cash" ),
            array("id" => "12", "user_id" => "1", "customer_id" => "12", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "130000000", "metode_pembelian" => "cash" ),
            array("id" => "13", "user_id" => "1", "customer_id" => "13", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "140000000", "metode_pembelian" => "cash" ),
            array("id" => "14", "user_id" => "1", "customer_id" => "14", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "150000000", "metode_pembelian" => "cash" ),
            array("id" => "15", "user_id" => "1", "customer_id" => "15", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "160000000", "metode_pembelian" => "cash" ),
            array("id" => "16", "user_id" => "1", "customer_id" => "16", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "170000000", "metode_pembelian" => "cash" ),
            array("id" => "17", "user_id" => "1", "customer_id" => "17", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "180000000", "metode_pembelian" => "cash" ),
            array("id" => "18", "user_id" => "1", "customer_id" => "18", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "190000000", "metode_pembelian" => "cash" ),
            array("id" => "19", "user_id" => "1", "customer_id" => "19", "kegiatan" => "SPH", "sumber_anggaran" => "APBN", "nilai_pagu" => "200000000", "metode_pembelian" => "cash" ),
            array("id" => "20", "user_id" => "1", "customer_id" => "20", "kegiatan" => "SPH", "sumber_anggaran" => "APBD", "nilai_pagu" => "210000000", "metode_pembelian" => "cash" )
        );

        $po = array(
            array("id" => "1", "user_id" => "1", "customer_id" => "1",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "2", "user_id" => "1", "customer_id" => "2",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "3", "user_id" => "1", "customer_id" => "3",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "4", "user_id" => "1", "customer_id" => "4",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "5", "user_id" => "1", "customer_id" => "5",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "6", "user_id" => "1", "customer_id" => "6",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "7", "user_id" => "1", "customer_id" => "7",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "8", "user_id" => "1", "customer_id" => "8",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "9", "user_id" => "1", "customer_id" => "9",  "kegiatan" => "Purchase Order", "due_date" => Carbon::now(), ),
            array("id" => "10", "user_id" => "1", "customer_id" => "10","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "11", "user_id" => "1", "customer_id" => "11","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "12", "user_id" => "1", "customer_id" => "12","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "13", "user_id" => "1", "customer_id" => "13","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "14", "user_id" => "1", "customer_id" => "14","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "15", "user_id" => "1", "customer_id" => "15","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "16", "user_id" => "1", "customer_id" => "16","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "17", "user_id" => "1", "customer_id" => "17","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "18", "user_id" => "1", "customer_id" => "18","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "19", "user_id" => "1", "customer_id" => "19","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), ),
            array("id" => "20", "user_id" => "1", "customer_id" => "20","kegiatan" => "Purchase Order",  "due_date" => Carbon::now(), )

        );

        $call = array(
            array("id" => "1", "user_id" => "1", "customer_id" => "1", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "2", "user_id" => "1", "customer_id" => "2", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "3", "user_id" => "1", "customer_id" => "3", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "4", "user_id" => "1", "customer_id" => "4", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "5", "user_id" => "1", "customer_id" => "5", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "6", "user_id" => "1", "customer_id" => "6", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "7", "user_id" => "1", "customer_id" => "7", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "8", "user_id" => "1", "customer_id" => "8", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "9", "user_id" => "1", "customer_id" => "9", "kegiatan" => "Call", "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "10", "user_id" => "1", "customer_id" => "10","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "11", "user_id" => "1", "customer_id" => "11","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "12", "user_id" => "1", "customer_id" => "12","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "13", "user_id" => "1", "customer_id" => "13","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "14", "user_id" => "1", "customer_id" => "14","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "15", "user_id" => "1", "customer_id" => "15","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "16", "user_id" => "1", "customer_id" => "16","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "17", "user_id" => "1", "customer_id" => "17","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "18", "user_id" => "1", "customer_id" => "18","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "19", "user_id" => "1", "customer_id" => "19","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat"),
            array("id" => "20", "user_id" => "1", "customer_id" => "20","kegiatan" => "Call",  "tanggal" => Carbon::now(), "pertemuan" => "1", "note" => "Note Singkat")
        );


        $presentasi = array(
            array("id" => "1", "user_id" => "1", "customer_id" => "1",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "2", "user_id" => "1", "customer_id" => "2",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "3", "user_id" => "1", "customer_id" => "3",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "4", "user_id" => "1", "customer_id" => "4",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "5", "user_id" => "1", "customer_id" => "5",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "6", "user_id" => "1", "customer_id" => "6",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "7", "user_id" => "1", "customer_id" => "7",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "8", "user_id" => "1", "customer_id" => "8",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "9", "user_id" => "1", "customer_id" => "9",  "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "10", "user_id" => "1", "customer_id" => "10", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "11", "user_id" => "1", "customer_id" => "11", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "12", "user_id" => "1", "customer_id" => "12", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "13", "user_id" => "1", "customer_id" => "13", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "14", "user_id" => "1", "customer_id" => "14", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "15", "user_id" => "1", "customer_id" => "15", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "16", "user_id" => "1", "customer_id" => "16", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "17", "user_id" => "1", "customer_id" => "17", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "18", "user_id" => "1", "customer_id" => "18", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "19", "user_id" => "1", "customer_id" => "19", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),
            array("id" => "20", "user_id" => "1", "customer_id" => "20", "kegiatan" => "Presentasi", "tanggal" => Carbon::now(), "pertemuan" =>"1", "note" => "Note Singkat"),

        );


        $role = array(

            array('name' => 'admin', 'guard_name' => 'web'),
            array('name' => 'manager', 'guard_name' => 'web'),
            array('name' => 'sales', 'guard_name' => 'web')


        );
        DB::table('roles')->insert($role);

        // array(
        //     array('id' => '1', 'user_id' =>  '1', 'customer_id' => '1', 'sumber_anggaran' => 'APBN', 'nilai_pagu' => '2000000', 'metode_pembelian' => 'cash', 'metode_pembayaran' => 'cash', 'time_line' =>  'Q1', 'tanggal_pengiriman' => Carbon::now(), 'tanggal_instalasi' => Carbon::now(),)
        // );

        $product = array(
            array("principal_id" => "12", "nama_produk" => "P700 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P701 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P702 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P703 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P704 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P705 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P706 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P707 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P712 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P713A - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P714 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P715 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P716 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P717 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P718 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P719 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P721 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P725 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P726 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P729 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P733 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P735 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P736 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P737 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P738 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P739 - D2 Lamps"),
            array("principal_id" => "12", "nama_produk" => "P747 - D2 Lamps"),

        );

        $admin = User::create(
            [
                'username' => 'admin',
                'firstname' => 'Admin',
                'lastname' => 'Admin',
                'role' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password')
            ]
        );
        $admin->assignRole('admin');

        $sales = User::create([
            'username' => 'sales',
            'firstname' => 'sales',
            'lastname' => 'sales',
            'role' => 'sales',
            'email' => 'sales@admin.com',
            'password' => bcrypt('password')
        ]);
        $sales->assignRole('sales');

        $manager = User::create([
            'username' => 'manager',
            'firstname' => 'manager',
            'lastname' => 'manager',
            'role' => 'manager',
            'email' => 'manager@admin.com',
            'password' => bcrypt('password')
        ]);

        $manager->assignRole('manager');
        




        

        DB::table('jenis_perusahaan')->insert($perusahaan);
        DB::table('segmentasi')->insert($segmentasi);
        // DB::table('jenis_kegiatan')->insert($kegiatan);
        DB::table('principal')->insert($principal);
        DB::table('pertemuan')->insert($pertemuan);
        DB::table('status')->insert($status);
        DB::table('sumber_anggaran')->insert($sumber_anggaran);
        // DB::table('metode_pembayaran')->insert($metode_pembayaran);
        DB::table('metode_pembelian')->insert($metode_pembelian);
        DB::table('time_line')->insert($time_line);
        DB::table('customers')->insert($nama_customer);
        DB::table('kegiatan_visits')->insert($kegiatan_visit);
        DB::table('kegiatan_others')->insert($kegiatan_other);
        DB::table('sphs')->insert($sph);
        DB::table('products')->insert($product);
        DB::table('calls')->insert($call);
        // Qutation
        DB::table('presentasis')->insert($presentasi);
        DB::table('preorders')->insert($po);
    }
}
