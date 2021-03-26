<?php

namespace App\Services;

use ResponseService;

class CrudService 
{
	private $validator,
			$model,
			$request,
			$facade = null,
			$params = [],
			$id_old;

	public function setValidator($validator)
	{
		$this->validator = $validator;
		return $this;
	}

	public function setModel($model)
	{
		$this->model = $model;
		return $this;
	}

	public function setRequest($request)
	{
		$this->request = $request;
		return $this;
	}

	public function setFacade($facade)
	{
		$this->facade = $facade;
		return $this;
	}

	public function setParams($params)
	{
		$this->params = $params;
		return $this;
	}

	public function setIdOld($id_old)
	{
		$this->id_old = $id_old;
		return $this;
	}

// save method =========================================
	public function save($data = [])
	{
		$response     = new ResponseService;
		if($this->validator){
			$validator = $this->validator->validation($this->request,'store');
        
	        if ($validator->fails()) {
	           $errors =  $validator->errors()->all();
	           return $response
	                     ->setCode(400)
	                     ->setErrors($errors)
	                     ->get();
	        }
		}
		

        try {
            
            if($this->facade == null){

            	if($data){
            		$this->model->create($data);	
            	}else{
            		$this->model->create($this->request->all());
            	}
            	
            }else{
            	$this->facade->save($this->request);
            }

            return $response->setCode(201)
                     	    ->setMsg("Data Berhasil Disimpan")
                     	    ->get();

        }catch(\Exception $e){

          	return $response->setErrors($e->getMessage())
                       	    ->get();
            
        }
	}
// update ========================================
	public function update($data = [])
	{
		
		$response     = new ResponseService;
		
        if($this->validator){
        	$validator = $this->validator->validation($this->request,'update',$this->id_old);
	        if ($validator->fails()) {
	           $errors =  $validator->errors()->all();
	           return $response
	                     ->setCode(400)
	                     ->setErrors($errors)
	                     ->get();
	        }
    	}


        try {
            
            if($this->facade == null){
            	if($data){
            		$this->model->where($this->params)->update($data);
            	}else{
            		$this->model->where($this->params)->update($this->request->except('_token','_method'));
            	}
            	
            }else{
            	$this->facade->update($this->request,$this->params);
            }

            return $response->setCode(200)
                     	 ->setMsg("Data Berhasil Diubah")
                     	 ->get();

        }catch(\Exception $e){

          	return $response->setErrors($e->getMessage())
                       	 	->get();
            
        }
	}
// Delete =======================================
	public function delete()
	{
		$response = new ResponseService;
		try {
				if($this->facade == null){
	            	$this->model->where($this->params)->delete();
	            }else{
	            	$this->facade->delete($this->params);
	            }

				return $response->setCode(200)
	                     	 ->setMsg("Data Berhasil Dihapus")
	                     	 ->get();

			} catch (\Exception $e) {

	          	return $response->setErrors($e->getMessage())
	                       	 	->get();
			}		
	}
}