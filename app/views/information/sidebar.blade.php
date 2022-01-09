<div class="grid grid-cols-1 gap-4 h-full">
    <div class="rounded-lg bg-white overflow-hidden shadow">
        <div class="p-6">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Any customized setup is recommended to manually change once module has been generated.
                    </p>
                </div>
            </div>
        </div>

        <div class="lg:hidden block">
            <hr class="pt-4">
            <div class="px-6 font-bold text-sm pb-2">
                <h3>Get started</h3>
            </div>
            @component('components.form-version-select', [
                'isSelected'        => false,
                'hasBgColor'        => true,
                'showInformation'   => true
            ])
            @endcomponent
        </div>

    </div>
</div>
