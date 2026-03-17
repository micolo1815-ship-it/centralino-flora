<x-layout :locations="$locations">

    <main class="about-page">
        <div class="container">

            <nav aria-label="breadcrumb" class="fadeInUp wow">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/abouts') }}">About</a></li>
                    <li class="breadcrumb-item active" aria-current="page">History of Previous Officers</li>
                </ol>
            </nav>

            <h1 class="oleez-page-title text-center wow fadeInUp my-5">History of Previous Officers</h1>

            @forelse($previousOfficers as $year => $officers)
                <div class="mb-5">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                        <h3 class="section-title wow fadeInUp">
                            MCU Biological Society <span>({{ $year }})</span>
                        </h3>
                    </div>

                    <section class="biosoc-section mt-4">
                        <div class="row biosoc">
                            @foreach($officers as $officer)
                                @php
                                    $linkedUser = $usersMap->get($officer->id);
                                    $imgPath    = $linkedUser?->profile_image
                                                ?? $officer->image_path
                                                ?? null;
                                @endphp
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 mb-4 wow fadeInUp">
                                    <div class="team-card">
                                        <img src="{{ $imgPath ? asset('storage/' . $imgPath) : asset('images/avatar/blank-profile.png') }}"
                                            alt="{{ $officer->firstname }} {{ $officer->lastname }}">
                                        <h5>
                                            {{ $officer->firstname }}
                                            {{ $officer->middle_initial ? $officer->middle_initial . '.' : '' }}
                                            {{ $officer->lastname }}
                                        </h5>
                                        <p>{{ $officer->position }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Divider between years --}}
                    <hr class="my-4">
                </div>
            @empty
                <div class="text-center py-5">
                    <p class="text-secondary">No previous officers found.</p>
                </div>
            @endforelse

        </div>
    </main>

</x-layout>