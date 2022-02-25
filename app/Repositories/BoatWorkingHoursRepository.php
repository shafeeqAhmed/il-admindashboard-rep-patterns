<?php

namespace App\Repositories;

use App\Models\Boat;
use App\Models\BoatWorkingHour;
use App\Models\Booking;
use App\Models\User;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatWorkingHoursResponse;
use DateTime;

//use Your Model

/**
 * Class BoatRepository.
 */
class BoatWorkingHoursRepository extends BaseRepository implements RepositoryInterface {

    use BoatWorkingHoursResponse;

    /**
     * @return string
     *  Return the model
     */
    protected $mainSlots = [];
    protected $final = [];

    public function model() {
        return BoatWorkingHour::class;
    }

    public function multiSchedules($params) {
        $dates = [];
        $params['boat_id'] = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
        $bookingsData = (new BookingRepository())->getMultipleSchedulesBookings($params);
        $booking = $this->multiSchedulesResponse($bookingsData);
        $startDate = $params['start_date'];
        $endDate = $params['end_date'];
        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $key => $date) {
            $monthDate = $date->format('Y-m-d');
            $dates[$key]['date'] = $monthDate;
            $dates[$key]['has_booking'] = (in_array($monthDate, $booking)) ? true : false;
            if ($dates[$key]['has_booking'] == true) {
                $dates[$key]['booking_date_time'] = $this->getBookingsDateTime($bookingsData, $monthDate);
            }
        }

