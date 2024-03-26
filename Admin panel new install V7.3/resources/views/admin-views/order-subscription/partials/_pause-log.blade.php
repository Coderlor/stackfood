@php
$logs = $subscription->pause()->latest()->paginate(config('default_pagination'));
@endphp
<div class="card">
<div class="card-header">
    <h5 class="card-header-title">{{translate('messages.subscription_pause_logs')}}<span class="badge badge-soft-dark ml-2">{{$logs->total()}}</span></h5>
</div>
<!-- Table -->
<div class="table-responsive datatable-custom">
    <table id="columnSearchDatatable"
           class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
           data-hs-datatables-options='{
             "order": [],
             "orderCellsTop": true,
             "paging":false
           }'>
        <thead class="thead-light">
        <tr>
            <th class="w-20p">{{translate('messages.sl')}}</th>
            <th class="w-35p" class="text-center">{{translate('messages.from')}}</th>
            <th class="w-35p" class="text-center">{{translate('messages.to')}}</th>
            <th class="w-10p" class="text-center">{{translate('messages.action')}}</th>
        </tr>
        </thead>

        <tbody>
        @foreach($logs as $key=>$log)
            <tr>
                <td>{{$key+$logs->firstItem()}}</td>
                <td class="table-column-pl-0">
                    {{  Carbon\Carbon::parse($log->from)->locale(app()->getLocale())->translatedFormat('d M Y ') }}
                </td>
                <td class="table-column-pl-0 ">
                    {{  Carbon\Carbon::parse($log->to)->locale(app()->getLocale())->translatedFormat('d M Y ') }}
                </td>

                <td>
                    @php
                        $current_date = date('Y-m-d');
                        $from = Carbon\Carbon::parse($log->from);
                    @endphp

                    @if ( $from->gt($current_date))
                    <a class="btn btn-sm btn-danger form-alert" href="javascript:"
                    data-id="role-{{$log['id']}}" data-message="{{translate('messages.Want_to_delete_this_log_?')}}" title="{{translate('messages.delete_log')}}"><i class="tio-delete-outlined"></i>
                    </a>
                    <form action="{{route('admin.order.subscription.pause_log_delete',[$log['id']])}}"
                    method="post" id="role-{{$log['id']}}">
                    @csrf @method('delete')
                    </form>
                    @endif
                    </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- Footer -->
    <div class="card-footer">
        <!-- Pagination -->
    {!! $logs->links() !!}
    <!-- End Pagination -->
    </div>
    <!-- End Footer -->
</div>
</div>
