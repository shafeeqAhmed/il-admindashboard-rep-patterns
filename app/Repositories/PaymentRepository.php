<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\CommonHelper;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Support\Str;
use DB;

//use Your Model

/**
 * Class PaymentRepository.
 */
class PaymentRepository extends BaseRepository implements RepositoryInterface {

    use \App\Traits\Responses\PaymentResponse;

    /**
     * @return string
     *  Return the model
     */
    public function model() {
        return User::class;
    }

    public function getRequestToken($data) {
        if ((isset($data['type'])) && ($data['type'] == 'apple_pay')) {
            $decodedData = $this->prepareAppleSDKToken($data);
        } elseif ((!isset($data['type'])) || ($data['type'] != 'apple_pay')) {
            $decodedData = $this->prepareSDKToken($data);
        }
        $response = !empty($decodedData) ? $this->prepareTokenResponse($decodedData) : [];
        return $response;
    }

    public function prepareSDKToken($data) {
        $requestParams = array(
            'service_command' => 'SDK_TOKEN',
            'access_code' => config("general.payfort.access_code"),
            'merchant_identifier' => config("general.payfort.merchant_identifier"),
            'language' => 'en',
            'device_id' => $data['device_id'],
        );
        $signature = $this->prepareSignature($requestParams);
        $requestParams['signature'] = $signature;
        $url = config("general.payfort.payfort_url");
        $decodeData = $this->prepareCurlRequest($url, $requestParams);
        return $decodeData ? $decodeData : [];
    }

    public function prepareAppleSDKToken($data) {
        $requestParams = array(
            'service_command' => 'SDK_TOKEN',
            'access_code' => config("general.payfort.apple_access_code"),
            'merchant_identifier' => config("general.payfort.merchant_identifier"),
            'language' => 'en',
            'device_id' => $data['device_id'],
        );
        $signature = $this->prepareAppleSignature($requestParams);
        $requestParams['signature'] = $signature;
        $url = config("general.payfort.apple_payfort_url");
        $decodeData = $this->prepareCurlRequest($url, $requestParams);
        return $decodeData ? $decodeData : [];
    }

    public function getCardsList($data) {
        $user = $this->getByColumn($data['user_uuid'], 'user_uuid', ['id']);
        if (empty($user)) {
            return ['success' => false, 'message' => 'User does not exist'];
        }
        $cards = \App\Models\UserCard::getUserCards($user->id);
        $response = $this->prepareCardsResponse($cards);
        return $response;
    }

    public function saveAuthorizationData($data) {
        // if remember me is true then save the new card
        $cardUuid = null;
        if (($data['remember_me'] == 'true') && (empty($data['card_uuid']))) {
            // save customer card
            $data['user'] = $this->getByColumn($data['user_uuid'], 'user_uuid', ['id']);
            $cardData = $this->makeCustomerCardParams($data);
            $saveData = \App\Models\UserCard::saveData($cardData);
            if (!$saveData) {
                return ['success' => false, 'message' => 'Error occurred while saving card data'];
            }
            $cardUuid = $saveData['card_uuid'];
        }
//else if remember me is false and the card is not empty then delete the card from db as well as payfort
        elseif (($data['remember_me'] == 'false') && (!empty($data['card_uuid']))) {
            // check if card exists in db
            $saveData = \App\Models\UserCard::updateData('card_uuid', $data['card_uuid'], ['is_active' => 0]);
            if (!$saveData) {
                return ['success' => false, 'message' => 'Error occurred while updating details'];
            }
            $cardUuid = $data['card_uuid'];
        }
        // if remember me is true but customer card uuid is not empty then do not save card because it already exists in db


        // push notification/ email notification to boat owner about payment received
//        $booking = (new BookingRepository())->where('booking_uuid', $data['merchant_reference'])->with('boat.user.notification_settings', 'user')->first();
//        if($booking){
//            $booking = $booking->toArray();
//            if($booking['boat']['user']['notification_settings']['is_email_on_payment_received'] ?? false){
//                $user = $booking['user'];
//                CommonHelper::sendEmail($user, $booking, 'Payment Received!', 'payment_received');
//            }
//
//            if($booking['boat']['user']['notification_settings']['is_payment_received'] ?? false){
//                // todo push notification on payment_received type
//            }
//        }

        $response = $this->bookingAuthorizationData($data, $cardUuid);
        DB::commit();
        return $response;
    }

