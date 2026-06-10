<?php

namespace App\Http\Controllers;

use App\Services\DashboardAnalyticsService;
use Illuminate\Http\Request;
class DashboardAnalyticsController extends Controller
{
    private function service(Request $request): DashboardAnalyticsService
    {
        return new DashboardAnalyticsService(
            $request->query('year'),
            $request->query('month')
        );
    }

    public function getDashboardData(Request $request)
    {
        return response()->json($this->service($request)->getDashboardData());
    }

    public function getDrilldownData(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'value' => 'required|string',
        ]);

        $service = $this->service($request);
        $cases = $service->getDrilldownCases(
            $request->query('type'),
            $request->query('value')
        );

        return response()->json([
            'cases' => $cases,
            'total' => count($cases),
        ]);
    }

    /** @deprecated Gunakan getDashboardData */
    public function getKPIData(Request $request)
    {
        return response()->json($this->service($request)->getKpiSummary());
    }
}
