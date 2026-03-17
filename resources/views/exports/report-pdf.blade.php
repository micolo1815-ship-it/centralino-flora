<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Report - {{ ucfirst($type) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif !important;
        }

        body {
            font-family: Arial, sans-serif !important;
            font-size: 12px;
            color: #000;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        td,
        th,
        li,
        span,
        div,
        ul,
        ol {
            font-family: Arial, sans-serif !important;
        }

        .page {
            padding: 30px 40px;
            page-break-after: always;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .report-header h1 {
            font-family: Arial;
            font-size: 16px;
            font-weight: bold;
        }

        .report-header p {
            font-family: Arial;
            font-size: 11px;
            color: #333;
            margin-top: 4px;
        }

        /* Tree card */
        .tree-name {
            font-family: Arial;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 14px;
        }

        .tree-cover {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            margin-bottom: 14px;
        }

        .tree-no-cover {
            width: 100%;
            height: 220px;
            background: #eee;
            text-align: center;
            line-height: 220px;
            font-family: Arial;
            font-size: 11px;
            color: #999;
            margin-bottom: 14px;
        }

        .two-col {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .col-left {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-right: 16px;
        }

        .col-right {
            display: table-cell;
            width: 52%;
            vertical-align: top;
        }

        .field-label {
            font-family: Arial;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .field-value {
            font-family: Arial;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .section-title {
            font-family: Arial;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 12px;
        }

        .section-body {
            font-family: Arial;
            font-size: 12px;
            line-height: 1.7;
            margin-bottom: 12px;
        }

        ul.classification {
            font-family: Arial;
            font-size: 12px;
            line-height: 1.8;
            padding-left: 20px;
            margin-bottom: 16px;
        }

        /* Generic table (locations, users, activity, analytics) */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table thead tr {
            background-color: #000;
            color: #fff;
        }

        table.data-table th {
            font-family: Arial;
            padding: 7px 8px;
            text-align: left;
            font-size: 11px;
            border: 1px solid #000;
        }

        table.data-table td {
            font-family: Arial;
            padding: 6px 8px;
            border: 1px solid #ccc;
            font-size: 11px;
            vertical-align: top;
            word-wrap: break-word;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .page-footer {
            margin-top: 20px;
            text-align: right;
            font-family: Arial;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 6px;
        }

        .report-footer {
            margin-top: 20px;
            text-align: right;
            font-family: Arial;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 6px;
        }
    </style>
</head>

<body>

    @if($type === 'trees')

        {{-- One page per tree --}}
        @foreach($data as $item)
            <div class="page">

                {{-- Page header --}}
                <div class="report-header">
                    <h1>Centralino Flora — Trees Report</h1>
                    <p>Generated at: {{ $generatedAt }}</p>
                </div>

                {{-- Tree Name --}}
                <div class="tree-name">{{ $item->name }}</div>

                {{-- Cover Image — wide landscape --}}
                @if($item->cover_base64)
                    <img src="{{ $item->cover_base64 }}" class="tree-cover" alt="cover">
                @else
                    <div class="tree-no-cover">No Image Available</div>
                @endif

                {{-- Two columns: left = basic info, right = classification --}}
                <div class="two-col">
                    <div class="col-left">
                        <p class="field-label">Scientific Name:</p>
                        <p class="field-value"><em>{{ $item->scientific_name ?? '—' }}</em></p>

                        <p class="field-label">Common Name:</p>
                        <p class="field-value">{{ $item->common_name ?? '—' }}</p>

                        <p class="field-label">Local Name:</p>
                        <p class="field-value">{{ $item->local_name ?? '—' }}</p>

                        <p class="field-label">Status:</p>
                        <p class="field-value">{{ $item->status }}</p>

                        <p class="field-label">Created At:</p>
                        <p class="field-value">{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</p>
                    </div>

                    <div class="col-right">
                        <p class="field-label">Scientific Classification:</p>
                        <ul class="classification">
                            @if($item->domain)
                            <li>Domain: {{ $item->domain }}</li> @endif
                            @if($item->kingdom)
                            <li>Kingdom: {{ $item->kingdom }}</li> @endif
                            @if($item->phylum)
                            <li>Phylum: {{ $item->phylum }}</li> @endif
                            @if($item->class)
                            <li>Class: {{ $item->class }}</li> @endif
                            @if($item->order)
                            <li>Order: {{ $item->order }}</li> @endif
                            @if($item->family)
                            <li>Family: {{ $item->family }}</li> @endif
                            @if($item->genus)
                            <li>Genus: <em>{{ $item->genus }}</em></li> @endif
                            @if($item->species)
                            <li>Species: <em>{{ $item->species }}</em></li> @endif
                        </ul>
                    </div>
                </div>

                {{-- Description --}}
                @if($item->description)
                    <p class="section-title">Description:</p>
                    <p class="section-body">{{ strip_tags($item->description) }}</p>
                @endif

                {{-- Uses in Filipino Folklore --}}
                @if($item->uses_filipino)
                    <p class="section-title">Uses in Filipino Folklore and Other Uses:</p>
                    <p class="section-body">{{ strip_tags($item->uses_filipino) }}</p>
                @endif

                {{-- Tree Facts --}}
                @if($item->tree_facts)
                    <p class="section-title">Tree Facts:</p>
                    <p class="section-body">{{ strip_tags($item->tree_facts) }}</p>
                @endif

                {{-- Tagged Trees --}}
                @if($item->tagged_trees)
                    <p class="section-title">Tagged Trees:</p>
                    <p class="section-body">{{ strip_tags($item->tagged_trees) }}</p>
                @endif

                <div class="page-footer">Centralino Flora &copy; {{ date('Y') }}</div>
            </div>
        @endforeach

    @else

        @if($type === 'locations')
            {{-- Page header --}}
            <div class="page">
                <div class="report-header">
                    <h1>Centralino Flora — Location Report</h1>
                    <p>Generated at: {{ $generatedAt }}</p>
                </div>
                @foreach($data as $item)
                        <div
                            style="page-break-inside: avoid; margin-bottom: 24px; border-bottom: 1px solid #ccc; padding-bottom: 16px;">

                            {{-- Two column: image left, info right --}}
                            <div style="display:table; width:100%; margin-bottom:10px;">

                                {{-- Image --}}
                                <div style="display:table-cell; width:160px; vertical-align:top;">
                                    @if($item->cover_base64)
                                        <img src="{{ $item->cover_base64 }}"
                                            style="width:150px; height:110px; object-fit:cover; display:block;" alt="location">
                                    @else
                                        <div
                                            style="width:150px; height:110px; background:#eee; text-align:center; line-height:110px; font-family:Arial; font-size:10px; color:#999;">
                                            No Image</div>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div style="display:table-cell; vertical-align:top; padding-left:14px;">
                                    <p style="font-family:Arial; font-size:15px; font-weight:bold; margin-bottom:8px;">{{ $item->name }}
                                    </p>
                                    <p style="font-family:Arial; font-size:12px; margin-bottom:4px;"><strong>Status:</strong>
                                        {{ $item->status }}</p>
                                    <p style="font-family:Arial; font-size:12px; margin-bottom:4px;"><strong>Created At:</strong>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</p>

                                    {{-- Trees --}}
                                    <p style="font-family:Arial; font-size:12px; font-weight:bold; margin-top:8px; margin-bottom:4px;">
                                        Trees:</p>
                                    @if($item->trees && $item->trees->count() > 0)
                                        @foreach($item->trees as $tree)
                                            <p style="font-family:Arial; font-size:12px; margin-bottom:3px;">
                                                • {{ $tree->name }}
                                                <span style="font-size:10px; color:{{ $tree->pivot->status ? '#000' : '#999' }};">
                                                    ({{ $tree->pivot->status ? 'Active' : 'Inactive' }})
                                                </span>
                                            </p>
                                        @endforeach
                                    @else
                                        <p style="font-family:Arial; font-size:12px;">—</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            {{-- Single page for all other types --}}
            <div class="page">
                <div class="report-header">
                    <h1>Centralino Flora — {{ ucfirst(str_replace('-', ' ', $type)) }} Report</h1>
                    <p>Generated at: {{ $generatedAt }}</p>
                    @if($startDate || $endDate)
                        <p>Date Range: {{ $startDate ?? 'All' }} — {{ $endDate ?? 'All' }}</p>
                    @endif
                </div>

                <table class="data-table">
                    <thead>

        @elseif($type === 'users')
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>School Year</th>
                            <th>Created At</th>
                        </tr>
                    @elseif($type === 'activity-log')
                        <tr>
                            <th>Type</th>
                            <th>Event</th>
                            <th>Action</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    @elseif($type === 'analytics')
                        <tr>
                            <th>Tree</th>
                            <th>Location</th>
                            <th>Total Visits</th>
                            <th>Last Visit</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @forelse($data as $item)
                        @if($type === 'locations')
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</td>
                            </tr>
                        @elseif($type === 'users')
                            <tr>
                                <td>{{ $item->first_name }} {{ $item->middle_initial ? $item->middle_initial . '.' : '' }}
                                    {{ $item->last_name }}
                                </td>
                                <td>{{ $item->email ?? '—' }}</td>
                                <td>{{ $item->position ?? '—' }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->school_year ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</td>
                            </tr>
                        @elseif($type === 'activity-log')
                            <tr>
                                <td>{{ $item->subject_type ? class_basename($item->subject_type) : '—' }}</td>
                                <td>{{ $item->event }}</td>
                                <td>{{ $item->action }}</td>
                                <td>{{ $item->user ? $item->user->first_name . ' ' . $item->user->last_name : '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('h:i A') }}</td>
                            </tr>
                        @elseif($type === 'analytics')
                            <tr>
                                <td>{{ $item->tree ? $item->tree->name : '—' }}</td>
                                <td>{{ $item->location ? $item->location->name : '—' }}</td>
                                <td>{{ $item->total }}</td>
                                <td>{{ $item->last_visit ? \Carbon\Carbon::parse($item->last_visit)->format('M d, Y') : '—' }}</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center; padding:20px;">No data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="report-footer">
                Total Records: {{ count($data) }} &nbsp;|&nbsp; Centralino Flora &copy; {{ date('Y') }}
            </div>
        </div>

    @endif

</body>

</html>