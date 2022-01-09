@php($xDataName = "x_data_set_name_". $setXName)
@php($addFields = $add_form_field ?? false)
@php($allInputChecked = $allCheckboxChecked ?? false)

<li class="relative border-gray-200" x-data="{ {{ $xDataName }}: {!! $isOpen ?? "null" !!} }">
    <button type="button" class="border-b w-full px-8 py-6 text-left" @click="{{ $xDataName }} !== 1 ? {{ $xDataName }} = 1 : {{ $xDataName }} = null">
        <span class="flex items-center justify-between">
            <span class="text-xl">
                {!! $title ?? '' !!}
            </span>
            <span class="absolute right-0 lg:mt-8 mt-4 mr-12">
                <svg x-bind:class="{{ $xDataName }} == null ? 'hidden' : 'block'" class="w-6 h-6 transition duration-700 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                <svg x-bind:class="{{ $xDataName }} == 1? 'hidden' : 'block'" class="w-6 h-6 transition duration-700 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </span>
        </span>
        <span class="text-sm text-gray-500">
            Found
            <span class="inline-flex items-center px-3 rounded-full text-xs bg-blue-200 text-blue-800">
               {{ $countInfo }}
            </span>
        </span>
    </button>
    <div class="relative border-b  overflow-hidden transition-all max-h-0 duration-700" x-ref="container{!! $containerID !!}" x-bind:style="{{ $xDataName }} == 1 ? 'max-height: ' + $refs.container{!! $containerID !!}.scrollHeight + 'px' : ''">

        @if($addFields)
            <div class="px-8 py-5 bg-blue-50 border-b">
                <form action="{{ \Pecee\SimpleRouter\SimpleRouter::getUrl('new_input_field') }}" method="post">
                    @csrf

                    <div class="bg-white border border-gray-300 rounded-md px-3 py-4 shadow-sm ">
                        <label for="ignore-new-field" class="block text-xs font-medium text-gray-900">Table name</label>
                        <input type="text" name="ignore_new_field" placeholder="Add to to ignore..." class="block py-4 pb-2 pt-2 px-2 w-full border-0 p-0 text-gray-900 placeholder-gray-500 outline-white sm:text-sm" id="ignore-new-field">
                    </div>
                    <a href="javascript:void(0);" onclick="newCheckbox({{ $containerID }})" class="mt-3 flex items-center justify-center bg-blue-500 text-white py-1.5 px-2 rounded-full lg:w-3/12 w-full">
                        Add
                    </a>
                </form>
            </div>
        @endif

        <div class="bg-gray-50 pt-8 pb-5 pl-8 space-y-4 grid grid-cols-1 lg:grid-cols-{{ $cols ?? 3 }}" id="list_data_{{ $containerID }}">

            <div class="col-span-full inline-flex">
                <label class="flex items-center cursor-pointer w-full">
                    <input id="checkAll_{{ $setXName }}" type="checkbox" @if($allInputChecked) checked="checked" @endif onchange="CheckedAll('{{ $setXName }}');" class="h-4 w-4 border border-gray-500 rounded" />
                    <span class="ml-3 text-sm text-gray-700">Select all</span>
                </label>
            </div>
            <div class="col-span-full -ml-8" id="split-{{ $containerID }}">
                <hr class="pt-4">
            </div>

            {!! $slot !!}
        </div>

    </div>
</li>
