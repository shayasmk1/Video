<?php namespace App\Modules\Video\Controllers;

use App\Modules\ApiBaseController;
use App\Http\Controllers\Controller;

use League\Fractal\Manager;
use Illuminate\Http\Request;

use App\Modules\Managers\Video\VideoRepositoryInterface;
use App\Modules\Managers\PrivacyOption\PrivacyOptionRepositoryInterface;
use App\Modules\Managers\VideoLog\VideoLogRepositoryInterface;
use App\Modules\Managers\VideoHistory\VideoHistoryRepositoryInterface;
use App\Modules\Managers\Channel\ChannelRepositoryInterface;
use App\Modules\Video\Validators\VideoValidator;
use App\Modules\Helper\Helper;
use App\Modules\Video\Transformers\VideoTransformer;
use App\Modules\Video\Transformers\PrivacyOptionTransformer;
use Illuminate\Support\Facades\Storage;
use App\Modules\Video\Transformers\VideoHistoryTransformer;
use App\Modules\Managers\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

use Google_Service_Drive_Permission;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;

use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_Video;
use Google_Http_MediaFileUpload;

//tyyJR1VDZKAAAAAAAAAE99P1cxgDW-uEBEJn3myW0mDCfXNEDhDsh55_uDBuIpTL

class VideoApiController extends ApiBaseController
{
    public function __construct(Request $request, Manager $fractal, VideoRepositoryInterface $videoRepo, 
            PrivacyOptionRepositoryInterface $privacyOptionsRepo, VideoLogRepositoryInterface $videoLogRepo, 
            VideoHistoryRepositoryInterface $videoHistoryRepo,
            ChannelRepositoryInterface $channelRepo,
            UserRepositoryInterface $userRepo)
    {
        $this->video = $videoRepo;
        $this->videoValidator = new VideoValidator();
        $this->privacyOptions = $privacyOptionsRepo;
        $this->videoLog = $videoLogRepo;
        $this->videoHistory = $videoHistoryRepo;
        $this->channel = $channelRepo;
        $this->user = $userRepo;
        
        $google_redirect_url = route('glogin');
        $this->gClient = new \Google_Client();
        
        $this->gClient->setRedirectUri($google_redirect_url);
        
        $this->gClient->addScope('https://www.googleapis.com/auth/drive');
        
        $this->helper = new Helper();
        parent::__construct($fractal);
    }
    
//    public function store(Request $request)
//    {
//        if(!$request->exists('data'))
//        {
//            return $this->errorWrongArgs(['No Input found']);
//        }
//        
//        if (!$request->hasFile('video')) {
//            return $this->errorWrongArgs(['No Video Updated']);
//        }
//        
//        $data = $request->get('data');
//        $video = $request->file('video');
//        
//       
//        $validation = $this->videoValidator->store($data, $video);
//        
//        if($validation)
//        {
//            return $this->errorWrongArgs($validation['errors']);
//        }
//        
//        $extension = $video->extension();
//        $imageName = date('dmyHis') . $this->helper->addUuid();
//        //$path = $video->storeAs('/uploads/video', $imageName . '.' . $extension);
//        $video->move('uploads/video' , $imageName . '.' . $extension);
//        $data = $this->helper->clearEmptyValues($data);
//        $data['uuid'] = $this->helper->addUuid();
//        $data['user_id'] = $request->get('id');
//        $data['url'] = $imageName;
////        if($request->exists('thumbnail'))
////        {
////            $data['thumbnail'] = 
////        }
//        
//        $uuid = $this->video->insertData($data);
//        $video = $this->video->findWhere(['uuid' => $uuid])->first();
//        return $this->respondWithItem($video, new VideoTransformer());
//    }
    
    public function index(Request $request)
    {
        $videos = $this->video->getAllPublicVideos();
        return $this->respondWithCollection($videos, new VideoTransformer());
    }
    
    public function my(Request $request)
    {
        $videos = $this->video->findWhere(['user_id'  => $request->get('id')]);
        return $this->respondWithCollection($videos, new VideoTransformer());
    }
    
