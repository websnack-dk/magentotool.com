@extends('layout.app')

@section('header')
    <header class="wrapper pb-16 lg:pb-10 pt-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8 mt-8 lg:mt-0 lg:py-4 py-2">
            <div class="pt-8 pb-4 relative lg:py-5 flex items-center justify-center lg:justify-between h-36 lg:h-40">
                <div class="absolute left-0 flex-shrink-0 lg:static">
                    <span class="text-white">Configure your</span>
                    <br>
                    <span class="text-3xl lg:text-4xl font-bold text-white">
                        Migration Module
                    </span>
                    <div class="text-white text-sm leading-loose w-full mt-1">
                        <p class="leading-relaxed text-lg">
                            {!! \App\Http\Config\Information::TOP_INFO !!}
                        </p>
                        <div class="pr-1 flex items-center flex-wrap lg:flex-nowrap lg:mt-3 mt-2">
                            <span class="inline-flex items-center px-2 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800 mr-2 lg:ml-0">
                                @isset($_GET['from_version'])
                                    Magento v.{{ ( $_GET['from_version'] ?? '') }}
                                @else
                                    Choose Magento v.1.x
                                @endisset
                            </span>
                            <div class="pr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                            <span class="inline-flex items-center px-2 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800">
                                Magento v.{{ $_ENV['COMPOSER_MIGRATION_VERSION'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8 pt-0 lg:py-10">

        <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">
            <div class="grid grid-cols-1 gap-4 lg:col-span-2">

                <section>
                        <div class="rounded-lg bg-white overflow-hidden shadow">
                            <div class="pb-5 px-6 lg:pt-6 pt-5">
                                <h1 class="text-2xl text-sm">Configure Module for v.{{ $chosen_version }}</h1>
                                <p class="text-gray-500  leading-relaxed mt-2">
                                    Setup map.xml and Config.xml.
                                </p>
                                <span class="text-gray-500 lg:block hidden leading-relaxed">Add Source/Destination information to the right.</span>
                                <span class="text-gray-500 lg:hidden md:hidden sm:hidden block leading-relaxed">Configure migration Source/Destination information at the <a class="text-blue-400" href="#db_information">bottom</a>.</span>
                            </div>

                            @component('components.form-version-select', [
                                'title'         => 'Found '. count($migration_versions) .' magento versions',
                                'isSelected'    => true,
                                'hasBgColor'    => true,
                                'showInformation' => true
                            ])
                            @endcomponent

                            <form action="{{ url('save_configuration') }}" method="post" id="serializeConfigData" x-data="generate_config_data()" @submit.prevent="generateData()" onkeydown="return event.key !== 'Enter';">
                                <div class="p-6">
                                    <div class="py-3">
                                        <h2 class="text-xl font-bold">Configure Map.xml</h2>
                                        <p class="text-gray-500 pb-3 pt-1">
                                            Checkmark input fields to ignore tables in data and settings. Click on source or Destination to view all settings.
                                        </p>
                                    </div>
                                    <div class="bg-white border border-gray-200">
                                        <ul class="shadow-box">
                                            <!-- Output source tables to ignore --->
                                            @component("components.xml-checkbox", [
                                                "containerID"        => 1,
                                                "cols"               => 2,
                                                "add_form_field"      => true,
                                                "isOpen"             => true, // true or null
                                                "title"              => "Source",
                                                "setXName"           => "source_ignore_",
                                                "countInfo"          => count($list_map_source),
                                                "allCheckboxChecked" => true
                                            ])
                                                @foreach($list_map_source as $key => $field)
                                                    <div class="flex lg:col-span-1 col-span-2">
                                                        <div class="flex items-center h-5">
                                                            <input id="source_ignore_{{ $key }}" name="source_ignore--{{ $field }}" checked="checked" type="checkbox" class="h-4 w-4 border border-gray-500 rounded">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="source_ignore_{{ $key }}" class="text-sm text-gray-700">{{ $field }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endcomponent

                                            <!-- Output destination tables to ignore --->
                                            @component("components.xml-checkbox", [
                                                "containerID"        => 2,
                                                "cols"               => 2,
                                                "title"              => "Destination",
                                                "setXName"           => "destination_ignore_",
                                                "countInfo"          => count($list_map_destination),
                                                "allCheckboxChecked" => true
                                            ])
                                                @foreach($list_map_destination as $key => $field)
                                                    <div class="flex lg:col-span-1 col-span-2">
                                                        <div class="flex items-center h-5">
                                                            <input id="destination_ignore_{{ $key }}" name="destination_ignore--{{ $field }}" checked type="checkbox" class="h-4 w-4 border border-gray-500 rounded">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="destination_ignore_{{ $key }}" class="text-gray-700 text-sm">{{ $field }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endcomponent
                                        </ul>
                                    </div>
                                    <div>
                                        <div class="border-b py-3">
                                            <h2 class="mt-6 text-xl font-bold">Configure Config.xml</h2>
                                            <p class="text-gray-500 pb-3 pt-1">
                                                Checkmark input fields to ignore steps.
                                            </p>
                                        </div>
                                        <ul class="shadow">

                                            <!-- Output Setting Steps --->
                                            @component("components.xml-checkbox", [
                                                "containerID"   => 3,
                                                "title"         => "Setting Step",
                                                "setXName"      => "settings_step_",
                                                "countInfo"     => count($list_config_steps[0]['step']),
                                                "allCheckboxChecked" => false
                                            ])
                                                @foreach($list_config_steps[0]['step'] as $key => $step)
                                                    <div class="flex items-center">
                                                        <input id="settings_step_{{ $key }}" name="settings_step--{{ \App\Http\Traits\Helpers::strReplace($step['title'], false, false) }}" type="checkbox" class="h-4 w-4 border border-gray-500 rounded">
                                                        <label for="settings_step_{{ $key }}" class="ml-2 text-sm text-gray-700">{{ $step['title'] }}</label>
                                                    </div>
                                                @endforeach
                                            @endcomponent

                                            <!-- Output Setting Data --->
                                            @component("components.xml-checkbox", [
                                                "containerID"        => 4,
                                                "title"              => "Setting Data",
                                                "setXName"           => "settings_data_",
                                                "countInfo"          => count($list_config_steps[1]['step']),
                                                "allCheckboxChecked" => false
                                            ])
                                                @foreach($list_config_steps[1]['step'] as $key => $step)
                                                    <div class="flex items-center">
                                                        <input id="settings_data_{{ $key }}" name="settings_data--{{ \App\Http\Traits\Helpers::strReplace($step['title'], false, false) }}" type="checkbox" class="h-4 w-4 border border-gray-500 rounded">
                                                        <label for="settings_data_{{ $key }}" class="ml-2 text-sm text-gray-700">{{ $step['title'] }}</label>
                                                    </div>
                                                @endforeach
                                            @endcomponent
                                        </ul>
                                    </div>

                                    <div class="mt-8">
                                        <div id="outputMessage" class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-yellow-700" id="showMessage"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <span class="flex items-center justify-center lg:pt-4">
                                          <button type="submit" class="flex items-center justify-center px-4 lg:py-6 py-4 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-green-600 rounded-full w-full" id="submitGenerator">
                                                <svg id="generatorLoading" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="lg:text-xl">
                                                     <strong>Generate Migration Module</strong> <em>(Free)</em> {{--(${{ number_format(substr($_ENV['MIGRATION_PRICE'],0, -2), 2) }})--}}
                                                </span>
                                              <span class="ml-4">
                                                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path></svg>
                                              </span>
                                          </button>
                                        </span>
                                        <input type="hidden" name="version" value="{{ $chosen_version }}">
                                    </div>
                                    <p class="text-center text-gray-400 text-sm mt-3">
                                        Don't waste time on manual work. Save yourself time, energy, and stress!
                                    </p>
                                </div>
                            </form>

                            <div class="relative py-5">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="px-2 bg-white text-sm text-gray-500 flex items-center">
                                        Will generate&nbsp;<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><strong>Migration Module</strong></span>&nbsp;like example below
                                    </span>
                                </div>
                            </div>
                            <div>
                                <img class="object-fill" src="/app/public/preview1.gif" alt="">
                            </div>

                        </div>
                </section>
            </div>

            @include("migration_config", ["version" => $chosen_version])

        </div>
    </div>
@endsection

@section("end_script")

    @include("js.save_configs")
    @include("js.save_config_files")

@endsection
