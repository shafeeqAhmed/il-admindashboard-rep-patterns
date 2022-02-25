<?php

namespace App\Repositories;


use App\Models\SystemSettings;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\SystemSettingsResponse;
use Illuminate\Support\Str;
//use Your Model

/**
 * Class BoatRepository.
 */
class SystemSettingRepository extends BaseRepository implements RepositoryInterface
{
    use SystemSettingsResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return SystemSettings::class;
    }
    public function getSystemSettings() {
        return $this->model->systemSettings();
    }
    public function addSettings($params) {
        return $this->model->addSettings($this->mapOnTable($params));
    }

    public function getSystemSettingActive(){
        $settings = $this->model->systemSettingActive();

        return $settings ? $this->systemSettingsResponse($this->model->systemSettingActive()) : null;
    }

    public function mapOnTable($params){
        return [
            'system_setting_uuid'=>Str::uuid()->toString(),
            'vat'=>$params['vat'],
            'boatek_commission_charges'=>$params['boatek_commission_charges'],
            'transaction_charges'=>$params['transaction_charges'],
            'withdraw_scheduled_duration'=>$params['withdraw_scheduled_duration'],
        ];
    }
    public function editSystemSettings($uuid) {
        return $this->model->where('system_setting_uuid', $uuid)->first();
    }
    public function updateSystemSettings($params,$uuid) {

        return $this->getByColumn($uuid,'system_setting_uuid')->update($this->prepareParams($params));
    }
    public function prepareParams($params) {
        return [
            'vat'=> $params['vat'],
            'boatek_commission_charges'=> $params['boatek_commission_charges'],
            'transaction_charges'=> $params['transaction_charges'],
            'withdraw_scheduled_duration'=> $params['withdraw_scheduled_duration'],
        ];
    }
}
