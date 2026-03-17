<x-layout :locations="$locations">
    <main class="portfolio-grid-page fixed-bg">
        <div class="container-custom tree-list-content">
            <h1 class="oleez-page-title wow fadeInUp">Forest</h1>
            <div class="row">
                @forelse($locations as $location)
                    <div class="col-md-6 portfolio-card wow fadeInUp">
                        <div class="project-thumbnail-wrapper">
                            <a href="{{ route('home.location', $location->id) }}">
                                <img src="{{ $location->image ? asset('storage/' . $location->image) : asset('storage/no_image.jpg') }}" alt="{{ $location->name }}"
                                    class="project-thumbnail">
                            </a>
                        </div>
                        <h5 class="project-name">
                            <a href="{{ route('home.location', $location->id) }}">
                                {{ $location->name }}
                                @if($location->abbreviation)
                                    <span class="abbreviation">({{ $location->abbreviation }})</span>
                                @endif
                            </a>
                        </h5>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">No locations available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</x-layout>