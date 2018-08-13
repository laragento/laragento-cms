<div class="pseudo-table table-columns-{{count($tableheader)+1}}">
    <div class="table-header">
        @foreach($tableheader as $header)
        <div class="table-cell">{{$header}}</div>
        @endforeach
        <div class="table-cell">{{trans('admin::admin.actions')}}</div>
    </div>

    @foreach($entities as $entity)
        <div class="table-body">

            @foreach($tableheader as $header)
            <div data-content="{{$header}}" class="table-cell">{{ eval('echo '.$properties[$loop->index].";") }}</div>
            @endforeach
            <div data-content="Aktionen" class="table-cell">
                <a href="{{route($editRoute['url'],[$editRoute['param'] => $entity->id])}}">{{trans('admin::admin.update')}}</a><br/>
                <form style="display:none" id="{{$slug}}-{{$entity->id}}-delete-form" action="{{route($deleteRoute['url'],[$deleteRoute['param'] => $entity->id])}}" method="post">
                    @csrf
                    @method('delete')
                </form>
                <span onclick="submitDeleteForm({{$entity->id}})" class="pseudo-link">{{trans('admin::admin.remove')}}</span>
                {{$additionalActions}}
            </div>
        </div>
    @endforeach
</div>
@section('scripts')
    <script>
        let submitDeleteForm = function (id) {
            let form = document.querySelector('#{{$slug}}-'+id+'-delete-form');
            form.submit();
        };
        {{$additionalScripts}}
    </script>
@endsection