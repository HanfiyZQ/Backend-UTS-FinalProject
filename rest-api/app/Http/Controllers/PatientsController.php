<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use Illuminate\Http\Request;

class PatientsController extends Controller
{

    /**
     * format patients untuk mengubah tampilan status id menjadi nama status 
     * dengan mengambil data dari table
     */
    public function formatPatients($patients)
    {
        return [
            'id' => $patients->id,
            'name' => $patients->name,
            'phone_number' => $patients->phone_number,
            'address' => $patients->address,
            'status' => $patients->status->name_status,
            'date_in' => $patients->date_in,
            'date_out' => $patients->date_out,
            'created_at' => $patients->created_at,
            'updated_at' => $patients->updated_at
        ];
    }

    /**
     * fitur index
     * menggunakan all()
     * mengambil seluruh data di tabel patient
     */
    public function index()
    {
        $patients = Patients::all();

        if ($patients->isNotEmpty()) {
            $patients = $patients->map(function ($patients) {
                return $this->formatPatients($patients);
            });

            $data = [
                'message' => 'Get all patients',
                'data' => $patients
            ];

            return response()->json($data, 200);
        } else {

            $data = [
                'message' => 'Data is empty',
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * fitur store atau add
     * menggunakan create()
     * mengambil inputan name, phone_number, address, status_id, date_in, date_out
     * menggunakan validasi data agar data yang di input sesuai
     * lalu diinput datanya ke database dengan patients model
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'address' => 'required',
            'status_id' => 'required|numeric',
            'date_in' => 'required|date_format:Y-m-d',
            'date_out' => 'nullable|date_format:Y-m-d'

        ]);

        $patients = Patients::create($validateData);


        $data = [
            'message' => 'patients data is created',
            'data' => $this->formatPatients($patients)
        ];

        return response()->json($data, 201);
    }

    /**
     * fitur show
     * menggunakan find()
     * untuk mengambil satu data menggunakan id patient
     */
    public function show($id)
    {
        $patients = Patients::find($id);

        if ($patients) {

            $data = [
                'message' => 'Get detail patients',
                'data' => $this->formatPatients($patients)
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Patients data is not found'
            ];

            return response()->json($data, 404);
        }
    }

    /**
     * fitur update
     * menggunakan find()
     * mengambil inputan name, phone_number, address, status_id, date_in, date_out
     * bisa hanya menginput partial data dengan menggunakan (??)
     * lalu diinput datanya ke database dengan patients model
     */
    public function update(Request $request, $id)
    {

        $patients = Patients::find($id);

        if ($patients) {
            $input = [
                'name' => $request->name ?? $patients->name,
                'phone_number' => $request->phone_number ?? $patients->phone_number,
                'address' => $request->address ?? $patients->address,
                'status_id' => $request->status_id ?? $patients->status_id,
                'date_in' => $request->date_in ?? $patients->date_in,
                'date_out' => $request->date_out ?? $patients->date_out
            ];

            $patients->update($input);

            $data = [
                'message' => 'Patients data is updated',
                'data' => $this->formatPatients($patients)
            ];


            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Patients data is not found'
            ];

            return response()->json($data, 404);
        }
    }

    /**
     * fitur destroy atau delete
     * menggunakan find()
     * menghapus data patient dengan menggunakan id patient
     * lalu data akan dihapus di database
     */
    public function destroy($id)
    {

        $patients = Patients::find($id);

        if ($patients) {
            $patients->delete();

            $data = [
                'message' => 'Patients data is deleted'
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Data is not found'
            ];

            return response()->json($data, 404);
        }
    }

    /**
     * fitur search
     * menggunakan where()
     * mencari berdasarkan nama 
     * memasukkan argumen LIKE untuk mencari data tanpa nama lengkap
     * lalu menggunakan method map() agar collection data bisa dipanggil semuanya yang cocok
     */
    public function search($name)
    {

        $patients = Patients::where('name', 'LIKE', '%' . $name . '%')->get();

        if ($patients->isNotEmpty()) {
            $patients = $patients->map(function ($patient) {
                return $this->formatPatients($patient);
            });

            $data = [
                'message' => 'Get searched resource',
                'data' => $patients
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Data is not found'
            ];
            return response()->json($data, 404);
        }
    }

    /**
     * fitur status positive
     * menggunakan where()
     * mencari berdasarkan status positive
     * memasukkan status_id yang sesuai yaitu 1 sebagai id positive
     * lalu menggunakan method map() agar collection data bisa dipanggil semuanya yang cocok
     * menggunakan method count() untuk mendapatkan total data
     */
    public function positive()
    {
        $patients = Patients::where('status_id', 1)->get();

        if ($patients) {
            $patients = $patients->map(function ($patient) {
                return $this->formatPatients($patient);
            });

            $data = [
                'message' => 'Get searched positive',
                'total' => count($patients),
                'data' => $patients
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Data is not found'
            ];
            return response()->json($data, 404);
        }
    }

    /**
     * fitur status recovered
     * menggunakan where()
     * mencari berdasarkan status recovered
     * memasukkan status_id yang sesuai yaitu 2 sebagai id recovered
     * lalu menggunakan method map() agar collection data bisa dipanggil semuanya yang cocok
     * menggunakan method count() untuk mendapatkan total data
     */
    public function recovered()
    {
        $patients = Patients::where('status_id', 2)->get();

        if ($patients) {
            $patients = $patients->map(function ($patient) {
                return $this->formatPatients($patient);
            });

            $data = [
                'message' => 'Get searched recovered',
                'total' => count($patients),
                'data' => $patients
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Data is not found'
            ];
            return response()->json($data, 404);
        }
    }

    /**
     * fitur status dead
     * menggunakan where()
     * mencari berdasarkan status dead
     * memasukkan status_id yang sesuai yaitu 3 sebagai id dead
     * lalu menggunakan method map() agar collection data bisa dipanggil semuanya yang cocok
     * menggunakan method count() untuk mendapatkan total data
     */
    public function dead()
    {
        $patients = Patients::where('status_id', 3)->get();

        if ($patients) {
            $patients = $patients->map(function ($patient) {
                return $this->formatPatients($patient);
            });

            $data = [
                'message' => 'Get searched recovered',
                'total' => count($patients),
                'data' => $patients
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Data is not found'
            ];
            return response()->json($data, 404);
        }
    }
}
