<form novalidate class="page-form" method="{{$method}}" action="{{$action}}">
    <div class="form-title">
        <h1>{{ $title }}</h1>
    </div>
    @csrf
    {{ $additionalTopFields }}
    <div class="form-row">
        <label>{{ trans('cms::cms.page.title') }}
            <input type="text" name="title" required autofocus placeholder=""
                   value="{{ old('title') ? old('title') : (!empty($page) ? $page->title : '') }}">
            @if ($errors->has('title'))
                <span class="form-error is-visible">{{ $errors->first('title') }}</span>
            @endif
        </label>
    </div>
    <div class="form-row">
        <label>{{ trans('cms::cms.page.urlpath') }}
            <input type="text" name="slug" required autofocus placeholder=""
                   value="{{ old('slug') ? old('slug') : (!empty($page) ? $page->slug : '') }}" >
            @if ($errors->has('slug'))
                <span class="form-error is-visible">{{ $errors->first('slug') }}</span>
            @endif
        </label>
    </div>
    {{ $additionalMiddleFields }}
    <div class="form-row">
        <span>Blocktypen</span>
        @if ($errors->has('blocktypes'))
            <br/><span class="form-error is-visible">{{ $errors->first('blocktypes') }}</span>
        @endif
        @forelse($blocktypes as $type)
            <label class="sub-label">{{ $type->title }}
                <input type="checkbox" name="blocktypes[]"
                       value="{{$type->id}}"
                        {{is_array(old('blocktypes')) && in_array($type->id, old('blocktypes')) ? ' checked' :
                        !empty($page) && $page->blocktypes && in_array($type->id, $page->blocktypes()->pluck('id')->toArray()) ? ' checked' : ''}} >
            </label>

        @empty
            <p>{{trans('cms::cms.blocktypes.no_blocktypes')}}</p>
        @endforelse
    </div>
    {{ $additionalBottomFields }}
    <div class="form-row action-row">
            <span class="text-left">
                <button class="button" type="submit">{{ $buttonTitle }}</button>
            </span>
    </div>
</form>