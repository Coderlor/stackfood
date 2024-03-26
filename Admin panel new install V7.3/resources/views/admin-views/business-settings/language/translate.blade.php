@extends('layouts.admin.app')

@section('title',translate('messages.language'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{dynamicAsset('/public/assets/admin/img/notes.png')}}" class="mw-26px" alt="public">
                    </div>
                    <span>
                        {{ translate('messages.business_setup') }}
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.business-settings.partials.nav-menu')
            </div>
        </div>
        <div class="row __mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="search--button-wrapper justify-content-between">
                            <h5 class="m-0">{{translate('language_content_table')}}</h5>
                            <form class="search-form min--260">
                                <div class="input-group input--group">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                            placeholder="{{ translate('messages.Ex : Search') }}" aria-label="{{translate('messages.search')}}" value="{{ request()?->search ?? null }}" required>
                                    <input type="hidden">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable"  >
                                <thead>
                                <tr>
                                    <th >{{translate('SL#')}}</th>
                                    <th >{{translate('Current_value')}}</th>
                                    <th >{{translate('translated_value')}}</th>
                                    <th > {{translate('auto_translate')}}</th>
                                    <th >{{translate('update')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($count=0)
                                @foreach($full_data as $key=>$value)
                                @php($count++)

                                <tr id="lang-{{$count}}">
                                    <td>{{ $count+$full_data->firstItem() -1}}</td>
                                    <td >
                                        <input type="text" name="key[]"
                                        value="{{$key}}" hidden>
                                        <div style="max-inline-size: 450px"> {{translate($key) }}</div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="value[]"
                                        id="value-{{$count}}"
                                        value="{{$full_data[$key]}}">
                                    </td>
                                    <td >
                                        <button type="button"
                                                data-key="{{$key}}" data-id="{{$count}}"
                                                class="btn btn-ghost-success btn-block auto-translate-btn"><i class="tio-globe"></i>
                                        </button>
                                    </td>
                                    <td >
                                        <button type="button"
                                                data-key="{{$key}}"
                                                data-id="{{$count}}"
                                                class="btn btn--primary btn-block update-language-btn"><i class="tio-save-outlined"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                                </tbody>
                            </table>
                            @if(count($full_data) !== 0)
                            <hr>
                            @endif
                            <div class="page-area">
                                {!! $full_data->links() !!}
                            </div>
                            @if(count($full_data) === 0)
                            <div class="empty--data">
                                <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                                <h5>
                                    {{translate('no_data_found')}}
                                </h5>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict"
        $(document).on('click', '.update-language-btn', function () {
            let key = $(this).data('key');
            let id = $(this).data('id');
            let value = $('#value-'+id).val() ;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.language.translate-submit',[$lang])}}",
                method: 'POST',
                data: {
                    key: key,
                    value: value
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function () {
                    toastr.success('{{translate('text_updated_successfully')}}');
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

        $(document).on('click', '.auto-translate-btn', function () {
            let key = $(this).data('key');
            let id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.language.auto-translate',[$lang])}}",
                method: 'POST',
                data: {
                    key: key
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    toastr.success('{{translate('Key translated successfully')}}');
                    $('#value-'+id).val(response.translated_data);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>

@endpush
