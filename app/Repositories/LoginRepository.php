<?php

namespace App\Repositories;

use App\Models\UserDevice;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\Responses\UserResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

//use Your Model

/**
 * Class LoginRepository.
 */
class LoginRepository extends BaseRepository {

    use UserResponse;

    /**
     * @return string
     *  Return the model
     */
    public function model() {
        return User::class;
    }

    public function loginUser($params) {
        if (Auth::attempt(['phone_number' => $params['phone_number'], 'password' => $params['password'] ,'role'=>$params['login_user_type']])) {
            if (Auth::attempt(['phone_number' => $params['phone_number'], 'password' => $params['password'], 'is_active' => 0 ,'role'=>$params['login_user_type']])) {
                return ['success' => 'false', 'message' => 'User has been deactivated'];
            }
            $user = $this->model->getUserByColumn($params['phone_number'], 'phone_number', $params['login_user_type']);
            $user['login_user_type'] = !empty($params['login_user_type']) ? $params['login_user_type'] : $user['role'];
            //TODO: check if verification code is verified or not otherwise send code
            return $this->userResponse($user);
        }
        return ['success' => false, 'message' => __("invalid_creds")];
    }

    public function logout($params) {
        $user = $this->getByColumn($params['user_uuid'], 'user_uuid');
        return UserDevice::where('user_id', $user->id)->where('device_token', $params['device_token'])->update(['is_active' => 0]);
    }
    public function verifyCode($params) {
        $user = $this->model->getUserByColumn($params['phone_number'], 'phone_number',$params['role']);
        if (empty($user)) {
            return ['success' => false, 'message' => 'User does not exist'];
        }
        $verifyCode = $this->checkCode($user, $params);
        if (!$verifyCode) {
            return ['success' => false, 'message' => 'Invalid verification code provided'];
        }
        $codeExpiry = $this->checCodeExpiration($user);
        if (!$codeExpiry) {
            return ['success' => false, 'message' => 'Your code has been expired'];
        }
        // update code data if it is verified
        $updateUser = $this->updateUser($user);
        if (!$updateUser) {
            return ['success' => false, 'message' => 'Error Occurred while saving user data'];
        }
        // get updated user
        $updated_user = $this->getByColumn($user->user_uuid, 'user_uuid');
        return $this->userResponse($updated_user);
    }

    public function checkCode($user, $params) {
        if ($user->verification_code != $params['verification_code']) {
            return false;
        }
        return true;
    }

    public function checCodeExpiration($user) {
        if ($user['code_expires_at'] <= now()) {
            return false;
        }
        return true;
    }

    public function updateUser($user) {
        $params = $this->getParams();
        $update = User::where('user_uuid', $user->user_uuid)->update($params);
        if (!$update) {
            return false;
        }
        return true;
    }

    public function getParams() {
        return [
            'is_verified' => 1];
    }

    public function getColumns($params) {
        return [
            'email' => $params['email'],
        ];
    }

}