    public function show(Request $request, $id)
    {
        if($request->exists('id'))
        {
            $video = $this->video->findWhere(['uuid' => $id])->first();
            if($video && ($video['user_id'] != $request->get('id')))
            {
                return $this->respondWithCollection([], new ChannelTransformer());
            }
        }
        else
        {
            $video = $this->video->findWhere(['uuid' => $id, 'active' => 1, 'admin_active' => 1])->first();
        }
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        return $this->respondWithItem($video, new VideoTransformer());
//        $video = $this->video->findWhere(['uuid' => $id, 'active' => 1, 'admin_active' => 1])->first();
//        if(!$video)
//        {
//            return $this->errorWrongArgs(['Video not found']);
//        }
//        return $this->respondWithItem($video, new VideoTransformer());
    }
    
    public function showMe(Request $request, $id)
    {
        $videos = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        return $this->respondWithItem($videos, new VideoTransformer());
    }


    public function update(Request $request, $id)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        
        $data = $request->get('data');
        $validation = $this->videoValidator->store($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $video = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$video)
        {
            return $this->respondWithError(['Video not found'], 201);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $video = $this->video->updateData($data, $id);
        
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function destroy(Request $request, $id)
    {
        $data = $request->get('data');
        $video = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        $video = $this->video->deleteData($id);
        
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function activate(Request $request, $id)
    {
        $data = $request->get('data');
        $video = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        $data['active'] = 1;
        $video = $this->video->updateData($data, $id);
        
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function deactivate(Request $request, $id)
    {
        $data = $request->get('data');
        $video = $this->video->findWhere(['user_id'  => $request->get('id'), 'uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        $data['active'] = 0;
        $video = $this->video->updateData($data, $id);
        
        return $this->respondWithBoolean($video, new VideoTransformer());
    }
    
    public function privacyOptions()
    {
        $privacyOptions = $this->privacyOptions->all();
        return $this->respondWithCollection($privacyOptions, new PrivacyOptionTransformer());
    }
    
    public function searchVideos($name, $noOfResults)
    {
        $videos = $this->video->searchVideos($name, $noOfResults);
        return $this->respondWithCollection($videos, new VideoTransformer());
    }
    
    
    public function currentVideoPosition(Request $request, $id)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        $data = $request->get('data');
        
        $video = $this->video->findWhere(['uuid' => $id])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        
        $validation = $this->videoValidator->videoLogInsert($data, $id);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        $data['video_id'] = $id;
        
        $log = $this->videoLog->insertVideoDetails($data);
        return $this->respondWithBoolean($log, new VideoTransformer());
    }
    
    
    public function startVideo(Request $request, $videoID)
    {
        $video = $this->video->findWhere(['uuid' => $videoID])->first();
        if(!$video)
        {
            return $this->errorWrongArgs(['Video not found']);
        }
        
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        $data['video_id'] = $videoID;
        
        $log = $this->videoHistory->insertData($data);
        return $this->respondWithBoolean($log, new VideoTransformer());
    }
    
    public function history(Request $request)
    {
        $history = $this->videoHistory->findWhere(['user_id' => $request->get('id')]);
        return $this->respondWithCollection($history, new VideoHistoryTransformer());
    }
    
    public function pasteLink(Request $request)
    {
        if(!$request->exists('data'))
        {
            return $this->errorWrongArgs(['No Input found']);
        }
        
        $data = $request->get('data');
        $validation = $this->videoValidator->pasteLink($data);
        if($validation)
        {
            return $this->errorWrongArgs($validation['errors']);
        }
        
        $data = $this->helper->clearEmptyValues($data);
        
        $privacyOption = $this->privacyOptions->findWhere(['name' => 'Public'])->first();
        $channel = $this->channel->findWhere(['name' => 'General'])->first();
        
        $data['name'] = 'Plain URL - ' . $data['url'];
        $data['uuid'] = $this->helper->addUuid();
        $data['user_id'] = $request->get('id');
        
        $data['channel_id'] = $channel->uuid;
        $data['privacy_option_id'] = $privacyOption->uuid;
        $data['active'] = 1;
        
        $video = $this->video->create($data);
        return $this->respondWithItem($video, new VideoTransformer());
    }
    
    public function googleLogin(Request $request)  {
            if($request->exists('id'))
            {
                $id = $request->get('id');
                $request->session()->put('idValue', $id);
            }
            
            $id = $request->session()->get('idValue');
            
          //  $this->gClient->setAccessType("offline");
          //  $this->gClient->setApprovalPrompt("force");
            $user = $this->user->find($id);
            if(!$request->session()->has('videoUUID'))
            {
                if (!$request->hasFile('file'))
                {
                    return redirect()->back()->withErrors(['Video Not Found']);
                }

                $data = $request->get('data');
                $video = $request->file('file');

                $validation = $this->videoValidator->storeGoogle($data, $video);
                if($validation)
                {
                    return $this->errorWrongArgs($validation['errors']);
                }
                

                
                $errors = array();
                if($user->google_app_name == '')
                {
                    $errors[] = 'Google App Name Not Found for this user';
                }
                if($user->google_client_id == '')
                {
                    $errors[] = 'Google Client ID Not Found  for this user';
                }
                if($user->google_client_secret == '')
                {
                    $errors[] = 'Google Client Secret Not Found  for this user';
                }
                if($user->google_api_key == '')
                {
                    $errors[] = 'Google API Key Not Found  for this user';
                }
           
                if(!empty($errors))
                {
                    return redirect()->back()->withErrors($errors);
                }
                
                $privacyOption = $this->privacyOptions->findWhere(['name' => 'Public'])->first();
                $channel = $this->channel->findWhere(['name' => 'General'])->first();
                
                $extension = $video->extension();
                $imageName = date('dmyHis') . $this->helper->addUuid();
                //$request->session()->put('imageName', $imageName . '.' . $extension);
               // $path = $video->storeAs('/video/', $imageName . '.' . $extension);
                
                $video->move('video' , $imageName . '.' . $extension);
                $data = $this->helper->clearEmptyValues($data);
                $data['uuid'] = $this->helper->addUuid();
                $data['user_id'] = $request->get('id');
                $data['url'] = $imageName . '.' . $extension;
               //$data['channel_id'] = $channel->uuid;
               // $data['privacy_option_id'] = $privacyOption->uuid;
                $data['active'] = 1;
                $data['type'] = 'Google Drive';
                
                $request->session()->put('videoUUID', $data['uuid']);
                $request->session()->put('clientToken', $request->get('token'));
                $request->session()->put('clientID', $request->get('client_id'));

                $videoResult = $this->video->create($data);
                if(!$videoResult)
                {
                    return redirect()->back()->withErrors(['Something Went Wrong']);
                }
            }
            
            
            
            $this->gClient->setApplicationName($user->google_app_name);
            //$this->gClient->setScopes(SCOPES);
            $this->gClient->setClientId($user->google_client_id);
            $this->gClient->setClientSecret($user->google_client_secret);
            $this->gClient->setDeveloperKey($user->google_api_key);
            
            
            
            $google_oauthV2 = new \Google_Service_Oauth2($this->gClient);
            if ($request->get('code')){
                $this->gClient->authenticate($request->get('code'));
                $request->session()->put('token', $this->gClient->getAccessToken());
            }
            if ($request->session()->get('token'))
            {
                $this->gClient->setAccessToken($request->session()->get('token'));
            }
            if ($this->gClient->getAccessToken())
            {
                //For logged in user, get details from google using acces
               // $user=$this->user->find(1);
                //$user->access_token=json_encode($request->session()->get('token'));
               // $user->save();    
                $upload = $this->uploadFileUsingAccessToken($id);  
                if($upload)
                {
                    return redirect('/api/v1/video/google/drive/upload/get?token=' . Session::get('clientToken') . '&client_id=' . Session::get('clientID'))->withErrors(['Video Uploaded Successfully']);
                }
                else
                {
                    return redirect('/api/v1/video/google/drive/upload/get?token=' . Session::get('clientToken') . '&client_id=' . Session::get('clientID'))->withErrors(['Somethiong Went Wrong']);
                }
                //dd("Successfully authenticated");
            } else
            {
                //For Guest user, get google login url
                $authUrl = $this->gClient->createAuthUrl();
                return redirect()->to($authUrl);
            }
        }
        
        public function listGoogleUser(Request $request){
          //$users = $this->user->orderBy('uuid','DESC')->paginate(5);
         return view('users.list')->with('i', ($request->input('page', 1) - 1) * 5);;
        }
        
        public function uploadFileUsingAccessToken($id){
            $user = $this->user->find($id);
            $videoUpload= $this->video->find(Session::get('videoUUID'));
            $this->gClient->setApplicationName($user->google_app_name);
            //$this->gClient->setScopes(SCOPES);
            $this->gClient->setClientId($user->google_client_id);
            $this->gClient->setClientSecret($user->google_client_secret);
            $this->gClient->setDeveloperKey($user->google_api_key);
            
            $service = new \Google_Service_Drive($this->gClient);
           // $user=$this->user->find(1);
            $this->gClient->setAccessToken(Session::get('token'),true);
            
            if ($this->gClient->isAccessTokenExpired()) {
               
                // save refresh token to some variable
                $refreshTokenSaved = $this->gClient->getRefreshToken();
               
                $this->gClient->setAccessType('offline');
                $this->gClient->setApprovalPrompt('force');
                // update access token
                $this->gClient->fetchAccessTokenWithRefreshToken($refreshTokenSaved);    
                 
                // // pass access token to some variable
                $updatedAccessToken = $this->gClient->getAccessToken();
                
                // // append refresh token
                $updatedAccessToken['refresh_token'] = $refreshTokenSaved;
                
            
                //Set the new acces token
                $this->gClient->setAccessToken($updatedAccessToken);
                
               // $user->access_token=$updatedAccessToken;
              //  $user->save();                
            }
            
            Session::forget('videoUUID');
            $folderID = $user->google_folder_id;
            if($user->google_folder_id == '')
            {
            
                $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                     'name' => 'Application Storage - Video',
                     'mimeType' => 'application/vnd.google-apps.folder'));


                 $folder = $service->files->create($fileMetadata, array(
                     'fields' => 'id'));
                 $folderID = $folder->id;
                 $userData['google_folder_id'] = $folderID;
                 
                 $userUpdate = $this->user->updateData($userData, $user->uuid);
                 if(!$userUpdate)
                 {
                     return 0;
                     exit;
                 }
            }
            
            //printf("Folder ID: %s\n", $folder->id);
               
            
            $file = new \Google_Service_Drive_DriveFile(array(
                            'name' => $videoUpload->name,
                            'parents' => array($folderID)
                        ));
            
            //Give everyone permission to read and write the file
//                $permission = new Google_Service_Drive_Permission();
//                $permission->setRole( 'writer' );
//                $permission->setType( 'anyone' );
//                $permission->setValue( 'me' );
//                $service->permissions->insert( $file->getId(), $permission );
            
            $result = $service->files->create($file, array(
              'data' => file_get_contents(public_path('video/' . $videoUpload->url)),
              'mimeType' => 'application/octet-stream',
              'uploadType' => 'media'
            ));
            
            File::delete(public_path('video/' . $videoUpload->url));
            // get url of uploaded file
            $url='https://drive.google.com/open?id='.$result->id;
            $data['url'] = 'https://docs.google.com/file/d/' . $result->id . '/view';
            
            $fileId = $result->id;
            $service->getClient()->setUseBatch(true);
            try {
                $batch = $service->createBatch();

                $userPermission = new Google_Service_Drive_Permission(array(
                    'type' => 'anyone',
                    'role' => 'reader'
                ));
                $request = $service->permissions->create(
                    $fileId, $userPermission, array('fields' => 'id'));
                $batch->add($request, 'user');
                $domainPermission = new Google_Service_Drive_Permission(array(
                    'type' => 'domain',
                    'role' => 'reader',
                    'domain' => url('/')
                ));
                $request = $service->permissions->create(
                    $fileId, $domainPermission, array('fields' => 'id'));
                $batch->add($request, 'domain');
                $results = $batch->execute();

                
            } finally {
                $service->getClient()->setUseBatch(false);
            }
            
            $update = $this->video->updateData($data, $videoUpload->uuid);
            if(!$update)
            {
                return 0;
                exit;
            }
            return 1;
            exit;
            
            // https://docs.google.com/file/d/1aaofFq1VLLtwAKaMOdfGTQMCThSrrSiw/edit
            
        }
        
        
        public function googleLoginGet()
        {
            return view('users.googleLogin');
        }
        
        public function dropboxFileUpload(Request $request)
        {
            $user = $this->user->find($request->get('id'));
            
            $errors = array();
            if($user->dropbox_key == '')
            {
                $errors[] = 'DropBox Key Not Found for this user';
            }
            if($user->dropbox_secret == '')
            {
                $errors[] = 'DropBox Secret Not Found  for this user';
            }
            
            if(!empty($errors))
            {
                return redirect()->back()->withErrors($errors);
                exit;
            }
            
            if (!$request->hasFile('file'))
            {
                return redirect()->back()->withErrors(['Video Not Found']);
            }

            $data = $request->get('data');
            $video = $request->file('file');
            
            

            $validation = $this->videoValidator->storeGoogle($data, $video);
            if($validation)
            {
                return $this->errorWrongArgs($validation['errors']);
            }
            
            $extension = $video->extension();
            $imageName = date('dmyHis') . $this->helper->addUuid() . '.' . $extension;
            $video->move('video' , $imageName);
            //$Client = new Client('tyyJR1VDZKAAAAAAAAAE99P1cxgDW-uEBEJn3myW0mDCfXNEDhDsh55_uDBuIpTL');
            $filename = public_path('/video/' . $imageName);
            
            $pathToLocalFile = $filename;
            
            $app = new DropboxApp($user->dropbox_key, $user->dropbox_secret, $user->dropbox_access_token);
            $dropbox = new Dropbox($app);
            
            $dropboxFile = new DropboxFile($pathToLocalFile);
            $file = $dropbox->upload($dropboxFile, "/" . $imageName, ['autorename' => true]);

            //Uploaded File
            $name = $file->getName();

            $dropbox = new Dropbox($app);

            $response = $dropbox->postToAPI("/sharing/create_shared_link_with_settings", [
                "path" => "/" . $imageName
            ]);

            $data1 = $response->getDecodedBody();

            $privacyOption = $this->privacyOptions->findWhere(['name' => 'Public'])->first();
            $channel = $this->channel->findWhere(['name' => 'General'])->first();
                
            $data['uuid'] = $this->helper->addUuid();;
            $data['type'] = 'DropBox';
            $data['url'] = $data1['url'];
            $data['user_id'] = $request->get('id');
          //  $data['channel_id'] = $channel->uuid;
          //  $data['privacy_option_id'] = $privacyOption->uuid;
            $data['active'] = 1;
            $upload = $this->video->create($data);
            if($upload)
            {
                return redirect('/api/v1/video/dropbox/upload/get?token=' . Session::get('clientToken') . '&client_id=' . Session::get('clientID'))->withErrors(['Video Uploaded Successfully']);
            }
            else
            {
                return redirect('/api/v1/video/dropbox/upload/get?token=' . Session::get('clientToken') . '&client_id=' . Session::get('clientID'))->withErrors(['Somethiong Went Wrong']);
            }
        }
        
        public function youtubeFileUpload(Request $request)
        {
            if($request->exists('id'))
            {
                $id = $request->get('id');
                $request->session()->put('idValue', $id);
            }
            
            $id = $request->session()->get('idValue');
            $user = $this->user->find($id);
            
            
            if(!$request->session()->has('videoUUID'))
            {
                if (!$request->hasFile('file'))
                {
                    return redirect()->back()->withErrors(['Video Not Found']);
                }
                $data = $request->get('data');
                $video = $request->file('file');

                $validation = $this->videoValidator->storeGoogle($data, $video);
                if($validation)
                {
                    return $this->errorWrongArgs($validation['errors']);
                }
                
                $errors = array();
                if($user->google_app_name == '')
                {
                    $errors[] = 'Google App Name Not Found for this user';
                }
                if($user->google_client_id == '')
                {
                    $errors[] = 'Google Client ID Not Found  for this user';
                }
                if($user->google_client_secret == '')
                {
                    $errors[] = 'Google Client Secret Not Found  for this user';
                }
                if($user->google_api_key == '')
                {
                    $errors[] = 'Google API Key Not Found  for this user';
                }

                if(!empty($errors))
                {
                    return redirect()->back()->withErrors($errors);
                }
                
                $privacyOption = $this->privacyOptions->findWhere(['name' => 'Public'])->first();
                $channel = $this->channel->findWhere(['name' => 'General'])->first();
                
                $extension = $video->extension();
                $imageName = date('dmyHis') . $this->helper->addUuid();
                //$request->session()->put('imageName', $imageName . '.' . $extension);
               // $path = $video->storeAs('/video/', $imageName . '.' . $extension);
                
                $video->move('video' , $imageName . '.' . $extension);
                $data = $this->helper->clearEmptyValues($data);
                $data['uuid'] = $this->helper->addUuid();
                $data['user_id'] = $request->get('id');
                $data['url'] = $imageName . '.' . $extension;
             //   $data['channel_id'] = $channel->uuid;
              //  $data['privacy_option_id'] = $privacyOption->uuid;
                $data['active'] = 1;
                $data['type'] = 'Youtube';
                
                $request->session()->put('videoUUID', $data['uuid']);
                $request->session()->put('clientToken', $request->get('token'));
                $request->session()->put('clientID', $request->get('client_id'));

                $videoResult = $this->video->create($data);
                if(!$videoResult)
                {
                    return redirect()->back()->withErrors(['Something Went Wrong']);
                }
            }
           
            $videoData = $this->video->findWhere(['uuid' => $request->session()->get('videoUUID')])->first();
            if(!$videoData)
            {
                return redirect()->back()->withErrors(['Video Not Uploaded']);
            }
           $OAUTH2_CLIENT_ID = $user->google_client_id;
           $OAUTH2_CLIENT_SECRET = $user->google_client_secret;

           $client = new Google_Client();
           $client->setClientId($OAUTH2_CLIENT_ID);
           $client->setClientSecret($OAUTH2_CLIENT_SECRET);
           $client->setScopes('https://www.googleapis.com/auth/youtube');
           $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . '/api/v1/video/youtube/upload',
               FILTER_SANITIZE_URL);
           $client->setRedirectUri($redirect);
           
           // Define an object that will be used to make all API requests.
           $youtube = new Google_Service_YouTube($client);

           // Check if an auth token exists for the required scopes
           $tokenSessionKey = 'token-' . $client->prepareScopes();
          
           if (isset($_GET['code'])) {
             if (strval($request->session()->get('state')) !== strval($_GET['state'])) {
               die('The session state did not match.');
             }

             $client->authenticate($_GET['code']);
         //    $_SESSION[$tokenSessionKey] = $client->getAccessToken();
             $request->session()->put($tokenSessionKey, $client->getAccessToken());
             return redirect($redirect);
            // header('Location: ' . $redirect);
           }

           if ($request->session()->get($tokenSessionKey)) {
             $client->setAccessToken($request->session()->get($tokenSessionKey));
           }
          
           // Check to ensure that the access token was successfully acquired.
           if ($client->getAccessToken()) {
             $htmlBody = '';
             try{
               Session::forget('videoUUID');
               // REPLACE this value with the path to the file you are uploading.
               $videoPath = public_path('video/' . $videoData->url);

               // Create a snippet with title, description, tags and category ID
               // Create an asset resource and set its snippet metadata and type.
               // This example sets the video's title, description, keyword tags, and
               // video category.
               $snippet = new Google_Service_YouTube_VideoSnippet();
               $snippet->setTitle($videoData->name);
               $snippet->setDescription($videoData->description);
               
               //$snippet->setTags(array("tag1", "tag2"));

               // Numeric video category. See
               // https://developers.google.com/youtube/v3/docs/videoCategories/list
               //$snippet->setCategoryId("22");

               // Set the video's status to "public". Valid statuses are "public",
               // "private" and "unlisted".
               $status = new Google_Service_YouTube_VideoStatus();
               $status->privacyStatus = "public";

               // Associate the snippet and status objects with a new video resource.
               $video = new Google_Service_YouTube_Video();
               $video->setSnippet($snippet);
               $video->setStatus($status);

               // Specify the size of each chunk of data, in bytes. Set a higher value for
               // reliable connection as fewer chunks lead to faster uploads. Set a lower
               // value for better recovery on less reliable connections.
               $chunkSizeBytes = 1 * 1024 * 1024;

               // Setting the defer flag to true tells the client to return a request which can be called
               // with ->execute(); instead of making the API call immediately.
               $client->setDefer(true);

               // Create a request for the API's videos.insert method to create and upload the video.
               $insertRequest = $youtube->videos->insert("status,snippet,player", $video);

               // Create a MediaFileUpload object for resumable uploads.
               $media = new Google_Http_MediaFileUpload(
                   $client,
                   $insertRequest,
                   'video/*',
                   null,
                   true,
                   $chunkSizeBytes
               );
               $media->setFileSize(filesize($videoPath));
               

               // Read the media file and upload it chunk by chunk.
               $status = false;
               $handle = fopen($videoPath, "rb");
               while (!$status && !feof($handle)) {
                 $chunk = fread($handle, $chunkSizeBytes);
                 $status = $media->nextChunk($chunk);
               }
               
               fclose($handle);

               // If you want to make other calls after the file upload, set setDefer back to false
               $client->setDefer(false);
               File::delete(public_path('video/' . $videoData->url));
               $videoUpdateData['url'] = $status->id;
               $update = $this->video->updateData($videoUpdateData, $videoData->uuid);
                if(!$update)
                {
                    return 0;
                    exit;
                }
               return redirect('/api/v1/video/youtube/upload/get')->withErrors(['Video Uploaded Successfully']);

             } catch (Google_Service_Exception $e) {
                 return redirect('/api/v1/video/youtube/upload/get')->withErrors(['A service error occurred: <code>%s</code>' . $e->getMessage()]);
               //$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                   //htmlspecialchars($e->getMessage()));
             } catch (Google_Exception $e) {
                 return redirect('/api/v1/video/youtube/upload/get')->withErrors(['A client error occurred: <code>%s</code>' . $e->getMessage()]);
               //$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                  // htmlspecialchars($e->getMessage()));
             }

             $_SESSION[$tokenSessionKey] = $client->getAccessToken();
           } 
           elseif ($OAUTH2_CLIENT_ID == 'REPLACE_ME') {
               return redirect('/video/youtube/upload/get');
           }
           else
           {
                $state = mt_rand();
                $client->setState($state);
                $request->session()->put('state', $state);
                $authUrl = $client->createAuthUrl();
                
                return redirect($authUrl);
           }
        }
        
        public function dropboxFileUploadGet()
        {
            return view('users.dropBoxLogin');
        }
        
        public function youtubeFileUploadGet()
        {
            return view('users.youTubeLogin');
        }
}