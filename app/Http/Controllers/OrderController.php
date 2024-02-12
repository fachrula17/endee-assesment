<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Jakarta");
    }

    public function index()
    {
        $data['title'] = 'Order';
        return view('content.order.index', $data);
    }

    public function datatables(Request $request){
        try{
            if ($request->ajax()) :
                $order = Order::join('client', 'client.id', '=', 'order.client_id')->get(['order.*', 'client.nama_client']);
                
                return DataTables::of($order)
                    ->addIndexColumn() //memberikan penomoran    
                    ->addColumn('action', function ($row) {
                        
                        $btn = ' <a href="'.route('order.edit', $row->id).'" class="btn btn-sm btn-outline-info" title="edit"><i class="fa fa-edit"></i></a>';

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
        $data['title'] = 'Add Order';
        $data['client'] = Client::orderByDesc('nama_client')->get();
        return view('content.order.create', $data);
    }

    public function store(Request $request){
        $order = new Order;
        $order->client_id     = $request->client_id;
        $order->nama_item     = $request->item_name;
        $order->harga_item    = $request->item_price;
        $order->tanggal_order = $request->order_date;

        $order->save();

        return redirect()->route('order')->with('success', 'New Order Has Been Saved!');
    }

    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        $client = Client::orderByDesc('nama_client')->get();
        $title = 'Edit Client';
        return view('content.order.edit', compact(['order', 'client', 'title']));
    }
    
    public function update(Request $request, $id){
        $data = [
            'client_id'     => $request->input('client_id', $request->client_id),
            'nama_item'     => $request->input('item_name', $request->item_name),
            'harga_item'    => $request->input('item_price', $request->item_price),
            'tanggal_order' => $request->input('order_date', $request->order_date)
        ];

        $order = Order::findOrFail($id);

        $order->update($data);

        return redirect()->route('order')
            ->with('success', 'Order updated successfully');

    }

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order Has Been Removed!',
        ]); 
    }

    public function print_report(){

        $data['order'] = Order::join('client', 'client.id', '=', 'order.client_id')->get(['order.*', 'client.nama_client']);
        return view('content.order.print', $data);
    }
}
