<?php

namespace Orchid\LogViewer;

use Arcanedev\LogViewer\Contracts\LogViewer as Log;
use Arcanedev\LogViewer\Exceptions\LogNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Orchid\Platform\Http\Controllers\Controller;


class LogController extends Controller
{
    /**
     * The log viewer instance.
     *
     * @var \Orchid\Log\Contracts\Log
     */
    protected $log;

    /**
     * @var int
     */
    protected $perPage = 30;

    /**
     * @var string
     */
    protected $showRoute = 'dashboard.systems.logs.show';

    /**
     * LogController constructor.
     *
     * @param Log $log
     */
    public function __construct(Log $log)
    {
        $this->checkPermission('dashboard.systems.logs');
        $this->log = $log;
    }

    /**
     * List all logs.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $stats = $this->log->statsTable();
        $headers = $stats->header();
        $rows = $this->paginate($stats->rows(), $request);

        return view('orchid/logs::logs', compact('headers', 'rows', 'footer'));
    }

    /**
     * Paginate logs.
     *
     * @param array                    $data
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginate(array $data, Request $request)
    {
        $page = $request->get('page', 1);
        $offset = ($page * $this->perPage) - $this->perPage;
        $items = array_slice($data, $offset, $this->perPage, true);
        $rows = new LengthAwarePaginator($items, count($data), $this->perPage, $page);

        $rows->setPath($request->url());

        return $rows;
    }

    /**
     * Show the log.
     *
     * @param string $date
     *
     * @return \Illuminate\View\View
     */
    public function show($date)
    {
        $log = $this->getLogOrFail($date);
        $levels = $this->log->levelsNames();
        $entries = $log->entries()->paginate($this->perPage);

        return view('orchid/logs::show', compact('log', 'levels', 'entries'));
    }

    /**
     * Get a log or fail.
     *
     * @param string $date
     *
     * @return \Orchid\Log\Entities\Log|null
     */
    protected function getLogOrFail($date)
    {
        $log = null;

        try {
            $log = $this->log->get($date);
        } catch (LogNotFoundException $e) {
            abort(404, $e->getMessage());
        }

        return $log;
    }

    /**
     * Filter the log entries by level.
     *
     * @param string $date
     * @param string $level
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showByLevel($date, $level)
    {
        $log = $this->getLogOrFail($date);

        if ($level === 'all') {
            return redirect()->route($this->showRoute, [$date]);
        }

        $levels = $this->log->levelsNames();
        $entries = $this->log
            ->entries($date, $level)
            ->paginate($this->perPage);

        return view('orchid/logs::show', compact('log', 'levels', 'entries'));
    }
}
