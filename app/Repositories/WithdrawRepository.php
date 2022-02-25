<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\WithdrawBooking;
use App\Traits\CommonHelper;
use App\Traits\Responses\WithDrawResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class WithdrawRepository.
 */
class WithdrawRepository extends BaseRepository
{
    use WithDrawResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Withdraw::class;
    }

    public function getOwnerBookingStats($user_uuid)
    {
        $owner_boat_bookings = (new User())->getOwnerBoatBookings($user_uuid);
        $bookings = (new BookingRepository())->prepareBookingResposeFromOwnerBoats($owner_boat_bookings);
        $stats['all'] = 0;
        $stats['pending'] = 0;
        $stats['available'] = 0;
        $stats['transferred'] = 0;
        $common_helper = new CommonHelper();

        if (!empty($bookings)){
            foreach ($bookings as $book){
                $booking_amount = $this->calculateBookingAmount($book);
                $stats['all'] += $booking_amount;
                if ($book['is_transferred']) {
                    $stats['transferred'] += $booking_amount;
                }
                if (($book['status'] == 'completed' || $book['status'] == 'confirmed') && !$book['is_transferred'] ){
                    if ($common_helper->checkDateHours($book['end_date_time'], 24)){
                        $stats['available'] += $booking_amount;
                    } else{
                        $stats['pending'] += $booking_amount;
                    }
                }
            }
        }

        return $stats;
    }

    public function getOwnerTransferredPayments($user_uuid)
    {
        return (new Withdraw())->getOwnerTransferredPayments($user_uuid);
    }

    public function getAvailableTransactions($user_uuid)
    {
        $owner_boat_bookings = (new User)->getOwnerBoatBookings($user_uuid, 'available');
        if (!empty($owner_boat_bookings)) {
            $owner_boat_bookings = $owner_boat_bookings->toArray();
            $common_helper = new CommonHelper();
            foreach ($owner_boat_bookings['boats'] as $b_ind => $boat) {
                if (!empty($boat['bookings'])) {
                    foreach ($boat['bookings'] as $bk_ind => $booking) {
                        $booking['created_at'] = date('d-m-Y h:i a', strtotime($booking['created_at']));
                        $booking['created_at_converted'] = $common_helper->convertDateTimeToLocalTimezone($booking['created_at'], 'UTC', 'Asia/Riyadh');
                        $booking['start_date_time'] = date('d-m-Y h:i a', $booking['start_date_time']);
                        $booking['start_date_time_converted'] = $common_helper->convertDateTimeToLocalTimezone($booking['start_date_time'], $booking['local_timezone'], 'Asia/Riyadh');
                        $booking['end_date_time'] = date('d-m-Y h:i a', $booking['end_date_time']);
                        $booking['end_date_time_converted'] = $common_helper->convertDateTimeToLocalTimezone($booking['end_date_time'], $booking['local_timezone'], 'Asia/Riyadh');
                        $booking['owner']['boat_owner'] = $owner_boat_bookings['first_name'] . ' ' . $owner_boat_bookings['last_name'];
                        $booking['owner']['email'] = $owner_boat_bookings['email'];
                        $booking_amount = $this->calculateBookingAmount($booking);
                        $booking['owner_earning'] = $booking_amount;

                        if ($booking['end_date_time']) {
                            $owner_boat_bookings['boats'][$b_ind]['bookings'][$bk_ind] = $booking;
                        } else {
                            unset($owner_boat_bookings['boats'][$b_ind]['bookings'][$bk_ind]);
                        }
                    }
                }
            }
            $owner_boat_bookings['boats'][$b_ind]['bookings'] = array_values($owner_boat_bookings['boats'][$b_ind]['bookings']);
        } else {
            $owner_boat_bookings = [];
        }
        return $owner_boat_bookings;
    }

    public function saveAvailableTransactions($inputs){
        $draw_validate = $this->validateWithDrawDate($inputs);
        if (!$draw_validate){
            throw new \ErrorException('Unable to withdraw amount, please check system settings.');
        }
        $withdraw_bookings = $this->makeWithDrawBookingDictionary($inputs);
        $withdraw_data = [];
        $withdraw_data['user_id'] = $inputs['owner_id'];
        $withdraw_data['withdraw_uuid'] = Str::uuid()->toString();
        $withdraw_data['amount'] = $withdraw_bookings['total'];
        $withdraw_data['transaction_charges'] = 0;
        $withdraw_data['receipt_id'] = $inputs['receipt_id'];
        $withdraw_data['receipt_date'] = $inputs['receipt_date'];
        $withdraw_data['last_withdraw_date'] = $this->getLastWithdrawDate($inputs);
        if (isset($inputs['receipt_file'])) {
            $file_result = CommonHelper::uploadSingleImage($inputs['receipt_file'], CommonHelper::$s3_image_paths['mobile_uploads'], $pre_fix = '', $server = 's3');
            $withdraw_data['receipt_url'] = $file_result['file_name'];
        }
        $withdraw = Withdraw::create($withdraw_data);

        foreach ($withdraw_bookings['withdraw_bookings'] as $key => $entries){
            Booking::where('id', $entries['booking_id'])->update(['is_transferred'=> 1]);
            $withdraw_bookings['withdraw_bookings'][$key]['withdraw_id'] = $withdraw->id;
        }
        if (!empty($withdraw_bookings['withdraw_bookings'])) {
            WithdrawBooking::insert($withdraw_bookings['withdraw_bookings']);
            $withdraw_bookings_data = Withdraw::where('id', $withdraw->id)->with('withdrawBookings.withdrawBooking.user', 'withdrawUser.bankAccountDetail')->first();
        }

        if ($inputs['save'] == 'true') {
            if ($withdraw_bookings_data) {
                $this->preparePDFDownloadForTransactions($withdraw_bookings_data->toArray());
            }
        }
    }

    public function makeWithDrawBookingDictionary($inputs)
    {
        $bookings = Booking::with('refundedTransaction')->whereIn('id', $inputs['bookings'])->get();
        $common_helper = new CommonHelper();
        $withdraw_bookings = [];
        $total_amount = 0;
        foreach ($bookings as $book){
            $booking_amount = $this->calculateBookingAmount($book);
            $booking_end_time = date('d-m-Y h:i a', $book->end_date_time);
            if (!$book['is_transferred'] && $common_helper->checkDateHours($booking_end_time, 24)){
                $data['booking_id'] = $book['id'];
                $data['amount'] = $booking_amount;
                $data['withdraw_booking_uuid'] = Str::uuid()->toString();
                $total_amount += $booking_amount;
                $withdraw_bookings[] = $data;
            }
        }
        $results['withdraw_bookings'] = $withdraw_bookings;
        $results['total'] = $total_amount;
        return $results;
    }

    public function getTransferredPaymentDetail($withdraw_id)
    {
        return (new WithdrawBooking())->getOwnerTransferredPaymentDetail($withdraw_id);
    }

    public function calculateBookingAmount($book){
        $booking_amount = $book['payment_received'] - (($book['boatek_fee'] ?? 0) + ($book['transaction_charges'] ?? 0));
        if ($book['is_refund']){
            $booking_amount = $book['refunded_transaction']['boat_earning'] ?? 0;
        }
        return $booking_amount;
    }

    public function validateWithDrawDate($inputs){
        $validate_status = true;
        $system_setting = (new SystemSettingRepository())->getSystemSettings();
        if (!$system_setting){
            return false;
        }
        $common_helper = new CommonHelper();
        $withdraw = $this->model->getLatesOwnerBooking($inputs['owner_id']);
        if ($withdraw){
            if ($system_setting['withdraw_scheduled_duration'] == 0){
                if ($common_helper->checkDateHours($withdraw['last_withdraw_date'], 168) === false){
                    $validate_status = false;
                }
            } elseif ($system_setting['withdraw_scheduled_duration'] == 1){
                if ($common_helper->checkDateHours($withdraw['last_withdraw_date'], 336) === false){
                    $validate_status = false;
                }
            } else{
                if ($common_helper->checkDateHours($withdraw['last_withdraw_date'], 730) === false){
                    $validate_status = false;
                }
            }
        }
        return $validate_status;
    }

    public function getLastWithdrawDate($inputs){
        $date = '';
        $system_setting = (new SystemSettingRepository())->getSystemSettings();
        $withdraw = $this->model->getLatesOwnerBooking($inputs['owner_id']);
        if ($withdraw && $system_setting){

            if ($system_setting['withdraw_scheduled_duration'] == 0){
                $date = date("Y-m-d", strtotime('+168 hour',strtotime($withdraw['last_withdraw_date'])));
            } elseif ($system_setting['withdraw_scheduled_duration'] == 1){
                $date = date("Y-m-d", strtotime('+336 hour',strtotime($withdraw['last_withdraw_date'])));
            } else{
                $date = date("Y-m-d", strtotime('+730 hour',strtotime($withdraw['last_withdraw_date'])));
            }
        } else{
            $date = $date('Y-m-d');
        }
        return $date;
    }

    public function preparePDFDownloadForTransactions($withdraw_bookings_data)
    {
        $pdf = PDF::loadView('pdf.transactions.transaction', compact('withdraw_bookings_data'));
        $pdf->download('transactoin.pdf');

        $path = public_path('pdf/');
        if (!file_exists($path)) {
            mkdir($path);
        }
        $pdf->save($path . 'transactoin.pdf');
        $pdf_path = public_path('pdf/transactoin.pdf');

        if (!file_exists($pdf_path)) {
            mkdir($pdf_path);
        }

        return response()->download($pdf_path);
    }
    public function makeMultiWithDrawResponse($records){
        $final = [];
        foreach($records as $record){
            $final[]= $this->withDrawResponse($record);
        }
        return $final;
    }
    public function makeMultiWithDrawBookingResponse($records){

            $final = [];
            foreach($records as $record){
                if(!empty($record['withdraw_booking'])) {
                    $final[]= (new WalletRepository())->transactionResponse($record['withdraw_booking']);
                }
            }
        return $final;
    }

    public function transferBalance($params) {
        $user = (new UserRepository)->getBoatOwnerDetail('user_uuid',$params['user_uuid']);
        if (empty($user)) {
            return ['success' => false, 'message' => 'User Does not exist'];
        }
        $data['stats'] =  $this->getOwnerBookingStats($params['user_uuid']);
        $data['transfer_balance'] = $this->makeMultiWithDrawResponse($this->model->getRecords('user_id',$user->id,$params));
        return $data;
    }
    public function transferBalanceDetail($params) {


        $withdraw = $this->getByColumn($params['withdraw_uuid'],'withdraw_uuid');
        if (empty($withdraw)) {
            return ['success' => false, 'message' => 'Withdraw uuid Does not exist'];
        }
        $withdrawBooking = $this->getTransferredPaymentDetail($withdraw->id);
        $user = (new UserRepository())->getByColumn($withdraw->user_id,'id');
        $data['stats'] =  $this->getOwnerBookingStats($user->user_uuid);
        $data['transfer_balance'] = $this->withDrawResponse($withdraw);
        $data['bookings']=$this->makeMultiWithDrawBookingResponse($withdrawBooking);

        return $data;

    }
}
