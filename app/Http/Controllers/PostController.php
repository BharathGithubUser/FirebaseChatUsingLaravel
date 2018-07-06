<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;

class PostController extends Controller
{
  public function index()
     {
         //parent::__construct();
            }

 public function store(Request $request)
 {
     $this->validate($request, [
         'title' => 'required|max:255',
         'description' => 'required',
     ]);

     $input = array_except($request->all(),array('_token'));

     $this->Post->AddData($input);

     // $notification = \DB::table('api_users')->get();
     //
     // foreach ($notification as $key => $value) {
     //     $this->notification($value->token, $request->get('title'));
     // }
     $this -> notification( 'chMpONBhbyw:APA91bFQCMc9iSWKNc0qroXzuxXFopsKzNHnB-yfbPAdsBGzxyDgbMn_Ah_gof4kNJJ8HD2_h26xOmF_tTiq5-LAvikKih3wrRS2sFNu5RHDQje9lUzaXScznzm4M7ZYoSJ071unaxQgkIENVHK0VDw-zibgA8Qamg', $request -> get('title'));

     \Session::put('success','Post store and send notification successfully!!');

     return redirect()->route('post.index');
 }

 public function notification($token, $title)
 {
     $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
     $token=$token;

     $notification = [
         'title' => $title,
         'sound' => true,
     ];

     $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

     $fcmNotification = [
         //'registration_ids' => $tokenList, //multple token array
         'to'        => $token, //single token
         'notification' => $notification,
         'data' => $extraNotificationData
     ];

     $headers = [
         'Authorization: key= AIzaSyDB9r7EQL6KiqhdXtNUOrhvlMVSDafBTLY',
         'Content-Type: application/json'
     ];


     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL,$fcmUrl);
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
     $result = curl_exec($ch);
     curl_close($ch);

     return true;
 }
}