        return $dates;
    }

    public function getBookingsDateTime($bookings = [], $monthDate = null) {
        $date = [];
        $count = 0;
        if (!empty($bookings)) {
            foreach ($bookings as $key => $booking) {
                if (date('Y-m-d', $booking['start_date_time']) == $monthDate) {
                    $date[$count]['booking_start_date_time'] = date('Y-m-d H:i:s', $booking['start_date_time']);
                    $date[$count]['booking_end_date_time'] = date('Y-m-d H:i:s', $booking['end_date_time']);
                }
                $count++;
            }
            return array_values($date);
        }
    }

    public function multiSchedulesResponse($bookings) {
        $final = [];
        foreach ($bookings as $book) {
            $date = date('Y-m-d', $book['start_date_time']);
            if (!in_array($date, $final)) {
                array_push($final, $date);
            }
        }
        return $final;
    }

    public function addBoatWorkingHours($boat_uuid) {
        $final = [];
        $boatRepository = new BoatRepository();
        $boat_id = $boatRepository->getByColumn($boat_uuid, 'boat_uuid')->id;
        $params = [
            'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'from_time' => '00:00:00', 'to_time' => '23:59:00', 'local_timezone' => 'UTC', 'saved_timezone' => 'Asia/Karachi'];
        $weekDays = [];
        foreach ($params['days'] as $day) {

            $weekDays['boat_id'] = $boat_id;
            $weekDays['local_timezone'] = $params['local_timezone'];
            $weekDays['saved_timezone'] = $params['saved_timezone'];
            $weekDays['from_time'] = $params['from_time'];
            $weekDays['to_time'] = $params['to_time'];
            $weekDays['day'] = $day;

            $final[] = $this->mapOnTable($weekDays);
        }

        $this->createMultiple($final);
        return $this->makeMultipleResponse($this->model->getSchedules($boat_id));
    }

    public function boatSchedules($params) {
//        if ($params['type'] == 'single') {
//            $day = \Carbon\Carbon::parse($params['date'])->format('l');
//            $boat_id = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
//
//            $user_id = User::where('user_uuid', $params['user_uuid'])->value('id');
//
//            //TODO::get boat booking with customer with same time and date and check boat booking with anyone with same date and time
//
//            $bookingSlots = (new BookingRepository())->boatBookingsByDate($boat_id, $params, $user_id);
//
//            //TODO::make slots for 30 mints difference
//            $boatSlots = $this->getBoatWorkingHoursByDay($params['date'], $day, $boat_id, $params);
//            $singleTime = $this->getSingleValue($boatSlots);
//
//            $finalKeys = $this->searchSlots($bookingSlots, $boatSlots, $singleTime, $params);
//
//            if (isset($finalKeys['noKey'])) {
//                unset($finalKeys['noKey']);
//                return [$finalKeys];
//            }
//            $separateBusyAndFreeSlots = $this->makeSlots($finalKeys, $boatSlots);
//            return $this->separateBusyAndFreeSlots([], $separateBusyAndFreeSlots, $separateBusyAndFreeSlots, $params);
//        }
        $day = \Carbon\Carbon::parse($params['date'])->format('l');
        $params['boat_id'] = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
        $params['user_id'] = User::where('user_uuid', $params['user_uuid'])->value('id');
        $params['start_date'] = $params['date'] . ' ' . '00:00:00';
//        $params['start_date'] = strtotime($params['start_date_time']);
        $params['end_date'] = $params['date'] . ' ' . '23:59:00';
        $nextBooking = Booking::getNextBooking($params);

//        if (!empty($nextBooking)) {
//            echo "<pre>";
//            print_r(date('Y-m-d H:i:s', $nextBooking['start_date_time']));
//            echo "<pre>";
//            print_r(date('Y-m-d H:i:s', $nextBooking['end_date_time']));
//            exit;
////            $params['start_date'] = date('Y-m-d H:i:s', $nextBooking['start_date_time']);
//            $params['end_date'] = date('Y-m-d H:i:s', $nextBooking['end_date_time']);
//        }
//        $params['end_date'] = strtotime($params['end_date_time']);
        $bookings = Booking::boatBookingsByStartDateEndDate($params);
        // TODO: Need to handle this case when 07-02-2022 23:30 ---- 08-02-2022 00:30
        $booked_slots = $this->prepraeBookedSlots($bookings, $params);
        return $booked_slots;
    }

    public function prepraeBookedSlots($bookings, $params) {
        $bookedSlots = [];
        $availableSlot = [];
        $unbookedSlots = [];
        $previousBookingSlot = [];
        $count = 0;
        $currentDate = date('Y-m-d', strtotime($params['start_date']));
        $previousBooking = Booking::getPreviousBooking($params);
        if ((!empty($previousBooking))) {
            $previousBookingDate = date('Y-m-d', $previousBooking['end_date_time']);
            if ($previousBookingDate == $currentDate) {

                $previousBookingSlot[$count]['start_time'] = '00:00:00';
                $previousBookingSlot[$count]['end_time'] = date("H:i:s", $previousBooking['end_date_time']);
                $previousBookingSlot[$count]['is_available'] = false;
            }
        }
        if (!empty($bookings)) {
//            if (empty($previousBookingSlot) && (date('H:i:s', $bookings[0]['start_date_time'])) != '00:00:00') {
            if (empty($previousBookingSlot)) {
                if (date("H:i:s", $bookings[0]['start_date_time']) != '00:00:00') {
                    $availableSlot[0]['start_time'] = '00:00:00';
                    $availableSlot[0]['end_time'] = date("H:i:s", $bookings[0]['start_date_time']);
                    $availableSlot[0]['is_available'] = true;
                }
            }
            foreach ($bookings as $key => $booking) {
                if (date("Y-m-d", $booking['start_date_time']) == $currentDate) {
                    $bookedSlots[$key]['start_time'] = date("H:i:s", $booking['start_date_time']);
                    $bookedSlots[$key]['end_time'] = date("H:i:s", $booking['end_date_time']);
                    $bookedSlots[$key]['is_available'] = false;
                    if (date('Y-m-d', $booking['end_date_time']) > $currentDate) {
                        $bookedSlots[$key]['end_time'] = '23:59:00';
                    }
                    if (!empty($bookedSlots)) {
                        $unbookedSlots[$key] = $this->prepareUnBookedSlots($bookings, $bookedSlots, $key, $params);
                    }
                }
            }
        }
        if (empty($bookedSlots) && empty($previousBookingSlot) && empty($unbookedSlots)) {
            $unbookedSlots[$count]['start_time'] = '00:00:00';
            $unbookedSlots[$count]['end_time'] = '23:59:00';
            $unbookedSlots[$count]['is_available'] = true;
        }

        if (empty($bookedSlots) && !empty($previousBookingSlot)) {
            $unbookedSlots[$count]['start_time'] = $previousBookingSlot[0]['end_time'];
            $unbookedSlots[$count]['end_time'] = '23:59:00';
            $unbookedSlots[$count]['is_available'] = true;
        }
        $freeSlots = array_merge($availableSlot, $unbookedSlots);
        $newSlots = $this->prepareNewSlots(array_values(array_filter($freeSlots)), $params);

        $finalSlots = array_merge($newSlots, $previousBookingSlot, $bookedSlots);
//        $finalSlots = array_merge($availableSlot, $previousBookingSlot, $bookedSlots, $unbookedSlots);
//        $finalSlots = array_merge($availableSlot, $previousBookingSlot, $bookedSlots, $unbookedSlots);
        //        usort($finalSlots, function ($a, $b) {
//            return strcmp($b["start_time"], $a["start_time"]);
//        });

        return array_values(array_filter($finalSlots));
    }

    public function prepareNewSlots($slots = [], $params = []) {
//        echo "<pre>";
//        print_r($slots);
//        exit;

        $newSlot = [];
        $date = date('H:i:s');
        $convertedTime = $this->convertTimeToTimeZone($date, 'UTC', $params['local_timezone']);

        $n = date('Y-m-d H:i:s', strtotime($date . " +15 minute"));
        $convertedNewTime = $this->convertFullDateAndToTimeZone($n, 'UTC', $params['local_timezone']);
        if (!empty($slots)) {
            foreach ($slots as $key => $slot) {
                if (($slot['is_available'] == true) && ($slot['start_time'] <= $convertedTime) && ($slot['end_time'] >= $convertedTime)) {
//                    $newSlot[$key]['start_time'] = $slot['start_time'];
                    $newSlot[$key]['start_time'] = date('H:i:s', strtotime($convertedNewTime));
                    $newSlot[$key]['end_time'] = $slot['end_time'];
                    $newSlot[$key]['is_available'] = true;
                }
                if (($slot['is_available'] == true) && ($slot['start_time'] >= $convertedTime)) {
//                    $newSlot[$key]['start_time'] = $slot['start_time'];
                    $newSlot[$key]['start_time'] = $slot['start_time'];
                    $newSlot[$key]['end_time'] = $slot['end_time'];
                    $newSlot[$key]['is_available'] = true;
                }
            }
        }
        return $newSlot;
    }

    public function prepareUnBookedSlots($bookings, $bookedSlots, $key, $params) {
        $unbookedSlots = [];

        $count = $key + 1;
//        echo "<pre>";
//        print_r(date('Y-m-d H:i:s',$bookings[1]['start_date_time']));
//        echo "<pre>";
//        print_r(date('Y-m-d H:i:s',$bookings[1]['end_date_time']));
//        echo "<pre>";
//        print_r(date('Y-m-d H:i:s',$bookings[2]['start_date_time']));
//        echo "<pre>";
//        print_r(date('Y-m-d H:i:s',$bookings[2]['end_date_time']));
//        exit;
        if ((!empty($bookedSlots)) && ((date('Y-m-d', ($bookings[$key]['end_date_time']))) == (date('Y-m-d', strtotime($params['start_date']))))) {
            $unbookedSlots['start_time'] = !empty($bookings[$key]['end_date_time']) ? date("H:i:s", $bookings[$key]['end_date_time']) : '00:00:00';
            $unbookedSlots['end_time'] = '23:59:00';
            if (isset($bookings[$count])) {
                $unbookedSlots['end_time'] = date("H:i:s", $bookings[$count]['start_date_time']);
            }
            $unbookedSlots['is_available'] = true;
        }
        if ((!empty($unbookedSlots) && ($unbookedSlots['start_time'] == $unbookedSlots['end_time'])) || (($unbookedSlots['start_time'] == '23:59:00') && ($unbookedSlots['start_time'] == '00:00:00'))) {
            unset($unbookedSlots['start_time']);
            unset($unbookedSlots['end_time']);
            unset($unbookedSlots['is_available']);
            array_filter($unbookedSlots);
        }
        return (($unbookedSlots));
    }

    public function separateBusyAndFreeSlots($final, $boatSlots, $original, $params) {
        $freeSlot = [];
        $busySlot = [];

        foreach ($boatSlots as $key => $boat) {

            if ($boat['status'] == 'free') {

                array_push($freeSlot, $key);
                unset($boatSlots[$key]);
                if (empty($boatSlots)) {

                    $final[] = [
                        'startTime' => $original[$freeSlot[0]]['start_time'],
                        'endTime' => $original[$freeSlot[sizeof($freeSlot) - 1]]['end_time'],
                        'is_available' => true];
                    break;
                }
            } else {
                if (!empty($freeSlot)) {

                    $final[] = [
                        'startTime' => $original[$freeSlot[0]]['start_time'],
                        'endTime' => $original[$freeSlot[sizeof($freeSlot) - 1]]['end_time'],
                        'is_available' => true];
                }
                break;
            }
        }

        foreach ($boatSlots as $key => $boat) {
            //TODO::info in status is busy push all key into array and in else just get generated slots first and last key
            if ($boat['status'] == 'busy') {

                array_push($busySlot, $key);
                unset($boatSlots[$key]);
                if (empty($boatSlots)) {
                    $final[] = [
                        'startTime' => $original[$busySlot[0]]['start_time'],
                        'endTime' => $original[$busySlot[sizeof($busySlot) - 1]]['end_time'],
                        'is_available' => false];
                    break;
                }
            } else {
                if (!empty($busySlot)) {

                    $final[] = [
                        'startTime' => $original[$busySlot[0]]['start_time'],
                        'endTime' => $original[$busySlot[sizeof($busySlot) - 1]]['end_time'],
                        'is_available' => false];
                }
                break;
            }
        }



        if (!empty($boatSlots)) {
            return $this->separateBusyAndFreeSlots($final, $boatSlots, $original, $params);
        }

        return $final;
    }

    public static function convertFullDateAndToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('Y-m-d H:i:s'); // 2020-8-13
    }

    public static function convertDateToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('Y-m-d'); // 2020-8-13
    }

    public static function convertTimeToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('H:i:s'); // 2020-8-13
    }

    public static function convertTwellHoursTimeToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('H:i:s'); // 2020-8-13
    }

    public function makeSlots($finalKeys, $boatSlots) {
        $finalSlots = [];
        foreach ($boatSlots as $slotKey => $slot) {
            foreach ($finalKeys as $key => $value) {

                if ($slotKey >= $value['startKey'] && $slotKey < $value['endKey']) {
                    $finalSlots[] = $slotKey;
                }
            }
        }
        foreach ($boatSlots as $slotKey => $slot) {
            if (in_array($slotKey, $finalSlots)) {
                $boatSlots[$slotKey]['status'] = 'busy';
            } else {
                $boatSlots[$slotKey]['status'] = 'free';
            }
        }

        return $boatSlots;
    }

    public function getSingleValue($slots) {
        $final = [];
        foreach ($slots as $sl) {
            array_push($final, $sl['start_time']);
        }
        return $final;
    }

    public function searchSlots($bookingSlots, $boatSlots, $singleSlots, $params) {

        $finalKeys = [];
        if ($bookingSlots == null) {

            return [
                'startTime' => $this->convertTwellHoursTimeToTimeZone($params['date'] . ' ' . $boatSlots[0]['start_time'], 'UTC', 'Asia/Karachi'),
                'endTime' => $this->convertTwellHoursTimeToTimeZone($params['end_date'] . ' ' . $boatSlots[sizeof($boatSlots) - 1]['end_time'], 'UTC', 'Asia/Karachi'),
                //'startTime'=>$boatSlots[0]['start_time'],
                //'endTime'=>$boatSlots[sizeof($boatSlots)-1]['end_time'],
                'is_available' => true,
                'noKey' => true
            ];
        } else {

            foreach ($bookingSlots as $bookingKey => $booking) {
                $finalKeys[] = [
                    'startKey' => (array_search($booking['startTime'], $singleSlots)),
                    'endKey' => (array_search($booking['endTime'], $singleSlots))
                ];
            }

            return $finalKeys;
        }
    }

    public function getBoatWorkingHoursByDay($date, $day, $boat_id, $params) {
        // dd(strtotime(date('Y-m-d H:i:s', strtotime('2022-01-04 10:00'))));
        $boatSchedules = $this->model->getBoatWorkingHoursByDay($day, $boat_id);

        return $this->getTimeSlot(30, $params);
    }

    public function getTimeSlot($interval, $params) {

        $start_time = $this->convertFullDateAndToTimeZone($params['date'] . ' ' . $params['from_time'], 'UTC', 'Asia/Karachi');  //start time as string
        // dd(strtotime($start_time));
        //dd($start_time);
        $end_time = $this->convertFullDateAndToTimeZone($params['end_date'] . ' ' . $params['to_time'], 'UTC', 'Asia/Karachi'); //end time as string

        $booked = array();    //booked slots as arrays

        $start = \DateTime::createFromFormat('Y-m-d H:i:s', $start_time);  //create date time objects

        $end = \DateTime::createFromFormat('Y-m-d H:i:s', $end_time);  //create date time objects
        $end = $end < $start ? $end->modify('+1 day') : $end;
        $count = 0;  //number of slots
        $out = array();   //array of slots
        for ($i = $start; $i < $end;) {  //for loop
            $avoid = false;   //booked slot?
            $time1 = $i->format('H:i');   //take hour and minute

            $i->modify("+" . $interval . " minutes");      //add 20 minutes

            $time2 = $i->format('H:i');     //take hour and minute
            $slot = $time1 . "-" . $time2;      //create a format 12:40-13:00 etc

            if (!$avoid && $i <= $end) {  //if not booked and less than end time
                $count++;           //add count
                $slots = ['start_time' => $time1, 'end_time' => $time2];         //add count
                array_push($out, $slots); //add slot to array
            }
        }
        // dd($out);
        return $out;   //array out
    }

    public function makeMultipleResponse($records) {
        $finalRes = [];
        foreach ($records as $record) {
            $finalRes[] = $this->boatWorkingHoursResponse($record);
        }
        return $finalRes;
    }

    public function mapOnTable($param) {

        return [
            'working_hour_uuid' => Str::uuid()->toString(),
            'boat_id' => $param['boat_id'],
            'day' => $param['day'],
            'from_time' => $param['from_time'],
            'to_time' => $param['to_time'],
            'saved_timezone' => $param['saved_timezone'],
            'local_timezone' => $param['local_timezone'],
        ];
    }

}
