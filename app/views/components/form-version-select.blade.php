<section>
    <div class="@if(! $isSelected) overflow-hidden @endif">
        <div class="@if($hasBgColor) bg-white shadow bg-blue-600 px-6 py-8 pb-4 @endif ">
            <form action="{{ url('version') }}" method="get">
                <label for="versions_m1">
                    <select onchange="this.form.submit()" name="from_version" id="versions_m1" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border rounded-lg">
                        <option value="NADA">- Choose Magento version -</option>
                        @foreach($migration_versions as $version)
                            <option @if ($isSelected && (\App\Http\Controllers\BaseController::version($version->getbasename())) === $_GET['from_version'])) selected="selected" @endif value="{{ (new App\Http\Models\XMLFile())->rename($version->getbasename()) }}">
                                v.{{ $version->getbasename() }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </form>
            @if($showInformation)
                <div class="mt-3 pb-0">
                    <p class="text-md text-center text-white opacity-50 ml-2">
                        <strong>Generate for Opensource-to-opensource</strong> <br>
                        Versions supported v.1.6.x, 1.7.x, 1.8.x, 1.9.x. for data migration
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>
