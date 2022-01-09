@extends('layout.app')

@section('header')
    <header class="relative w-full bg-gradient-to-b from-purple-900 via-blue-900 to-purple-500 overflow-hidden pt-16 lg:pt-10">
        <div class="relative top-0 left-0 z-0 h-full mx-auto opacity-25">
            <div class="relative opacity-50">
                <div class="absolute left-0 w-full h-56 -mt-20 -mt-48 -ml-48 transform -rotate-45 bg-purple-200 rounded-l-lg opacity-25 sm:-ml-56"></div>
                <div class="absolute w-full h-64 min-w-full -mt-2 -ml-6 transform -rotate-45 bg-indigo-200 rounded-l-lg opacity-25 sm:-ml-32 sm:mt-20"></div>
                <div class="absolute left-0 w-full h-64 mt-24 ml-64 transform -rotate-45 bg-blue-200 rounded-lg rounded-l-lg opacity-25 third"></div>
            </div>
        </div>
        <div class="relative z-10 max-w-3xl px-12 mx-auto space-y-5 text-center lg:px-0 ">
            <div class="w-full relative top-7">
                <span class="font-medium text-gray-200">Auto-generate</span>
                <h1 class="text-6xl font-black text-white lg:text-7xl">Migration Module</h1>
            </div>
            <br>
            <p class="font-medium text-gray-200 text-xl relative mt-4">
                Get a more painless setup-process, and save yourself time, energy, and stress️
            </p>

            @component('components.form-version-select', [
                'isSelected'        => false,
                'hasBgColor'        => false,
                'showInformation'   => true
            ])
            @endcomponent
        </div>
        <div class="relative flex justify-center items-center z-30 max-w-4xl px-4 mx-auto lg:mt-12 mt-7 lg:px-0">
            <div class="absolute bottom-0 flex justify-center mx-auto w-full py-3 shadow-lg">
                <div class="px-2 text-xs text-white flex items-center bg-black rounded-full px-3 py-2 bg-opacity py-2.5 px-6">
                    Will auto-generate&nbsp;<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-700 text-white"><strong>Migration Module</strong></span>&nbsp;like shown on video
                </div>
            </div>
            <img class="rounded-t-xl shadow-2xl" src="/app/public/preview1.gif" alt="" />
        </div>
    </header>
@endsection

