<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Jakarta");
    }

    public function index()
    {
        $data['title'] = 'Client';
        return view('content.client.index', $data);
    }

    public function datatables(Request $request){
        try{
            if ($request->ajax()) :
                $client = Client::get();
                
                return DataTables::of($client)
                    ->addIndexColumn() //memberikan penomoran    
                    ->addColumn('action', function ($row) {
                        
                        $btn = ' <a href="'.route('client.edit', $row->id).'" class="btn btn-sm btn-outline-info" title="edit"><i class="fa fa-edit"></i></a>';

                        $btn .= ' <a href="javascript:remove(\'' .  $row->id . '\')" class="btn btn-sm btn-outline-danger" title="edit"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['dropdown', 'action'])   //merender content column dalam bentuk html
                    ->escapeColumns()  //mencegah XSS Attack
                    ->toJson(); //merubah response dalam bentuk Json
            endif;
        }
        catch (Throwable $e) {
            report($e);

            return false;
        }
    }
    
    public function create(){
        $data['title'] = 'Add Client';
        return view('content.client.create', $data);
    }

    public function store(Request $request){
        $client = new Client;
        $client->nama_client       = $request->client_name;
        $client->alamat_client     = $request->client_address;
        $client->tgl_mulai_kontrak = $request->start_contract;
        $client->tgl_akhir_kontrak = $request->end_contract;

        $client->save();

        return redirect()->route('client')->with('success', 'New Client Has Been Saved!');
    }

    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('content.client.edit', compact('client'));
    }

    public function update(Request $request, $id){
        $data = [
            'nama_client' => $request->input('client_name', $request->client_name),
            'alamat_client'  => $request->input('client_address', $request->client_address),
            'tgl_mulai_kontrak'        => $request->input('start_contract', $request->start_contract),
            'tgl_akhir_kontrak'        => $request->input('end_contract', $request->endt_contract)
        ];

        $client = Client::findOrFail($id);

        $client->update($data);

        return redirect()->route('client')
            ->with('success', 'Client updated successfully');

    }

    public function delete($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client Has Been Removed!',
        ]); 
    }

}
