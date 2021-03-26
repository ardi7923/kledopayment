<?php

namespace App\Services;

class ResponseService 
{
	private 
			$code   = 500,
			$msg    = "",
			$errors = [],
			$data   = [] ;

	public function setCode($code)
	{
		$this->code = $code;
		return $this;
	}

	public function setMsg($msg)
	{
		$this->msg = $msg;
		return $this;
	}

	public function setErrors($errors)
	{
		$this->errors = $errors;
		return $this;
	}

	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function get()
	{
		$response = [];

		if($this->msg){
			$response['msg'] = $this->msg;
		}
		
		if($this->data){
			$response['data'] = $this->data;
		}

		if($this->errors){
			$response['errors'] = $this->errors;
		}

		return Response()->json($response,$this->code);
		
	}

}


