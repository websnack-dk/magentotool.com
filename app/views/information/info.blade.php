@extends('layout.app')

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:py-12">
        <div class="h-full rounded-lg bg-white overflow-hidden shadow">
            <div class="py-8 px-8 bg-white">
                <div class="pb-4">
                    <div class="border-b pb-6">
                        <h1 class="text-4xl">Information</h1>
                    </div>
                    <br>

                    <p class="leading-loose">
                        General information: <a class="text-blue-500" href="https://devdocs.magento.com/guides/v2.4/migration/migration-tool.html" target="_blank" rel="nofollow">Magento's official guide</a> operates in three modes:
                    </p>
                    <ul class="list-disc ml-6 lg:ml-12 mt-4">
                        <li class="mt-3">
                            <p>
                                <strong>Settings</strong>. The tool migrates the store and system configuration settings from Magento 1 to Magento 2.
                            </p>
                        </li>
                        <li class="mt-3">
                            <p>
                                <strong>Data</strong>. The tool migrates data from Magento 1 database to Magento 2 database.
                            </p>
                        </li>
                        <li class="mt-3">
                            <p>
                                <strong>Delta</strong>. The tool migrates incremental data such as new orders and catalog data that may have changed since the primary data migration.
                                <br>
                                <em>At the moment you'll have to configure this setup manually.</em>
                            </p>
                        </li>
                    </ul>

                    <hr class="mt-12">
                    <br>
                    <p class="text-xl font-bold mt-2">
                        Follow steps after module has been downloaded
                    </p>
                    <p class="leading-loose mt-2">
                        Once your generated Migration module has been downloaded into your app/code directory. You're ready to run the migration from your terminal. <strong>Always</strong> back up or dump your Magento 2 database before beginning the process.
                    </p>
                </div>
                <div class="p-6 pt-2">
                    <ul class="list-decimal ml-0 lg:ml-7 leading-loose">
                        <li>Drag the generated Migration module folder into your project code folder <span class="bg-gray-50 py-1.5 text-sm px-4 px-1.5 ml-1 border text-gray-600">app/code/GENERATED_MODULE_FOLDER</span></li>
                        <li>
                            Run the typical magento migration command from your terminal.
                            <div class="bg-gray-50 border p-4 pt-3 pb-3 mt-2 text-sm text-gray-500">
                                {{ "bin/magento migrate:settings -r -a app/code/VENDOR_FOLDER/Migration/etc/opensource-to-opensource/VERSION/config.xml" }}
                            </div>
                            <div class="bg-gray-50 border p-4 pt-3 pb-3 mt-2 text-sm text-gray-500">
                                {{ "bin/magento migrate:data -r -a app/code/VENDOR_FOLDER/Migration/etc/opensource-to-opensource/VERSION/config.xml" }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection
