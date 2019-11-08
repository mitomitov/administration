@php
    $id = uniqid();
    $options['[attr2'] = array();
    $options['attr'] = $options['attr'];
    $options['attr']['id'] = $id;
    $listUpdates = array();
    foreach ($options['params'] as $param) {
        $listUpdates[$options['update_key']($param)] = $options['update_value']($param);
    }
    $required = false;
    if (!empty($options['attr']['required'])) {
        $required = true;
    }
@endphp

<div class="form-group without-language {{ @$options['class'] }}">
    <label class="col-sm-12">{{ $options['title'] }}@if ($required) *@endif</label>
    <div class="col-sm-12 m-b-20">
            <ul id="sortable">
                @foreach($options['params'] as $param)
                <li class="ui-state-default"
                    id="sortable-list"
                    data-key="{{ $options['update_key']($param) }}"
                    data-value="{{ $options['update_value']($param) }}">
                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{ $options['display_value']($param) }}
                </li>
                @endforeach
            </ul>
<span class="help-block with-errors"></span>
        <span class="help-block_custom">
        <small>
            @if (!empty($options['helper_box']))
                {{ $options['helper_box'] }}
            @endif
        </small>
    </span>

        <div>
            @foreach($options['params'] as $param)
                <input type="hidden" class="list-values"
                       id="{{ $name }}[{{ $options['update_key']($param) }}]"
                       name="{{ $name }}[{{ $options['update_key']($param) }}]"
                       data-key="{{ $options['update_key']($param)  }}"
                       value="{{ $options['update_value']($param) }}">
            @endforeach
        </div>
    </div>
</div>

@section('js')
<script>
    var listUpdates = @json($listUpdates);
    $( function() {
        $( "#sortable" ).sortable({
            update: function( event, ui ) {
                recalculateListPositions();
            }

        });
        $( "#sortable" ).disableSelection();
        function recalculateListPositions() {
            let list_len = $( "#sortable" ).get(0).children.length;
            for (let i = 0; i < list_len; i++ ) {
                let child = $( "#sortable" ).get(0).children.item(i);
                listUpdates[child.getAttribute("data-key")] = list_len - i;
            }
            updateListInputs();
        }

        function updateListInputs() {
            $(".list-values").each(function(index, input) {
                var position = listUpdates[input.getAttribute("data-key")] || 0;
                input.value = position;
            })
        }
        // Populate list updates in case they are with default values
        // recalculateListPositions();
    } );
</script>
@endsection

<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
    #sortable li span { position: absolute; margin-left: -1.3em; }
</style>
