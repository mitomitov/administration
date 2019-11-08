@php
      $required = false;
      if (!empty($options['attr']['required'])) {
          $required = true;
      }
@endphp
@if(!empty($options['translate']) && $options['translate'])
    @foreach(LaravelLocalization::getSupportedLocales() as $locale => $data)
        @php
            $value = $options['value'];

           if (!empty($options['model'])) {
               $translation = $options['model']->translate($locale);
           }
           if (!empty($translation)) {
               $value = $translation->$name;
            } else {
                $value = null;
            }
        @endphp

        <div class="form-group language-{{$locale}} {{ @$options['class'] }}">
            <label class="col-sm-12"><span class="flag-icon flag-icon-{{$locale}}"></span>{{ $options['title'] }}@if ($required) *@endif</label>
            <div class="col-sm-12 m-b-20">
                {!!  Form::text($locale . '[' .$name . ']', $value, $options['attr']) !!}
                <span class="help-block with-errors"></span>
                <span class="help-block_custom">
            <small>
                @if (!empty($options['helper_box']))
                    {{ $options['helper_box'] }}
                @endif
            </small>
        </span>
            </div>
        </div>
    @endforeach
@else
    <div class="form-group without-language {{ @$options['class'] }}">
        <label class="col-sm-12">{{ $options['title'] }}@if ($required) *@endif</label>
        <div class="col-sm-12 m-b-20">
            {!!  Form::text($name, $options['value'], $options['attr']) !!}
            <span class="help-block with-errors"></span>
            <span class="help-block_custom">
            <small>
                @if (!empty($options['helper_box']))
                    {{ $options['helper_box'] }}
                @endif
            </small>
        </span>
        </div>
    </div>
@endif

@section('js')

    <script>
        $(".colorpicker").asColorPicker();
    </script>

@append