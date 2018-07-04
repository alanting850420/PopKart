<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div id="{{we_id($name)}}" style="width: 100%; height: 100%;">
            <p>{!! old($column, $value) !!}</p>
        </div>

        <input type="hidden" name="{{$name}}" value="{{ old($column, $value) }}" />

        {!! we_config(we_id($name), $name) !!}
        @include('admin::form.help-block')
    </div>
</div>