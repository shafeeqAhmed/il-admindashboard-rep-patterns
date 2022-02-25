<?php

namespace App\Repositories;

use App\Models\CustomerSupport;
use App\Models\Booking;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\WithdrawBooking;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\CommonHelper;
use App\Traits\CommonService;
use App\Traits\Responses\CityResponse;
use App\Traits\Responses\CountryResponse;
use App\Traits\Responses\UserResponse;
use App\Traits\ThumbnailHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Aws\Sns\SnsClient;
use App\Traits\MediaUploadHelper;
use DB;
use Illuminate\Support\Facades\Auth;

//use Your Model

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository implements RepositoryInterface {

    use UserResponse;
    use CountryResponse;
    use CityResponse;
    use CommonService;



    /**
     * @return string
     *  Return the model
     */
    public function model() {
        return User::class;
    }

    public function getAllUser() {
        return $this->all()->map(function ($user) {
                    return $user->format();
                });
    }

    // public function createUser($params) {
    //     $user = $this->create($this->mapOnTable($params));
    //     // send user verification code. Uncomment this code on production
    //     $checkValidity = $this->checkPhoneValidity($user);
    //     if ((isset($checkValidity['success'])) && ($checkValidity['success'] == false)) {
    //         return ['sucess' => false, 'message' => 'Error occurred while sending verification code'];
    //     }
    //     $response = $this->userResponse($user);
    //     DB::commit();
    //     return $response;
    // }


    public function createUser($params) {
        $user = $this->mapOnTable($params);
        $userdata = null;
        $checkPhoneAndEmail = $this->checkPhoneAndEmail($user);
        if ((isset($checkPhoneAndEmail['success'])) && ($checkPhoneAndEmail['success'] == false)) {
           return $checkPhoneAndEmail;
        }

        $useremailphone = $this->model->getUserByEmailandPhone($params);
        $useremail = $this->model->getUserByColumn($user['email'], 'email',$user['role']);
        $userphone = $this->model->getUserByColumn($user['phone_number'], 'phone_number',$user['role']);
        if(!empty($useremailphone)){
            if(!$useremailphone['is_verified']){
               $userdata = $useremailphone;
               $result = $userdata->update(['first_name' => $params['first_name'] , 'last_name'=> $params['last_name'] , 'email'=> $params['email'], 'phone_number'=> $params['phone_number']]);
               $userdata = $userdata->refresh();
            }
        }
        elseif(!empty($userphone)){
            if(!$userphone['is_verified']){
                $userdata = $userphone;
                $result = $userdata->update(['first_name' => $params['first_name'] , 'last_name'=> $params['last_name'] , 'email'=> $params['email'], 'phone_number'=> $params['phone_number']]);
               $userdata = $userdata->refresh();
            }
        }elseif(!empty($useremail)){
            if(!$useremail['is_verified']){
                $userdata = $useremail;
                $result = $userdata->update(['first_name' => $params['first_name'] , 'last_name'=> $params['last_name'] , 'email'=> $params['email'], 'phone_number'=> $params['phone_number']]);
                $userdata = $userdata->refresh();
            }
        }else{
            $userdata = $this->create($user);
        }


        // send user verification code. Uncomment this code on production
        $checkValidity = $this->checkPhoneValidity($userdata);
        if ((isset($checkValidity['success'])) && ($checkValidity['success'] == false)) {
            return ['sucess' => false, 'message' => 'Error occurred while sending verification code'];
        }
        $response = $this->userResponse($userdata);
        DB::commit();
        return $response;
    }


    public function getReportedPost() {
        return count((new ReportedPostRepository())->getReportedPost());
    }

    public function checkPhoneAndEmail($user)
    {
        $useremail = $this->model->getUserByColumn($user['email'], 'email', $user['role']);
        $userphone = $this->model->getUserByColumn($user['phone_number'], 'phone_number', $user['role']);
        $userdata = $this->model->getUserByEmailandPhone($user);
        $emailVerifyAlreadyExist = 0;
        $phoneVerifyAlreadyExist = 0;

        if(!empty($userdata)){
            if($userdata['is_verified']){
                $emailVerifyAlreadyExist=1;
                $phoneVerifyAlreadyExist=1;
            }
        }
        if(!empty($useremail)){
            if($useremail['is_verified']){
                $emailVerifyAlreadyExist=1;
            }
        }
        if(!empty($userphone)){
            if($userphone['is_verified']){
                $phoneVerifyAlreadyExist=1;
            }
        }

        if ($emailVerifyAlreadyExist && $phoneVerifyAlreadyExist) {

           return ['success' => false, 'message' => 'This email and phone number already taken'];
        }

        if ($emailVerifyAlreadyExist) {

           return ['success' => false, 'message' => 'This email already taken'];
        }

        if ($phoneVerifyAlreadyExist) {

           return ['success' => false, 'message' => 'This phone number already taken'];
        }
    }

    public function getUserById($userId) {

        return $this->userResponse($this->model->getUserById($userId));
    }

    public function getUser($userUuid) {
        return self::getByColumn($userUuid, 'user_uuid');
    }

    public function updateUser($params) {
        //check user exist
        $user = $this->getByColumn($params['user_uuid'], 'user_uuid');
        if (!$user) {
            return ['success' => false, 'message' => __('invalid_user')];
        }
        $data = $this->processUser($params);
        //update user detail
        $result = $this->model->updateData('user_uuid', $params['user_uuid'], $data);

        //record not updated
        if (!$result) {
            return ['success' => false, 'message' => __('request_failed')];
        }
        return ['success' => true, 'data' => $this->userResponse($this->getByColumn($params['user_uuid'], 'user_uuid'))];
    }

    public function processUser($params) {
        $inputs = customMapOnTable($params, 'user_uuid');
        if (isset($inputs['profile_pic'])) {
            //to do need to fix
            MediaUploadHelper::moveSingleS3Image($inputs['profile_pic'], CommonHelper::$s3_image_paths['profile_image']);
        }
        return $inputs;
    }

    public function updateUserImage($params) {
        $this->model->updateData('user_uuid', $params['user_uuid'], ['profile_pic' => $params['image']]);
        return $this->userResponse($this->getUser($params['user_uuid']));
    }

    public function createCaptain($params) {
        return $this->create($params);
    }

    public function getUserBoats($userId) {
        return (new BoatRepository())->getUserBoats($userId);
    }

    public function mapOnTable($params) {

        return [
            'user_uuid' => Str::uuid()->toString(),
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'email' => $params['email'],
            'password' => Hash::make($params['password']),
            'phone_number' => $params['phone_number'],
            'role' => $params['role'],
            'country_code' => $params['country_code'],
            'country_name' => $params['country_name'],
//            'verification_code' => 1234,
            //TODO: to generate real code
            'verification_code' => $this->generateVerificationCode(),
            'code_expires_at' => $this->generateCodeExpirationTime(),
//          'profile_pic'=>(isset($params['user_profile_pic']))?$params['user_profile_pic']:null,
//          'google_id'=>$params['user_google_id'],
//          'facebook_id'=>$params['user_facebook_id'],
//          'apple_id'=>$params['user_apple_id'],
        ];
    }

    public function checkPhoneValidity($user) {
        // check phone validity
        $isValid = $this->validatePhoneNumber($user);
        if (!$isValid['success']) {
            DB::rollback();
            return ['sucess' => false, 'message' => __('phone_validation_error')];
        }
        // prepare message data and send verification code
        return $this->sendUserVerificationCode($user);
    }

    public function sendUserVerificationCode($user) {
        $message = $this->prepareMessageText($user);
        $confirmation['phone_number'] = $user->phone_number;
        $confirmation['message'] = $message;
        $confirmation['verification_code'] = $user->verification_code;
        $smsStatus = ['@metadata' => ['statusCode' => 200]];
        // uncomment these lines when you need real time sms
        $smsStatus = $this->sendSms($confirmation);
        if (isset($smsStatus['@metadata']['statusCode']) && $smsStatus['@metadata']['statusCode'] == 200) {
            return ['success' => true, 'message' => __('verification_code_sent')];
        } else {
            DB::rollback();
            return ['success' => false, 'message' => __('phone_validation_error')];
        }
    }

    public function sendSms($params) {

        $snsclient = new SnsClient([
            'region' => 'ap-south-1',
            'version' => '2010-03-31',
            'credentials' => [
                'key' => 'AKIAU3J43LNMAGCKSYVU',
                'secret' => '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',
            ]
        ]);
        $message = '[Boatek]' . ' ' . $params['verification_code'] . __('verification_code_message_dont_share');
        $phone = $params['phone_number'];

        try {
            $result = $snsclient->publish([
                'Message' => $message,
                'PhoneNumber' => $phone,
            ]);
            return $result;
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }

    public function prepareMessageText($params) {
        $params['lang'] = !isset($params->lang) ? $params->lang : 'EN';
        if (strtolower($params['lang']) == 'ar') {
            $message = 'هو رمز ك ' . $params->verification_code . ' وستنتهي صلاحيته خلال ساعتين';
        } else {
            $message = $params->verification_code . ' is your boatek OTP. Do not share it with anyone';
        }
        return $message;
    }

    public function validatePhoneNumber($inputs) {
        $response = ['success' => true];
        $phone = $inputs['phone_number'];
        $accountId = config('paths.twillio_account_id');
        $token = config('paths.twillio_auth_token');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://lookups.twilio.com/v1/PhoneNumbers/$phone?Type=carrier&Type=caller-name");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_USERPWD, "$accountId" . ":" . "$token");
        $curlResult = curl_exec($ch);
        if (curl_errno($ch)) {
            $response = ['success' => false, 'message' => $ch];
        }
        curl_close($ch);
        $decode = json_decode($curlResult);
        if (isset($decode->status) && ($decode->status == 404 || $decode->status == '404')) {
            if (strtolower($inputs['lang']) == 'en') {
                $response = ['success' => false, 'message' => 'Invalid phone number'];
            }
            if (strtolower($inputs['lang']) == 'ar') {
                $response = ['success' => false, 'message' => 'رقم الهات�? غير صحيح'];
            }
        }
        return $response;
    }

    public function getCode($params) {
        $user = $this->model->getUserByColumn($params['phone_number'], 'phone_number',$params['role']);
        if (empty($user)) {
            return ['success' => false, 'message' => __('invalid_user')];
        }
        $prepareParams = $this->prepareCodeParams();
        $update = User::updateData('user_uuid', $user->user_uuid, $prepareParams);
        if (!$update) {
            return ['success' => false, 'message' => __('request_failed')];
        }
        $user->verification_code = $prepareParams['verification_code'];
        $user->is_verified = $prepareParams['is_verified'];
        // send user verification code
        $checkValidity = $this->checkPhoneValidity($user);
        if ((isset($checkValidity['success'])) && ($checkValidity['success'] == false)) {
            DB::rollback();
            return ['sucess' => false, 'message' => 'Error occurred while sending verification code'];
        }
        return $this->userResponse($user);
    }

    public function resetPassword($params) {
        $user = $this->getByColumn($params['phone_number'], 'phone_number');
        if (empty($user)) {
            return ['success' => false, 'message' => __('invalid_user')];
        }
        if (Auth::attempt(['phone_number' => $params['phone_number'], 'password' => $params['old_password']])) {
            if (Auth::attempt(['phone_number' => $params['phone_number'], 'password' => $params['new_password']])) {
                return['success' => false, 'message' => __('old_new_same_password')];
            }
            $updateUser = User::updateData('phone_number', $params['phone_number'], ['password' => Hash::make($params['new_password'])]);
            if (!$updateUser) {
                return ['success' => false, 'message' => __('request_failed')];
            }
            $response = $this->userResponse($user);
            DB::commit();
            return $response;
        }
        return ['success' => false, 'message' => 'old password does not match'];
    }

    public function forgetPassword($params) {
        $user = $this->getByColumn($params['phone_number'], 'phone_number');
        if (empty($user)) {
            return ['success' => false, 'message' => __('invalid_user')];
        }
        $updateUser = User::updateData('phone_number', $params['phone_number'], ['password' => Hash::make($params['password'])]);
        if (!$updateUser) {
            return ['sucess' => false, 'message' => __('request_failed')];
        }
        $response = $this->userResponse($user);
        DB::commit();
        return $response;
    }

    public function prepareCodeParams() {
        $data = [
//            'verification_code' => 1234,
            'verification_code' => $this->generateVerificationCode(),
            'code_expires_at' => $this->generateCodeExpirationTime(),
            'is_verified' => 0,
        ];
        return $data;
    }

    public function generateVerificationCode() {
        return rand(1000, 9999);
    }

    public function generateCodeExpirationTime() {
        $start = date('Y-m-d H:i:s');
        $minutes = config('general.globals.code_expire_time');
        return date('Y-m-d H:i:s', strtotime($minutes, strtotime($start)));
    }

    public function getIdByUuid($column, $value) {
        return self::getByColumn($value, $column, ['id'])->id;
    }

    public function getCountries() {
        $countries = $this->model->getCountries();
        $list = [];
        foreach ($countries as $country) {
            if (!empty($country['country_name'])) {
                $list[] = $this->country($country);
            }
        }
        return $list;
    }

    public function getCities($search) {
        $contries = $this->model->getCities($search);
        $list = [];
        foreach ($contries as $key => $contry) {
            $list[$key] = $this->city($contry);
        }
        return $list;
    }
    public function getBoatOwners($type=false) {
        return $this->model->getBoatOwners($type);
    }
    public function getCustomers($type) {
        return $this->model->getCustomers($type);
    }
    public function messageCodes() {
        return $this->model->messageCodes();
    }
    public function getUserCount($role,$type) {
        return count($this->model->getUsers($role,$type));
    }
    public function getBookingCount($status) {
        return count((new BookingRepository())->getAdminBookings($status));
    }

    public function getBoatCount($col,$val) {
        return count((new BoatRepository())->getAdminBoats($col,$val));
    }



    public function getBlockedPost() {
        return count((new BoatPostRepository())->getBlockedPosts());
    }
    public function getBoatOwnerDetail($col,$val) {
        return $this->model->getBoatOwnerDetail($col,$val);
    }
    public function getBoats($status=false){

    }

    public function setUserLanguage($params){
        //check user exist
        $user = $this->getByColumn($params['user_uuid'], 'user_uuid');
        if (!$user) {
            return ['success' => false, 'message' => __('invalid_user')];
        }
        $language = $params['language'] ?? "en";
        //update user detail
        $update = User::updateData('user_uuid', $params['user_uuid'], $params);
        if (!$update) {
            return ['success' => false, 'message' => __('request_failed')];
        }
        return ['success' => true, 'data' => $this->userResponse($this->getByColumn($params['user_uuid'], 'user_uuid'))];
    }

    public function saveSupportQuestionnaire($params){
        //check user exist
        $user = $this->getByColumn($params['user_uuid'], 'user_uuid');
        if (!$user) {
            return ['success' => false, 'message' => __('invalid_user')];
        }
        $params['status'] = 'todo';
        //update user detail
        $update = CustomerSupport::create($params);
        if (!$update) {
            return ['success' => false, 'message' => __('request_failed')];
        }

        return ['success' => true, 'message' => __('savedCustomerQuery')];
    }


    public function sendMailUser($request_params=[])
    {
        $data['email'] = "meer.aali@ilsainteractive.com";
        $data['first_name'] = "Hello";
        $data['subject'] = 'Boatek';
        $template = 'email.test';
        $sendEmail =  $this->sendEmailUser($template, $data);
        if (!$sendEmail) {
            return false;
        }
        return true;
    }

    public function blackEmail($responseData) {
        $response = [];
        $response['response_type'] =$responseData['notificationType'];
        if($responseData['notificationType'] == 'Bounce'){
            $response['response_email']   =  $responseData['bounce']['bouncedRecipients'][0]['emailAddress'];
        }elseif($responseData['notificationType'] == 'Complaint'){
            $response['response_email']   =  $responseData['complaint']['complainedRecipients'][0]['emailAddress'];
        }
        $update_response = $this->updateEmailResponse($response);
        return $response;
    }

    public function updateEmailResponse($input_params)
    {
        \Log::channel('customLogs')->info($input_params);
        if ($input_params['response_type'] == 'Bounce') {
            $request_params['type'] = 'bounced';
        } elseif ($input_params['response_type'] == 'Complaint') {
            $request_params['type'] = 'complaint';
        }
        $userData = $this->model->getUserByEmail($input_params['response_email']);
        \Log::channel('customLogs')->info($userData);
        if ($userData) {
            $request_params['email'] = $input_params['response_email'];
            $request_params['user_id'] = $userData->id;
            $userUpdate = $this->model->updateData('id', $userData->id, ['email_status' => $request_params['type']]);
            \Log::channel('customLogs')->info($userUpdate);
            if ($userUpdate) {
                return 'success';
            } else {
                return 'false';
            }
        }

    }
}
