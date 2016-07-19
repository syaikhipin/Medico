<?php namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\Clinicschedule;
use App\Models\Appointment;
use App\Models\Scheduledetail;
use DateTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function getIndex( Request $request )
	{

		if(\Auth::user()->group_id == 3){
			$schedule=Clinicschedule::where('DoctorID',\Session::get('ref_id'))->orderBy('ClinicID')->get();
				$this->data['schedule'] = $schedule;
			$timezone = \SiteHelpers::gridDisplayView(Auth::user()->id,'DoctorID','1:tb_doctor:UserID:timezone');
			if($timezone==''){
				$timezone = 'UTC';
			}
			$now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d'),$timezone,'Y-m-d H:i:s');
			$today= date("D",strtotime($now));
				foreach ($this->data['schedule'] as $key=>$sch) {
					$schedule_detail = Scheduledetail::where('ScheduleID', $sch['ScheduleID'])->where('Day',$today)->get();
					$sch['detail']= $schedule_detail;
					if($schedule_detail->isEmpty())
					{
						$this->data['schedule']->forget($key);
					}

				}

			$from = $now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d 00:00:00'),$timezone,'Y-m-d H:i:s');
			$to= date('Y-m-d 00:00:00', strtotime($from . ' +1 day'));

			$appointments = \DB::table('tb_appointment')->where('DoctorID',\Session::get('ref_id'))->where('isCancelled',0)
				->whereBetween('StartAt',[$from,$to])
				->orderBy('StartAt')->get();

			$this->data['appointments'] = $appointments;

		}

		if(\Auth::user()->group_id == 5){
			$from = date('Y-m-d 00:00:00',time());
			$patients= \DB::table('tb_patient')->where('entry_by',\Auth::user()->id)->lists('PatientID');

			$appointments = \DB::table('tb_appointment')->whereIn('PatientID',$patients)
				->where('StartAt','>',$from)
				->where('isCancelled',0)
				->orderBy('StartAt')->get();
			$this->data['appointments'] = $appointments;

		}

		$this->data['online_users'] = \DB::table('tb_users')->orderBy('last_activity','desc')->limit(10)->get(); 
		return view('dashboard.index',$this->data);
	}

	public function getRead()
	{
		\DB::table('tb_notification')->where('userid',\Session::get('uid'))->update(array('is_read'=>'1'));
	}


}