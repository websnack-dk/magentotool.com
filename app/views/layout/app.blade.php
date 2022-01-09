<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:title" content="{{ \App\Http\Config\Information::META_TITLE }}" />
    <meta property="og:description" content="{{ \App\Http\Config\Information::META_DESCRIPTION }}" />
    <meta property="og:site_name" content="{{ \App\Http\Config\Information::SITE_NAME }}" />
    <meta property="og:image" content="{{ $_ENV['APP_DOMAIN'] }}/app/public/ogimage.jpg" />
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:image" content="{{ $_ENV['APP_DOMAIN'] }}/app/public/ogimage.jpg" />
    <meta property="twitter:description" content="{{ \App\Http\Config\Information::META_DESCRIPTION }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <link href="{{ $_ENV['APP_DOMAIN'] }}/app/public/css/tailwind.min.css" rel="stylesheet">
    <link href="{{ $_ENV['APP_DOMAIN'] }}/app/public/css/style.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="/app/public/favicon.ico">
    <link rel="icon" href="{{ $_ENV['APP_DOMAIN'] }}/app/public/favicon.ico" type="image/x-icon">
    <script src="{{ $_ENV['APP_DOMAIN'] }}/app/public/js/alpine.min.js" defer type="text/javascript"></script>
    <script src="{{ $_ENV['APP_DOMAIN'] }}/app/public/js/axios.min.js" defer type="text/javascript"></script>
    <title>{{ \App\Http\Config\Information::TITLE }}</title>
    @if($_ENV['APP_ENV'] === 'production')
        {{--paste e.g. analytics--}}
    @endif
</head>
<body class="bg-black">
    <div class="min-h-screen">
        <div class="relative bg-white">
            <div class=" mx-auto py-3 px-3 sm:px-6 lg:px-8">
                <div class="text-center">
                    <a class="hover:text-blue-500 font-medium" href="{{ url('module_extensions')  }}">
                        <strong>New Module:</strong> Zero Downtime Deployment for Magento 2 🚀
                    </a>
                </div>
            </div>
        </div>

        <header class="lg:z-50 lg:sticky lg:top-0 w-full px-8 text-gray-700 bg-black">
            <div class="flex flex-col flex-wrap items-center justify-between py-6 mx-auto md:flex-row max-w-7xl">
                <div class="relative flex flex-col md:flex-row">
                    <a href="{{ url('home') }}" class="flex items-center mb-5 font-medium text-xl text-gray-400 lg:w-auto lg:items-center lg:justify-center md:mb-0">
                        <strong><span class="text-white">Migration Module</span></strong>
                    </a>
                </div>

                <div class="inline-flex flex-col items-center sm:flex-row sm:ml-5 lg:justify-end">
                    <nav class="flex flex-wrap items-center space-x-4 text-xs font-semibold tracking-wide uppercase sm:space-x-6">

                        @if(! isset($_GET['from_version']) && (url()->getOriginalUrl() !== '/'))
                            @component('components.form-version-select', [
                              'isSelected' => false,
                              'hasBgColor' => false,
                              'showInformation' => false
                          ])
                            @endcomponent
                        @endif
                    </nav>
                </div>
            </div>
        </header>

        @yield('header')

        <main class="bg-gray-100 py-8 lg:py-0">
           @yield('content')
        </main>

        <footer class="bg-black">

            <div class="px-6 mx-auto max-w-7xl lg:px-0">

                <div class="box-border flex flex-wrap pt-20 pb-0 text-base leading-tight text-gray-500 md:pb-12">
                    <div class="flex-initial w-1/2 px-2 pb-12 leading-tight md:w-1/4">
                        <div class="box-border text-base text-gray-500">
                            <div class="flex items-center justify-center w-10 h-10 mr-3 rounded-lg mb-7 bg-gradient-to-b from-purple-900 via-blue-900 to-purple-500 rounded-xxl">
                                🛠️
                            </div>
                            <ul class="p-0 m-0 text-base leading-tight list-none">
                                <li class="box-border py-2 text-sm font-normal text-left text-gray-500 md:text-base md:mb-1">
                                    <a href="{{ url('home') }}" class="text-base leading-tight no-underline bg-transparent cursor-pointer hover:text-gray-400">Home</a>
                                </li>
                                <li class="box-border py-2 text-sm font-normal text-left text-gray-500 md:text-base md:mb-1">
                                    <a href="{{ url('information') }}" class="text-base leading-tight no-underline bg-transparent cursor-pointer hover:text-gray-400">How to use</a>
                                </li>
                                <li class="box-border py-2 text-sm font-normal text-left text-gray-500 md:text-base md:mb-1">
                                    <a href="{{ url('module_extensions') }}" class="text-base leading-tight no-underline bg-transparent cursor-pointer hover:text-gray-400">
                                        Magento 2 Extensions & Modules
                                    </a>
                                </li>
                                <li class="box-border py-2 text-sm font-normal text-left text-gray-500 md:text-base md:mb-1">
                                    <a href="{{ url('changelog') }}" class="text-base leading-tight no-underline bg-transparent cursor-pointer hover:text-gray-400">Changelog</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div  class="flex-initial w-full px-2 pb-12 leading-tight lg:w-1/4">
                        <div class="box-border text-base text-gray-500">
                            <h2 class="mb-2 font-sans text-xl font-bold tracking-wide text-gray-100 md:text-2xl">
                                Choose Magento version
                            </h2>
                            @component('components.form-version-select', [
                                  'isSelected' => false,
                                  'hasBgColor' => false,
                                  'showInformation' => false
                              ])
                            @endcomponent
                        </div>
                    </div>
                    <div class="flex-initial px-2 pb-12 leading-tight lg:ml-24 md:w-1/4 w-full">
                        <div class="box-border text-base text-gray-500">
                            <h2 class="mb-2 font-sans text-xl font-bold tracking-wide text-gray-100 md:text-2xl">
                                Information
                            </h2>
                            <ul class="p-0 m-0 text-base leading-tight list-none">
                                <li class="box-border py-2 text-sm font-normal text-left text-gray-500 md:text-base md:mb-1">
                                    <a href="{{ url('pros_cons') }}" class="text-base leading-tight no-underline bg-transparent cursor-pointer hover:text-gray-400">
                                        What are the pros and cons of using Magento's data migration tool?
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-between w-full py-9 text-xs leading-none text-gray-500 border-t border-gray-800 lg:flex-row">
                    <ul class="flex my-6 text-sm text-gray-500 list-none lg:flex-grow-0 lg:flex-shrink-0 lg:my-0">
                        <li class="box-border block text-left">
                            <p class="pr-5 mr-5 text-gray-500 no-underline bg-transparent">
                                🚀&nbsp; Configure & auto-generate a <strong class="text-white">Migration Module</strong> for Magento
                            </p>
                        </li>
                    </ul>
                    <p class="mt-3 leading-tight text-gray-500 sm:mt-0 text-sm">©<a class="text-blue-500" href="https://websnack.dk" target="_blank">Websnack</a>, {{ (Date('Y')) }}. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </div>
    @yield('end_script')
</body>
</html>
