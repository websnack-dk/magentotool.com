<div class="grid grid-cols-1 gap-4 sticky top-24" id="db_information">
    <div class="rounded-lg bg-white overflow-hidden shadow">
        <div class="p-6">
            <form action="{{ url('save_configuration') }}" method="post" id="saveConf" x-data="save_configuration()" @submit.prevent="submitData">
                @csrf
                <h2 id="migration-config-headline">Configure map.xml</h2>

                <hr class="pb-4 mt-3">

                <div class="col-span-6 border-b pb-4">
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm text-red-700">
                                    Fields below is required to fill out in order to generate a Migration Module. Feel free to enter dummy data and re-fill manually in file config.xml
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div x-show="is_saved" class="mb-4">
                    <div class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800" x-text="message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-6 gap-6">

                    <div class="col-span-6 sm:col-span-6">
                        <label for="module-name" class="block text-sm font-medium text-gray-700">Module name
                            <input type="text" name="module-name" value="{{ ($_SESSION['config']['module-name'] ?? '') }}" id="module-name" class="pl-2 px-3 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md" placeholder="Enter desired module name...">
                        </label>
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="encryption-key" class="block text-sm font-medium text-gray-700">Crypt key <span class="text-xs font-normal text-gray-500">{{ (!empty($version)) ? 'from Magento v.'. $version:'' }} {{ (!empty($version)) ? '':'From Magento v.1.x' }}</span></label>
                        <input type="text" name="encryption-key" value="{{ ($_SESSION['config']['encryption-key'] ?? '') }}" id="encryption-key" class="pl-2 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md" placeholder="Crypt key from local.xml">
                    </div>

                    <!-- Magento 1.x.x -->
                    <div class="col-span-6 m-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          Source database (m1)
                        </span>
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="source-host" class="block text-sm font-medium text-gray-700">Host
                            <input type="text" name="source-host" value="{{ ($_SESSION['config']['source-host'] ?? '127.0.0.1') }}" id="source-host" placeholder="127.0.0.1" class="pl-2 px-3 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md">
                        </label>
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="source-name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="source-name" value="{{ ($_SESSION['config']['source-name'] ?? 'db_m1') }}" id="source-name" class="pl-2 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="source-user" class="block text-sm font-medium text-gray-700">User</label>
                        <input type="text" name="source-user" value="{{ ($_SESSION['config']['source-user'] ?? 'db_m1') }}" id="source-user" class="pl-2 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="source-password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="text" name="source-password" value="{{ ($_SESSION['config']['source-password'] ?? 'db_pass_m1') }}" id="source-password" class="pl-2 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md">
                    </div>

                    <!-- Magento 2.x.x -->
                    <div class="col-span-6 border-t pt-4 m-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          Destination database (m2)
                        </span>
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="destination-host" class="block text-sm font-medium text-gray-700">Host
                            <input type="text" name="destination-host" value="{{ ($_SESSION['config']['destination-host'] ?? '127.0.0.1') }}" id="destination-host" placeholder="127.0.0.1" class="pl-2 px-3 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md">
                        </label>
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="destination-name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="destination-name" value="{{ ($_SESSION['config']['destination-name'] ?? 'db_m2') }}" id="source-name" class="pl-2 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="destination-user" class="block text-sm font-medium text-gray-700">User</label>
                        <input type="text" name="destination-user" value="{{ ($_SESSION['config']['destination-user'] ?? 'db_m2') }}" id="destination-user" class="pl-2 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="destination-password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="text" name="destination-password" value="{{ ($_SESSION['config']['destination-password'] ?? 'db_pass_m2') }}" id="destination-password" class="pl-2 mt-1 block w-full shadow-sm sm:text-sm border py-2 rounded-md">
                    </div>
                </div>

                <button class="flex items-center justify-center mt-5 bg-blue-500 text-white py-2 px-4 rounded-full w-full">
                    Save configuration
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </button>
            </form>

        </div>

    </div>
</div>

