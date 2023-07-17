<!-- Main --->
<div class="flex flex-col flex-grow-0 h-full w-full">
    @include('lyra::partials.navbar')

    <!-- Content --->
    <div class="h-full overflow-y-auto p-4 w-full">
        <router-view></router-view>
    </div>
</div>
