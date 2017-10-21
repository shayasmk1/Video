<?php namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    { 
        $this->app->bind(
            'App\Modules\Managers\User\UserRepositoryInterface',
            'App\Modules\Managers\User\UserRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\SessionToken\SessionTokenRepositoryInterface',
            'App\Modules\Managers\SessionToken\SessionTokenRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\Channel\ChannelRepositoryInterface',
            'App\Modules\Managers\Channel\ChannelRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\Tag\TagRepositoryInterface',
            'App\Modules\Managers\Tag\TagRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\Video\VideoRepositoryInterface',
            'App\Modules\Managers\Video\VideoRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\Like\LikeRepositoryInterface',
            'App\Modules\Managers\Like\LikeRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\Subscription\SubscriptionRepositoryInterface',
            'App\Modules\Managers\Subscription\SubscriptionRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\PrivacyOption\PrivacyOptionRepositoryInterface',
            'App\Modules\Managers\PrivacyOption\PrivacyOptionRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\Comment\CommentRepositoryInterface',
            'App\Modules\Managers\Comment\CommentRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\ReplyComment\ReplyCommentRepositoryInterface',
            'App\Modules\Managers\ReplyComment\ReplyCommentRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\VideoLog\VideoLogRepositoryInterface',
            'App\Modules\Managers\VideoLog\VideoLogRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\UserTag\UserTagRepositoryInterface',
            'App\Modules\Managers\UserTag\UserTagRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\ChannelLog\ChannelLogRepositoryInterface',
            'App\Modules\Managers\ChannelLog\ChannelLogRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\Category\CategoryRepositoryInterface',
            'App\Modules\Managers\Category\CategoryRepository'
        );
        
        $this->app->bind(
            'App\Modules\Managers\VideoHistory\VideoHistoryRepositoryInterface',
            'App\Modules\Managers\VideoHistory\VideoHistoryRepository'
        );
    }
}
