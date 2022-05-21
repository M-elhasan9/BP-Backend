<?php

namespace App\Ntfs;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class FireNearUser extends Notification
{

    var $type;
    var $fire_id ;
    use Queueable;

    public function __construct($type ,$fire_id )
    {

        $this->type = $type;
        $this->fire_id = $fire_id;
    }


    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData([  'fire_id' => $this->fire_id , 'type' => $this->type, 'click_action' => 'FLUTTER_NOTIFICATION_CLICK'])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('Fire alarm')
                ->setBody('A fire near one of your saved places'));

    }

    // optional method when using kreait/laravel-firebase:^3.0, this method can be omitted, defaults to the default project
    public function fcmProject($notifiable, $message)
    {
        // $message is what is returned by `toFcm`
        return 'app'; // name of the firebase project to use
    }
}
