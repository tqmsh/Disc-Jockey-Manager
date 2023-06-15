<style>
    :root {
        --color-text: navy;
        --color-bg: papayawhip;
        --color-bg-accent: #ecdcc0;
        --size: clamp(10rem, 1rem + 40vmin, 30rem);
        --gap: calc(var(--size) / 14);
        --duration: 20s;
        --scroll-start: 0;
        --scroll-end: calc(-100% - var(--gap));
    }
    .marquee {
        --gap: 1rem;
        display: flex;
        overflow: hidden;
        user-select: none;
        gap: var(--gap);
    }

    .marquee__group {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: space-around;
        gap: var(--gap);
        min-width: 100%;
        animation: scroll-x var(--duration) linear infinite;
    }
    @keyframes scroll-x {
        from {
            transform: translateX(var(--scroll-start));
        }
        to {
            transform: translateX(var(--scroll-end));
        }
    }
</style>
<div class="marquee">
    <div class="marquee__group">
        @foreach ($ads as $ad)
            <x-ad-card :ad="$ad"/>
        @endforeach
    </div>
    <!-- Mirrors the content above -->
    <div class="marquee__group" aria-hidden="true">
        @foreach ($ads as $ad)
            <x-ad-card :ad="$ad"/>
        @endforeach
    </div>
</div>