    public function prepareSignature($requestParams) {
        $shaString = '';
        ksort($requestParams);
        foreach ($requestParams as $key => $value) {
            $shaString .= $key . '=' . $value;
        }
        $shaString = config("general.payfort.SHA_REQUEST_PHRASE") . $shaString . config("general.payfort.SHA_REQUEST_PHRASE");
        $signature = hash("sha256", $shaString);
        return $signature;
    }

    public function prepareAppleSignature($requestParams) {
        $shaString = '';
        ksort($requestParams);
        foreach ($requestParams as $key => $value) {
            $shaString .= $key . '=' . $value;
        }
        $shaString = config("general.payfort.APPLE_SHA_REQUEST_PHRASE") . $shaString . config("general.payfort.APPLE_SHA_REQUEST_PHRASE");
        $signature = hash("sha256", $shaString);
        return $signature;
    }

    public function prepareCurlRequest($url, $requestParams) {
        $ch = curl_init($url);
        $data = json_encode($requestParams);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $decoded_data = json_decode($result);
        return $decoded_data;
    }

    public function mapOnTable($params) {
        return [
        ];
    }

    public function makeCustomerCardParams($params) {

        return [
            'user_id' => $params['user']->id,
            'card_uuid' => Str::uuid()->toString(),
            'card_id' => null,
            'token' => $params['token'],
            'card_name' => !empty($params['card_name']) ? $params['card_name'] : null,
            'card_type' => !empty($params['card_type']) ? $params['card_type'] : null,
            'last_digits' => !empty($params['last_digits']) ? $params['last_digits'] : null,
//            'card_holder_name' => !empty($params['card_holder_name']) ? $params['card_holder_name'] : null,
            'expiry' => !empty($params['expiry']) ? $params['expiry'] : null,
        ];
    }

    public function prepareAuthorizationParams($params) {

        return [
            'amount' => $params['amount'],
            'fort_id' => $params['fort_id'],
            'merchant_reference' => $params['merchant_reference'],
            'card_name' => !empty($params['card_type']) ? $params['card_type'] : null,
            'digital_wallet' => !empty($params['digital_wallet']) ? $params['digital_wallet'] : null,
            'signature' => !empty($params['signature']) ? $params['signature'] : null,
            'booking_auth_uuid' => Str::uuid()->toString(),
        ];
    }

    public function bookingAuthorizationData($data, $cardUuid) {
        $card['id'] = null;
        $authorizationParams = self::prepareAuthorizationParams($data);
        $saveAuthorization = \App\Models\BookingAuthorizationData::saveData($authorizationParams);
        if (!$saveAuthorization) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Error occurred while authorizing'];
        }
        // get card id
        if (!empty($cardUuid)) {
            $card = UserCard::getCardDetail('card_uuid', $cardUuid);
        }
        $update_booking = \App\Models\Booking::updateBookingStatus('booking_uuid', $data['merchant_reference'], ['status' => 'pending', 'card_id' => $card['id']]);
        if (!$update_booking) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Error occurred while saving data'];
        }
        $response = $this->prepareBookingAuthorizationResponse($saveAuthorization);
        return $response;
    }

    public function deleteCard($data) {
        $checkCard = UserCard::getCardDetail('card_uuid', $data['card_uuid']);
        if (empty($checkCard)) {
            return ['success' => false, 'message' => 'Card does not exist'];
        }
        $update_data = UserCard::updateData('card_uuid', $data['card_uuid'], ['is_active' => 0]);
        if (!$update_data) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Error occurred while deleting card'];
        }
        $cards = \App\Models\UserCard::getUserCards($checkCard['user_id']);
        $response = $this->prepareCardsResponse($cards);
        return $response;
    }

    public function transactionFeedback($request) {
        $data = file_get_contents('php://input');
        $decoded_data = json_decode($data, true);
        $identifier = config('general.payfort.merchant_identifier');
        if ((empty($decoded_data)) || ($decoded_data['merchant_identifier'] != $identifier)) {
            return ['status' => 400, 'success' => false, 'message' => 'Error occurred'];
        }
        $response = $decoded_data;
        return ['status' => 200, 'success' => true, 'message' => 'request was succesful'];
    }

    public function paymentNotification($request) {
        $data = file_get_contents('php://input');
        $decoded_data = json_decode($data, true);
        $identifier = config('general.payfort.merchant_identifier');
        if (empty($decoded_data) || ($decoded_data['merchant_identifier'] != $identifier)) {
            return ['status' => 400, 'success' => false, 'message' => 'Error occurred'];
        }
        $notification = $decoded_data;
        return ['status' => 200, 'success' => true, 'message' => 'request was succesful'];
    }

}
