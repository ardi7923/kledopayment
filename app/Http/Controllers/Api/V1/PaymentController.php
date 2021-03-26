<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use ResponseService;
use CrudService;
use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{
    private $model,
            $response,
            $validator,
            $crud_service;

    public function __construct(Payment $model,ResponseService $response,CrudService $crud_service)
    {
        $this->model        = $model;
        $this->response     = $response;
        $this->crud_service = $crud_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit  = $request->limit ?? 6;
        $name   = $request->name;

        $data = $this->model->query();

        if($name){
            $data->where('payment_name','like','%'.$name.'%');
        }

        return $this->response->setCode(200)->setData($data->paginate($limit))->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        return $this->crud_service
                    ->setModel( $this->model )
                    ->setRequest( $request )
                    ->save();
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
    public function destroy($id,Request $request)
    {

        if($id == 'multiple'){
            $this->model->whereIn('id',$request->ids)->delete();
             return $this->response->setCode(200)->setMsg('Data Berhasil Dihapus')->get();
        }else{

            $this->model->findOrFail($id)->delete();
             return $this->response->setCode(200)->setMsg('Data Berhasil Dihapus')->get();
        }

       

    }

}
