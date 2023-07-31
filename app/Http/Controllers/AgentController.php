<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Agent;
use App\Models\User;
use App\Models\Referral;
use App\Models\CommissionHistory;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function commissionConvert(Request $request)
    {
        try {
            if($request->points > Auth::user()->agent->current_commission) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient points to convert',
                ], 402);
            }

            if($request->points < 200) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Minimum commission points is 200.00',
                ], 402);
            }

            $agent = Agent::where('user_id', Auth::user()->id)->first();
            $agent->current_commission -= $request->points;
            $agent->save();

            $user = User::find(Auth::user()->id);
            $user->points += $request->points;
            $user->save();

            CommissionHistory::create([
                'user_id' => Auth::user()->id,
                'points_converted' => $request->points,
                'current_points' => $user->points,
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ], 500);
        }

        return response()->json([
            'data' => [
                'current_commission' => number_format($agent->current_commission,2),
                'points' => $user->points,
            ],
            'status' => 'success',
        ], 200);
    }

    public function playersUnder()
    {
        return view('agents.index');
    }

    public function playerLists()
    {
        $players = Referral::with('user')
            ->with('bet')
            ->withSum('bet','agent_commission')
            ->where('referrer_id', Auth::user()->id)->get();

        return response()->json([
            'data' => $players,
            'status' => 'success',
        ], 200);
    }
}
