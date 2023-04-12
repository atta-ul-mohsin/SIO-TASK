<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;
use App\Services\TimeLogService;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timelogs = Auth::user()->timelogs()->latest()->paginate(50);
        foreach ($timelogs as $timelog) {
            $timelog->timeData = TimeLogService::secondsToTime($timelog->time_spent);
        }
        return view('timelogs.index', compact('timelogs'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('timelogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'log_date' => 'required',
            'hours' => 'required|integer',
            'minutes' => 'required|integer',
            'seconds' => 'required|integer',
        ]);

        $timeLog = array();
        $timeLog['time_spent'] = TimeLogService::timeToSeconds($request->all());
        if ($timeLog['time_spent'] > 0) {
            $timeLog['log_date'] = $request->get('log_date');
            $remainingTime = TimeLogService::isValidTime(Auth::user()->id, $timeLog['time_spent'], $timeLog['log_date'], null);

            if (isset($remainingTime['available_time'])) {
                return redirect()->back()
                    ->withErrors([sprintf('Only %s time for the date %s is free to be logged', $remainingTime['available_time'], $timeLog['log_date'])]);
            } else {
                Auth::user()->timelogs()->create($timeLog);
                return redirect()->route('timelogs.index')
                    ->with('success', 'TimeLog created successfully.');
            }
        } else {
            return redirect()->back()
                ->withErrors([sprintf("You can't log empty time")]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\TimeLog $timelog
     * @return \Illuminate\Http\Response
     */
    public function show(TimeLog $timelog)
    {
        $timelog->timeData = TimeLogService::secondsToTime($timelog->time_spent);
        return view('timelogs.show', compact('timelog'));
    }

    /**
     * @param TimeLog $timelog
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeLog $timelog)
    {
        $timelog->timeData = TimeLogService::secondsToTime($timelog->time_spent);
        return view('timelogs.edit', compact('timelog'));
    }

    /**
     * @param Request $request
     * @param TimeLog $timelog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TimeLog $timelog)
    {
        $request->validate([
            'log_date' => 'required',
            'hours' => 'required|integer',
            'minutes' => 'required|integer',
            'seconds' => 'required|integer',
        ]);


        $timeLog = array();
        $timeLog['time_spent'] = TimeLogService::timeToSeconds($request->all());
        $timeLog['log_date'] = $request->get('log_date');
        $remainingTime = TimeLogService::isValidTime(Auth::user()->id, $timeLog['time_spent'], $timeLog['log_date'], $timelog->id);

        if (isset($remainingTime['available_time'])) {
            return redirect()->back()
                ->withErrors([sprintf('Only %s time for the date %s is free to be logged', $remainingTime['available_time'], $timeLog['log_date'])]);
        } else {
            $timelog->update($timeLog);
            return redirect()->route('timelogs.index')
                ->with('success', 'TimeLog updated successfully');
        }
    }

    /**
     * @param TimeLog $timelog
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function destroy(Timelog $timelog)
    {
        if (Auth::user()->id == $timelog->user_id) {
            $timelog->delete();

            return redirect()->route('timelogs.index')
                ->with('success', 'Timelog deleted successfully');
        } else {
            error(404);
        }
    }

    public function report(string $type)
    {
        $graphData = TimeLogService::getReportData($type, Auth::user()->id);
        return view('timelogs.report', array('dates' => array_keys($graphData), 'times' => array_values($graphData)));
    }
}