@section('content')

    <div class="mx-auto px-4 sm:px-6 lg:px-0">
        <div class="grid grid-cols-1 gap-4 items-start lg:gap-8">
            <div class="bg-white h-full  border-b">
                <div class="max-w-7xl mx-auto pb-2 px-6 pt-8 lg:py-12">
                    <h1 class="text-2xl">
                        Configure a <strong>Migration Module</strong>
                    </h1>
                    <p class="text-gray-500 mt-1">Don't waste time on manual work</p>
                    <p class="mt-2 leading-loose">
                        Migrating data from Magento 1 to Magento 2 store can be quite complex and time consuming. In order to migrate data smoothly, you need to do a data migration. It's completely free, but requires some technically knowledge.
                    </p>
                    <hr class="py-3 mt-6">
                    <p>
                        I provide a free solution for you to configure and auto-generate a <strong class="bg-yellow-50 px-0.5 py-0.5">Migration Module </strong> <em>visually</em> without having to waste time editing XML-files constantly.
                        Simple follow steps below to automatic generate a module that fits your needs. And get a more painless setup-process.
                    </p>
                    <br>
                    <ul class="grid lg:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 md:grid-cols-2 gap-2 leading-loose list-decimal ml-5">
                        <li>
                            @component('components.form-version-select', [
                              'isSelected'      => false,
                              'hasBgColor'      => false,
                              'showInformation' => false
                              ])
                            @endcomponent
                        </li>
                        <li class="lg:ml-8">
                            Configure data setup
                        </li>
                        <li>
                            Move generated migration module to your project
                        </li>
                        <li class="lg:ml-8">
                            Run migration process from your terminal
                        </li>
                    </ul>
                </div>
            </div>
            <div class="bg-gray-100 lg:pb-12 lg:pt-3">
                    <div class="max-w-7xl mx-auto  space-y-5">
                        <div x-data="{ show: true }" class="relative overflow-hidden border-2 border-gray-200 rounded-lg select-none hover:bg-white">
                            <h4 @click="show=!show" class="flex items-center justify-between text-lg font-medium text-gray-900 cursor-pointer sm:text-xl px-7 py-7 hover:text-gray-800 bg-white">
                                <span>What does the module include?</span>
                                <svg class="w-6 h-6 transition-all duration-200 ease-out transform rotate-0 -rotate-45" :class="{ '-rotate-45' : show }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </h4>
                            <p class="pt-0 -mt-2 text-gray-500 sm:text-lg py-7 px-7 bg-white" x-transition:enter="transition-all ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform -translate-y-0" x-transition:leave="transition-all ease-out hidden duration-200" x-transition:leave-start="opacity-100 transform -translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" x-show="show">
                                At the moment a generated module folder based on which magento 1 version you choose will generate <strong>map.xml</strong> and <strong>config.xml</strong> + the base module files required from Magento v.{{ \App\Http\Config\Information::MAGENTO_VERSION }}.
                            </p>
                        </div>
                        <div x-data="{ show: true }" class="relative overflow-hidden border-2 border-gray-200 rounded-lg select-none hover:bg-white">
                            <h4 @click="show=!show" class="flex items-center justify-between text-lg font-medium text-gray-900 cursor-pointer sm:text-xl px-7 py-7 hover:text-gray-800 bg-white">
                                <span>How do I download my Migration Module?</span>
                                <svg class="w-6 h-6 transition-all duration-200 ease-out transform rotate-0" :class="{ '-rotate-45' : show }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" class=""></path></svg>
                            </h4>
                            <p class="pt-0 -mt-2 text-gray-500 sm:text-lg py-7 px-7 bg-white" x-transition:enter="transition-all ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform -translate-y-0" x-transition:leave="transition-all ease-out hidden duration-200" x-transition:leave-start="opacity-100 transform -translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" x-show="show" style="display: none;">
                                Once you hit "generate", a Zip-file will be available for you to download. You can customize the Migration module / all files as you please afterwards.
                            </p>
                        </div>
                        <div x-data="{ show: true }" class="relative overflow-hidden border-2 border-gray-200 rounded-lg select-none hover:bg-white">
                            <h4 @click="show=!show" class="flex items-center justify-between text-lg font-medium text-gray-900 cursor-pointer sm:text-xl px-7 py-7 hover:text-gray-800 bg-white">
                                <span>What does it cost?</span>
                                <svg class="w-6 h-6 transition-all duration-200 ease-out transform rotate-0" :class="{ '-rotate-45' : show }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" class=""></path></svg>
                            </h4>
                            <p class="pt-0 -mt-2 text-gray-500 sm:text-lg py-7 px-7 bg-white" x-transition:enter="transition-all ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform -translate-y-0" x-transition:leave="transition-all ease-out hidden duration-200" x-transition:leave-start="opacity-100 transform -translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" x-show="show" style="display: none;">
                                It's completely FREE to generate a Module
                            </p>
                        </div>
                        <div x-data="{ show: true }" class="relative overflow-hidden border-2 border-gray-200 rounded-lg select-none hover:bg-white">
                            <h4 @click="show=!show" class="flex items-center justify-between text-lg font-medium text-gray-900 cursor-pointer sm:text-xl px-7 py-7 hover:text-gray-800 bg-white">
                                <span>What if I need help?</span>
                                <svg class="w-6 h-6 transition-all duration-200 ease-out transform rotate-0" :class="{ '-rotate-45' : show }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" class=""></path></svg>
                            </h4>
                            <p class="pt-0 -mt-2 text-gray-500 sm:text-lg py-7 px-7 bg-white" x-transition:enter="transition-all ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform -translate-y-0" x-transition:leave="transition-all ease-out hidden duration-200" x-transition:leave-start="opacity-100 transform -translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" x-show="show" style="display: none;">
                                Check out <a target="_blank" class="hover:text-blue-500" href="https://devdocs.magento.com/guides/v2.4/migration/migration-migrate-data.html#migrate-data-cmd">"Run the data migration command"</a> from Magento's official documentation.
                            </p>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

@section("end_script")
    @include("js.save_configs")
@endsection
