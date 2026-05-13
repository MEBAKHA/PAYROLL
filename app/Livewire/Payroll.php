<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Payroll extends Component
{
    public $user_id;
    public $start_date;
    public $end_date;
    public $total_hour = 0;
    public $total_salary = 0;
    public $leave_pay = 0;

    public $rate_per_hour = 35000;


    public $pegawai;
    public $total_duration = '00:00:00';
    public function render()
    {

        $users = User::all();
        return view('livewire.payroll', compact('users'))->layout('layouts.main');
    }

    public function calculate()
    {
        $this->validate([
           'user_id' => 'required',
           'start_date' => 'required|date',
           'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $this->pegawai = User::find($this->user_id);

        $start = Carbon::parse($this->start_date)->startOfDay();
        $end = Carbon::parse($this->end_date)->endOfDay();

        //total detik
        $attendances = Attendance::where('user_id', $this->user_id)
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('duration')
            ->get();
        $attendancesSeconds = $attendances->sum(function ($attendance){
            return strtotime($attendance->duration) - strtotime('00:00:00');
        });
        
        $Schedule = Schedule::where('user_id', $this->user_id)->first();

        $scheduleStart = Carbon::parse($Schedule->shift->start_time);
        $scheduleEnd = Carbon::parse($Schedule->shift->end_time);

        $scheduleSeconds = $scheduleStart->diffInSeconds($scheduleEnd);

        $leaves = Leave::where('user_id', $this->user_id)
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['pending', 'approved'])
            ->get();
        $totalLeavesDays = $leaves->count();    
        //convert ke jam menit, detik
        $leavesSeconds = $totalLeavesDays * $scheduleSeconds;

         /*total gajih bersih */
         $totalSeconds = $attendancesSeconds + $leavesSeconds;

         //ubah detik cuti ke jam
         $leaveHour = $leavesSeconds  / 3600;

         //total tambahan Cuti
         $this->leave_pay = $leaveHour * $this->rate_per_hour;

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $this->total_duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        //hitung jam dalam bentuk desimal
        $this->total_hour = $totalSeconds / 3600;

        //hitung total gaji
        $this->total_salary = $this->total_hour * $this->rate_per_hour;
    }

    public function getFormattedDurationProperty()
    {
       if (!$this->total_duration) {
           return null;
       }

       [$jam, $menit, $detik] = explode(':', $this->total_duration);

       return $jam . ' jam ' . $menit . ' menit ' . $detik . ' detik';
    }
}
