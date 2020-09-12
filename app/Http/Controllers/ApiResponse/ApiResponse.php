<?php


namespace App\Http\Controllers\ApiResponse;


use App\ApiCode;
use App\Http\Controllers\ApiResponse\MyResponseBuilder as ResponseBuilder  ;

trait ApiResponse
{
    /**
     * @param $api_code
     * @param $http_code
     * @param null $message
     * @param null $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondWithError($api_code , $http_code , $message = null , $data = null){
        return ResponseBuilder::asError($api_code)
            ->withMessage($message)
            ->withData($data?[
                "errors" =>$data
            ]:null)
            ->withHttpCode($http_code)->build();
    }

    /**
     * @param null $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondWithMessage($message =null){
        return ResponseBuilder ::asSuccess()
            ->withMessage($message)
            ->build();
    }

    /**
     * @param $data
     * @param null $msg
     * @param int $http_code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respond($data , $msg = null, $http_code =200){
        return ResponseBuilder ::asSuccess($http_code)
            ->withData($data)
            ->withHttpCode($http_code)
            ->withMessage($msg)
            ->build();
    }

    /**
     * @param $api_code
     * @param null $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondUnAuthorizedRequest($api_code , $message = null){
        $message = $message?: ApiCode::$statusTexts[ApiCode::INVALID_CREDENTIALS];
        return $this->respondWithError($api_code , ApiCode::INVALID_CREDENTIALS , $message);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondWithMethodNotAllowedError(){
        $errorCode = ApiCode::METHOD_NOT_ALLOWED ;
        return $this->respondWithError($errorCode , $errorCode , ApiCode::$statusTexts[$errorCode]);
    }


    /**
     * @param null $data
     * @return array
     */
    public function respondWithSuccessArray($data = null){
        return  (array)($this->respond($data))->getData();
    }

    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondWithValidationError($data){
        $errorCode = ApiCode::VALIDATION_ERROR;
        return $this->respondWithError($errorCode , $errorCode, 'Validation Error' , $data);
    }

    /**
     * @param null $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondWithUnexpectedError($message =null){
        $errorCode = ApiCode::INTERNAL_SERVER_ERROR;
        return $this->respondWithError($errorCode , $errorCode  , $message);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondWithHttpNotFoundError(){
        $errorCode = ApiCode::NOT_FOUND;
        return $this->respondWithError($errorCode , $errorCode , 'The specified URL cannot be found');
    }

}
