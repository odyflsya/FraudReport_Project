<?php

namespace App\Http\Controllers;

use App\Services\DashboardAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAnalyticsController extends Controller
{
    /**
     * Get KPI data
     */
    public function getKPIData(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $service = new DashboardAnalyticsService(Auth::id(), $year, $month);

        return response()->json([
            'total_kasus' => $service->getTotalKasus(),
            'total_kerugian' => $service->getTotalKerugian(),
            'total_recovery' => $service->getTotalRecovery(),
            'recovery_rate' => $service->getRecoveryRate(),
            'active_cases' => $service->getActiveCases(),
            'completion_percentage' => $service->getCompletionPercentage(),
        ]);
    }

    /**
     * Get trend data
     */
    public function getTrendData(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $service = new DashboardAnalyticsService(Auth::id(), $year, $month);

        return response()->json([
            'trend_cases' => $service->getTrendCases(),
            'trend_loss' => $service->getTrendLoss(),
        ]);
    }

    /**
     * Get fraud analysis data
     */
    public function getFraudAnalysis(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $service = new DashboardAnalyticsService(Auth::id(), $year, $month);

        return response()->json([
            'top_jenis_fraud' => $service->getTopJenisFraud(),
            'activity_related' => $service->getActivityRelated(),
            'fraud_by_division' => $service->getFraudByDivision(),
        ]);
    }

    /**
     * Get pelaku analysis data
     */
    public function getPelakuAnalysis(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $service = new DashboardAnalyticsService(Auth::id(), $year, $month);

        return response()->json([
            'internal_vs_external' => $service->getInternalVsExternal(),
            'status_pelaku' => $service->getStatusPelaku(),
            'top_jabatan_pelaku' => $service->getTopJabatanPelaku(),
        ]);
    }

    /**
     * Get kerugian analysis data
     */
    public function getKerugianAnalysis(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $service = new DashboardAnalyticsService(Auth::id(), $year, $month);

        return response()->json([
            'loss_by_victim' => $service->getLossByVictim(),
            'top_cases_by_loss' => $service->getTop10CasesWithLargestLoss(),
        ]);
    }

    /**
     * Get handling analysis data
     */
    public function getHandlingAnalysis(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $service = new DashboardAnalyticsService(Auth::id(), $year, $month);

        return response()->json([
            'handling_status' => $service->getHandlingStatus(),
        ]);
    }

    /**
     * Get root cause analysis data
     */
    public function getRootCauseAnalysis(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $service = new DashboardAnalyticsService(Auth::id(), $year, $month);

        return response()->json([
            'top_kelemahan' => $service->getTopKelemahan(),
        ]);
    }

    /**
     * Get pencegahan analysis data
     */
    public function getPencegahanAnalysis(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $service = new DashboardAnalyticsService(Auth::id(), $year, $month);

        return response()->json([
            'prevention_status' => $service->getPreventionStatus(),
            'on_time_completion_rate' => $service->getOnTimeCompletionRate(),
        ]);
    }
}
