<div
    class="ms-2 me-2 flex items-center"
    x-data="{ isFullscreen: document.fullscreenElement !== null }"
    x-init="
        document.addEventListener('fullscreenchange', () => { isFullscreen = document.fullscreenElement !== null });
        document.addEventListener('mozfullscreenchange', () => { isFullscreen = document.mozFullScreenElement !== null });
        document.addEventListener('webkitfullscreenchange', () => { isFullscreen = document.webkitFullscreenElement !== null });
        document.addEventListener('msfullscreenchange', () => { isFullscreen = document.msFullscreenElement !== null });
    "
>
    <x-filament::icon-button
        icon="heroicon-o-arrows-pointing-out"
        label="To'liq ekran"
        x-show="!isFullscreen"
        x-on:click="
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) { /* Firefox */
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
                document.documentElement.webkitRequestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) { /* IE/Edge */
                document.documentElement.msRequestFullscreen();
            }
        "
        color="gray"
        size="lg"
    />
    <x-filament::icon-button
        icon="heroicon-o-arrows-pointing-in"
        label="Oddiy rejim"
        x-show="isFullscreen"
        x-on:click="
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) { /* Firefox */
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) { /* Chrome, Safari & Opera */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE/Edge */
                document.msExitFullscreen();
            }
        "
        color="gray"
        size="lg"
    />
</div>
