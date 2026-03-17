<x-layout :locations="$locations">

    <main class="blog-post-single mb-5">
        <div class="container">
            <nav aria-label="breadcrumb" class="wow fadeInUp mb-5">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $tree->name }}</li>
                </ol>
            </nav>

            <h1 class="post-title wow fadeInUp mt-5">{{ $tree->name }}</h1>

            <div class="row">
                <div class="col-md-8 blog-post-wrapper">
                    <div class="post-header wow fadeInUp">
                        <a href="{{ asset('storage/' . $tree->cover_image) }}"
                            class="gallery-grid-item" data-fancybox="widget-gallery" loading="lazy">
                            <img src="{{ asset('storage/' . $tree->cover_image) }}" alt="blog post"
                                class="post-featured-image">
                        </a>
                    </div>
                    <div class="post-content wow fadeInUp light-span">
                        <h5 class="scientificname">Scientific Name: <span>{{ $tree->scientific_name }}</span></h5>
                        <h5>Common Name: <span>{{ $tree->common_name }}</span></h5>
                        <h5>Local Name: <span>{{ $tree->local_name }}</span></h5>

                        <h5 class="mt-4">Description:</h5>
                        <p>{{ $tree->description }}</p>

                        <h5 class="mt-4">Uses in Filipino Folklore and Other Uses:</h5>
                        <p>{{ $tree->uses_filipino }}</p>

                        @php
                            // Split by new lines
                            $facts = preg_split('/\r\n|\r|\n/', $tree->tree_facts);
                        @endphp

                        @if(!empty($facts))
                            <ul class="tree-fact-list">
                                @foreach($facts as $fact)
                                    @php
                                        // Remove bullet characters like • or -
                                        $fact = trim(str_replace(['•', '-', '–'], '', $fact));
                                    @endphp

                                    @if($fact !== '')
                                        <li>{{ $fact }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="sidebar-widget wow fadeInUp">
                        <h5 class="widget-title">Gallery</h5>
                        <div class="widget-content">
                            @php
                                $images = $tree->image_gallery ?? [];
                                $visibleLimit = 11;
                                $totalImages = count($images);
                            @endphp

                            <div class="gallery">

                                {{-- Visible images (max 11) --}}
                                @foreach($images as $index => $image)
                                    @if($index < $visibleLimit)
                                        <a href="{{ asset('storage/' . $image) }}" class="gallery-grid-item"
                                            data-fancybox="widget-gallery">
                                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $tree->name }}" loading="lazy">
                                        </a>
                                    @endif
                                @endforeach

                                {{-- +MORE button (only if images exceed 11) --}}
                                @if($totalImages > $visibleLimit)
                                    <a href="{{ asset('storage/' . $images[$visibleLimit]) }}"
                                        class="gallery-grid-item gallery-show-more"
                                        data-more-text="+{{ $totalImages - $visibleLimit }} more"
                                        data-fancybox="widget-gallery">
                                    </a>
                                @endif

                                {{-- Hidden images --}}
                                <div class="hidden-images" style="display:none;">
                                    @foreach($images as $index => $image)
                                        @if($index >= $visibleLimit)
                                            <a href="{{ asset('storage/' . $image) }}" class="gallery-grid-item"
                                                data-fancybox="widget-gallery">
                                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $tree->name }}"
                                                    loading="lazy">
                                            </a>
                                        @endif
                                    @endforeach
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="sidebar-widget wow fadeInUp">
                        <h5 class="widget-title">Scientific Classification:</h5>
                        <div class="widget-content">
                            <ul class="category-list light-span">
                                <li>Domain: <span>{{ $tree->domain }}</span></li>
                                <li>Kingdom: <span>{{ $tree->kingdom }}</span></li>
                                <li>Phylum: <span>{{ $tree->phylum }}</span></li>
                                <li>Class: <span>{{ $tree->class }}</span></li>
                                <li>Order: <span>{{ $tree->order }}</span></li>
                                <li>Family: <span>{{ $tree->family }}</span></li>
                                <li class="genus">Genus: <span>{{ $tree->genus }}</span></li>
                                <li class="species">Species: <span>{{ $tree->species }}</span></li>
                            </ul>
                        </div>
                    </div>
                    {{-- Paano ito? baka gawin ko nalang itong mga location lang walang besides or near --}}
                    {{-- Next: Count each visit in this tree. mag bibilang na kung ilang visit na GPT makakasagot --}}
                    <div class="sidebar-widget wow fadeInUp">
                        <h5 class="widget-title">Tagged Trees:</h5>
                        <div class="widget-content">
                            <ul class="category-list light-span">
                                <li>TA 16: <span>Besides Laboratory StockRoom (FDT)</span></li>
                                <li>TA 87: <span>Near BED Entrance</span></li>
                                <li>TA 110: <span>Center edge of parking</span></li>
                                <li>TA 119: <span>Beside Gate 5</span></li>
                                <li>TA 121: <span>Center edge of parking</span></li>
                                <li>TA 254: <span>Across from LRC, FDT</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DYNAMIC THIS SECTION FOR NEXT AND PREVIOUS TREE FOR EACH GROUP LOCATION -->
            <section>
                <h2 class="section-title text-center my-5 wow fadeInUp">Explore Trees</h2>
                <div class="row">

                    <div class="col-6 blog-post-wrapper">
                        <div class="post-header wow fadeInLeft">
                            <a href="Yellow-Bell.html">
                                <div class="news-card cardimg-hover">
                                    <img src="../../assets/images/Trees/Yellow Bell/Yellow bell TA 4(3).webp"
                                        alt="Yellow Bell" class="card-img">
                                    <div class="card-body">
                                        <h5 class="card-title text-center text-sm-res">Yellow Bell</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-6 blog-post-wrapper">
                        <div class="post-header wow fadeInRight">
                            <a href="Blackboard-Tree.html">
                                <div class="news-card cardimg-hover">
                                    <img src="../../assets/images/Trees/Blackboard Tree/Milkwood TA 109(4).webp"
                                        alt="Blackboard Tree" class="card-img">
                                    <div class="card-body">
                                        <h5 class="card-title text-center text-sm-res">Blackboard Tree</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </section>

        </div>
    </main>
    <script>
        new WOW().init();

        $(document).ready(function () {
            $("[data-fancybox='widget-gallery']").fancybox({
                caption: function (instance, item) {
                    var fileName = item.src.split('/').pop();
                    fileName = fileName.split('.').slice(0, -1).join('.');
                    return fileName;
                }
            });
        });
    </script>
</x-layout>