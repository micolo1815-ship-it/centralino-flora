<x-layout :locations="$locations">
    <main class="portfolio-grid-page fixed-bg">
        <div class="container-custom tree-list-content">

            <nav aria-label="breadcrumb" class="fadeInUp wow">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('forestry.forestrylist') }}">Forest</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $location->abbreviation ?? $location->name }}
                    </li>
                </ol>
            </nav>

            <h1 class="oleez-page-title wow fadeInUp mt-4">{{ $location->abbreviation ?? $location->name }}</h1>

            <div class="row">
                @forelse($location->trees as $tree)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 portfolio-card wow fadeInUp">
                        <div class="project-thumbnail-wrapper">
                            <a href="{{ route('home.tree', ['location' => $location->id, 'tree' => $tree->id]) }}">
                                <img src="{{ $tree->cover_image ? asset('storage/' . $tree->cover_image) : asset('storage/no_image.jpg') }}"
                                    alt="{{ $tree->name }}" class="project-thumbnail" loading="lazy">
                            </a>
                        </div>

                        <h5 class="project-name">
                            <a href="{{ route('home.tree', ['location' => $location->id, 'tree' => $tree->id]) }}">
                                {{ $tree->name }}
                            </a>
                        </h5>


                        <p class="project-category">
                            {{ $tree->scientific_name }}
                        </p>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No trees available for this forest.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </main>
</x-layout>