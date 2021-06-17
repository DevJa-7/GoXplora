@extends(backpack_view('blank'))

@php
$breadcrumbs = [
trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
'Dashboard' => false,
];
@endphp

@section('header')
<section class="container-fluid">
    <h2>Dashboard</h2>
</section>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        @if(is('admin'))
        <div class="card">
            <div class="card-header with-border">
                <a  data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="false" aria-controls="collapseOne">
                    <div class="" style="color: #000">Acções administrativas <span class="glyphicon glyphicon-chevron-down" aria-hidden="true" style="font-size: 12px; margin-left: 10px;"></span></div>
                </a>
            </div>
            <div id="collapseOne" class="collapse">
                <div class="card-body">
                    @if(backpack_user()->id == 1)
                    <button path="{{ url('/admin/cache/flush') }}" success="Cache limpa" class="btn btn-primary ajax">Limpar cache</button>
                    <hr />
                    <div style="margin-bottom: 5px;"></div>
                    <button path="{{ url('/admin/cache/config') }}" success="Configurações em cache" class="btn btn-default ajax">Cache config</button>
                    <button path="{{ url('/admin/cache/config/clear') }}" success="Cache das configurações limpas" class="btn btn-default ajax">Limpar cache config</button>
                    {{-- <button path="{{ url('/admin/cache/route') }}" success="Routes em cache" class="btn btn-default ajax">Cache route</button>
                    <button path="{{ url('/admin/cache/route/clear') }}" success="Cache das routes limpas" class="btn btn-default ajax">Limpar cache route</button>
                    <button path="{{ url('/admin/cache/view') }}" success="Views em cache" class="btn btn-default ajax">Cache view</button>
                    <button path="{{ url('/admin/cache/view/clear') }}" success="Cache das views limpas" class="btn btn-default ajax">Limpar cache view</button> --}}
                    <div style="margin-bottom: 5px;"></div>
                    <button path="{{ url('/admin/maintenance/down') }}" success="Modo de manuntenção activado" class="btn btn-danger ajax">Activar manutenção</button>
                    <button path="{{ url('/admin/maintenance/up') }}" success="Modo de manuntenção desactivado" class="btn btn-success ajax">Desativar manutenção</button>
                    @endif
                    <script>
                        document.querySelectorAll('.btn.ajax').forEach(btn => {
                            btn.addEventListener('click', e => {
                                fetch(btn.getAttribute('path'), {
                                    method: 'POST',
                                    credentials: "same-origin",
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                });
                                new PNotify({
                                    title: "Sucesso",
                                    text: btn.getAttribute('success'),
                                    type: "success",
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header with-border">
                {{ trans('backpack::base.login_status') }}
            </div>

            <div class="card-body">{{ trans('backpack::base.logged_in') }}</div>
        </div>
    </div>
</div>
@endsection