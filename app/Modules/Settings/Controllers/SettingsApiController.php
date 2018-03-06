<?php namespace App\Modules\Settings\Controllers;

use App\Modules\ApiBaseController;

use Illuminate\Http\Request;

//use App\Modules\Managers\Channel\ChannelRepository;
use App\Modules\Managers\User\UserRepositoryInterface;
use App\Modules\Settings\Validators\SettingsValidator;
use App\Modules\User\Transformers\UserTransformer;
use App\Modules\Helper\Helper;
use League\Fractal\Manager;


class SettingsApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, UserRepositoryInterface $userRepo)
    {
        $this->user = $userRepo;
        $this->settingsValidator = new SettingsValidator();
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
    public function updateColor(Request $request)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        $id = $request->get('id');
        $data = $request->get('data');
        $validation = $this->settingsValidator->updateColor($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
       
        $user = $this->user->findWhere(['uuid'  => $id])->first();
        if(!$user)
        {
            return $this->respondWithError(['User not found'], 201);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $channel = $this->user->updateData($data, $id);
        
        return $this->respondWithBoolean($channel, new UserTransformer());
    }
}

