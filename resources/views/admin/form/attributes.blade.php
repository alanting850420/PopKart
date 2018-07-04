<div class="{{$viewClass['form-group']}}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <table class="table table-hover" id="table-{{$name}}">
            <tbody>
            <tr>
                <th style="width: 200px">項目名稱</th>
                <th>項目規格</th>
            </tr>

            @if($value)
                @foreach($value as $index => $field)
                    <tr>
                        <td>
                            <input type="text" name="{{$name}}[{{$index}}][name]" class="form-control" placeholder="輸入 名稱" value="{{$field['name']}}" />
                        </td>
                        <td><input type="text" class="form-control" placeholder="輸入 規格" name="{{$name}}[{{$index}}][value]" value="{{$field['value']}}"/></td>
                        <td><a class="btn btn-sm btn-danger table-{{$name}}-remove"><i class="fa fa-trash"></i> remove</a></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        <input type="text" name="{{$name}}[0][name]" class="form-control" placeholder="輸入 名稱" />
                    </td>
                    <td><input type="text" class="form-control" placeholder="輸入 規格" name="{{$name}}[0][value]"></td>
                    <td><a class="btn btn-sm btn-danger table-{{$name}}-remove"><i class="fa fa-trash"></i> remove</a></td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class='form-inline margin' style="width: 100%">
            <div class='form-group'>
                <div class="btn btn-sm btn-success" id="table-{{$name}}-add"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new') }}</div>
            </div>
        </div>
        @include('admin::form.help-block')
    </div>
</div>

<template id="table-{{$name}}-tpl">
    <tr>
        <td>
            <input type="text" name="{{$name}}[__index__][name]" class="form-control" placeholder="輸入 名稱" />
        </td>
        <td><input type="text" class="form-control" placeholder="輸入 規格" name="{{$name}}[__index__][value]"></td>
        <td><a class="btn btn-sm btn-danger table-{{$name}}-remove"><i class="fa fa-trash"></i> remove</a></td>
    </tr>
</template>

<script>
    $(function () {
        $('#table-{{$name}}-add').click(function (event) {
            $('#table-{{$name}} tbody').append($('#table-{{$name}}-tpl').html().replace(/__index__/g, $('#table-{{$name}} tr').length - 1));
        });
        $('#table-{{$name}}').on('click', '.table-{{$name}}-remove', function(event) {
            $(event.target).closest('tr').remove();
        });
    });
</script>