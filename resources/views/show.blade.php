@extends('dashboard::layouts.dashboard')

@section('title','Log Viewer')
@section('description',  $log->getPath() )

@section('content')


    <div class="hbox hbox-auto-xs hbox-auto-sm" id="menu-vue">

        <div class="col w-xxl bg-white-only b-r bg-auto no-border-xs">

            <div class="panel-heading"><i class="fa fa-fw fa-flag"></i> Levels</div>
            <ul class="list-group  m-b-n-xs">
                @foreach($log->menu() as $level => $item)
                @if ($item['count'] === 0)
                    <a href="#" class="list-group-item disabled">
                <span class="badge">
                    {{ $item['count'] }}
                </span>
                        <i class="{{ $item['icon'] }}"></i> {{ $item['name'] }}
                    </a>
                @else
                    <a href="?{{ $level }}" class="list-group-item {{ $level }}">
                <span class="badge level-{{ $level }}">
                    {{ $item['count'] }}
                </span>
                        <span class="level level-{{ $level }}">
                     <i class="{{ $item['icon'] }}"></i> {{ $item['name'] }}
                </span>
                    </a>
                @endif
                @endforeach
            </ul>


            <ul class="list-group">

                <li class="list-group-item">
                    <small>Log entries : {{ $entries->total() }}</small>
                </li>
                <li class="list-group-item">
                    <small>Size : {{ $log->size() }}</small>
                </li>
                <li class="list-group-item">
                    <small>Created at : {{ $log->createdAt() }}</small>
                </li>
                <li class="list-group-item">
                    <small>Updated at : {{ $log->updatedAt() }}</small>
                </li>
            </ul>


        </div>


        <!-- main content  -->
        <div class="col">
            <section class="wrapper-md">


                <div class="bg-white-only bg-auto no-border-xs">


                    <div class="panel">

                        <div class="row wrapper">


                            <div class="panel ">

                                <div class="table-responsive">
                                    <table id="entries" class="table table-condensed">
                                        <thead>
                                        <tr>
                                            <th width="10%">ENV</th>
                                            <th width="10%">Time</th>
                                            <th>Header</th>
                                            <th width="10%" class="text-right">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($entries as $key => $entry)
                                            <tr>
                                                <td>
                                                <span class="label label-env text-dark">
                                                      <i class="{{$entry->level()}}"></i>
                                                    {{ $entry->env }}</span>
                                                </td>

                                                <td>{{ $entry->datetime->format('H:i:s') }}</td>
                                                <td>
                                                    <span class="text-ellipsis">{{ $entry->header }}</span>
                                                </td>
                                                <td class="text-right">
                                                    @if ($entry->hasStack())
                                                        <a class="btn btn-xs btn-link" role="button"
                                                           data-toggle="collapse" href="#log-stack-{{ $key }}"
                                                           aria-expanded="false" aria-controls="log-stack-{{ $key }}">Stack
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if ($entry->hasStack())
                                                <tr>
                                                    <td colspan="4" class="no-padder">
                                                            <pre class="collapse bg-black no-border m-n no-radius"
                                                                 id="log-stack-{{ $key }}">
                                                                {!! $entry->stack() !!}
                                                            </pre>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($entries->hasPages())
                                    <footer class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <small class="text-muted inline m-t-sm m-b-sm">{{trans('dashboard::common.show')}} {{$entries->total()}}
                                                    -{{$entries->perPage()}} {{trans('dashboard::common.of')}} {!! $entries->count() !!} {{trans('dashboard::common.elements')}}</small>
                                            </div>
                                            <div class="col-sm-4 text-right text-center-xs">
                                                {!! $entries->render() !!}
                                            </div>
                                        </div>
                                    </footer>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </section>

        </div>


    </div>



@stop
