<?php namespace App\Modules\Managers\Channel;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Managers\Channel\ChannelModel;

class ChannelManager extends Model
{
    public function __construct()
    {
        $this->model = new ChannelModel;
    }
    
}