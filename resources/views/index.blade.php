<x-layout :locations="$locations">
    <section class="hero-section">
        <div class="container">
            <div class="hero-text wow fadeInDown" data-wow-delay="0.3s" data-wow-duration="1.2s">Welcome to
                <span>Centralino Flora</span>
            </div>
    </section>

    <section class="py-5 bg-white"></section>

    <section class="py-5 bg-light introduction-section" id="introduction">
        <div class="background-blur"></div>
        <div class="container">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-11">
                    <h1 class="display-4 fw-bold text-uppercase wow fadeInDown introduction-text" data-wow-delay="0.3s"
                        data-wow-duration="1.2s">The Legacy of Growth and Discovery</h1>
                    <p class="lead mt-3 wow fadeInDown introduction-sub pt-2" data-wow-delay="0.3s"
                        data-wow-duration="1.2s">
                        Welcome to a world of curiosity, discovery, and growth. This page is more than just a
                        hub—it’s a celebration of biology and the vibrant community of budding scientists at MCU,
                        home to one of the greenest campuses in Caloocan.
                    </p>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                <div class="col-lg-11">
                    <p class="fs-5 wow fadeInDown introduction-sub-sub" data-wow-delay="0.3s" data-wow-duration="1.2s">
                        As you explore, take a moment to admire our gallery, featuring the magnificent trees that
                        grace our surroundings. Each tree is a testament to life’s beauty and resilience,
                        symbolizing our shared commitment to learning, sustainability, and connection with nature.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white"></section>

    <section class="fixed-bg">
        <div class="tree-list-content">
            <div class="shop-page">
                <div class="container trees-container">
                    <div class=" wow fadeInUp">
                        <h2 class="page-title text-center title-list">Trees</h2>
                    </div>
                    <div class="row">
                        @forelse ($trees as $tree)
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 product-card wow fadeInUp">
                                <div class="product-thumbnail-wrapper">
                                    <a href="{{ route('home.trees', ['tree' => $tree->id]) }}">
                                        <img src="{{ asset('storage/' . $tree->cover_image) }}" alt="{{ $tree->name }}"
                                            class="product-thumbnail" loading="lazy">
                                    </a>

                                </div>
                                <h5>{{ $tree->name }}</h5>
                            </div>
                        @empty
                            <p>No trees available.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </section>
</x-layout>