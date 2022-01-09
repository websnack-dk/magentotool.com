<div class="flex lg:col-span-1 col-span-2">
    <div class="flex items-center h-5">
        <input id="source_ignore_{{ $key }}" name="source_ignore--{{ $fieldNameInput }}" checked="checked" type="checkbox" class="h-4 w-4 border border-gray-500 rounded">
    </div>
    <div class="ml-3 text-sm">
        <label for="source_ignore_{{ $key }}" class="text-sm text-gray-700">
            {{ $field }}
        </label>
    </div>
</div>
