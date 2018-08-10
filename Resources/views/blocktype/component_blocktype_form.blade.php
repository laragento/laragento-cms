<form novalidate class="blocktype-form" method="{{$method}}" action="{{$action}}">
        <div class="form-title">
            <h1>{{ $title }}</h1>
        </div>
        @csrf
        {{ $additionalTopFields }}
        <div class="form-row">
            <label>{{ trans('cms::blocktype.blocktypetitle') }}
                <input type="text" name="title" required autofocus placeholder=""
                       value="{{ old('title') ? old('title') : !empty($blocktype) ? $blocktype->title : '' }}">
                @if ($errors->has('title'))
                    <span class="form-error is-visible">{{ $errors->first('title') }}</span>
                @endif
            </label>
        </div>
        {{ $additionalBottomFields }}
        <div class="form-row action-row">
            <span class="text-left">
                <button class="button" type="submit">{{ $buttonTitle }}</button>
            </span>
        </div>
    </form>