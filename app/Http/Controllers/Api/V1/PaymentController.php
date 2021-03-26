<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use ResponseService;
use CrudService;
use App\Http\Requests\PaymentRequest;
use App\Jobs\DeleteMultiplePayment;
use App\Events\DeletePaymentEvent;

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
        //check single or multiple delete
        if($id == 'multiple'){

             // count data
             $total_data = count($request->ids);

             if($total_data > 0 ){
                
                // looping ids request
                for ($i=0; $i < count($request->ids) ; $i++) { 

                  // execute jobs delete mulitple payment
                  DeleteMultiplePayment::dispatch($request->ids[$i]);
                  DeletePaymentEvent::dispatch($total_data,$i+1);
                }

                // response success delete multiple
                return $this->response->setCode(200)->setMsg('Telah Berhasil menghapus '.$total_data.' Data' )->get();
                
             }else{

                // response when ids request < 1
                return $this->response->setCode(500)->setErrors(['details' => 'Data Harus Lebih dari 1'])->get();
             }
             
        }else{
            //single delete
             $this->model->findOrFail($id)->delete();
             return $this->response->setCode(200)->setMsg('Data Berhasil Dihapus')->get();
        }

       

    }

}
