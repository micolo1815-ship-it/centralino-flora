<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/Logo/Logo.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/editor-katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/editor.atom-one-dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/editor.quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/full-calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/jquery-jvectormap-2.0.5.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/audioplayer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-auth.css') }}">
    <link rel="stylesheet" href="{{ asset('custom-style.css') }}">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
</head>
<style>
    .auto-dismiss {
        transition: transform 1s ease, opacity 1s ease, margin 1s ease, padding 1s ease;
    }

    .auto-dismiss.closing {
        transform: translateY(-100%);
        opacity: 0;
        margin: 0;
        padding: 0;
    }
</style>
{{ $slot }}
<div id="loadingOverlay"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999; justify-content:center; align-items:center;">
    <div class="text-center text-white">
        <div class="spinner-border text-white mb-3" role="status" style="width:3rem; height:3rem;"></div>
        <div class="fw-semibold fs-5" id="loadingMessage">Please Wait Loading...</div>
        <div class="text-sm mt-2 opacity-75" id="loadingSubMessage"></div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Select the loader element
        const loader = document.getElementById('loadingOverlay');

        // Helper function to show the loader
        const showLoader = () => {
            loader.style.display = 'flex'; // Use flex to keep the centering
        };

        // 2. Trigger on Search Form Submit
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function () {
                showLoader();
            });
        }

        // 3. Trigger on Pagination Link Clicks
        const pageLinks = document.querySelectorAll('.page-link');
        pageLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                // Only show loader if the link is valid and not disabled
                const href = this.getAttribute('href');
                if (href && href !== 'javascript:void(0)' && !this.classList.contains('pe-none')) {
                    showLoader();
                }
            });
        });

        // 4. Trigger on Filter Changes (Entries per page & Status)
        // Note: This also adds the logic to reload the page with the new filter
        const filters = ['entriesPerPage', 'statusFilter'];
        filters.forEach(filterId => {
            const element = document.getElementById(filterId);
            if (element) {
                element.addEventListener('change', function () {
                    showLoader();

                    // Construct new URL with existing query params
                    const url = new URL(window.location.href);

                    if (filterId === 'entriesPerPage') {
                        url.searchParams.set('entries', this.value);
                    } else if (filterId === 'statusFilter') {
                        url.searchParams.set('status', this.value);
                    }

                    // Reset to page 1 when filtering ensures data integrity
                    url.searchParams.set('page', 1);

                    window.location.href = url.toString();
                });
            }
        });

        // 5. Hide Loader on Browser "Back" Button
        // If user clicks back, the page is loaded from cache and loader might still be visible. 
        // This ensures it hides.
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                loader.style.display = 'none';
            }
        });
    });
</script>
<script src="{{ asset('js/lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/lib/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/lib/apexcharts.min.js') }}"></script>
<script src="{{ asset('js/lib/dataTables.min.js') }}"></script>
<script src="{{ asset('js/lib/iconify-icon.min.js') }}"></script>
<script src="{{ asset('js/lib/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('js/lib/magnifc-popup.min.js') }}"></script>
<script src="{{ asset('js/lib/slick.min.js') }}"></script>
<script src="{{ asset('js/lib/prism.js') }}"></script>
<script src="{{ asset('js/lib/file-upload.js') }}"></script>
<script src="{{ asset('js/lib/audioplayer.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/homeOneChart.js') }}"></script>

{{-- <!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> --}}
<!-- Bootstrap Select JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
</body>

</html>