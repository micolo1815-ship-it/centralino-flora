<x-layout :locations="$locations">
    <main class="about-page">
        <div class="container">
            <h1 class="oleez-page-title wow fadeInUp">About Us</h1>
            <section class="oleez-about-features">
                <div class="row">
                    <div class="col-md-4 mb-5 mb-md-0 feature-card wow fadeInUp">
                        <h5 class="feature-card-title">MCU Biological Society</h5>
                        <p class="feature-card-content">The MCU Biological Society is the heart of Manila Central
                            University's Biology community, bringing together passionate students and educators
                            dedicated to
                            exploring and preserving the wonders of life.</p>
                    </div>
                    <div class="col-md-4 mb-5 mb-md-0 feature-card wow fadeInUp">
                        <h5 class="feature-card-title">Our Green Campus</h5>
                        <p class="feature-card-content">As one of the greenest schools in Caloocan, MCU takes pride in
                            its
                            lush campus, a sanctuary for diverse tree species and a testament to environmental
                            sustainability. This website is a collaborative initiative to document and showcase the
                            trees
                            within the university, serving as both a digital gallery and an educational resource for the
                            community.</p>
                    </div>
                    <div class="col-md-4 mb-5 mb-md-0 feature-card wow fadeInUp">
                        <h5 class="feature-card-title">Our Purpose</h5>
                        <p class="feature-card-content">Through this platform, we aim to emphasize the importance of
                            biodiversity and inspire a deeper appreciation for nature within our campus grounds,
                            reflecting
                            our enduring commitment to learning and sustainability.</p>
                    </div>
                </div>
            </section>

            <section class="biosoc-section">
                <h2 class="section-title text-center mb-4 wow fadeInUp">
                    MCU Biological Society <span id="sy-main">({{ $school_year }})</span>
                </h2>
                <div class="row">
                    @forelse($currentOfficers as $officer)
                        @php
                            $linkedUser = $usersMap->get($officer->id);
                            $imgPath = $linkedUser?->profile_image
                                ?? $officer->image_path
                                ?? null;
                        @endphp
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 mb-4 wow fadeInUp">
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
                    @empty
                        <div class="col-12 text-center">
                            <p>No officers found for {{ $school_year }}.</p>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <h5 class="card-title mb-0"></h5>
                    <a href="{{ route('home.historical_officers') }}"
                        class="btn btn-link text-secondary-light text-sm float-end d-inline-flex align-items-center gap-1">
                        History of Previous Officers
                        <iconify-icon icon="formkit:arrowright"></iconify-icon>
                    </a>
                </div>
            </section>

            <section class="devs-section mt-5">
                <h2 class="section-title text-center mb-4 wow fadeInUp">Centralino Flora Founders</h2>
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 mb-4 wow fadeInUp">
                        <div class="team-card">
                            <img src="images/People/Elizar.jpg" alt="Elizar Padua">
                            <h5>Elizar J. Padua</h5>
                            <p>Team Leader, Client Coordinator, Main Front-End Developer & UI/UX Designer</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 mb-4 wow fadeInUp">
                        <div class="team-card">
                            <img src="images/People/Jereyko.jpg" alt="Jereyko Dela Cruz">
                            <h5>Jereyko Dela Cruz</h5>
                            <p>Main Back-End Developer</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 mb-4 wow fadeInUp">
                        <div class="team-card">
                            <img src="images/People/Mico.jpg" alt="Mico Soriano">
                            <h5>Mico Soriano</h5>
                            <p>Documentation Specialist</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 mb-4 wow fadeInUp">
                        <div class="team-card">
                            <img src="images/People/3rd Year Rep-Chad Gian Dy.JPG" alt="Chad Gian Dy">
                            <h5>Chad Gian Dy</h5>
                            <p>3rd Year Representative (2024 – 2025)</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 mb-4 wow fadeInUp">
                        <div class="team-card">
                            <img src="images/People/President-Christian Cabasal.jpg" alt="Christian Cabasal">
                            <h5>Christian P. Cabasal</h5>
                            <p>President (2024 – 2025)</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 mb-4 wow fadeInUp">
                        <div class="team-card">
                            <img src="images/People/VP External-Jezca Dame Radaza.JPG" alt="Jezca Dame Radaza">
                            <h5>Jezca Dame Radaza</h5>
                            <p>Vice President External (2024 – 2025)</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 mb-4 wow fadeInUp">
                        <div class="team-card">
                            <img src="images/People/PRO-Odlanyer Reyes.JPG" alt="Odlanyer Reyes">
                            <h5>Odlanyer Reyes</h5>
                            <p>PRO (2024 – 2025)</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layout>