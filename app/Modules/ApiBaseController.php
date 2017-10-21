<?php namespace App\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;

//use Illuminate\Support\Collection;
class ApiBaseController extends Controller
{
    const OK = 200;
    const ERROR = 202;
    const LIMIT = 10;

    const CODE_WRONG_ARGS = 'GEN-FUBARGS';
    const CODE_NOT_FOUND = 'GEN-LIKETHEWIND';
    const CODE_INTERNAL_ERROR = 'GEN-AAAGGH';
    const CODE_UNAUTHORIZED = 'GEN-MAYBGTFO';
    const CODE_FORBIDDEN = 'GEN-GTFO';
    const CODE_UNPROCESSABLE = 'GEN-UNPROCESSABLE';

    protected $statusCode = 200;

    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
        $this->fractal->setSerializer(new ArraySerializer());

        // Are we going to try and include embedded data?
        if (Input::has('embed')) {
            $this->fractal->parseIncludes(Input::get('embed'));
        }
    }

    public function respondWithModel($model)
    {
        if ($model) {
            return $this->respondWithObject($model);
        } else {
            return $this->respondwithErrors($model->getErrors());
        }
    }

    public function respondWithObject($obj)
    {
        return $this->respondWithData($obj);
    }

    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);
        $rootScope = $this->fractal->createData($resource);
        return $this->respondWithData($rootScope->toArray());
    }

    protected function respondWithCollection1($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        if ($collection instanceof LengthAwarePaginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        }
        $rootScope = $this->fractal->createData($resource);
        return $this->respondWithArray($rootScope->toArray());
    }
    
    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        if ($collection instanceof LengthAwarePaginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        }
        $rootScope = $this->fractal->createData($resource);
        return $this->respondWithData($rootScope->toArray());
    }

    protected function getData($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        if ($collection instanceof LengthAwarePaginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        }
        $rootScope = $this->fractal->createData($resource);
        return $rootScope->toArray();
    }

    /*
    public function respondWithCollection($coll)
    {
        if ($coll instanceof LengthAwarePaginator) {
            $data = $coll->toArray();
        } else if ($coll instanceof Collection) {
            $data = $coll->toArray();
        } else {
            $data = $coll;
        }
        if (isset($data['data']) && isset($data['per_page'])) { //in case of pagination
            $data['status'] = self::OK;
            return $this->setStatusCode(self::OK)->respond($data);
        }

        return $this->respondWithData($data);
    }
    */

    public function respondWithArray($data, $message = 'dashboard.success', array $headers = [])
    {
        $data['status'] = empty($data['status']) ? $this->getStatusCode() : $data['status'];
        $data['message'] = empty($data['message']) ? Lang::get($message) : $data['message'];
        return $this->respond($data, $headers);
    }


    public function respondWithBoolean($res)
    {
        return $res ? $this->respondWithSuccess() : $this->errorUnknown();
    }

    public function respondWithSuccess($message = 'dashboard.success')
    {
        return $this->setStatusCode(self::OK)->respond(['message' => Lang::get($message), 'status' => $this->getStatusCode()]);
    }

    private function respond($data, $headers = [])
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respondWithErrors($errors, $message = 'dashboard.errors')
    {
        if (!empty($errors)) {
            return $this->setStatusCode(self::ERROR)->respond(['errors' => $errors, 'message' => Lang::get($message)]);
        } else {
            return $this->errorUnknown();
        }
    }

    public function respondWithData($data)
    {
        return $this->setStatusCode(self::OK)->respond(['data' => $data, 'status' => $this->getStatusCode()]);
    }

    protected function respondWithError($message, $errorCode)
    {
//        if ($this->statusCode === 200) {
//            trigger_error(
//                "You better have a really good reason for erroring on a 200...",
//                E_USER_WARNING
//            );
//        }
        return $this->respondWithArray([
            'code' => $errorCode,
            'error' => $this->statusCode,
            'error_description' => $message
        ]);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)
            ->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)
            ->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorUnknown($message = 'dashboard.unknown_error')
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }
}
