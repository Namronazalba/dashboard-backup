<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\troubleshooted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('index');
    }
    public function troubleshooted(Request $request){
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $countTroubleshooted = DB::table('tbl_trblesht_report')
            ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
            ->where('tbl_cvehicles.accnt_id', 22)
            ->whereNotNull('tbl_cvehicles.vplatenum')
            ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
            ->count();
        return view('card', ['data' => $countTroubleshooted])
        ->with('modal', $countTroubleshooted);
    }
    public function totaltroubleshoot($startDate, $endDate){
        $countTroubleshooted = DB::table('tbl_trblesht_report')
            ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
            ->where('tbl_cvehicles.accnt_id', 22)
            ->whereNotNull('tbl_cvehicles.vplatenum')
            ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
            ->count();
        return $countTroubleshooted;
    }
    public function modalcard(Request $request){
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $fixed = $this->modalcardfixed($startDate, $endDate);
        $standby = $this->modalcardstandby($startDate, $endDate);
        $breakdown = $this->modalcardbreakdown($startDate, $endDate);
        $decommissioned = $this->modalcarddecommissioned($startDate, $endDate);
        $totaltroubleshoot = $this->totaltroubleshoot($startDate, $endDate);
        return view('modal',['fixed'=>$fixed, 'standby'=>$standby, 'breakdown'=>$breakdown, 'decommissioned'=>$decommissioned, 'data'=>$totaltroubleshoot]);
    }

    public function modalcardfixed($startDate, $endDate) {
        $countFixed = DB::table('tbl_trblesht_report')
        ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
        ->where('tbl_cvehicles.accnt_id', 22)
        ->whereNotNull('tbl_cvehicles.vplatenum')
        ->where('tbl_trblesht_report.task_status', 'Finished')
        ->where('tbl_trblesht_report.troubleshooting_status', 'Fixed')
        ->where('tbl_trblesht_report.action_taken', 'not like', '%Do nothing%')
        ->where('tbl_trblesht_report.action_taken', '!=', '')
        ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
        ->count();
        return  $countFixed;
    } 
    public function modalcardstandby($startDate, $endDate) {
        $countStandby = DB::table('tbl_trblesht_report')
        ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
        ->where('tbl_cvehicles.accnt_id', 22)
        ->whereNotNull('tbl_cvehicles.vplatenum')
        ->where('tbl_trblesht_report.cause_offline', 'like', '%Standby%')
        ->where(function ($query) {
            $query->where('tbl_trblesht_report.troubleshooting_status', 'like', '%Not Fixed%')
                ->orWhere('tbl_trblesht_report.troubleshooting_status', 'like', '%For GPS transfer%');
        })
        ->where('tbl_trblesht_report.action_taken', '!=', '')
        ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
        ->count();
        return $countStandby;
    }
    public function modalcardbreakdown($startDate, $endDate) {
        $countBreakdown = DB::table('tbl_trblesht_report')
        ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
        ->where('tbl_cvehicles.accnt_id', 22)
        ->whereNotNull('tbl_cvehicles.vplatenum')
        ->where('tbl_trblesht_report.cause_offline', 'like', '%Breakdown%')
        ->where(function ($query) {
            $query->where('tbl_trblesht_report.troubleshooting_status', 'like', '%For GPS transfer%')
                ->orWhere('tbl_trblesht_report.troubleshooting_status', 'like', '%Not Fixed%');
        })
        ->where('tbl_trblesht_report.action_taken', '!=', '')
        ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
        ->count();
        return $countBreakdown;
    }
    public function modalcarddecommissioned($startDate, $endDate) {
        $countDecommissioned = DB::table('tbl_trblesht_report')
        ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
        ->where('tbl_cvehicles.accnt_id', 22)
        ->whereNotNull('tbl_cvehicles.vplatenum')
        ->where(function ($query) {
            $query->where('tbl_trblesht_report.cause_offline', 'like', '%Vehicle was sold%')
                ->orWhere('tbl_trblesht_report.cause_offline', 'like', '%Decommissioned%')
                ->orWhere('tbl_trblesht_report.cause_offline', 'like', '%Intentionally switched off the GPS device%')
                ->orWhere('tbl_trblesht_report.cause_offline', 'like', '%Pulled Out GPS Device%')
                ->orWhere('tbl_trblesht_report.cause_offline', 'like', '%Missing GPS Device%');
        })
        ->where('tbl_trblesht_report.action_taken', '!=', '')
        ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
        ->count();
        return $countDecommissioned;
    }
    public function modalfixedtable(Request $request){
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $fixedReports = $this->fixedtable($startDate, $endDate);
        return view('mdfixedtable', ['fixedReports' =>  $fixedReports]);
    }
    public function fixedtable($startDate, $endDate) {   
        $fixedReports = DB::table('tbl_trblesht_report')
        ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
        ->where('tbl_cvehicles.accnt_id', 22)
        ->whereNotNull('tbl_cvehicles.vplatenum')
        ->where('tbl_trblesht_report.task_status', 'Finished')
        ->where('tbl_trblesht_report.troubleshooting_status', 'Fixed')
        ->where('tbl_trblesht_report.action_taken', 'not like', '%Do nothing%')
        ->where('tbl_trblesht_report.action_taken', '!=', '')
        ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
        ->get();
        return $fixedReports;
    }
    public function modalstandbytable(Request $request){
        $startDate = $request->input('start');
        $endDate = $request->input('end');
    
        $standbyReports = $this->standbytable($startDate, $endDate);
        return view('mdstandbytable', ['standbyReports' =>  $standbyReports]);
    }
    public function standbytable($startDate, $endDate) {
        $standbyReports = DB::table('tbl_trblesht_report')
        ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
        ->where('tbl_cvehicles.accnt_id', 22)
        ->whereNotNull('tbl_cvehicles.vplatenum')
        ->where('tbl_trblesht_report.cause_offline', 'like', '%Standby%')
        ->where(function ($query) {
            $query->where('tbl_trblesht_report.troubleshooting_status', 'like', '%Not Fixed%')
                ->orWhere('tbl_trblesht_report.troubleshooting_status', 'like', '%For GPS transfer%');
        })
        ->where('tbl_trblesht_report.action_taken', '!=', '')
        ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
        ->get();
        return $standbyReports;
    }
    public function modalbreakdowntable(Request $request){
        $startDate = $request->input('start');
        $endDate = $request->input('end');
    
        $breakdownReports = $this->breakdowntable($startDate, $endDate);
        return view('mdbreakdowntable', ['breakdownReports' =>  $breakdownReports]);
    }
    public function breakdowntable($startDate, $endDate) {
        $breakdownReports = DB::table('tbl_trblesht_report')
        ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
        ->where('tbl_cvehicles.accnt_id', 22)
        ->whereNotNull('tbl_cvehicles.vplatenum')
        ->where('tbl_trblesht_report.cause_offline', 'like', '%Breakdown%')
        ->where(function ($query) {
            $query->where('tbl_trblesht_report.troubleshooting_status', 'like', '%For GPS transfer%')
                ->orWhere('tbl_trblesht_report.troubleshooting_status', 'like', '%Not Fixed%');
        })
        ->where('tbl_trblesht_report.action_taken', '!=', '')
        ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
        ->get();
        return $breakdownReports;
    }
    public function modaldecommissionedtable(Request $request){
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        // $startDate = '2023-01-01';
        // $endDate = '2023-12-01';
    
        $decommissionedtable = $this->decommissionedtable($startDate, $endDate);
        return view('mddecommissionedtable', ['decommissionedReports' =>  $decommissionedtable]);
    }
    public function decommissionedtable($startDate, $endDate) {
        $decommissionedReports = DB::table('tbl_trblesht_report')
        ->join('tbl_cvehicles', 'tbl_trblesht_report.vid', '=', 'tbl_cvehicles.vid')
        ->where('tbl_cvehicles.accnt_id', 22)
        ->whereNotNull('tbl_cvehicles.vplatenum')
        ->where(function ($query) {
            $query->where('tbl_trblesht_report.cause_offline', 'like', '%Vehicle was sold%')
                ->orWhere('tbl_trblesht_report.cause_offline', 'like', '%Decommissioned%')
                ->orWhere('tbl_trblesht_report.cause_offline', 'like', '%Intentionally switched off the GPS device%')
                ->orWhere('tbl_trblesht_report.cause_offline', 'like', '%Pulled Out GPS Device%')
                ->orWhere('tbl_trblesht_report.cause_offline', 'like', '%Missing GPS Device%');
        })
        ->where('tbl_trblesht_report.action_taken', '!=', '')
        ->whereBetween('tbl_trblesht_report.date_performed', [$startDate, $endDate])
        ->get();
        return $decommissionedReports;
    }
}
